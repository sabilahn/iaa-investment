<?php

namespace App\Controllers;

use App\Libraries\Ciqrcode;
use App\Models\TransaksiInvestasi;

class Penarikan_investasi extends BaseController
{
    protected TransaksiInvestasi $transaksiInvestasi;

    public function __construct()
    {
        $this->transaksiInvestasi = new TransaksiInvestasi();
    }

    public function index()
    {
        $data['title']         = 'IAA Investment | Penarikan Investasi';
        $data['data_rekening'] = $this->transaksiInvestasi->rekening_list();

        return view('header', $data)
            . view('menu')
            . view('penarikan_investasi', $data)
            . view('footer');
    }

    public function getData($tahun)
    {
        return $this->response->setJSON(
            $this->transaksiInvestasi->penarikan_list($tahun)->getResult()
        );
    }

    public function getDataID()
    {
        $id = $this->request->getPost('id_penarikan');

        return $this->response->setJSON(
            $this->transaksiInvestasi->getWhere('v_penarikan_investasi', ['id_penarikan' => $id])->getResult()
        );
    }

    public function update_saldo_user($user, $tglTransaksi, $thn)
    {
        $saldoSkr        = 0;
        $penambahanSaldo = 0;

        $from = date('Y-m-d', strtotime($thn . '-01-01 -1 days'));
        $to   = $tglTransaksi;
        while (strtotime($from) < strtotime($to)) {
            $from = date('Y-m-d', strtotime($from . ' +1 day'));

            $cek  = $this->transaksiInvestasi->cekTransaksi($user, $from)->getResultArray();
            $cek2 = $this->transaksiInvestasi->cekPenarikan($user, $from)->getResultArray();

            if ($cek) {
                foreach ($cek as $transaksi) {
                    $saldoSkr        += $transaksi['jml_transaksi'] + $penambahanSaldo;
                    $penambahanSaldo += $transaksi['jml_transaksi'];
                }
            } elseif ($cek2) {
                foreach ($cek2 as $transaksi) {
                    $saldoSkr        = $saldoSkr - $transaksi['jml_penarikan'] + $penambahanSaldo;
                    $penambahanSaldo -= $transaksi['jml_penarikan'];
                }
            } else {
                $saldoSkr += $penambahanSaldo;
            }
        }

        return $saldoSkr;
    }

    public function getHari($tanggal)
    {
        $tglSkrg         = strtotime(date('Y-m-d'));
        $tglAwal         = date('Y-m-d', strtotime(date('Y') . '-01-01 -1 days'));
        $tglTransaksi    = date('Y-m-d', strtotime($tanggal . ' -1 day'));
        $hariSaldoHarian = abs($tglSkrg - strtotime($tglTransaksi)) / 86400;
        $hariSaldoTahun  = ($tglSkrg - strtotime($tglAwal)) / 86400;

        return [$hariSaldoHarian, $hariSaldoTahun];
    }

    protected function recalculateSaldo(string $noRekening, string $tglPenarikan): void
    {
        $thn  = date('Y', strtotime($tglPenarikan));
        $tgl  = date($thn . '-m-d');
        $hari = $this->getHari($tgl);

        $rataHarian = $this->update_saldo_user($noRekening, $tgl, $thn);
        $rataTahun  = ($hari[1] > 0) ? ($rataHarian / $hari[1]) : 0;

        $this->transaksiInvestasi->edit('saldo_harian', [
            'rata_rata_harian' => $rataHarian,
            'rata_rata_tahun'  => $rataTahun,
            'updated_at'       => date('Y-m-d H:i:s'),
            'updated_by'       => $this->session->get('user_id'),
        ], ['no_rekening' => $noRekening, 'tahun' => $thn]);
    }

    public function tambah_data_penarikan()
    {
        $noRekening   = trim((string) $this->request->getPost('no_rekening'));
        $tglPenarikan = trim((string) $this->request->getPost('tgl_penarikan'));
        $jmlPenarikan = $this->parseNominal($this->request->getPost('jml_penarikan'));

        if ($noRekening === '' || $tglPenarikan === '' || $jmlPenarikan === null || (float) $jmlPenarikan <= 0) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Gagal! Pastikan rekening, tanggal, dan jumlah penarikan terisi dengan benar.',
            ]);
        }

        try {
            $data = [
                'no_rekening'   => $noRekening,
                'tgl_penarikan' => $tglPenarikan,
                'jml_penarikan' => $jmlPenarikan,
                'created_at'    => date('Y-m-d H:i:s'),
                'created_by'    => $this->session->get('user_id'),
            ];

            $id = $this->transaksiInvestasi->tambah_id('penarikan_investasi', $data);
            if (! $id) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Gagal! Data penarikan gagal disimpan.',
                ]);
            }

            $this->transaksiInvestasi->tambah('activity_log', [
                'user_id'  => $this->session->get('user_id'),
                'activity' => 'Tambah Penarikan Transaksi Berhasil',
                'times'    => date('Y-m-d H:i:s'),
            ]);

            $this->recalculateSaldo($noRekening, $tglPenarikan);
            $this->create_qrcode($id);

            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Sukses! Data berhasil disimpan.',
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Gagal tambah_data_penarikan: {message}', ['message' => $e->getMessage()]);

            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Gagal! Terjadi kesalahan saat menyimpan data.',
            ]);
        }
    }

    public function update()
    {
        $idPenarikan  = trim((string) $this->request->getPost('id_penarikan'));
        $tglPenarikan = trim((string) $this->request->getPost('tgl_penarikan'));
        $noRekening   = trim((string) $this->request->getPost('no_rekening'));

        if ($idPenarikan === '' || $tglPenarikan === '' || $noRekening === '') {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Gagal! Pastikan tanggal penarikan terisi dengan benar.',
            ]);
        }

        try {
            $updated = $this->transaksiInvestasi->edit('penarikan_investasi', [
                'tgl_penarikan' => $tglPenarikan,
                'updated_at'    => date('Y-m-d H:i:s'),
                'updated_by'    => $this->session->get('user_id'),
            ], ['id_penarikan' => $idPenarikan]);

            if (! $updated) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Gagal! Data penarikan gagal diperbarui.',
                ]);
            }

            $this->transaksiInvestasi->tambah('activity_log', [
                'user_id'  => $this->session->get('user_id'),
                'activity' => 'Ubah Penarikan Transaksi Berhasil',
                'times'    => date('Y-m-d H:i:s'),
            ]);

            $this->recalculateSaldo($noRekening, $tglPenarikan);

            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Sukses! Data berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Gagal update penarikan: {message}', ['message' => $e->getMessage()]);

            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Gagal! Terjadi kesalahan saat memperbarui data.',
            ]);
        }
    }

    public function delete()
    {
        $idPenarikan  = trim((string) $this->request->getPost('id_penarikan'));
        $noRekening   = trim((string) $this->request->getPost('no_rekening'));
        $tglPenarikan = trim((string) $this->request->getPost('tgl_penarikan'));

        if ($idPenarikan === '' || $noRekening === '' || $tglPenarikan === '') {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Gagal! Data penarikan tidak valid.',
            ]);
        }

        try {
            $deleted = $this->transaksiInvestasi->hapus('penarikan_investasi', ['id_penarikan' => $idPenarikan]);

            if (! $deleted) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Gagal! Data penarikan gagal dihapus.',
                ]);
            }

            $this->transaksiInvestasi->tambah('activity_log', [
                'user_id'  => $this->session->get('user_id'),
                'activity' => 'Hapus Penarikan Transaksi Berhasil',
                'times'    => date('Y-m-d H:i:s'),
            ]);

            $this->recalculateSaldo($noRekening, $tglPenarikan);

            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Sukses! Data berhasil dihapus.',
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Gagal delete penarikan: {message}', ['message' => $e->getMessage()]);

            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Gagal! Terjadi kesalahan saat menghapus data.',
            ]);
        }
    }

    public function getJumlah()
    {
        $noRekening = $this->request->getPost('rekening');
        $thn        = date('Y');

        return $this->response->setJSON(
            $this->transaksiInvestasi->jml_transaksi($noRekening, $thn)->getResult()
        );
    }

    public function create_qrcode($id)
    {
        $config = [
            'cacheable' => true,
            'cachedir'  => FCPATH . 'assets/stisla',
            'errorlog'  => FCPATH . 'assets/stisla',
            'quality'   => true,
            'size'      => 1024,
            'black'     => [224, 255, 255],
            'white'     => [70, 130, 180],
        ];

        $ciqrcode = new Ciqrcode($config);

        $imageDir  = 'assets/stisla/images/qrcode/penarikan/';
        $imageName = $id . '.png';

        $ciqrcode->generate([
            'data'     => base_url('penarikan/index/') . $id,
            'level'    => 'H',
            'size'     => 10,
            'savename' => FCPATH . $imageDir . $imageName,
        ]);
    }
}

