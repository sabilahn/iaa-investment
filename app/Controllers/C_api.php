<?php

namespace App\Controllers;

use App\Models\M_api;

class C_api extends BaseController
{
    protected M_api $mApi;

    public function __construct()
    {
        $this->mApi = new M_api();
    }

    public function Users_get()
    {
        $data = $this->mApi->getUser();
        $row  = [];

        foreach ($data as $res) {
            $row[] = [
                'user_id'  => $res['user_id'],
                'name'     => ucwords((string) ($res['nama'] ?? '')),
                'username' => $res['username'],
                'userpass' => $res['userpass'],
                'role'     => $res['role'],
            ];
        }

        return $this->response->setJSON($row)->setStatusCode(200);
    }

    public function Clients_get()
    {
        $data = $this->mApi->getClient();
        $row  = [];

        foreach ($data as $res) {
            $tglLahir  = date('d-m-Y', strtotime($res['tgl_lahir']));
            $tglDaftar = date('d-m-Y', strtotime($res['tgl_daftar']));

            $row[] = [
                'no_rekening' => $res['no_rekening'],
                'nik'         => $res['nik'],
                'nama'        => ucwords((string) ($res['nama'] ?? '')),
                'tgl_lahir'   => ucwords((string) ($res['tmp_lahir'] ?? '')) . '/' . $tglLahir,
                'alamat'      => ucwords((string) ($res['dsn'] ?? '')) . ' ' . ucwords((string) ($res['ds'] ?? '')) . ' RT/RW ' . $res['rt'] . '/' . $res['rw'],
                'kec_kab'     => ucwords((string) ($res['kec'] ?? '')) . ' - ' . ucwords((string) ($res['kab'] ?? '')),
                'tgl_daftar'  => $tglDaftar,
            ];
        }

        return $this->response->setJSON($row)->setStatusCode(200);
    }

    public function ClientId_get($noRekening)
    {
        $data = $this->mApi->getClientId($noRekening);
        $row  = [];

        foreach ($data as $res) {
            $tglLahir  = date('d-m-Y', strtotime($res['tgl_lahir']));
            $tglDaftar = date('d-m-Y', strtotime($res['tgl_daftar']));

            $row[] = [
                'no_rekening' => $res['no_rekening'],
                'nik'         => $res['nik'],
                'nama'        => ucwords((string) ($res['nama'] ?? '')),
                'tgl_lahir'   => ucwords((string) ($res['tmp_lahir'] ?? '')) . '/' . $tglLahir,
                'alamat'      => ucwords((string) ($res['dsn'] ?? '')) . ' ' . ucwords((string) ($res['ds'] ?? '')) . ' RT/RW ' . $res['rt'] . '/' . $res['rw'],
                'kec_kab'     => ucwords((string) ($res['kec'] ?? '')) . ' - ' . ucwords((string) ($res['kab'] ?? '')),
                'tgl_daftar'  => $tglDaftar,
            ];
        }

        return $this->response->setJSON($row)->setStatusCode(200);
    }

    public function SaldoHarianId_get($noRekening)
    {
        $data = $this->mApi->getRataHarianId($noRekening);
        $row  = [];

        foreach ($data as $res) {
            $row[] = [
                'tahun'            => $res['tahun'],
                'nilai_investasi'  => number_format((float) $res['nilai_investasi'], 2, ',', '.'),
                'rata_rata_harian' => number_format((float) $res['rata_rata_harian'], 2, ',', '.'),
                'rata_rata_tahun'  => number_format((float) $res['rata_rata_tahun'], 2, ',', '.'),
            ];
        }

        return $this->response->setJSON($row)->setStatusCode(200);
    }

    public function DividenId_get($noRekening)
    {
        $data = $this->mApi->getDividenId($noRekening);
        $row  = [];

        foreach ($data as $res) {
            $row[] = [
                'tahun'         => $res['tahun'],
                'saham_persero' => number_format((float) $res['saham_persero'], 2, ',', '.'),
                'shu'           => number_format((float) $res['shu'], 2, ',', '.'),
                'bagi_hasil'    => $res['nisbah_bagi_hasil'],
            ];
        }

        return $this->response->setJSON($row)->setStatusCode(200);
    }

    public function TransaksiId_get($noRekening)
    {
        $data = $this->mApi->getTransaksi($noRekening);
        $row  = [];

        foreach ($data as $res) {
            $tglLahir     = date('d-m-Y', strtotime($res['tgl_lahir']));
            $tglTransaksi = date('d-m-Y', strtotime($res['tgl_transaksi']));

            $row[] = [
                'no_rekening'   => $res['no_rekening'],
                'nama'          => ucwords((string) ($res['nama'] ?? '')),
                'nik'           => $res['nik'],
                'tgl_lahir'     => ucwords((string) ($res['tmp_lahir'] ?? '')) . ', ' . $tglLahir,
                'tgl_transaksi' => $tglTransaksi,
                'jml_transaksi' => number_format((float) $res['jml_transaksi'], 2, ',', '.'),
                'no_sertifikat' => $res['no_sertifikat'],
            ];
        }

        return $this->response->setJSON($row)->setStatusCode(200);
    }

    public function TransaksiSaham_get($noSertifikat)
    {
        $data = $this->mApi->getTransaksi2($noSertifikat);
        $row  = [];

        foreach ($data as $res) {
            $tglLahir     = date('d-m-Y', strtotime($res['tgl_lahir']));
            $tglTransaksi = date('d-m-Y', strtotime($res['tgl_transaksi']));

            $row[] = [
                'no_rekening'   => $res['no_rekening'],
                'nama'          => ucwords((string) ($res['nama'] ?? '')),
                'nik'           => $res['nik'],
                'tgl_lahir'     => ucwords((string) ($res['tmp_lahir'] ?? '')) . ', ' . $tglLahir,
                'tgl_transaksi' => $tglTransaksi,
                'jml_transaksi' => number_format((float) $res['jml_transaksi'], 2, ',', '.'),
                'no_sertifikat' => $res['no_sertifikat'],
            ];
        }

        return $this->response->setJSON($row)->setStatusCode(200);
    }

    public function SaldoInvestasi_get($noRekening, $year)
    {
        $data = $this->mApi->getSaldoInvestasi($noRekening, $year);
        $temp = 0;
        $row  = [];

        foreach ($data as $res) {
            $tanggal = date('d-m-Y', strtotime($res['tanggal']));
            $tgl     = explode('-', $tanggal);
            $ket     = ($tgl[0] === '01' && $tgl[1] === '01') ? 'Saldo Awal' : '-';
            $temp    = $temp + $res['jml_transaksi'] - $res['jml_penarikan'];

            $row[] = [
                'no_rekening'   => $res['no_rekening'],
                'tanggal'       => $tanggal,
                'jml_transaksi' => number_format((float) $res['jml_transaksi'], 2, ',', '.'),
                'jml_penarikan' => number_format((float) $res['jml_penarikan'], 2, ',', '.'),
                'saldo'         => number_format((float) $temp, 2, ',', '.'),
                'ket'           => $ket,
            ];
        }

        return $this->response->setJSON($row)->setStatusCode(200);
    }
}

