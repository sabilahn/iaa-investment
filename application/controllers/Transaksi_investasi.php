<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi_investasi extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('TransaksiInvestasi');

        if ($this->session->userdata('status') != 'login') {
            redirect(base_url('auth'));
        }
    }
    public function index()
    {
        $data['title'] = 'IAA Investment | Transaksi Investasi';

        $thn = $this->input->post('tahun_transaksi');
        if ($thn == "") {
            $thn = date('Y');
        }
        $data['tahun'] = $thn;

        $data['data_transaksi'] = $this->TransaksiInvestasi->transaksi_list($thn)->result_array();

        foreach ($data['data_transaksi'] as $i => $transaksi) {
            $saldo = $this->TransaksiInvestasi->getWhere('v_transaksi_thn', ['no_rekening' => $transaksi['no_rekening'], 'tahun' => $thn - 1])->row_array();
            // var_dump($saldo['saldo']);
            if ($transaksi['jml_transaksi'] == isset($saldo['saldo']) && $transaksi['tgl_transaksi'] == $thn . '-01-01') {
                $data['data_transaksi'][$i]['action'] = '<button class="btn btn-sm btn-info" disabled><i class="fa fa-ban"></i></button>
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal' . $transaksi['id_transaksi'] . ' "><i class="fas fa-pencil-alt"></i></button>
                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal' . $transaksi['id_transaksi'] . ' "><i class="fa fa-trash"></i></button>';
            } else {
                $data['data_transaksi'][$i]['action'] = '<a target="_blank" href="' . site_url() . 'transaksi_investasi/cetak_surat_saham/' . $transaksi['id_transaksi'] . '/' . $transaksi['no_rekening'] . '" class="btn btn-sm btn-info"><i class="fa fa-print"></i></a>
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal' . $transaksi['id_transaksi'] . ' "><i class="fas fa-pencil-alt"></i></button>
                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal' . $transaksi['id_transaksi'] . ' "><i class="fa fa-trash"></i></button>';
            }
            // break;
        }

        $data['data_rekening'] = $this->TransaksiInvestasi->rekening_list();

        $data['action2'] = '<a class="btn btn-warning" href="' . site_url() . 'transaksi_investasi/migrasi/' . $thn . '"><i class="fas fa-sync"> Jadikan Saldo Awal</i></a>';

        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('transaksi_investasi', $data);
        $this->load->view('footer');
    }

    public function update_saldo_user($user, $tgl_transaksi, $thn)
    {
        $saldo_skr = 0;
        $penambahan_saldo = 0;

        $from = date('Y-m-d', strtotime($thn . "-01-01" . "-1 days"));
        $to = $tgl_transaksi;
        while (strtotime($from) < strtotime($to)) {
            $from = mktime(0, 0, 0, date("m", strtotime($from)), date("d", strtotime($from)) + 1, date("Y", strtotime($from)));
            $from = date("Y-m-d", $from);

            $cek = $this->TransaksiInvestasi->cekTransaksi($user, $from)->result_array();
            $cek2 = $this->TransaksiInvestasi->cekPenarikan($user, $from)->result_array();
            if ($cek) {
                foreach ($cek as $transaksi) {
                    $saldo_skr = $saldo_skr + $transaksi['jml_transaksi'] + $penambahan_saldo;
                    $penambahan_saldo = $penambahan_saldo + $transaksi['jml_transaksi'];

                    // var_dump($saldo_skr, $penambahan_saldo);
                }
            } else if ($cek2) {
                foreach ($cek2 as $transaksi) {
                    $saldo_skr = $saldo_skr - $transaksi['jml_penarikan'] + $penambahan_saldo;
                    $penambahan_saldo = $penambahan_saldo - $transaksi['jml_penarikan'];
                }
            } else {
                $saldo_skr = $saldo_skr + $penambahan_saldo;
            }
        }
        return $saldo_skr;
    }

    public function getHari($tanggal)
    {
        $tgl_skrg = strtotime(date("Y-m-d"));
        $tgl_awal = date('Y-m-d', strtotime(date('Y') . "-01-01" . "-1 days"));
        $tgl_transaksi = date('Y-m-d', strtotime($tanggal . ' -1 day'));
        $hari_saldo_harian = abs($tgl_skrg - strtotime($tgl_transaksi)) / 86400;
        $hari_saldo_tahun = ($tgl_skrg - strtotime($tgl_awal)) / 86400;

        return array($hari_saldo_harian, $hari_saldo_tahun);
    }

    public function create_qrcode($no_sertifikat)
    {
        //CREATE QR-CODE

        $this->load->library('ciqrcode');

        $config['cacheable']    = true; //boolean, the default is true
        $config['cachedir']     = './assets/stisla'; //string, the default is application/cache/
        $config['errorlog']     = './assets/stisla'; //string, the default is application/logs/
        $config['imagedir']     = './assets/stisla/images/qrcode/surat_saham/'; //direktori penyimpanan qr code
        $config['quality']      = true; //boolean, the default is true
        $config['size']         = '1024'; //interger, the default is 1024
        $config['black']        = array(224, 255, 255); // array, default is array(255,255,255)
        $config['white']        = array(70, 130, 180); // array, default is array(0,0,0)
        $this->ciqrcode->initialize($config);

        $image_name = $no_sertifikat . '.png'; //buat name dari qr code sesuai dengan nim

        $params['data'] = base_url('surat_saham/index/') . $no_sertifikat; //data yang akan di jadikan QR CODE
        $params['level'] = 'H'; //H=High
        $params['size'] = 10;
        $params['savename'] = FCPATH . $config['imagedir'] . $image_name; //simpan image QR CODE ke folder assets/images/
        $this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
    }
    public function tambah_data_transaksi()
    {
        $this->db->trans_begin();
        try {

            $jml_transaksi = $this->input->post('jml_transaksi');
            $no_sertifikat = $this->input->post('sertifikat');

            $data = [
                'no_rekening' => $_POST['rekening'],
                'no_sertifikat' => $no_sertifikat,
                'tgl_transaksi' => $_POST['tgl_transaksi'],
                'jml_transaksi' => $jml_transaksi,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('user_id'),
                'is_deleted' => 0
            ];

            $transaksi_add = $this->TransaksiInvestasi->tambah('transaksi_investasi', $data);
            if (!$transaksi_add) {
                throw new \Exception('Failed');
            }

            // $hari = $this->getHari($_POST["tgl_transaksi"]);
            $thn = date('Y', strtotime($_POST['tgl_transaksi']));
            $tgl = date($thn . '-m-d');
            $hari = $this->getHari($tgl);

            $jml_rata_harian = (float) $_POST["jml_transaksi"] * $hari[0];

            $data_saldo = [
                'no_rekening' => $_POST['rekening'],
                'rata_rata_harian' => $jml_rata_harian,
                'rata_rata_tahun' => $jml_rata_harian / $hari[1],
                'tahun' => date('Y', strtotime($_POST['tgl_transaksi'])),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('user_id')
            ];
            $cek = $this->TransaksiInvestasi->cekSaldo($_POST['rekening'], $_POST['tgl_transaksi'])->result_array();

            if (!$cek) {
                $this->TransaksiInvestasi->tambah('saldo_harian', $data_saldo);
            } else {
                $rata_harian = $this->update_saldo_user($_POST['rekening'], $tgl,  $thn);
                $rata_tahun = $rata_harian / $hari[1];

                $update_saldo = [
                    'rata_rata_harian' => $rata_harian,
                    'rata_rata_tahun' => $rata_tahun,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->userdata('user_id')
                ];

                $this->TransaksiInvestasi->edit('saldo_harian', $update_saldo, array('no_rekening' => $_POST['rekening'], 'tahun' => $thn));
            }

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Tambah Transaksi Berhasil',
                'times' => date('Y-m-d H:i:s')
            );

            $this->TransaksiInvestasi->tambah('activity_log', $activity_data);

            $this->create_qrcode($no_sertifikat);

            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Sukses! Data berhasil disimpan.');
            redirect($_SERVER['HTTP_REFERER']);
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function ubah_data_transaksi()
    {
        $this->db->trans_begin();

        try {
            $data_transaksi = [
                'no_sertifikat' => $_POST["sertifikat_edt"],
                'tgl_transaksi' => $_POST["tgl_transaksi_edt"],
                'jml_transaksi' => $_POST["jml_transaksi_edt"],
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->session->userdata('user_id')
            ];

            $id_transaksi = $this->input->post('id_transaksi');
            $transaksi_edit = $this->TransaksiInvestasi->edit('transaksi_investasi', $data_transaksi, array('id_transaksi' => $id_transaksi));
            if (!$transaksi_edit) {
                throw new \Exception('Failed');
            }

            // $hari = $this->getHari($_POST["tgl_transaksi_edt"]);

            $thn = date('Y', strtotime($_POST['tgl_transaksi_hps']));
            $tgl = date($thn . '-m-d');
            $hari = $this->getHari($tgl);

            $rata_harian = $this->update_saldo_user($_POST['rekening_edt'], $tgl, $thn);
            $rata_tahun = $rata_harian / $hari[1];

            $update_saldo = [
                'rata_rata_harian' => $rata_harian,
                'rata_rata_tahun' => $rata_tahun,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->session->userdata('user_id')
            ];

            $this->TransaksiInvestasi->edit('saldo_harian', $update_saldo, array('no_rekening' => $_POST['rekening_edt'], 'tahun' => $thn));

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Ubah Transaksi Berhasil',
                'times' => date('Y-m-d H:i:s')
            );

            $this->TransaksiInvestasi->tambah('activity_log', $activity_data);

            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Sukses! Data berhasil disimpan.');
            redirect($_SERVER['HTTP_REFERER']);
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    public function hapus_data_transaksi()
    {
        $id_transaksi = $this->input->post('id_transaksi');
        $no_rekening = $this->input->post('rekening_hps');
        $tgl_transaksi = $this->input->post('tgl_transaksi_hps');

        $count = $this->TransaksiInvestasi->countTransaksi($no_rekening, $tgl_transaksi)->banyak_transaksi;

        $thn = date('Y', strtotime($tgl_transaksi));
        $tgl = date($thn . '-m-d');
        $hari = $this->getHari($tgl);

        $this->db->trans_begin();
        try {
            if ($count == 1) {
                $this->TransaksiInvestasi->hapus('transaksi_investasi', array('id_transaksi' => $id_transaksi));
                $this->TransaksiInvestasi->hapus('saldo_harian', array('no_rekening' => $no_rekening, 'tahun' => $thn));
            } else {
                $this->TransaksiInvestasi->hapus('transaksi_investasi', array('id_transaksi' => $id_transaksi));
                $rata_harian = $this->update_saldo_user($no_rekening, $tgl, $thn);
                $rata_tahun = $rata_harian / $hari[1];

                $update_saldo = [
                    'rata_rata_harian' => $rata_harian,
                    'rata_rata_tahun' => $rata_tahun,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->userdata('user_id')
                ];
            }

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Hapus Transaksi Berhasil',
                'times' => date('Y-m-d H:i:s')
            );

            $this->TransaksiInvestasi->tambah('activity_log', $activity_data);

            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Sukses! Data berhasil dihapus.');
            redirect($_SERVER['HTTP_REFERER']);
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function migrasi($thn)
    {
        $list_transaksi = $this->TransaksiInvestasi->getTransaksi($thn)->result_array();
        $this->db->trans_begin();

        try {
            foreach ($list_transaksi as $transaksi) {
                $rekening = explode('-', $transaksi['no_rekening']);
                $tgl_transaksi = ($thn + 1) . '-01-01';
                $no_sertifikat = ($thn + 1) . '0101' . $rekening[1];

                $cek = $this->TransaksiInvestasi->getWhere('transaksi_investasi', array('no_sertifikat' => $no_sertifikat))->result_array();
                $hari = $this->getHari($tgl_transaksi);

                if (!$cek) {
                    $data = [
                        'no_rekening' => $transaksi['no_rekening'],
                        'no_sertifikat' => $no_sertifikat,
                        'tgl_transaksi' => $tgl_transaksi,
                        'jml_transaksi' => $transaksi['saldo'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => $this->session->userdata('user_id'),
                        'is_deleted' => 0
                    ];
                    $this->TransaksiInvestasi->tambah('transaksi_investasi', $data);


                    $jml_rata_harian = (float) $transaksi['saldo'] * $hari[0];

                    $data_saldo = [
                        'no_rekening' => $transaksi['no_rekening'],
                        'rata_rata_harian' => $jml_rata_harian,
                        'rata_rata_tahun' => $jml_rata_harian / $hari[1],
                        'tahun' => date('Y', strtotime($tgl_transaksi)),
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => $this->session->userdata('user_id')
                    ];

                    $cek2 = $this->TransaksiInvestasi->cekSaldo($transaksi['no_rekening'], $tgl_transaksi)->result_array();

                    if (!$cek2) {
                        $this->TransaksiInvestasi->tambah('saldo_harian', $data_saldo);
                    } else {
                        $rata_harian = $this->update_saldo_user($transaksi['no_rekening'], $tgl_transaksi,  date('Y', strtotime($tgl_transaksi)));
                        $rata_tahun = $rata_harian / $hari[1];

                        $update_saldo = [
                            'rata_rata_harian' => $rata_harian,
                            'rata_rata_tahun' => $rata_tahun,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => $this->session->userdata('user_id')
                        ];

                        $this->TransaksiInvestasi->edit('saldo_harian', $update_saldo, array('no_rekening' => $transaksi['no_rekening'], 'tahun' => date('Y', strtotime($tgl_transaksi))));
                    }

                    // $this->create_qrcode($no_sertifikat);
                } else {
                    if ($cek[0]['no_sertifikat'] == $no_sertifikat && $cek[0]['jml_transaksi'] != $transaksi['saldo']) {

                        // var_dump($transaksi['jml_transaksi']);
                        $this->TransaksiInvestasi->edit('transaksi_investasi', ['jml_transaksi' => $transaksi['saldo']], ['no_sertifikat' => $no_sertifikat]);

                        $rata_harian = $this->update_saldo_user($transaksi['no_rekening'], $tgl_transaksi,  date('Y', strtotime($tgl_transaksi)));
                        $rata_tahun = $rata_harian / $hari[1];

                        $update_saldo = [
                            'rata_rata_harian' => $rata_harian,
                            'rata_rata_tahun' => $rata_tahun,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => $this->session->userdata('user_id')
                        ];

                        $this->TransaksiInvestasi->edit('saldo_harian', $update_saldo, array('no_rekening' => $transaksi['no_rekening'], 'tahun' => date('Y', strtotime($tgl_transaksi))));
                    }
                }
            }
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Sukses! Data berhasil disimpan');
            redirect($_SERVER['HTTP_REFERER']);
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function cetak_surat_saham($id_transaksi, $no_rekening)
    {
        $data['title'] = 'IAA Investment | Surat Saham';
        $data['data_transaksi'] = $this->TransaksiInvestasi->getWhere('transaksi_investasi', ['id_transaksi' => $id_transaksi])->row();
        $data['profiles'] = $this->TransaksiInvestasi->getWhere('client_account', ['no_rekening' => $no_rekening])->row();

        $this->load->library('cetak_pdf');

        // $this->cetak_pdf->setPaper('A5', 'landscape');
        $customPaper = array(0, 0, 771, 395);
        $this->cetak_pdf->set_paper($customPaper);
        $this->cetak_pdf->filename = "surat-saham-[" . $no_rekening . "].pdf";
        $this->cetak_pdf->load_view('surat_saham/surat_saham2.php', $data);
        // $this->cetak_pdf->load_view('surat_saham/surat_saham.php', $data);
    }

    public function cetak_surat_keterangan($id_penarikan)
    {
        $data['title'] = 'IAA Investment | Surat Keterangan';
        $data['data_penarikan'] = $this->TransaksiInvestasi->getWhere('v_penarikan_investasi', ['id_penarikan' => $id_penarikan])->row();

        $activity_data = array(
            'user_id' => $this->session->userdata('user_id'),
            'activity' => 'Cetak Surat Keterangan Berhasil',
            'times' => date('Y-m-d H:i:s')
        );

        $this->TransaksiInvestasi->tambah('activity_log', $activity_data);

        $this->load->library('cetak_pdf');

        $this->cetak_pdf->setPaper('A4', 'potrait');
        $this->cetak_pdf->filename = "surat-keterangan-penarikan.pdf";
        $this->cetak_pdf->load_view('surat_saham/surat_keterangan.php', $data);
    }
}
