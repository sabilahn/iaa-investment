<?php

namespace App\Controllers;

use App\Models\SaldoHarian;
use App\Models\TransaksiInvestasi;
use DateInterval;
use DatePeriod;
use DateTime;

class Saldo_harian extends BaseController
{
    protected SaldoHarian $saldoHarian;
    protected TransaksiInvestasi $transaksiInvestasi;

    public function __construct()
    {
        $this->saldoHarian        = new SaldoHarian();
        $this->transaksiInvestasi = new TransaksiInvestasi();
    }

    public function index()
    {
        $tahun = $this->request->getPost('tahun_saldo');
        $tahun = empty($tahun) ? date('Y') : $tahun;

        $data = [
            'tahun'        => $tahun,
            'daftar_saldo' => $this->saldoHarian->saldo_harian_list($tahun)->getResultArray(),
            'action'       => '<a class="btn btn-primary" href="' . site_url('saldo_harian/update_saldo/' . $tahun) . '"><i class="fas fa-sync"> Sesuaikan Saldo</i></a>',
            'title'        => 'IAA Investment | Saldo Harian',
        ];

        return view('header', $data)
            . view('menu')
            . view('saldo_harian', $data)
            . view('footer');
    }

    public function getHari($tahun)
    {
        return date('L', strtotime("$tahun-01-01")) ? 366 : 365;
    }

    public function update_saldo_user($user, $tahun)
    {
        $saldo     = 0;
        $akumulasi = 0;

        $start  = new DateTime("$tahun-01-01");
        $end    = new DateTime("$tahun-12-31");
        $period = new DatePeriod($start, new DateInterval('P1D'), $end->modify('+1 day'));

        foreach ($period as $date) {
            $tanggal   = $date->format('Y-m-d');
            $transaksi = $this->transaksiInvestasi->cekTransaksi($user, $tanggal)->getResultArray();
            $penarikan = $this->transaksiInvestasi->cekPenarikan($user, $tanggal)->getResultArray();

            $totalTransaksi = array_sum(array_column($transaksi, 'jml_transaksi'));
            $totalPenarikan = array_sum(array_column($penarikan, 'jml_penarikan'));

            $saldo     += $totalTransaksi - $totalPenarikan + $akumulasi;
            $akumulasi += $totalTransaksi - $totalPenarikan;
        }

        set_time_limit(500);

        return $saldo;
    }

    public function update_saldo($tahun, $offset = 0, $limit = 100)
    {
        $total = $this->transaksiInvestasi->jumlah_rekening();
        $hari  = $this->getHari($tahun);

        $users = $this->transaksiInvestasi->rekening_list_limit((int) $limit, (int) $offset)->getResultArray();

        if (empty($users)) {
            $this->session->setFlashdata('success', '✅ Semua data berhasil diproses.');

            return redirect()->to(base_url('saldo_harian'));
        }

        $this->db->transBegin();

        try {
            foreach ($users as $user) {
                $noRekening = $user['no_rekening'];

                $transaksiAll = $this->transaksiInvestasi->cekTransaksi2($noRekening, $tahun)->getResultArray();
                $penarikanAll = $this->transaksiInvestasi->cekPenarikanTahun($noRekening, $tahun)->getResultArray();

                $transaksiIndex = [];
                foreach ($transaksiAll as $row) {
                    $transaksiIndex[$row['tgl_transaksi']][] = $row;
                }

                $penarikanIndex = [];
                foreach ($penarikanAll as $row) {
                    $penarikanIndex[$row['tgl_penarikan']][] = $row;
                }

                $saldoSkr        = 0;
                $penambahanSaldo = 0;

                $start  = new DateTime("$tahun-01-01");
                $end    = new DateTime("$tahun-12-31");
                $period = new DatePeriod($start, new DateInterval('P1D'), $end->modify('+1 day'));

                foreach ($period as $date) {
                    $tanggal = $date->format('Y-m-d');

                    $transaksis = $transaksiIndex[$tanggal] ?? [];
                    $penarikans = $penarikanIndex[$tanggal] ?? [];

                    $jmlTransaksi = array_sum(array_column($transaksis, 'jml_transaksi'));
                    $jmlPenarikan = array_sum(array_column($penarikans, 'jml_penarikan'));

                    $saldoSkr        += ($jmlTransaksi - $jmlPenarikan) + $penambahanSaldo;
                    $penambahanSaldo += ($jmlTransaksi - $jmlPenarikan);
                }

                $rataTahun = $saldoSkr / $hari;

                $dataUpdate = [
                    'rata_rata_harian' => $saldoSkr,
                    'rata_rata_tahun'  => $rataTahun,
                    'updated_at'       => date('Y-m-d H:i:s'),
                    'updated_by'       => $this->session->get('user_id'),
                ];

                $this->transaksiInvestasi->edit('saldo_harian', $dataUpdate, [
                    'no_rekening' => $noRekening,
                    'tahun'       => $tahun,
                ]);
            }

            $this->db->transCommit();
            $this->session->setFlashdata('success', "✅ Batch berhasil diproses: $limit data.");
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', '❌ Gagal proses batch: ' . $e->getMessage());
            $this->session->setFlashdata('error', 'Batch gagal diproses.');

            return redirect()->to(base_url('saldo_harian'));
        }

        $nextOffset = (int) $offset + (int) $limit;
        if ($nextOffset < $total) {
            return redirect()->to(base_url("saldo_harian/update_saldo/$tahun/$nextOffset/$limit"));
        }

        $this->session->setFlashdata('success', 'Semua data berhasil disimpan!');

        return redirect()->to(base_url('saldo_harian'));
    }
}

