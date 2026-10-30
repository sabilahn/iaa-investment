<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LaporanModel extends CI_Model
{
    public function getUsers()
{
    return $this->db
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
            'tgl_daftar'
        ])
        ->from('client_account')
        ->get()
        ->result_array();
}

    public function getKec()
    {
        return $this->db
            ->select('kec')
            ->from('v_bagi_hasil')
            ->group_by('kec')
            ->order_by('kec', 'ASC')
            ->get()
            ->result_array();
    }

    public function getData($table, $tahun)
    {
        return $this->db
            ->get_where($table, $tahun)
            ->result_array();
    }
    
    public function getDataBagiHasil($conds)
    {
       return $this->db
            ->select('bh.*, rk.rek_bank, rk.nama_bank')
            ->from('v_bagi_hasil bh')
            ->join('v_rek_bank rk', 'rk.no_rekening = bh.no_rekening', 'left')
            ->where($conds)
            ->get()
            ->result_array();
    }

    public function getDataTransaksiBln($bulan, $tahun)
    {
        $tgl_awal = $tahun . '-01-01';

        return $this->db
            ->from('v_transaksi')
            ->where('MONTH(tgl_transaksi)', $bulan)
            ->where('YEAR(tgl_transaksi)', $tahun)
            ->where('is_deleted', 0)
            ->where('tgl_transaksi !=', $tgl_awal)
            ->get()
            ->result_array();
    }

    public function getDataTransaksiTerknini($tgl_awal, $tgl_akhir)
    {
        return $this->db
            ->from('v_transaksi')
            ->where('is_deleted', 0)
            ->where('tgl_transaksi >=', $tgl_awal)
            ->where('tgl_transaksi <=', $tgl_akhir)
            ->get()
            ->result_array();
    }

    public function getSaldoAwal($tahun)
    {
        $tgl_awal = $tahun . '-01-01';

        return $this->db
            ->from('v_transaksi')
            ->where('tgl_transaksi', $tgl_awal)
            ->where('is_deleted', 0)
            ->get()
            ->result_array();
    }

    public function tambah($table, $data)
    {
        return $this->db->insert($table, $data);
    }

    public function getRekeningBank()
    {
        return $this->db
            ->from('v_rek_bank')
            ->get()
            ->result_array();
    }
}