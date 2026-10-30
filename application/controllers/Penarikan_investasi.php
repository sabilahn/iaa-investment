<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penarikan_investasi extends CI_Controller
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
        $data['title'] = 'IAA Investment | Penarikan Investasi';

        $data['data_rekening'] = $this->TransaksiInvestasi->rekening_list();

        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('penarikan_investasi', $data);
        $this->load->view('footer');
    }

    public function getData($tahun)
    {
        echo json_encode($this->TransaksiInvestasi->penarikan_list($tahun)->result());
    }

    public function getDataID()
    {
        $id = $_POST["id_penarikan"];
        echo json_encode($this->TransaksiInvestasi->getWhere('v_penarikan_investasi', array('id_penarikan' => $id))->result());
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

    public function tambah_data_penarikan()
    {
        $no_rekening = $_POST['no_rekening'];
        $tgl_penarikan = $_POST['tgl_penarikan'];
        $jml_penarikan = $_POST['jml_penarikan'];
        // $thn = explode('-', $tgl_penarikan);

        $data = [
            'no_rekening' => $no_rekening,
            'tgl_penarikan' => $tgl_penarikan,
            'jml_penarikan' => $jml_penarikan,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('user_id')
        ];

        $activity_data = array(
            'user_id' => $this->session->userdata('user_id'),
            'activity' => 'Tambah Penarikan Transaksi Berhasil',
            'times' => date('Y-m-d H:i:s')
        );

        $id = $this->TransaksiInvestasi->tambah_id('penarikan_investasi', $data);
        $this->TransaksiInvestasi->tambah('activity_log', $activity_data);

        $thn = date('Y', strtotime($tgl_penarikan));
        $tgl = date($thn . '-m-d');
        $hari = $this->getHari($tgl);

        $rata_harian = $this->update_saldo_user($no_rekening, $tgl,  $thn);
        $rata_tahun = $rata_harian / $hari[1];

        $update_saldo = [
            'rata_rata_harian' => $rata_harian,
            'rata_rata_tahun' => $rata_tahun,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id')
        ];

        $this->TransaksiInvestasi->edit('saldo_harian', $update_saldo, array('no_rekening' => $no_rekening, 'tahun' => $thn));

        $this->create_qrcode($id);

        echo json_encode('');
    }

    public function update()
    {
        $id_penarikan = $_POST['id_penarikan'];
        $tgl_penarikan = $_POST['tgl_penarikan'];
        $no_rekening  =  $_POST['no_rekening'];

        $data = [
            'tgl_penarikan' => $tgl_penarikan,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id')
        ];

        $activity_data = array(
            'user_id' => $this->session->userdata('user_id'),
            'activity' => 'Ubah Penarikan Transaksi Berhasil',
            'times' => date('Y-m-d H:i:s')
        );

        $this->TransaksiInvestasi->edit('penarikan_investasi', $data, array('id_penarikan' => $id_penarikan));
        $this->TransaksiInvestasi->tambah('activity_log', $activity_data);

        $thn = date('Y', strtotime($tgl_penarikan));
        $tgl = date($thn . '-m-d');
        $hari = $this->getHari($tgl);

        $rata_harian = $this->update_saldo_user($no_rekening, $tgl,  $thn);
        $rata_tahun = $rata_harian / $hari[1];

        $update_saldo = [
            'rata_rata_harian' => $rata_harian,
            'rata_rata_tahun' => $rata_tahun,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id')
        ];

        $this->TransaksiInvestasi->edit('saldo_harian', $update_saldo, array('no_rekening' => $no_rekening, 'tahun' => $thn));

        echo json_encode($tgl_penarikan);
    }
    public function delete()
    {
        $id_penarikan = $_POST['id_penarikan'];
        $no_rekening  =  $_POST['no_rekening'];
        $tgl_penarikan = $_POST['tgl_penarikan'];

        $activity_data = array(
            'user_id' => $this->session->userdata('user_id'),
            'activity' => 'Hapus Penarikan Transaksi Berhasil',
            'times' => date('Y-m-d H:i:s')
        );

        $this->TransaksiInvestasi->hapus('penarikan_investasi', ['id_penarikan' => $id_penarikan]);
        $this->TransaksiInvestasi->tambah('activity_log', $activity_data);

        $thn = date('Y', strtotime($tgl_penarikan));
        $tgl = date($thn . '-m-d');
        $hari = $this->getHari($tgl);

        $rata_harian = $this->update_saldo_user($no_rekening, $tgl,  $thn);
        $rata_tahun = $rata_harian / $hari[1];

        $update_saldo = [
            'rata_rata_harian' => $rata_harian,
            'rata_rata_tahun' => $rata_tahun,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id')
        ];

        $this->TransaksiInvestasi->edit('saldo_harian', $update_saldo, array('no_rekening' => $no_rekening, 'tahun' => date('Y')));


        echo json_encode('');
    }

    public function getJumlah()
    {
        $no_rekening = $_POST['rekening'];
        $thn = date('Y');
        echo json_encode($this->TransaksiInvestasi->jml_transaksi($no_rekening, $thn)->result());
    }
    
     public function create_qrcode($id)
    {
        //CREATE QR-CODE

        $this->load->library('ciqrcode');

        $config['cacheable']    = true; //boolean, the default is true
        $config['cachedir']     = './assets/stisla'; //string, the default is application/cache/
        $config['errorlog']     = './assets/stisla'; //string, the default is application/logs/
        $config['imagedir']     = './assets/stisla/images/qrcode/penarikan/'; //direktori penyimpanan qr code
        $config['quality']      = true; //boolean, the default is true
        $config['size']         = '1024'; //interger, the default is 1024
        $config['black']        = array(224, 255, 255); // array, default is array(255,255,255)
        $config['white']        = array(70, 130, 180); // array, default is array(0,0,0)
        $this->ciqrcode->initialize($config);

        $image_name = $id . '.png'; //buat name dari qr code sesuai dengan nim

        $params['data'] = base_url('penarikan/index/') . $id; //data yang akan di jadikan QR CODE
        $params['level'] = 'H'; //H=High
        $params['size'] = 10;
        $params['savename'] = FCPATH . $config['imagedir'] . $image_name; //simpan image QR CODE ke folder assets/images/
        $this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
    }
}
