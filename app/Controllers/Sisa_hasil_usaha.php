<?php

namespace App\Controllers;

use App\Models\SisaHasilUsaha;

class Sisa_hasil_usaha extends BaseController
{
    protected SisaHasilUsaha $sisaHasilUsaha;

    public function __construct()
    {
        $this->sisaHasilUsaha = new SisaHasilUsaha();
    }

    public function index()
    {
        $data['title']    = 'IAA Investment | Sisa Hasil Usaha';
        $data['data_shu'] = $this->sisaHasilUsaha->shu_list()->getResultArray();

        return view('header', $data)
            . view('menu')
            . view('sisa_hasil_usaha', $data)
            . view('footer');
    }

    public function tambah_data_shu()
    {
        $this->db->transBegin();
        try {
            $tahunAdd = $this->request->getPost('tahun_add');
            $jmlShu   = (float) $this->parseNominal($this->request->getPost('jml_shu'));

            $cek  = $this->sisaHasilUsaha->cek($tahunAdd)->getResultArray();
            $cek1 = $this->sisaHasilUsaha->cek1($tahunAdd)->getResultArray();

            if ($cek1) {
                if (! $cek) {
                    $data = [
                        'tahun'      => $tahunAdd,
                        'jumlah'     => $jmlShu,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => $this->session->get('user_id'),
                    ];
                    $this->sisaHasilUsaha->tambah($data, 'sisa_hasil_usaha');

                    $porsiDispen  = [82.5, 17.5];
                    $uraianDispen = ['Dividen Pesero', 'IAA Pusat, PPA, dan Menejemen'];
                    foreach ($porsiDispen as $i => $porsi) {
                        $jumlah = ($porsi / 100) * $jmlShu;
                        $this->sisaHasilUsaha->tambah([
                            'porsi'      => $porsi,
                            'uraian'     => $uraianDispen[$i],
                            'shu'        => $jmlShu,
                            'jumlah'     => $jumlah,
                            'tahun'      => $tahunAdd,
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => $this->session->get('user_id'),
                        ], 'porsi_dispen');
                    }

                    $porsiDisda  = [30, 20, 50];
                    $uraianDisda = ['IAA Pusat', 'PP. Annuqayah', 'Menejemen IAA'];
                    $dispen      = ($porsiDispen[1] / 100) * $jmlShu;
                    foreach ($porsiDisda as $i => $porsi) {
                        $jumlah = ($porsi / 100) * $dispen;
                        $this->sisaHasilUsaha->tambah([
                            'porsi'      => $porsi,
                            'uraian'     => $uraianDisda[$i],
                            'menejemen'  => $dispen,
                            'jumlah'     => $jumlah,
                            'tahun'      => $tahunAdd,
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => $this->session->get('user_id'),
                        ], 'porsi_disda');
                    }

                    $this->sisaHasilUsaha->tambah([
                        'user_id'  => $this->session->get('user_id'),
                        'activity' => 'Tambah Data SHU Berhasil',
                        'times'    => date('Y-m-d H:i:s'),
                    ], 'activity_log');

                    $this->db->transCommit();
                    $this->session->setFlashdata('success', 'Sukses! Data berhasil disimpan.');
                } else {
                    $this->db->transRollback();
                    $this->session->setFlashdata('error', 'Gagal! Data SHU sudah ada');
                }
            } else {
                $this->db->transRollback();
                $this->session->setFlashdata('error', 'Gagal! Belum ada data transaksi.');
            }

            return redirect()->back();
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->session->setFlashdata('error', 'Gagal menyimpan data!');

            return redirect()->back();
        }
    }

    public function ubah_data_shu()
    {
        $this->db->transBegin();
        try {
            $jmlShuEdt = (float) $this->parseNominal($this->request->getPost('jml_shu_edt'));
            $idShu     = $this->request->getPost('id_shu');
            $tahun     = $this->request->getPost('tahun_edt');

            $this->sisaHasilUsaha->edit('sisa_hasil_usaha', [
                'jumlah'     => $jmlShuEdt,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->session->get('user_id'),
            ], ['id_shu' => $idShu]);

            $porsiDispen = [82.5, 17.5];
            foreach ($porsiDispen as $porsi) {
                $jumlah = ($porsi / 100) * $jmlShuEdt;
                $this->sisaHasilUsaha->edit('porsi_dispen', [
                    'shu'        => $jmlShuEdt,
                    'jumlah'     => $jumlah,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->get('user_id'),
                ], ['porsi' => $porsi, 'tahun' => $tahun]);
            }

            $porsiDisda = [30, 20, 50];
            $dispen     = ($porsiDispen[1] / 100) * $jmlShuEdt;
            foreach ($porsiDisda as $porsi) {
                $jumlah = ($porsi / 100) * $dispen;
                $this->sisaHasilUsaha->edit('porsi_disda', [
                    'menejemen'  => $dispen,
                    'jumlah'     => $jumlah,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->get('user_id'),
                ], ['porsi' => $porsi, 'tahun' => $tahun]);
            }

            $this->sisaHasilUsaha->tambah([
                'user_id'  => $this->session->get('user_id'),
                'activity' => 'Ubah Data SHU Berhasil',
                'times'    => date('Y-m-d H:i:s'),
            ], 'activity_log');

            $this->db->transCommit();
            $this->session->setFlashdata('success', 'Sukses! Data berhasil disimpan.');

            return redirect()->back();
        } catch (\Throwable $th) {
            $this->db->transRollback();
            $this->session->setFlashdata('error', 'Gagal! Pastikan input anda benar!');

            return redirect()->back();
        }
    }

    public function hapus_data_shu()
    {
        $idShu = $this->request->getPost('id_shu');
        $tahun = $this->request->getPost('tahun_hps');

        $this->db->transBegin();
        try {
            $this->sisaHasilUsaha->hapus(['id_shu' => $idShu], 'sisa_hasil_usaha');
            $this->sisaHasilUsaha->hapus(['tahun' => $tahun], 'porsi_dispen');
            $this->sisaHasilUsaha->hapus(['tahun' => $tahun], 'porsi_disda');

            $this->sisaHasilUsaha->tambah([
                'user_id'  => $this->session->get('user_id'),
                'activity' => 'Hapus Data SHU Berhasil',
                'times'    => date('Y-m-d H:i:s'),
            ], 'activity_log');

            $this->db->transCommit();
            $this->session->setFlashdata('success', 'Sukses! Data berhasil dihapus.');

            return redirect()->back();
        } catch (\Throwable $th) {
            $this->db->transRollback();

            return redirect()->back();
        }
    }
}

