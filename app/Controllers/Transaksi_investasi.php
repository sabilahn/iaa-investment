<?php

namespace App\Controllers;

use App\Libraries\Cetak_pdf;
use App\Libraries\Ciqrcode;
use App\Models\TransaksiInvestasi;

class Transaksi_investasi extends BaseController
{
    protected TransaksiInvestasi $transaksiInvestasi;

    public function __construct()
    {
        $this->transaksiInvestasi = new TransaksiInvestasi();
    }

    public function index()
    {
        $data['title'] = 'IAA Investment | Transaksi Investasi';

        $thn = $this->request->getPost('tahun_transaksi');
        if (empty($thn)) {
            $thn = $this->session->getFlashdata('tahun_transaksi');
        }
        if (empty($thn)) {
            $thn = date('Y');
        }
        $data['tahun']          = $thn;
        $data['data_transaksi'] = $this->transaksiInvestasi->transaksi_list($thn)->getResultArray();

        foreach ($data['data_transaksi'] as $i => $transaksi) {
            $saldo = $this->transaksiInvestasi
                ->getWhere('v_transaksi_thn', ['no_rekening' => $transaksi['no_rekening'], 'tahun' => $thn - 1])
                ->getRowArray();

            if (isset($saldo['saldo']) && $transaksi['jml_transaksi'] == $saldo['saldo'] && $transaksi['tgl_transaksi'] === $thn . '-01-01') {
                $data['data_transaksi'][$i]['action'] = '<button class="btn btn-sm btn-info" disabled><i class="fa fa-ban"></i></button>
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal' . $transaksi['id_transaksi'] . ' "><i class="fas fa-pencil-alt"></i></button>
                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal' . $transaksi['id_transaksi'] . ' "><i class="fa fa-trash"></i></button>';
            } else {
                $data['data_transaksi'][$i]['action'] = '<a target="_blank" href="' . site_url('transaksi_investasi/cetak_surat_saham/' . $transaksi['id_transaksi'] . '/' . $transaksi['no_rekening']) . '" class="btn btn-sm btn-info"><i class="fa fa-print"></i></a>
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal' . $transaksi['id_transaksi'] . ' "><i class="fas fa-pencil-alt"></i></button>
                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal' . $transaksi['id_transaksi'] . ' "><i class="fa fa-trash"></i></button>';
            }
        }

        $data['data_rekening'] = $this->transaksiInvestasi->rekening_list();
        $data['action2']       = '<a class="btn btn-warning" href="' . site_url('transaksi_investasi/migrasi/' . $thn) . '"><i class="fas fa-sync"> Jadikan Saldo Awal</i></a>';

        return view('header', $data)
            . view('menu')
            . view('transaksi_investasi', $data)
            . view('footer');
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

    protected function generateQrCode(string $subDir, string $fileName, string $qrUrl): void
    {
        $ciqrcode = new Ciqrcode([
            'cacheable' => true,
            'cachedir'  => FCPATH . 'assets/stisla',
            'errorlog'  => FCPATH . 'assets/stisla',
            'quality'   => true,
            'size'      => 1024,
            'black'     => [224, 255, 255],
            'white'     => [70, 130, 180],
        ]);

        $ciqrcode->generate([
            'data'     => $qrUrl,
            'level'    => 'H',
            'size'     => 10,
            'savename' => FCPATH . $subDir . $fileName,
        ]);
    }

    public function create_qrcode($noSertifikat)
    {
        $this->generateQrCode(
            'assets/stisla/images/qrcode/surat_saham/',
            $noSertifikat . '.png',
            base_url('surat_saham/index/' . $noSertifikat)
        );
    }

    public function create_qrcode_penarikan($id)
    {
        $this->generateQrCode(
            'assets/stisla/images/qrcode/penarikan/',
            $id . '.png',
            base_url('penarikan/index/' . $id)
        );
    }

    public function tambah_data_transaksi()
    {
        $this->db->transBegin();
        try {
            $rekening     = (string) $this->request->getPost('rekening');
            $tglTransaksi = (string) $this->request->getPost('tgl_transaksi');
            $jmlTransaksi = $this->parseNominal($this->request->getPost('jml_transaksi'));
            $noSertifikat = (string) $this->request->getPost('sertifikat');

            $data = [
                'no_rekening'   => $rekening,
                'no_sertifikat' => $noSertifikat,
                'tgl_transaksi' => $tglTransaksi,
                'jml_transaksi' => $jmlTransaksi,
                'created_at'    => date('Y-m-d H:i:s'),
                'created_by'    => (string) $this->session->get('user_id'),
                'updated_by'    => '',
                'is_deleted'    => 0,
            ];

            $transaksiAdd = $this->transaksiInvestasi->tambah('transaksi_investasi', $data);
            if (! $transaksiAdd) {
                throw new \Exception('Failed');
            }

            $thn         = date('Y', strtotime($tglTransaksi));
            $tgl         = date($thn . '-m-d');
            $hari        = $this->getHari($tgl);
            $pembagiHari = (! empty($hari[1]) && $hari[1] != 0) ? $hari[1] : 1;

            $jmlRataHarian = (float) $jmlTransaksi * $hari[0];

            $dataSaldo = [
                'no_rekening'      => $rekening,
                'rata_rata_harian' => $jmlRataHarian,
                'rata_rata_tahun'  => $jmlRataHarian / $pembagiHari,
                'tahun'            => $thn,
                'created_at'       => date('Y-m-d H:i:s'),
                'created_by'       => (string) $this->session->get('user_id'),
            ];

            $cek = $this->transaksiInvestasi->cekSaldo($rekening, $tglTransaksi)->getResultArray();
            if (! $cek) {
                $this->transaksiInvestasi->tambah('saldo_harian', $dataSaldo);
            } else {
                $rataHarian = $this->update_saldo_user($rekening, $tgl, $thn);
                $rataTahun  = $rataHarian / $pembagiHari;

                $this->transaksiInvestasi->edit('saldo_harian', [
                    'rata_rata_harian' => $rataHarian,
                    'rata_rata_tahun'  => $rataTahun,
                    'updated_at'       => date('Y-m-d H:i:s'),
                    'updated_by'       => (string) $this->session->get('user_id'),
                ], ['no_rekening' => $rekening, 'tahun' => $thn]);
            }

            $this->transaksiInvestasi->tambah('activity_log', [
                'user_id'  => (string) $this->session->get('user_id'),
                'activity' => 'Tambah Transaksi Berhasil',
                'times'    => date('Y-m-d H:i:s'),
            ]);

            if ($this->db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }

            $this->db->transCommit();

            if (! empty($noSertifikat)) {
                try {
                    $this->create_qrcode($noSertifikat);
                } catch (\Throwable $qrErr) {
                    // Jangan batalkan transaksi database yang sudah di-commit jika gagal membuat QR image
                }
            }

            $this->session->setFlashdata('tahun_transaksi', $thn);
            $this->session->setFlashdata('success', 'Sukses! Data berhasil disimpan.');

            return redirect()->to(! empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('transaksi_investasi'));
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->session->setFlashdata('error', 'Gagal menyimpan data transaksi: ' . $th->getMessage());

            return redirect()->to(! empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('transaksi_investasi'));
        }
    }

    public function ubah_data_transaksi()
    {
        $this->db->transBegin();

        try {
            $tglTransaksiEdt = (string) $this->request->getPost('tgl_transaksi_edt');
            $rekeningEdt     = (string) $this->request->getPost('rekening_edt');

            $dataTransaksi = [
                'no_sertifikat' => $this->request->getPost('sertifikat_edt'),
                'tgl_transaksi' => $tglTransaksiEdt,
                'jml_transaksi' => $this->parseNominal($this->request->getPost('jml_transaksi_edt')),
                'updated_at'    => date('Y-m-d H:i:s'),
                'updated_by'    => $this->session->get('user_id'),
            ];

            $idTransaksi   = $this->request->getPost('id_transaksi');
            $transaksiEdit = $this->transaksiInvestasi->edit('transaksi_investasi', $dataTransaksi, ['id_transaksi' => $idTransaksi]);
            if (! $transaksiEdit) {
                throw new \Exception('Failed');
            }

            $thn         = date('Y', strtotime($tglTransaksiEdt));
            $tgl         = date($thn . '-m-d');
            $hari        = $this->getHari($tgl);
            $pembagiHari = (! empty($hari[1]) && $hari[1] != 0) ? $hari[1] : 1;

            $rataHarian = $this->update_saldo_user($rekeningEdt, $tgl, $thn);
            $rataTahun  = $rataHarian / $pembagiHari;

            $this->transaksiInvestasi->edit('saldo_harian', [
                'rata_rata_harian' => $rataHarian,
                'rata_rata_tahun'  => $rataTahun,
                'updated_at'       => date('Y-m-d H:i:s'),
                'updated_by'       => $this->session->get('user_id'),
            ], ['no_rekening' => $rekeningEdt, 'tahun' => $thn]);

            $this->transaksiInvestasi->tambah('activity_log', [
                'user_id'  => $this->session->get('user_id'),
                'activity' => 'Ubah Transaksi Berhasil',
                'times'    => date('Y-m-d H:i:s'),
            ]);

            $this->db->transCommit();
            $this->session->setFlashdata('success', 'Sukses! Data berhasil disimpan.');

            return redirect()->back();
        } catch (\Throwable $th) {
            $this->db->transRollback();

            return redirect()->back();
        }
    }

    public function hapus_data_transaksi()
    {
        $idTransaksi  = $this->request->getPost('id_transaksi');
        $noRekening   = (string) $this->request->getPost('rekening_hps');
        $tglTransaksi = (string) $this->request->getPost('tgl_transaksi_hps');

        $count       = $this->transaksiInvestasi->countTransaksi($noRekening, $tglTransaksi)->banyak_transaksi;
        $thn         = date('Y', strtotime($tglTransaksi));
        $tgl         = date($thn . '-m-d');
        $hari        = $this->getHari($tgl);
        $pembagiHari = (! empty($hari[1]) && $hari[1] != 0) ? $hari[1] : 1;

        $this->db->transBegin();
        try {
            $this->transaksiInvestasi->hapus('transaksi_investasi', ['id_transaksi' => $idTransaksi]);

            if ($count == 1) {
                $this->transaksiInvestasi->hapus('saldo_harian', ['no_rekening' => $noRekening, 'tahun' => $thn]);
            } else {
                $rataHarian = $this->update_saldo_user($noRekening, $tgl, $thn);
                $rataTahun  = $rataHarian / $pembagiHari;

                $this->transaksiInvestasi->edit('saldo_harian', [
                    'rata_rata_harian' => $rataHarian,
                    'rata_rata_tahun'  => $rataTahun,
                    'updated_at'       => date('Y-m-d H:i:s'),
                    'updated_by'       => $this->session->get('user_id'),
                ], ['no_rekening' => $noRekening, 'tahun' => $thn]);
            }

            $this->transaksiInvestasi->tambah('activity_log', [
                'user_id'  => $this->session->get('user_id'),
                'activity' => 'Hapus Transaksi Berhasil',
                'times'    => date('Y-m-d H:i:s'),
            ]);

            $this->db->transCommit();
            $this->session->setFlashdata('success', 'Sukses! Data berhasil dihapus.');

            return redirect()->back();
        } catch (\Throwable $th) {
            $this->db->transRollback();

            return redirect()->back();
        }
    }

    public function migrasi($thn)
    {
        $listTransaksi = $this->transaksiInvestasi->getTransaksi($thn)->getResultArray();
        $this->db->transBegin();

        try {
            foreach ($listTransaksi as $transaksi) {
                $rekening     = explode('-', $transaksi['no_rekening']);
                $tglTransaksi = ($thn + 1) . '-01-01';
                $noSertifikat = ($thn + 1) . '0101' . $rekening[1];

                $cek         = $this->transaksiInvestasi->getWhere('transaksi_investasi', ['no_sertifikat' => $noSertifikat])->getResultArray();
                $hari        = $this->getHari($tglTransaksi);
                $pembagiHari = (! empty($hari[1]) && $hari[1] != 0) ? $hari[1] : 1;
                $tahunTarget = date('Y', strtotime($tglTransaksi));

                if (! $cek) {
                    $this->transaksiInvestasi->tambah('transaksi_investasi', [
                        'no_rekening'   => $transaksi['no_rekening'],
                        'no_sertifikat' => $noSertifikat,
                        'tgl_transaksi' => $tglTransaksi,
                        'jml_transaksi' => $transaksi['saldo'],
                        'created_at'    => date('Y-m-d H:i:s'),
                        'created_by'    => $this->session->get('user_id'),
                        'is_deleted'    => 0,
                    ]);

                    $jmlRataHarian = (float) $transaksi['saldo'] * $hari[0];
                    $cek2          = $this->transaksiInvestasi->cekSaldo($transaksi['no_rekening'], $tglTransaksi)->getResultArray();

                    if (! $cek2) {
                        $this->transaksiInvestasi->tambah('saldo_harian', [
                            'no_rekening'      => $transaksi['no_rekening'],
                            'rata_rata_harian' => $jmlRataHarian,
                            'rata_rata_tahun'  => $jmlRataHarian / $pembagiHari,
                            'tahun'            => $tahunTarget,
                            'created_at'       => date('Y-m-d H:i:s'),
                            'created_by'       => $this->session->get('user_id'),
                        ]);
                    } else {
                        $rataHarian = $this->update_saldo_user($transaksi['no_rekening'], $tglTransaksi, $tahunTarget);
                        $this->transaksiInvestasi->edit('saldo_harian', [
                            'rata_rata_harian' => $rataHarian,
                            'rata_rata_tahun'  => $rataHarian / $pembagiHari,
                            'updated_at'       => date('Y-m-d H:i:s'),
                            'updated_by'       => $this->session->get('user_id'),
                        ], ['no_rekening' => $transaksi['no_rekening'], 'tahun' => $tahunTarget]);
                    }
                } elseif ($cek[0]['no_sertifikat'] == $noSertifikat && $cek[0]['jml_transaksi'] != $transaksi['saldo']) {
                    $this->transaksiInvestasi->edit('transaksi_investasi', ['jml_transaksi' => $transaksi['saldo']], ['no_sertifikat' => $noSertifikat]);

                    $rataHarian = $this->update_saldo_user($transaksi['no_rekening'], $tglTransaksi, $tahunTarget);
                    $this->transaksiInvestasi->edit('saldo_harian', [
                        'rata_rata_harian' => $rataHarian,
                        'rata_rata_tahun'  => $rataHarian / $pembagiHari,
                        'updated_at'       => date('Y-m-d H:i:s'),
                        'updated_by'       => $this->session->get('user_id'),
                    ], ['no_rekening' => $transaksi['no_rekening'], 'tahun' => $tahunTarget]);
                }
            }

            $this->db->transCommit();
            $this->session->setFlashdata('success', 'Sukses! Data berhasil disimpan');

            return redirect()->back();
        } catch (\Throwable $th) {
            $this->db->transRollback();

            return redirect()->back();
        }
    }

    public function cetak_surat_saham($idTransaksi, $noRekening)
    {
        $data['title']          = 'IAA Investment | Surat Saham';
        $data['data_transaksi'] = $this->transaksiInvestasi->getWhere('transaksi_investasi', ['id_transaksi' => $idTransaksi])->getRow();
        $data['profiles']       = $this->transaksiInvestasi->getWhere('client_account', ['no_rekening' => $noRekening])->getRow();

        if (! empty($data['data_transaksi']->no_sertifikat)) {
            $qrFile = FCPATH . 'assets/stisla/images/qrcode/surat_saham/' . $data['data_transaksi']->no_sertifikat . '.png';
            if (! file_exists($qrFile)) {
                $this->create_qrcode($data['data_transaksi']->no_sertifikat);
            }
        }

        $cetakPdf = new Cetak_pdf();
        $cetakPdf->setPaper([0, 0, 771, 395]);
        $filename = 'surat-saham-[' . $noRekening . '].pdf';
        $cetakPdf->loadHtml(view('surat_saham/surat_saham2', $data));
        $cetakPdf->render();

        return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody($cetakPdf->output());
    }

    public function cetak_surat_keterangan($idPenarikan)
    {
        $data['title']          = 'IAA Investment | Surat Keterangan';
        $data['data_penarikan'] = $this->transaksiInvestasi->getWhere('v_penarikan_investasi', ['id_penarikan' => $idPenarikan])->getRow();

        if (! empty($data['data_penarikan']->id_penarikan)) {
            $qrFile = FCPATH . 'assets/stisla/images/qrcode/penarikan/' . $data['data_penarikan']->id_penarikan . '.png';
            if (! file_exists($qrFile)) {
                $this->create_qrcode_penarikan($data['data_penarikan']->id_penarikan);
            }
        }

        $this->transaksiInvestasi->tambah('activity_log', [
            'user_id'  => $this->session->get('user_id'),
            'activity' => 'Cetak Surat Keterangan Berhasil',
            'times'    => date('Y-m-d H:i:s'),
        ]);

        $cetakPdf = new Cetak_pdf();
        $cetakPdf->setPaper('A4', 'portrait');
        $filename = 'surat-keterangan-penarikan.pdf';
        $cetakPdf->loadHtml(view('surat_saham/surat_keterangan', $data));
        $cetakPdf->render();

        return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody($cetakPdf->output());
    }
}

