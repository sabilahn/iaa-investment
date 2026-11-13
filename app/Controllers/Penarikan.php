<?php

namespace App\Controllers;

use App\Models\TransaksiInvestasi;

class Penarikan extends BaseController
{
    protected TransaksiInvestasi $transaksiInvestasi;

    public function __construct()
    {
        $this->transaksiInvestasi = new TransaksiInvestasi();
    }

    public function index($id)
    {
        $data['title']          = 'IAA Investment | Data Transaksi';
        $data['data_penarikan'] = $this->transaksiInvestasi
            ->getWhere('v_penarikan_investasi', ['id_penarikan' => $id])
            ->getRow();

        return view('surat_saham/surat_keterangan_qrcode', $data);
    }
}

