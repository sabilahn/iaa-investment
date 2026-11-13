<?php

namespace App\Controllers;

use App\Models\BagiHasil;

class Bagi_hasil extends BaseController
{
    protected BagiHasil $bagiHasil;

    public function __construct()
    {
        $this->bagiHasil = new BagiHasil();
    }

    public function index()
    {
        $data['title'] = 'IAA Investment | Bagi Hasil SHU';

        $tahun = $this->request->getPost('tahunBagiHasil');
        if (empty($tahun)) {
            $tahun = date('Y');
        }
        $data['tahun']           = $tahun;
        $data['data_bagi_hasil'] = $this->bagiHasil->data($tahun)->getResultArray();

        return view('header', $data)
            . view('menu')
            . view('bagi_hasil', $data)
            . view('footer');
    }

    public function lihat_data()
    {
        return $this->index();
    }
}

