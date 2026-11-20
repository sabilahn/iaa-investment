<?php

namespace App\Controllers;

use App\Models\TransaksiInvestasi;

class Saham_umum extends BaseController
{
    protected TransaksiInvestasi $transaksiInvestasi;

    public function __construct()
    {
        $this->transaksiInvestasi = new TransaksiInvestasi();
    }

    public function index()
    {
        $data['title'] = 'IAA Investment | Saham Umum';

        return view('header', $data)
            . view('menu')
            . view('saham_umum', $data)
            . view('footer');
    }

    public function getData($tahun)
    {
        return $this->response->setJSON(
            $this->transaksiInvestasi->saldoInvestasi($tahun)->getResult()
        );
    }

    public function getDataID()
    {
        $noRekening = $this->request->getPost('no_rekening');
        $tahun      = $this->request->getPost('tahun');

        return $this->response->setJSON(
            $this->transaksiInvestasi->getSaldoId($tahun, $noRekening)->getResult()
        );
    }
}

