<?php

namespace App\Controllers;

use App\Models\DistribusiDanPorsi;

class Distribusi_dan_porsi extends BaseController
{
    protected DistribusiDanPorsi $distribusiDanPorsi;

    public function __construct()
    {
        $this->distribusiDanPorsi = new DistribusiDanPorsi();
    }

    public function index()
    {
        $data['title'] = 'IAA Investment | Distribusi dan Porsi';

        $tahun = $this->request->getPost('tahunDispor');
        if (empty($tahun)) {
            $tahun = date('Y');
        }
        $data['tahun']        = $tahun;
        $data['porsi_dispen'] = $this->distribusiDanPorsi->show('porsi_dispen', $tahun)->getResultArray();
        $data['porsi_disda']  = $this->distribusiDanPorsi->show('porsi_disda', $tahun)->getResultArray();

        return view('header', $data)
            . view('menu')
            . view('distribusi_dan_porsi', $data)
            . view('footer');
    }
}

