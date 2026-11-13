<?php

namespace App\Controllers;

use App\Models\PenambahanSaldo;

class Penambahan_saldo extends BaseController
{
    protected PenambahanSaldo $penambahanSaldo;

    public function __construct()
    {
        $this->penambahanSaldo = new PenambahanSaldo();
    }

    public function index()
    {
        $users = $this->penambahanSaldo->getUser();
        $thn   = date('Y');

        foreach ($users as $user) {
            $noRekening = $user['no_rekening'];

            $totalInvestasi = $this->penambahanSaldo->getTransaksi($noRekening, $thn);
            $saldoHarian    = $this->penambahanSaldo->getSaldo($noRekening, $thn);

            if (! empty($saldoHarian['rata_rata_harian']) && ! empty($totalInvestasi['jml_transaksi'])) {
                $totalSaldo = $saldoHarian['rata_rata_harian'] + $totalInvestasi['jml_transaksi'];
                $hari       = $this->getHari();

                $data = [
                    'rata_rata_harian' => $totalSaldo,
                    'rata_rata_tahun'  => $hari > 0 ? ($totalSaldo / $hari) : 0,
                ];

                $this->penambahanSaldo->edit('saldo_harian', $data, [
                    'no_rekening' => $noRekening,
                    'tahun'       => $thn,
                ]);
            }
        }
    }

    public function getHari()
    {
        $tglSkrg = strtotime(date('Y-m-d'));
        $tglAwal = date('Y-m-d', strtotime(date('Y') . '-01-01 -1 days'));

        return ($tglSkrg - strtotime($tglAwal)) / 86400;
    }
}

