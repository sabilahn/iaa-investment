<?php

namespace App\Models;

use CodeIgniter\Model;

class SisaHasilUsaha extends Model
{
    public function shu_list()
    {
        return $this->db->table('sisa_hasil_usaha')->get();
    }

    public function cek($tahun)
    {
        return $this->db->table('sisa_hasil_usaha')
            ->where('tahun', $tahun)
            ->get();
    }

    public function cek1($tahun)
    {
        return $this->db->table('saldo_harian')
            ->where('YEAR(created_at)', $tahun)
            ->get();
    }

    public function tambah($data, $table)
    {
        return (bool) $this->db->table($table)->insert($data);
    }

    public function getUser()
    {
        return $this->db->table('client_account')
            ->select('no_rekening, nama')
            ->get()
            ->getResultArray();
    }

    public function getTotalSaldoHarian($tahun)
    {
        return $this->db->table('saldo_harian')
            ->select('SUM(rata_rata_harian) as total_rata_rata_harian')
            ->where('YEAR(created_at)', $tahun)
            ->get()
            ->getRow();
    }

    public function getSaldoHarian($no_rekening, $tahun)
    {
        return $this->db->table('saldo_harian')
            ->select('rata_rata_harian')
            ->where('no_rekening', $no_rekening)
            ->where('YEAR(created_at)', $tahun)
            ->get()
            ->getRow();
    }

    public function edit($table, $data, $where)
    {
        return (bool) $this->db->table($table)->where($where)->update($data);
    }

    public function hapus($where, $table)
    {
        return $this->db->table($table)->where($where)->delete();
    }
}

