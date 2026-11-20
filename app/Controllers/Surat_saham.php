<?php

namespace App\Controllers;

use App\Models\SuratSaham;

class Surat_saham extends BaseController
{
    protected SuratSaham $suratSaham;

    public function __construct()
    {
        $this->suratSaham = new SuratSaham();
    }

    public function index($noSertifikat)
    {
        $data['title']          = 'IAA Investment | Data Transaksi';
        $data['data_transaksi'] = $this->suratSaham->getData(['no_sertifikat' => $noSertifikat])->getRow();

        return view('surat_saham/surat_saham_qrcode', $data);
    }
}

