<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/RestController.php';

use Restserver\Libraries\RestController;


class C_api extends RestController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("M_api");
    }

    function Users_get()
    {
        $data = $this->M_api->getUser();
        $row = array();

        foreach ($data as $res) {
            $row[] = array(
                'user_id' => $res['user_id'],
                'name' => ucwords((string) ($res['nama'] ?? '')),
                'username' => $res['username'],
                'userpass' => $res['userpass'],
                'role' => $res['role']
            );
        }

        $this->response($row, RestController::HTTP_OK);
    }

    function Clients_get()
    {
        $data = $this->M_api->getClient();
        $row = array();

        foreach ($data as $res) {

            $tgl_lahir = date("d-m-Y", strtotime($res['tgl_lahir']));
            $tgl_daftar = date("d-m-Y", strtotime($res['tgl_daftar']));

            $row[] = array(
                'no_rekening' => $res['no_rekening'],
                'nik' => $res['nik'],
                'nama' => ucwords((string) ($res['nama'] ?? '')),
                'tgl_lahir' => ucwords((string) ($res['tmp_lahir'] ?? '')) . '/' . $tgl_lahir,
                'alamat' => ucwords((string) ($res['dsn'] ?? '')) . ' ' . ucwords((string) ($res['ds'] ?? '')) . ' RT/RW ' . $res['rt'] . '/' . $res['rw'],
                'kec_kab' => ucwords((string) ($res['kec'] ?? '')) . ' - ' . ucwords((string) ($res['kab'] ?? '')),
                'tgl_daftar' => $tgl_daftar
            );
        }

        $this->response($row, RestController::HTTP_OK);
    }

    function ClientId_get($no_rekening)
    {
        $data = $this->M_api->getClientId($no_rekening);
        $row = array();

        foreach ($data as $res) {

            $tgl_lahir = date("d-m-Y", strtotime($res['tgl_lahir']));
            $tgl_daftar = date("d-m-Y", strtotime($res['tgl_daftar']));

            $row[] = array(
                'no_rekening' => $res['no_rekening'],
                'nik' => $res['nik'],
                'nama' => ucwords((string) ($res['nama'] ?? '')),
                'tgl_lahir' => ucwords((string) ($res['tmp_lahir'] ?? '')) . '/' . $tgl_lahir,
                'alamat' => ucwords((string) ($res['dsn'] ?? '')) . ' ' . ucwords((string) ($res['ds'] ?? '')) . ' RT/RW ' . $res['rt'] . '/' . $res['rw'],
                'kec_kab' => ucwords((string) ($res['kec'] ?? '')) . ' - ' . ucwords((string) ($res['kab'] ?? '')),
                'tgl_daftar' => $tgl_daftar
            );
        }

        $this->response($row, RestController::HTTP_OK);
    }

    function SaldoHarianId_get($no_rekening)
    {
        $data = $this->M_api->getRataHarianId($no_rekening);
        $row = array();

        foreach ($data as $res) {
            $row[] = array(
                'tahun' => $res['tahun'],
                'nilai_investasi' => number_format((float) $res['nilai_investasi'], 2, ',', '.'),
                'rata_rata_harian' => number_format((float) $res['rata_rata_harian'], 2, ',', '.'),
                'rata_rata_tahun' => number_format((float) $res['rata_rata_tahun'], 2, ',', '.'),
            );
        }

        $this->response($row, RestController::HTTP_OK);
    }

    function DividenId_get($no_rekening)
    {
        $data = $this->M_api->getDividenId($no_rekening);
        $row = array();

        foreach ($data as $res) {
            $row[] = array(
                'tahun' => $res['tahun'],
                'saham_persero' => number_format((float) $res['saham_persero'], 2, ',', '.'),
                'shu' => number_format((float) $res['shu'], 2, ',', '.'),
                'bagi_hasil' => $res['nisbah_bagi_hasil'],
            );
        }

        $this->response($row, RestController::HTTP_OK);
    }

    function TransaksiId_get($no_rekening)
    {
        $data = $this->M_api->getTransaksi($no_rekening);
        $row = array();

        foreach ($data as $res) {
            $tgl_lahir = date("d-m-Y", strtotime($res['tgl_lahir']));
            $tgl_transaksi = date("d-m-Y", strtotime($res['tgl_transaksi']));

            $row[] = array(
                'no_rekening' => $res['no_rekening'],
                'nama' => ucwords((string) ($res['nama'] ?? '')),
                'nik' => $res['nik'],
                'tgl_lahir' => ucwords((string) ($res['tmp_lahir'] ?? '')) . ', ' . $tgl_lahir,
                'tgl_transaksi' => $tgl_transaksi,
                'jml_transaksi' => number_format((float) $res['jml_transaksi'], 2, ',', '.'),
                'no_sertifikat' => $res['no_sertifikat'],
            );
        }

        $this->response($row, RestController::HTTP_OK);
    }

    function TransaksiSaham_get($no_sertifikat)
    {
        $data = $this->M_api->getTransaksi2($no_sertifikat);
        $row = array();

        foreach ($data as $res) {
            $tgl_lahir = date("d-m-Y", strtotime($res['tgl_lahir']));
            $tgl_transaksi = date("d-m-Y", strtotime($res['tgl_transaksi']));

            $row[] = array(
                'no_rekening' => $res['no_rekening'],
                'nama' => ucwords((string) ($res['nama'] ?? '')),
                'nik' => $res['nik'],
                'tgl_lahir' => ucwords((string) ($res['tmp_lahir'] ?? '')) . ', ' . $tgl_lahir,
                'tgl_transaksi' => $tgl_transaksi,
                'jml_transaksi' => number_format((float) $res['jml_transaksi'], 2, ',', '.'),
                'no_sertifikat' => $res['no_sertifikat']
            );
        }

        $this->response($row, RestController::HTTP_OK);
    }

    function SaldoInvestasi_get($no_rekening, $year)
    {
        $data = $this->M_api->getSaldoInvestasi($no_rekening, $year);
        $temp = 0;
        $ket = "";
        $row = array();

        foreach ($data as $res) {
            $tanggal = date("d-m-Y", strtotime($res['tanggal']));
            $tgl = explode('-', $tanggal);

            if ($tgl[0] == '01' && $tgl[1] == '01') {
                $ket = "Saldo Awal";
            } else {
                $ket = "-";
            }

            $temp = $temp + $res['jml_transaksi'] - $res['jml_penarikan'];

            $row[] = array(
                'no_rekening' => $res['no_rekening'],
                'tanggal' => $tanggal,
                'jml_transaksi' => number_format((float) $res['jml_transaksi'], 2, ',', '.'),
                'jml_penarikan' => number_format((float) $res['jml_penarikan'], 2, ',', '.'),
                'saldo' => number_format((float) $temp, 2, ',', '.'),
                'ket' => $ket
            );
        }

        $this->response($row, RestController::HTTP_OK);
    }
}
