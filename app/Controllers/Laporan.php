<?php

namespace App\Controllers;

use App\Libraries\Cetak_pdf;
use App\Models\LaporanModel;

class Laporan extends BaseController
{
    protected LaporanModel $laporanModel;

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
    }

    public function index()
    {
        return redirect()->to(base_url('dashboard'));
    }

    public function export_pdf(string $filename, string $view, array $data, $paper = 'A4', string $orientation = 'landscape')
    {
        $cetakPdf = new Cetak_pdf();

        ini_set('memory_limit', '2048M');
        set_time_limit(300);

        $cetakPdf->setPaper($paper, $orientation);
        $cetakPdf->loadHtml(view('laporan/' . $view, $data));
        $cetakPdf->render();

        return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody($cetakPdf->output());
    }

    // PROFIL PEMEGANG SAHAM
    public function cetak_profil_pemegang_saham()
    {
        $data['title']    = 'Profil Pemegang Saham';
        $data['action']   = '<a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
        <a class="btn" id="konfersi" href="' . site_url('laporan/konfersi_profil_pemegang_saham') . '"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>
        <a target="_blank" class="btn" href="' . site_url('laporan/export_profil_pemegang_saham') . '"><i class="fas fa-file"> Konversi ke PDF</i></a>';
        $data['profiles'] = $this->laporanModel->getUsers();

        return view('laporan/cetak-profil-pemegang-saham', $data);
    }

    public function konfersi_profil_pemegang_saham()
    {
        $data['profiles'] = $this->laporanModel->getUsers();

        return view('laporan/xls-profil-pemegang-saham', $data);
    }

    public function export_profil_pemegang_saham()
    {
        $data['profiles'] = $this->laporanModel->getUsers();
        $data['title']    = 'Profil Pemegang Saham';

        return $this->export_pdf('profil-pemegang-saham.pdf', 'pdf-profil-pemegang-saham', $data);
    }

    // SALDO AWAL TAHUN
    public function saldo_awal_tahun()
    {
        $data['title'] = 'IAA Investment | Laporan';

        return view('header', $data)
            . view('menu')
            . view('laporan/saldo-awal-tahun')
            . view('footer');
    }

    public function cetak_saldo_awal_tahun()
    {
        $tahun                  = $this->request->getPost('tahun') ?: date('Y');
        $data['title']          = 'Saldo Awal Tahun';
        $data['action']         = '<a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
        <a class="btn" id="konfersi" href="' . site_url('laporan/konfersi_saldo_awal_tahun/' . $tahun) . '"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>
        <a target="_blank" class="btn" href="' . site_url('laporan/export_saldo_awal_tahun/' . $tahun) . '"><i class="fas fa-file"> Konversi ke PDF</i></a>';
        $data['data_saldo_awal'] = $this->laporanModel->getSaldoAwal($tahun);
        $data['tahun']           = $tahun;

        return view('laporan/cetak-saldo-awal-tahun', $data);
    }

    public function konfersi_saldo_awal_tahun($tahun)
    {
        $data['tahun']           = $tahun;
        $data['data_saldo_awal'] = $this->laporanModel->getSaldoAwal($tahun);

        return view('laporan/xls-saldo-awal-tahun', $data);
    }

    public function export_saldo_awal_tahun($tahun)
    {
        $data['tahun']           = $tahun;
        $data['data_saldo_awal'] = $this->laporanModel->getSaldoAwal($tahun);
        $data['title']           = 'Saldo Awal Tahun';

        return $this->export_pdf('saldo_awal_tahun.pdf', 'pdf-saldo-awal-tahun', $data);
    }

    // TRANSAKSI TERKINI
    public function cetak_transaksi_terkini()
    {
        $tglAwal  = date('Y-01-02');
        $tglAkhir = date('Y-m-d');
        $bulan    = date('m');
        $nama     = [
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
            '12' => 'Desember',
        ];
        $namaBulan = $nama[$bulan];

        $data['title']              = 'Transaksi Investasi Terknini';
        $data['nama_bulan']         = $namaBulan;
        $data['action']             = '<a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
        <a class="btn" id="konfersi" href="' . site_url('laporan/konfersi_transaksi_terkini/' . $namaBulan . '/' . $tglAwal . '/' . $tglAkhir) . '"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>
        <a target="_blank" class="btn" href="' . site_url('laporan/export_transaksi_terkini/' . $namaBulan . '/' . $tglAwal . '/' . $tglAkhir) . '"><i class="fas fa-file"> Konversi ke PDF</i></a>';
        $data['data_trans_terkini'] = $this->laporanModel->getDataTransaksiTerknini($tglAwal, $tglAkhir);

        return view('laporan/cetak-transaksi-investasi-terkini', $data);
    }

    public function konfersi_transaksi_terkini($namaBulan, $tglAwal, $tglAkhir)
    {
        $data['nama_bulan']         = $namaBulan;
        $data['data_trans_terkini'] = $this->laporanModel->getDataTransaksiTerknini($tglAwal, $tglAkhir);

        return view('laporan/xls-transaksi-investasi-terkini', $data);
    }

    public function export_transaksi_terkini($namaBulan, $tglAwal, $tglAkhir)
    {
        $data['nama_bulan']         = $namaBulan;
        $data['data_trans_terkini'] = $this->laporanModel->getDataTransaksiTerknini($tglAwal, $tglAkhir);
        $data['title']              = 'Transaksi Terkini';

        return $this->export_pdf('transaksi_terkini.pdf', 'pdf-transaksi-terkini', $data);
    }

    // TRANSAKSI BULANAN
    public function transaksi_bulanan()
    {
        $data['title'] = 'IAA Investment | Laporan';

        return view('header', $data)
            . view('menu')
            . view('laporan/transaksi-investasi-bln')
            . view('footer');
    }

    public function cetak_transaksi_invest_bln()
    {
        $bulan = $this->request->getPost('bulanTransaksi') ?: date('n');
        $nama  = [
            '1'  => 'Januari',
            '2'  => 'Februari',
            '3'  => 'Maret',
            '4'  => 'April',
            '5'  => 'Mei',
            '6'  => 'Juni',
            '7'  => 'Juli',
            '8'  => 'Agustus',
            '9'  => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
        $tahun     = $this->request->getPost('tahunTransaksi') ?: date('Y');
        $namaBulan = $nama[$bulan];

        $data['title']          = 'Transaksi Investasi Bulanan';
        $data['nama_bulan']     = $namaBulan;
        $data['tahun']          = $tahun;
        $data['action']         = '<a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
        <a class="btn" id="konfersi" href="' . site_url('laporan/konfersi_transaksi_invest_bln/' . $namaBulan . '/' . $bulan . '/' . $tahun) . '"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>
        <a target="_blank" class="btn" href="' . site_url('laporan/export_transaksi_invest_bln/' . $namaBulan . '/' . $bulan . '/' . $tahun) . '"><i class="fas fa-file"> Konversi ke PDF</i></a>';
        $data['data_trans_bln'] = $this->laporanModel->getDataTransaksiBln($bulan, $tahun);

        return view('laporan/cetak-transaksi-investasi-bln', $data);
    }

    public function konfersi_transaksi_invest_bln($namaBulan, $bulan, $tahun)
    {
        $data['bulan']          = $bulan;
        $data['nama_bulan']     = $namaBulan;
        $data['tahun']          = $tahun;
        $data['data_trans_bln'] = $this->laporanModel->getDataTransaksiBln($bulan, $tahun);

        return view('laporan/xls-transaksi-investasi-bln', $data);
    }

    public function export_transaksi_invest_bln($namaBulan, $bulan, $tahun)
    {
        $data['bulan']          = $bulan;
        $data['nama_bulan']     = $namaBulan;
        $data['tahun']          = $tahun;
        $data['data_trans_bln'] = $this->laporanModel->getDataTransaksiBln($bulan, $tahun);
        $data['title']          = 'Transaksi Bulan ' . $namaBulan;

        return $this->export_pdf('transaksi_bulan_' . $namaBulan . '.pdf', 'pdf-transaksi-bln', $data);
    }

    // PENARIKAN INVESTASI
    public function penarikan_investasi()
    {
        $data['title'] = 'IAA Investment | Laporan';

        return view('header', $data)
            . view('menu')
            . view('laporan/penarikan-investasi')
            . view('footer');
    }

    public function cetak_penarikan_investasi()
    {
        $tahun                  = $this->request->getPost('tahun') ?: date('Y');
        $data['title']          = 'Penarikan Investasi';
        $data['tahun']          = $tahun;
        $data['action']         = '<a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
        <a class="btn" id="konfersi" href="' . site_url('laporan/konfersi_penarikan_investasi/' . $tahun) . '"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>
        <a target="_blank" class="btn" id="konfersi" href="' . site_url('laporan/export_penarikan_investasi/' . $tahun) . '"><i class="fas fa-file"> Konversi ke PDF</i></a>';
        $data['data_penarikan'] = $this->laporanModel->getData('v_penarikan_investasi', ['tahun' => $tahun]);

        return view('laporan/cetak-penarikan-investasi', $data);
    }

    public function konfersi_penarikan_investasi($tahun)
    {
        $data['tahun']          = $tahun;
        $data['data_penarikan'] = $this->laporanModel->getData('v_penarikan_investasi', ['tahun' => $tahun]);

        return view('laporan/xls-penarikan-investasi', $data);
    }

    public function export_penarikan_investasi($tahun)
    {
        $data['tahun']          = $tahun;
        $data['data_penarikan'] = $this->laporanModel->getData('v_penarikan_investasi', ['tahun' => $tahun]);
        $data['title']          = 'Penarikan Investasi';

        return $this->export_pdf('penarikan_investasi_' . $tahun . '.pdf', 'pdf-penarikan-investasi', $data);
    }

    // SALDO HARIAN
    public function saldo_harian()
    {
        $data['title'] = 'IAA Investment | Laporan';

        return view('header', $data)
            . view('menu')
            . view('laporan/saldo-harian')
            . view('footer');
    }

    public function cetak_saldo_harian()
    {
        $tahun              = $this->request->getPost('tahunSaldo') ?: date('Y');
        $data['title']      = 'Saldo Harian';
        $data['tahun']      = $tahun;
        $data['action']     = '<a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
        <a class="btn" id="konfersi" href="' . site_url('laporan/konfersi_saldo_harian/' . $tahun) . '"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>
        <a target="_blank" class="btn" id="konfersi" href="' . site_url('laporan/export_saldo_harian/' . $tahun) . '"><i class="fas fa-file"> Konversi ke PDF</i></a>';
        $data['data_saldo'] = $this->laporanModel->getData('v_saldo_harian', ['tahun' => $tahun]);

        return view('laporan/cetak-saldo-harian', $data);
    }

    public function konfersi_saldo_harian($tahun)
    {
        $data['tahun']      = $tahun;
        $data['data_saldo'] = $this->laporanModel->getData('v_saldo_harian', ['tahun' => $tahun]);

        return view('laporan/xls-saldo-harian', $data);
    }

    public function export_saldo_harian($tahun)
    {
        $data['tahun']      = $tahun;
        $data['data_saldo'] = $this->laporanModel->getData('v_saldo_harian', ['tahun' => $tahun]);
        $data['title']      = 'Saldo Harian';

        return $this->export_pdf('saldo_harian_' . $tahun . '.pdf', 'pdf-saldo-harian', $data);
    }

    // DISTRIBUSI DAN PORSI
    public function distribusi_dan_porsi()
    {
        $data['title'] = 'IAA Investment | Laporan';

        return view('header', $data)
            . view('menu')
            . view('laporan/distribusi-dan-porsi')
            . view('footer');
    }

    public function cetak_distribusi_dan_porsi()
    {
        $tahun               = $this->request->getPost('tahunDisporsi') ?: date('Y');
        $data['title']       = 'Distribusi dan Porsi';
        $data['tahun']       = $tahun;
        $data['action']      = '<a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
        <a class="btn" id="konfersi" href="' . site_url('laporan/konfersi_distribusi_dan_porsi/' . $tahun) . '"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>
        <a target="_blank" class="btn" id="konfersi" href="' . site_url('laporan/export_distribusi_dan_porsi/' . $tahun) . '"><i class="fas fa-file"> Konversi ke PDF</i></a>';
        $data['data_dispen'] = $this->laporanModel->getData('porsi_dispen', ['tahun' => $tahun]);
        $data['data_disda']  = $this->laporanModel->getData('porsi_disda', ['tahun' => $tahun]);

        return view('laporan/cetak-distribusi-dan-porsi', $data);
    }

    public function konfersi_distribusi_dan_porsi($tahun)
    {
        $data['tahun']       = $tahun;
        $data['data_dispen'] = $this->laporanModel->getData('porsi_dispen', ['tahun' => $tahun]);
        $data['data_disda']  = $this->laporanModel->getData('porsi_disda', ['tahun' => $tahun]);

        return view('laporan/xls-distribusi-dan-porsi', $data);
    }

    public function export_distribusi_dan_porsi($tahun)
    {
        $data['tahun']       = $tahun;
        $data['data_dispen'] = $this->laporanModel->getData('porsi_dispen', ['tahun' => $tahun]);
        $data['data_disda']  = $this->laporanModel->getData('porsi_disda', ['tahun' => $tahun]);
        $data['title']       = 'Distribusi dan Porsi';

        return $this->export_pdf('distribusi_dan_porsi_' . $tahun . '.pdf', 'pdf-distribusi-dan-porsi', $data);
    }

    // BAGI HASIL SHU
    public function bagi_hasil_shu()
    {
        $data['title']     = 'IAA Investment | Laporan';
        $data['kecamatan'] = $this->laporanModel->getKec();

        return view('header', $data)
            . view('menu')
            . view('laporan/bagi-hasil-shu', $data)
            . view('footer');
    }

    protected function getBagiHasilRows($tahun, string $kec): array
    {
        if ($kec === 'Semua') {
            return $this->laporanModel->getDataBagiHasil(['tahun' => $tahun]);
        }

        return $this->laporanModel->getDataBagiHasil(['tahun' => $tahun, 'kec' => $kec]);
    }

    public function cetak_bagi_hasil_shu()
    {
        $tahun = $this->request->getPost('tahunBagiHasil') ?: date('Y');
        $kec   = $this->request->getPost('Kec') ?: 'Semua';

        $data['title']           = 'Bagi Hasil SHU';
        $data['tahun']           = $tahun;
        $data['kecamatan']       = $kec;
        $data['action']          = '<a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
        <a class="btn" id="konfersi" href="' . site_url('laporan/konfersi_bagi_hasil_shu/' . $tahun . '/' . $kec) . '"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>
        <a target="_blank" class="btn" id="konfersi" href="' . site_url('laporan/export_bagi_hasil_shu/' . $tahun . '/' . $kec) . '"><i class="fas fa-file"> Konversi ke PDF</i></a>';
        $data['data_bagi_hasil'] = $this->getBagiHasilRows($tahun, $kec);

        return view('laporan/cetak-bagi-hasil-shu', $data);
    }

    public function konfersi_bagi_hasil_shu($tahun, $kec)
    {
        $data['tahun']           = $tahun;
        $data['kecamatan']       = $kec;
        $data['data_bagi_hasil'] = $this->getBagiHasilRows($tahun, $kec);

        return view('laporan/xls-bagi-hasil-shu', $data);
    }

    public function export_bagi_hasil_shu($tahun, $kec)
    {
        $data['tahun']           = $tahun;
        $data['kecamatan']       = $kec;
        $data['data_bagi_hasil'] = $this->getBagiHasilRows($tahun, $kec);
        $data['title']           = 'Bagi Hasil SHU';

        return $this->export_pdf('bagi_hasil_shu_' . $tahun . '.pdf', 'pdf-bagi-hasil-shu', $data);
    }

    public function label_surat()
    {
        $data['title']     = 'IAA Investment | Label';
        $data['kecamatan'] = $this->laporanModel->getKec();

        return view('header', $data)
            . view('menu')
            . view('laporan/label-surat', $data)
            . view('footer');
    }

    public function cetak_label_surat()
    {
        $tahun = $this->request->getPost('tahun') ?: date('Y');
        $kec   = $this->request->getPost('Kec');

        $data['title']      = 'Label';
        $data['data_label'] = $this->laporanModel->getData('v_label_surat', ['tahun' => $tahun, 'kec' => $kec]);

        return $this->export_pdf(
            'label_surat_' . $kec . '_' . $tahun . '.pdf',
            'pdf-label-surat',
            $data,
            [0, 0, 869, 415],
            'portrait'
        );
    }

    public function rek_bank_anggota()
    {
        $data['title']    = 'IAA Investment | Rekening Bank Anggota';
        $data['data_rek'] = $this->laporanModel->getRekeningBank();
        $data['action']   = '<a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
        <a class="btn" id="konfersi" href="' . site_url('laporan/konfersi_rek_bank_anggota/') . '"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>';

        return view('laporan/cetak-rek-bank-anggota', $data);
    }

    public function konfersi_rek_bank_anggota()
    {
        $data['data_rek'] = $this->laporanModel->getRekeningBank();

        return view('laporan/xls-rek-bank-anggota', $data);
    }
}

