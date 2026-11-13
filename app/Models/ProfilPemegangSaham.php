<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilPemegangSaham extends Model
{
    // Ambil semua data dari tabel client_account
    public function profiles()
    {
        return $this->db->table('client_account')->get()->getResultArray();
    }

    // Cek satu baris berdasarkan no_rekening dari tabel tertentu
    public function cek($table, $no_rekening)
    {
        return $this->db->table($table)
            ->getWhere(['no_rekening' => $no_rekening])
            ->getRowArray();
    }

    // Tambahkan data ke tabel tertentu
    public function tambah($table, $data)
    {
        return $this->db->table($table)->insert($data);
    }

    // Ambil detail data anggota berdasarkan no_rekening
    public function edit_data($no_rekening)
    {
        return $this->db->table('client_account')
            ->getWhere(['no_rekening' => $no_rekening])
            ->getRowArray();
    }

    // Update data anggota di tabel client_account
    public function ubah_anggota($where, $data)
    {
        return $this->db->table('client_account')->where($where)->update($data);
    }

    // Hapus data dari tabel tertentu berdasarkan kondisi where
    public function hapus_anggota($where, $table)
    {
        return $this->db->table($table)->where($where)->delete();
    }

    public function daftar_bank()
    {
        return $this->db->table('daftar_bank')->get()->getResultArray();
    }
}

