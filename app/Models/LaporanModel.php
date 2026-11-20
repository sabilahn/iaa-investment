<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    public function getUsers()
    {
        return $this->db->table('client_account')
            ->select([
                'no_rekening',
                'nik',
                'nama',
                'dsn',
                'ds',
                'rt',
                'rw',
                'kec',
                'kab',
                'tgl_daftar',
            ])
            ->get()
            ->getResultArray();
    }

    public function getKec()
    {
        return $this->db->table('v_bagi_hasil')
            ->select('kec')
            ->groupBy('kec')
            ->orderBy('kec', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getData($table, $tahun)
    {
        return $this->db->table($table)
            ->getWhere($tahun)
            ->getResultArray();
    }

    public function getDataBagiHasil($conds)
    {
        return $this->db->table('v_bagi_hasil bh')
            ->select('bh.*, rk.rek_bank, rk.nama_bank')
            ->join('v_rek_bank rk', 'rk.no_rekening = bh.no_rekening', 'left')
            ->where($conds)
            ->get()
            ->getResultArray();
    }

    public function getDataTransaksiBln($bulan, $tahun)
    {
        $tgl_awal = $tahun . '-01-01';

        return $this->db->table('v_transaksi')
            ->where('MONTH(tgl_transaksi)', $bulan)
            ->where('YEAR(tgl_transaksi)', $tahun)
            ->where('is_deleted', 0)
            ->where('tgl_transaksi !=', $tgl_awal)
            ->get()
            ->getResultArray();
    }

    public function getDataTransaksiTerknini($tgl_awal, $tgl_akhir)
    {
        return $this->db->table('v_transaksi')
            ->where('is_deleted', 0)
            ->where('tgl_transaksi >=', $tgl_awal)
            ->where('tgl_transaksi <=', $tgl_akhir)
            ->get()
            ->getResultArray();
    }

    public function getSaldoAwal($tahun)
    {
        $tgl_awal = $tahun . '-01-01';

        return $this->db->table('v_transaksi')
            ->where('tgl_transaksi', $tgl_awal)
            ->where('is_deleted', 0)
            ->get()
            ->getResultArray();
    }

    public function tambah($table, $data)
    {
        return $this->db->table($table)->insert($data);
    }

    public function getRekeningBank()
    {
        return $this->db->table('v_rek_bank')
            ->get()
            ->getResultArray();
    }
}

