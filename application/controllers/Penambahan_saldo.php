<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penambahan_saldo extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('PenambahanSaldo');
    }

    public function index()
    {
        $users = $this->PenambahanSaldo->getUser();
        $thn = date('Y');

        foreach ($users as $user) {
            $no_rekening = $user['no_rekening'];

            $total_investasi = $this->PenambahanSaldo->getTransaksi($no_rekening, $thn);
            $saldo_harian = $this->PenambahanSaldo->getSaldo($no_rekening, $thn);

            if ($saldo_harian['rata_rata_harian'] != null && $total_investasi['jml_transaksi'] != null) {
                $total_saldo = $saldo_harian['rata_rata_harian'] + $total_investasi['jml_transaksi'];
                $hari = $this->getHari();

                $data = [
                    'rata_rata_harian' => $total_saldo,
                    'rata_rata_tahun' => ($total_saldo / $hari)
                ];
            }

            $this->PenambahanSaldo->edit('saldo_harian', $data, ['no_rekening' => $no_rekening, 'tahun' => $thn]);
        }
    }

    public function getHari()
    {
        $tgl_skrg = strtotime(date("Y-m-d"));
        $tgl_awal = date('Y-m-d', strtotime(date('Y') . "-01-01" . "-1 days"));
        $hari = ($tgl_skrg - strtotime($tgl_awal)) / 86400;

        return $hari;
    }
}
