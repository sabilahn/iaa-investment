<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('LaporanModel');
        if ($this->session->userdata('status') != 'login') {
            redirect(base_url('auth'));
        }
    }
    public function index()
    {
    }

    public function export_pdf($filename, $view, $data)
    {
        $this->load->library('cetak_pdf');
        
        $old_limit = ini_set("memory_limit", "512M");
        
        $this->cetak_pdf->setPaper('A4', 'landscape');
        $this->cetak_pdf->filename = $filename;
        $this->cetak_pdf->load_view('laporan/' . $view, $data);
    }

    // PROFIL PEMEGANG SAHAM
    public function cetak_profil_pemegang_saham()
    {
        $data['title'] = 'Profil Pemegang Saham';
        $data['action'] = '<a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
        <a class="btn" id="konfersi" href="' . site_url() . 'laporan/konfersi_profil_pemegang_saham"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>
        <a target="_blank" class="btn" href="' . site_url() . 'laporan/export_profil_pemegang_saham"><i class="fas fa-file"> Konversi ke PDF</i></a>';
        $data['profiles'] = $this->LaporanModel->getUsers();
        // $activity_data = array(
        //     'user_id' => $this->session->userdata('user_id'),
        //     'activity' => 'Lihat Laporan Profil Pemegang Saham',
        //     'times' => date('Y-m-d H:i:s')
        // );

        // $this->LaporanModel->tambah('activity_log', $activity_data);

        $this->load->view('laporan/cetak-profil-pemegang-saham', $data);
    }
    public function konfersi_profil_pemegang_saham()
    {
        $data['profiles'] = $this->LaporanModel->getUsers();

        $this->load->view('laporan/xls-profil-pemegang-saham', $data);
    }
    public function export_profil_pemegang_saham()
    {
        $data['profiles'] = $this->LaporanModel->getUsers();
        $data['title'] = 'Profil Pemegang Saham';

        $this->export_pdf('profil-pemegang-saham.pdf', 'pdf-profil-pemegang-saham', $data);
    }

    // SALDO AWAL TAHUN

    public function saldo_awal_tahun()
    {
        $data['title'] = 'IAA Investment | Laporan';

        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('laporan/saldo-awal-tahun');
        $this->load->view('footer');
    }

    public function cetak_saldo_awal_tahun()
    {
        $tahun = $_POST['tahun'];
        $data['title'] = 'Saldo Awal Tahun';
        
        $data['action'] = '<a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
        <a class="btn" id="konfersi" href="' . site_url() . 'laporan/konfersi_saldo_awal_tahun/'.$tahun.'"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>
        <a target="_blank" class="btn" href="' . site_url() . 'laporan/export_saldo_awal_tahun/'.$tahun.'"><i class="fas fa-file"> Konversi ke PDF</i></a>';
        $data['data_saldo_awal'] = $this->LaporanModel->getSaldoAwal($tahun);
        $data['tahun'] = $tahun;

        // $activity_data = array(
        //     'user_id' => $this->session->userdata('user_id'),
        //     'activity' => 'Lihat Laporan Transaksi Terkini',
        //     'times' => date('Y-m-d H:i:s')
        // );

        // $this->LaporanModel->tambah('activity_log', $activity_data);

        $this->load->view('laporan/cetak-saldo-awal-tahun', $data);
    }
    public function konfersi_saldo_awal_tahun($tahun)
    {
        $data['tahun'] = $tahun;
        $data['data_saldo_awal'] = $this->LaporanModel->getSaldoAwal($tahun);

        $this->load->view('laporan/xls-saldo-awal-tahun', $data);
    }
    public function export_saldo_awal_tahun($tahun)
    {
        $data['tahun'] = $tahun;
        $data['data_saldo_awal'] = $this->LaporanModel->getSaldoAwal($tahun);

        $data['title'] = 'Saldo Awal Tahun';

        $this->export_pdf('saldo_awal_tahun.pdf', 'pdf-saldo-awal-tahun', $data);
    }

    // TRANSAKSI TERKINI
    public function cetak_transaksi_terkini()
    {
        $tgl_awal = date('Y-01-02');
        $tgl_akhir = date('Y-m-d');
        $data['title'] = 'Transaksi Investasi Terknini';
        $bulan = date('m');
        $nama = array(
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        );
        $nama_bulan = $nama[$bulan];
        $data['nama_bulan'] = $nama_bulan;
        $data['action'] = '<a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
        <a class="btn" id="konfersi" href="' . site_url() . 'laporan/konfersi_transaksi_terkini/' . $nama_bulan . '/' . $tgl_awal . '/' . $tgl_akhir . '"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>
        <a target="_blank" class="btn" href="' . site_url() . 'laporan/export_transaksi_terkini/' . $nama_bulan . '/' . $tgl_awal . '/' . $tgl_akhir . '"><i class="fas fa-file"> Konversi ke PDF</i></a>';
        $data['data_trans_terkini'] = $this->LaporanModel->getDataTransaksiTerknini($tgl_awal, $tgl_akhir);

        // $activity_data = array(
        //     'user_id' => $this->session->userdata('user_id'),
        //     'activity' => 'Lihat Laporan Transaksi Terkini',
        //     'times' => date('Y-m-d H:i:s')
        // );

        // $this->LaporanModel->tambah('activity_log', $activity_data);

        $this->load->view('laporan/cetak-transaksi-investasi-terkini', $data);
    }
    public function konfersi_transaksi_terkini($nama_bulan, $tgl_awal, $tgl_akhir)
    {
        $data['nama_bulan'] = $nama_bulan;
        $data['data_trans_terkini'] = $this->LaporanModel->getDataTransaksiTerknini($tgl_awal, $tgl_akhir);

        $this->load->view('laporan/xls-transaksi-investasi-terkini', $data);
    }

    public function export_transaksi_terkini($nama_bulan, $tgl_awal, $tgl_akhir)
    {
        $data['nama_bulan'] = $nama_bulan;
        $data['data_trans_terkini'] = $this->LaporanModel->getDataTransaksiTerknini($tgl_awal, $tgl_akhir);

        $data['title'] = 'Transaksi Terkini';

        $this->export_pdf('transaksi_terkini.pdf', 'pdf-transaksi-terkini', $data);
    }

    // TRANSAKSI BULANAN
    public function transaksi_bulanan()
    {
        $data['title'] = 'IAA Investment | Laporan';

        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('laporan/transaksi-investasi-bln');
        $this->load->view('footer');
    }
    public function cetak_transaksi_invest_bln()
    {
        $bulan = $this->input->post('bulanTransaksi');
        $nama = array(
            '1' => 'Januari',
            '2' => 'Februari',
            '3' => 'Maret',
            '4' => 'April',
            '5' => 'Mei',
            '6' => 'Juni',
            '7' => 'Juli',
            '8' => 'Agustus',
            '9' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        );
        $tahun = $this->input->post('tahunTransaksi');
        $nama_bulan = $nama[$bulan];
        $data['title'] = 'Transaksi Investasi Bulanan';
        $data['nama_bulan'] = $nama[$bulan];
        $data['tahun'] = $tahun;
        $data['action'] = '<a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
        <a class="btn" id="konfersi" href="' . site_url() . 'laporan/konfersi_transaksi_invest_bln/' . $nama_bulan . '/' . $bulan . '/' . $tahun . '"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>
        <a target="_blank" class="btn" href="' . site_url() . 'laporan/export_transaksi_invest_bln/' . $nama_bulan . '/' . $bulan . '/' . $tahun . '"><i class="fas fa-file"> Konversi ke PDF</i></a>';
        $data['data_trans_bln'] = $this->LaporanModel->getDataTransaksiBln($bulan, $tahun);

        // $activity_data = array(
        //     'user_id' => $this->session->userdata('user_id'),
        //     'activity' => 'Lihat Laporan Transaksi Bulanan',
        //     'times' => date('Y-m-d H:i:s')
        // );

        // $this->LaporanModel->tambah('activity_log', $activity_data);

        $this->load->view('laporan/cetak-transaksi-investasi-bln', $data);
    }
    public function konfersi_transaksi_invest_bln($nama_bulan, $bulan, $tahun)
    {
        $data['bulan'] = $bulan;
        $data['nama_bulan'] = $nama_bulan;
        $data['tahun'] = $tahun;
        $data['data_trans_bln'] = $this->LaporanModel->getDataTransaksiBln($bulan, $tahun);
        $this->load->view('laporan/xls-transaksi-investasi-bln', $data);
    }
    public function export_transaksi_invest_bln($nama_bulan, $bulan, $tahun)

    {
        $data['bulan'] = $bulan;
        $data['nama_bulan'] = $nama_bulan;
        $data['tahun'] = $tahun;
        $data['data_trans_bln'] = $this->LaporanModel->getDataTransaksiBln($bulan, $tahun);

        $data['title'] = 'Transaksi Bulan ' . $nama_bulan;

        $this->export_pdf('transaksi_bulan_' . $nama_bulan . '.pdf', 'pdf-transaksi-bln', $data);
    }


    //PENARIKAN INVESTASI

    public function penarikan_investasi()
    {
        $data['title'] = 'IAA Investment | Laporan';

        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('laporan/penarikan-investasi.php');
        $this->load->view('footer');
    }
    public function cetak_penarikan_investasi()
    {
        $tahun = $this->input->post('tahun');

        $data['title'] = 'Penarikan Investasi';
        $data['tahun'] = $tahun;
        $data['action'] = '<a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
        <a class="btn" id="konfersi" href="' . site_url() . 'laporan/konfersi_penarikan_investasi/' . $tahun . '"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>
        <a target="_blank" class="btn" id="konfersi" href="' . site_url() . 'laporan/export_penarikan_investasi/' . $tahun . '"><i class="fas fa-file"> Konversi ke PDF</i></a>';
        $data['data_penarikan'] = $this->LaporanModel->getData('v_penarikan_investasi', array('tahun' => $tahun));

        // $activity_data = array(
        //     'user_id' => $this->session->userdata('user_id'),
        //     'activity' => 'Lihat Laporan Penarikan Investasi',
        //     'times' => date('Y-m-d H:i:s')
        // );

        // $this->LaporanModel->tambah('activity_log', $activity_data);

        $this->load->view('laporan/cetak-penarikan-investasi', $data);
    }
    public function konfersi_penarikan_investasi($tahun)
    {
        $data['tahun'] = $tahun;
        $data['data_penarikan'] = $this->LaporanModel->getData('v_penarikan_investasi', array('tahun' => $tahun));
        $this->load->view('laporan/xls-penarikan-investasi', $data);
    }
    public function export_penarikan_investasi($tahun)
    {
        $data['tahun'] = $tahun;
        $data['data_penarikan'] = $this->LaporanModel->getData('v_penarikan_investasi', array('tahun' => $tahun));

        $data['title'] = 'Penarikan Investasi';

        $this->export_pdf('penarikan_investasi_' . $tahun . '.pdf', 'pdf-penarikan-investasi', $data);
    }

    // SALDO HARIAN
    public function saldo_harian()
    {
        $data['title'] = 'IAA Investment | Laporan';

        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('laporan/saldo-harian');
        $this->load->view('footer');
    }
    public function cetak_saldo_harian()
    {
        $tahun = $this->input->post('tahunSaldo');
        $data['title'] = 'Saldo Harian';
        $data['tahun'] = $tahun;
        $data['action'] = '<a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
        <a class="btn" id="konfersi" href="' . site_url() . 'laporan/konfersi_saldo_harian/' . $tahun . '"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>
        <a target="_blank" class="btn" id="konfersi" href="' . site_url() . 'laporan/export_saldo_harian/' . $tahun . '"><i class="fas fa-file"> Konversi ke PDF</i></a>';
        $data['data_saldo'] = $this->LaporanModel->getData('v_saldo_harian', array('tahun' => $tahun));

        // $activity_data = array(
        //     'user_id' => $this->session->userdata('user_id'),
        //     'activity' => 'Lihat Laporan Saldo Harian',
        //     'times' => date('Y-m-d H:i:s')
        // );

        // $this->LaporanModel->tambah('activity_log', $activity_data);

        $this->load->view('laporan/cetak-saldo-harian', $data);
    }
    public function konfersi_saldo_harian($tahun)
    {
        $data['tahun'] = $tahun;
        $data['data_saldo'] = $this->LaporanModel->getData('v_saldo_harian', array('tahun' => $tahun));
        $this->load->view('laporan/xls-saldo-harian', $data);
    }
    public function export_saldo_harian($tahun)
    {
        $data['tahun'] = $tahun;
        $data['data_saldo'] = $this->LaporanModel->getData('v_saldo_harian', array('tahun' => $tahun));

        $data['title'] = 'Saldo Harian';

        $this->export_pdf('saldo_harian_' . $tahun . '.pdf', 'pdf-saldo-harian', $data);
    }

    // DISTRIBUSI DAN PORSI
    public function distribusi_dan_porsi()
    {
        $data['title'] = 'IAA Investment | Laporan';

        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('laporan/distribusi-dan-porsi');
        $this->load->view('footer');
    }
    public function cetak_distribusi_dan_porsi()
    {
        $tahun = $this->input->post('tahunDisporsi');
        $data['title'] = 'Distribusi dan Porsi';
        $data['tahun'] = $tahun;
        $data['action'] = '<a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
        <a class="btn" id="konfersi" href="' . site_url() . 'laporan/konfersi_distribusi_dan_porsi/' . $tahun . '"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>
        <a target="_blank" class="btn" id="konfersi" href="' . site_url() . 'laporan/export_distribusi_dan_porsi/' . $tahun . '"><i class="fas fa-file"> Konversi ke PDF</i></a>';
        $data['data_dispen'] = $this->LaporanModel->getData('porsi_dispen', array('tahun' => $tahun));
        $data['data_disda'] = $this->LaporanModel->getData('porsi_disda', array('tahun' => $tahun));

        // $activity_data = array(
        //     'user_id' => $this->session->userdata('user_id'),
        //     'activity' => 'Lihat Laporan Distribusi dan Porsi',
        //     'times' => date('Y-m-d H:i:s')
        // );

        // $this->LaporanModel->tambah('activity_log', $activity_data);
        $this->load->view('laporan/cetak-distribusi-dan-porsi', $data);
    }
    public function konfersi_distribusi_dan_porsi($tahun)
    {
        $data['tahun'] = $tahun;
        $data['data_dispen'] = $this->LaporanModel->getData('porsi_dispen', array('tahun' => $tahun));
        $data['data_disda'] = $this->LaporanModel->getData('porsi_disda', array('tahun' => $tahun));
        $this->load->view('laporan/xls-distribusi-dan-porsi', $data);
    }
    public function export_distribusi_dan_porsi($tahun)
    {
        $data['tahun'] = $tahun;
        $data['data_dispen'] = $this->LaporanModel->getData('porsi_dispen', array('tahun' => $tahun));
        $data['data_disda'] = $this->LaporanModel->getData('porsi_disda', array('tahun' => $tahun));

        $data['title'] = 'Distribusi dan Porsi';

        $this->export_pdf('distribusi_dan_porsi_' . $tahun . '.pdf', 'pdf-distribusi-dan-porsi', $data);
    }

    // BAGI HASIL SHU
    public function bagi_hasil_shu()
    {
        $data['title'] = 'IAA Investment | Laporan';
        $data['kecamatan'] = $this->LaporanModel->getKec();

        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('laporan/bagi-hasil-shu');
        $this->load->view('footer');
    }
    public function cetak_bagi_hasil_shu()
    {
        $tahun = $this->input->post('tahunBagiHasil');
        $kec = $this->input->post('Kec');

        $data['title'] = 'Bagi Hasil SHU';
        $data['tahun'] = $tahun;
        $data['kecamatan'] = $kec;
        $data['action'] = '<a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
        <a class="btn" id="konfersi" href="' . site_url() . 'laporan/konfersi_bagi_hasil_shu/' . $tahun . '/' . $kec . '"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>
        <a target="_blank" class="btn" id="konfersi" href="' . site_url() . 'laporan/export_bagi_hasil_shu/' . $tahun . '/' . $kec . '"><i class="fas fa-file"> Konversi ke PDF</i></a>';

        if ($kec == "Semua") {
            $data['data_bagi_hasil'] = $this->LaporanModel->getDataBagiHasil(['tahun' => $tahun]);
        } else {
            $data['data_bagi_hasil'] = $this->LaporanModel->getDataBagiHasil(['tahun' => $tahun, 'kec' => $kec]);
        }

        // $activity_data = array(
        //     'user_id' => $this->session->userdata('user_id'),
        //     'activity' => 'Lihat Laporan Bagi Hasil SHU',
        //     'times' => date('Y-m-d H:i:s')
        // );

        // $this->LaporanModel->tambah('activity_log', $activity_data);

        $this->load->view('laporan/cetak-bagi-hasil-shu', $data);
    }
    public function konfersi_bagi_hasil_shu($tahun, $kec)
    {
        $data['tahun'] = $tahun;
        $data['kecamatan'] = $kec;
        if ($kec == "Semua") {
            $data['data_bagi_hasil'] = $this->LaporanModel->getDataBagiHasil(['tahun' => $tahun]);
        } else {
            $data['data_bagi_hasil'] = $this->LaporanModel->getDataBagiHasil(['tahun' => $tahun, 'kec' => $kec]);
        }
        $this->load->view('laporan/xls-bagi-hasil-shu', $data);
    }
    public function export_bagi_hasil_shu($tahun, $kec)
    {
        $data['tahun'] = $tahun;
        $data['kecamatan'] = $kec;
        if ($kec == "Semua") {
            $data['data_bagi_hasil'] = $this->LaporanModel->getDataBagiHasil(['tahun' => $tahun]);
        } else {
            $data['data_bagi_hasil'] = $this->LaporanModel->getDataBagiHasil(['tahun' => $tahun, 'kec' => $kec]);
        }
        $data['title'] = 'Bagi Hasil SHU';

        $this->export_pdf('bagi_hasil_shu_' . $tahun . '.pdf', 'pdf-bagi-hasil-shu', $data);
    }

    public function label_surat()
    {
        $data['title'] = 'IAA Investment | Label';

        $data['kecamatan'] = $this->LaporanModel->getKec();

        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('laporan/label-surat');
        $this->load->view('footer');
    }
    public function cetak_label_surat()
    {
        $data['title'] = 'IAA Investment | Label';
        $tahun = $this->input->post('tahun');
        $kec = $this->input->post('Kec');

        $data['data_label'] = $this->LaporanModel->getData('v_label_surat', array('tahun' => $tahun, 'kec' => $kec));

        $data['title'] = 'Label';

        $this->load->library('cetak_pdf');

        $customPaper = array(0, 0, 869, 415);
        $this->cetak_pdf->set_paper($customPaper);
        $this->cetak_pdf->filename = 'label_surat_' . $kec . '_' . $tahun . '.pdf';
        $this->cetak_pdf->load_view('laporan/pdf-label-surat', $data);
    }
    public function rek_bank_anggota()
    {
        $data['title'] = 'IAA Investment | Rekening Bank Anggota';

        $data['data_rek'] = $this->LaporanModel->getRekeningBank();
        
        $data['action'] = '<a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
        <a class="btn" id="konfersi" href="' . site_url() . 'laporan/konfersi_rek_bank_anggota/"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>';

        $this->load->view('laporan/cetak-rek-bank-anggota', $data);
    }
    public function konfersi_rek_bank_anggota()
    {
        $data['data_rek'] = $this->LaporanModel->getRekeningBank();

        $this->load->view('laporan/xls-rek-bank-anggota', $data);
    }
}
