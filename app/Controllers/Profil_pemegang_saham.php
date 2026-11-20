<?php

namespace App\Controllers;

use App\Models\ProfilPemegangSaham;

class Profil_pemegang_saham extends BaseController
{
    protected ProfilPemegangSaham $profilPemegangSaham;

    public function __construct()
    {
        $this->profilPemegangSaham = new ProfilPemegangSaham();
    }

    public function index()
    {
        $data['profiles'] = $this->profilPemegangSaham->profiles();

        foreach ($data['profiles'] as $i => $profile) {
            $data['profiles'][$i]['action'] = '<a href="' . site_url('profil_pemegang_saham/lihat_data_anggota/' . $profile['no_rekening']) . '" class="btn btn-sm btn-info"><i class=" far fa-eye"></i></a>
            <a href="' . site_url('profil_pemegang_saham/ubah_data_anggota/' . $profile['no_rekening']) . '" class=" btn btn-sm btn-primary"><i class="fas fa-pencil-alt"></i></a>
            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal' . $profile['no_rekening'] . ' "><i class="fa fa-trash"></i></button>';
        }

        $data['title'] = 'IAA Investment | Profil Pemegang Saham';

        return view('header', $data)
            . view('menu')
            . view('profil_pemegang_saham/profil_pemegang_saham', $data)
            . view('footer');
    }

    public function show()
    {
        return $this->response->setJSON($this->profilPemegangSaham->profiles());
    }

    public function get_bank()
    {
        return $this->response->setJSON($this->profilPemegangSaham->daftar_bank());
    }

    public function tambah_data_anggota()
    {
        $data['title'] = 'IAA Investment | Profil Pemegang Saham';

        return view('header', $data)
            . view('menu')
            . view('profil_pemegang_saham/create')
            . view('footer');
    }

    public function tambah_data()
    {
        $rules = [
            'rekening' => 'required|trim|exact_length[9]',
            'nama'     => 'required',
        ];

        if ($this->validate($rules)) {
            $rekening = (string) $this->request->getPost('rekening');
            $data     = [
                'no_rekening' => $rekening,
                'nik'         => $this->request->getPost('nik'),
                'nama'        => strtolower((string) $this->request->getPost('nama')),
                'tmp_lahir'   => strtolower((string) $this->request->getPost('tmp_lahir')),
                'tgl_lahir'   => $this->request->getPost('tgl_lahir'),
                'dsn'         => $this->request->getPost('dsn'),
                'ds'          => $this->request->getPost('ds'),
                'rt'          => $this->request->getPost('rt'),
                'rw'          => $this->request->getPost('rw'),
                'kec'         => $this->request->getPost('kec'),
                'kab'         => $this->request->getPost('kab'),
                'nama_ibu'    => $this->request->getPost('nama_ibu'),
                'pekerjaan'   => $this->request->getPost('pekerjaan'),
                'gaji'        => $this->parseNominal($this->request->getPost('gaji')),
                'usaha'       => $this->request->getPost('usaha'),
                'omzet'       => $this->parseNominal($this->request->getPost('omzet')),
                'tgl_daftar'  => $this->request->getPost('tgl_daftar'),
                'no_hp'       => $this->request->getPost('no_hp'),
                'id_bank'     => $this->request->getPost('id_bank'),
                'rek_bank'    => $this->request->getPost('rek_bank'),
                'created_at'  => date('Y-m-d H:i:s'),
                'created_by'  => $this->session->get('user_id'),
            ];

            $cek = $this->profilPemegangSaham->cek('client_account', $rekening);

            if (! $cek) {
                $this->profilPemegangSaham->tambah('client_account', $data);
                $this->session->setFlashdata('success', 'Sukses! Data berhasil disimpan.');

                $this->profilPemegangSaham->tambah('activity_log', [
                    'user_id'  => $this->session->get('user_id'),
                    'activity' => 'Tambah Data Anggota Berhasil',
                    'times'    => date('Y-m-d H:i:s'),
                ]);

                return redirect()->to(base_url('profil_pemegang_saham'));
            }

            $this->session->setFlashdata('error', 'Gagal! No rekening sudah ada.');

            return redirect()->to(base_url('profil_pemegang_saham/tambah_data_anggota'))->withInput();
        }

        $this->session->setFlashdata('error', 'Gagal! Pastikan input anda benar!');

        return redirect()->to(base_url('profil_pemegang_saham/tambah_data_anggota'))->withInput();
    }

    public function ubah_data_anggota($noRekening)
    {
        $data['title']    = 'IAA Investment | Profil Pemegang Saham';
        $data['profiles'] = $this->db->table('client_account')
            ->getWhere(['no_rekening' => $noRekening])
            ->getResultArray();

        return view('header', $data)
            . view('menu')
            . view('profil_pemegang_saham/update', $data)
            . view('footer');
    }

    public function ubah_data()
    {
        $rules = [
            'nama_edt' => 'required',
        ];

        if ($this->validate($rules)) {
            $noRekening  = (string) $this->request->getPost('rekening_edt');
            $idBankField = 'id_bank_edt_' . $noRekening;
            $idBank      = $this->request->getPost($idBankField);

            $data = [
                'nik'        => $this->request->getPost('nik_edt'),
                'nama'       => strtolower((string) $this->request->getPost('nama_edt')),
                'tmp_lahir'  => strtolower((string) $this->request->getPost('tmp_lahir_edt')),
                'tgl_lahir'  => $this->request->getPost('tgl_lahir_edt'),
                'dsn'        => $this->request->getPost('dsn_edt'),
                'ds'         => $this->request->getPost('ds_edt'),
                'rt'         => $this->request->getPost('rt_edt'),
                'rw'         => $this->request->getPost('rw_edt'),
                'kec'        => $this->request->getPost('kec_edt'),
                'kab'        => $this->request->getPost('kab_edt'),
                'nama_ibu'   => $this->request->getPost('nama_ibu_edt'),
                'pekerjaan'  => $this->request->getPost('pekerjaan_edt'),
                'gaji'       => $this->parseNominal($this->request->getPost('gaji_edt')),
                'usaha'      => $this->request->getPost('usaha_edt'),
                'omzet'      => $this->parseNominal($this->request->getPost('omzet_edt')),
                'tgl_daftar' => $this->request->getPost('tgl_daftar_edt'),
                'no_hp'      => $this->request->getPost('no_hp_edt'),
                'id_bank'    => $idBank,
                'rek_bank'   => $this->request->getPost('rek_bank_edt'),
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->session->get('user_id'),
            ];

            $this->profilPemegangSaham->ubah_anggota(['no_rekening' => $noRekening], $data);
            $this->session->setFlashdata('success', 'Sukses! Data berhasil disimpan.');

            $this->profilPemegangSaham->tambah('activity_log', [
                'user_id'  => $this->session->get('user_id'),
                'activity' => 'Ubah Data Anggota Berhasil',
                'times'    => date('Y-m-d H:i:s'),
            ]);

            return redirect()->to(base_url('profil_pemegang_saham'));
        }

        $this->session->setFlashdata('error', 'Gagal! Pastikan input anda benar!');

        return redirect()->to(base_url('profil_pemegang_saham'));
    }

    public function lihat_data_anggota($noRekening)
    {
        $data['title']    = 'IAA Investment | Profil Pemegang Saham';
        $data['profiles'] = $this->db->table('client_account')
            ->getWhere(['no_rekening' => $noRekening])
            ->getResultArray();

        return view('header', $data)
            . view('menu')
            . view('profil_pemegang_saham/read', $data)
            . view('footer');
    }

    public function hapus_anggota()
    {
        $noRekening = $this->request->getPost('rekening');
        $where      = ['no_rekening' => $noRekening];

        $cek = $this->profilPemegangSaham->cek('transaksi_investasi', $noRekening);
        if (! $cek) {
            $this->profilPemegangSaham->hapus_anggota($where, 'client_account');
            $this->session->setFlashdata('success', 'Sukses! Data berhasil dihapus.');

            $this->profilPemegangSaham->tambah('activity_log', [
                'user_id'  => $this->session->get('user_id'),
                'activity' => 'Hapus Data Anggota Berhasil',
                'times'    => date('Y-m-d H:i:s'),
            ]);
        } else {
            $this->session->setFlashdata('error', 'Gagal! Data tidak dapat dihapus.');

            $this->profilPemegangSaham->tambah('activity_log', [
                'user_id'  => $this->session->get('user_id'),
                'activity' => 'Hapus Data Anggota Gagal',
                'times'    => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->to(base_url('profil_pemegang_saham'));
    }
}

