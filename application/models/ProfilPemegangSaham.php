<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProfilPemegangSaham extends CI_Model
{
    // Ambil semua data dari tabel client_account
    public function profiles()
    {
        return $this->db->get('client_account')->result_array();
    }

    // Cek satu baris berdasarkan no_rekening dari tabel tertentu
    public function cek($table, $no_rekening)
    {
        return $this->db->get_where($table, ['no_rekening' => $no_rekening])->row_array();
    }

    // Tambahkan data ke tabel tertentu
    public function tambah($table, $data)
    {
        return $this->db->insert($table, $data);
    }

    // Ambil detail data anggota berdasarkan no_rekening
    public function edit_data($no_rekening)
    {
        return $this->db->get_where('client_account', ['no_rekening' => $no_rekening])->row_array();
    }

    // Update data anggota di tabel client_account
    public function ubah_anggota($where, $data)
    {
        return $this->db->update('client_account', $data, $where);
    }

    // Hapus data dari tabel tertentu berdasarkan kondisi where
    public function hapus_anggota($where, $table)
    {
        return $this->db->delete($table, $where);
    }
    
    public function daftar_bank()
    {
        return $this->db->get('daftar_bank')->result_array();
    }
}