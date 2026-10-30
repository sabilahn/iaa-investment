<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sisa_hasil_usaha extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('SisaHasilUsaha');

        if ($this->session->userdata('status') != 'login') {
            redirect(base_url('auth'));
        }
    }
    public function index()
    {
        $data['title'] = 'IAA Investment | Sisa Hasil Usaha';
        $data['data_shu'] = $this->SisaHasilUsaha->shu_list()->result_array();
        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('sisa_hasil_usaha', $data);
        $this->load->view('footer');
    }
    public function tambah_data_shu()
    {
        $this->db->trans_begin();
        try {
            $cek = $this->SisaHasilUsaha->cek($_POST['tahun_add'])->result_array();
            $cek1 = $this->SisaHasilUsaha->cek1($_POST['tahun_add'])->result_array();
            if ($cek1) {
                if (!$cek) {
                    $data = [
                        'tahun' => $_POST['tahun_add'],
                        'jumlah' => $_POST['jml_shu'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => $this->session->userdata('user_id')
                    ];
                    $this->SisaHasilUsaha->tambah($data, 'sisa_hasil_usaha');

                    $porsi_dispen = [82.5, 17.5];
                    $uraian_dispen = ['Dividen Pesero', 'IAA Pusat, PPA, dan Menejemen'];
                    for ($i = 0; $i < count($porsi_dispen); $i++) {
                        $jumlah = $porsi_dispen[$i] / 100 * (float) ($_POST['jml_shu']);
                        $data_dispen = [
                            'porsi' => $porsi_dispen[$i],
                            'uraian' => $uraian_dispen[$i],
                            'shu' => $_POST['jml_shu'],
                            'jumlah' => $jumlah,
                            'tahun' => $_POST['tahun_add'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => $this->session->userdata('user_id')
                        ];
                        $this->SisaHasilUsaha->tambah($data_dispen, 'porsi_dispen');
                    }

                    $porsi_disda = [30, 20, 50];
                    $uraian_disda = ['IAA Pusat', 'PP. Annuqayah', 'Menejemen IAA'];
                    $dispen = $porsi_dispen[1] / 100 * (float) ($_POST['jml_shu']);
                    for ($i = 0; $i < count($porsi_disda); $i++) {
                        $jumlah = $porsi_disda[$i] / 100 * $dispen;
                        $data_disda = [
                            'porsi' => $porsi_disda[$i],
                            'uraian' => $uraian_disda[$i],
                            'menejemen' => $dispen,
                            'jumlah' => $jumlah,
                            'tahun' => $_POST['tahun_add'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => $this->session->userdata('user_id')
                        ];

                        $this->SisaHasilUsaha->tambah($data_disda, 'porsi_disda');
                    }

                    // $users = $this->SisaHasilUsaha->getUser();
                    // foreach ($users as $user) {
                    //     $total_saldo_harian = $this->SisaHasilUsaha->getTotalSaldoHarian($_POST['tahun_add'])->total_rata_rata_harian;
                    //     $saldo_harian = $this->SisaHasilUsaha->getSaldoHarian($user['no_rekening'], $_POST['tahun_add'])->rata_rata_harian;
                    //     $shu = $this->input->post('jml_shu');
                    //     $data_bagi_hasil = [
                    //         'no_rekening' => $user['no_rekening'],
                    //         'shu' => $shu,
                    //         'total_saldo_harian' => $total_saldo_harian,
                    //         'nisbah_bagi_hasil' => (float) $shu / (float) $total_saldo_harian * (float) $saldo_harian,
                    //         'tahun' => $_POST['tahun_add'],
                    //         'created_at' => date('Y-m-d H:i:s'),
                    //         'created_by' => $this->session->userdata('user_id')
                    //     ];
                    //     $this->SisaHasilUsaha->tambah($data_bagi_hasil, 'bagi_hasil');
                    // }
                    $activity_data = array(
                        'user_id' => $this->session->userdata('user_id'),
                        'activity' => 'Tambah Data SHU Berhasil',
                        'times' => date('Y-m-d H:i:s')
                    );

                    $this->SisaHasilUsaha->tambah($activity_data, 'activity_log');

                    $this->db->trans_commit();
                    $this->session->set_flashdata('success', 'Sukses! Data berhasil disimpan.');
                } else {
                    $this->session->set_flashdata('error', 'Gagal! Data SHU sudah ada');
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal! Belum ada data transaksi.');
            }


            redirect($_SERVER['HTTP_REFERER']);
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal menyimpan data!');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    public function ubah_data_shu()
    {
        $this->db->trans_begin();
        try {
            $data = [
                'jumlah' => $_POST["jml_shu_edt"],
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->session->userdata('user_id')
            ];

            $id_shu = $this->input->post('id_shu');
            $where = array('id_shu' => $id_shu);

            $this->SisaHasilUsaha->edit('sisa_hasil_usaha', $data, $where);

            $porsi_dispen = [82.5, 17.5];
            $tahun = $this->input->post('tahun_edt');
            for ($i = 0; $i < count($porsi_dispen); $i++) {
                $jumlah = $porsi_dispen[$i] / 100 * (float) ($_POST['jml_shu_edt']);
                $data_dispen = [
                    'shu' => $_POST['jml_shu_edt'],
                    'jumlah' => $jumlah,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->userdata('user_id')
                ];
                $where1 = array('porsi' => $porsi_dispen[$i], 'tahun' => $tahun);
                $this->SisaHasilUsaha->edit('porsi_dispen', $data_dispen, $where1);
            }

            $porsi_disda = [30, 20, 50];
            $dispen = $porsi_dispen[1] / 100 * (float) ($_POST['jml_shu_edt']);
            for ($i = 0; $i < count($porsi_disda); $i++) {
                $jumlah = $porsi_disda[$i] / 100 * $dispen;
                $data_disda = [
                    'menejemen' => $dispen,
                    'jumlah' => $jumlah,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->userdata('user_id')
                ];
                $where1 = array('porsi' => $porsi_disda[$i], 'tahun' => $tahun);
                $this->SisaHasilUsaha->edit('porsi_disda', $data_disda, $where1);
            }

            // $users = $this->SisaHasilUsaha->getUser();
            // foreach ($users as $user) {
            //     $total_saldo_harian = $this->SisaHasilUsaha->getTotalSaldoHarian($tahun)->total_rata_rata_harian;
            //     $saldo_harian = $this->SisaHasilUsaha->getSaldoHarian($user['no_rekening'], $tahun)->rata_rata_harian;
            //     $shu = $this->input->post('jml_shu_edt');
            //     $data_bagi_hasil = [
            //         'shu' => $shu,
            //         'total_saldo_harian' => $total_saldo_harian,
            //         'nisbah_bagi_hasil' => (float) $shu / (float) $total_saldo_harian * (float) $saldo_harian,
            //         'updated_at' => date('Y-m-d H:i:s'),
            //         'updated_by' => $this->session->userdata('user_id')
            //     ];

            //     $this->SisaHasilUsaha->edit('bagi_hasil', $data_bagi_hasil, array('tahun' => $tahun));
            // }
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Ubah Data SHU Berhasil',
                'times' => date('Y-m-d H:i:s')
            );

            $this->SisaHasilUsaha->tambah($activity_data, 'activity_log');

            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Sukses! Data berhasil disimpan.');
            redirect($_SERVER['HTTP_REFERER']);
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal! Pastikan input anda benar!');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    public function hapus_data_shu()
    {
        $id_shu = $this->input->post('id_shu');
        $tahun = $this->input->post('tahun_hps');
        $where = array('id_shu' => $id_shu);
        $this->db->trans_begin();
        try {
            $this->SisaHasilUsaha->hapus($where, 'sisa_hasil_usaha');

            $where1 = array('tahun' => $tahun);
            $this->SisaHasilUsaha->hapus($where1, 'porsi_dispen');

            $this->SisaHasilUsaha->hapus($where1, 'porsi_disda');

            // $this->SisaHasilUsaha->hapus($where1, 'bagi_hasil');

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Hapus Data SHU Berhasil',
                'times' => date('Y-m-d H:i:s')
            );

            $this->SisaHasilUsaha->tambah($activity_data, 'activity_log');

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Sukses! Data berhasil dihapus.');

            redirect($_SERVER['HTTP_REFERER']);
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
}
