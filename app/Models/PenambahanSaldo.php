<?php

namespace App\Models;

use CodeIgniter\Model;

class PenambahanSaldo extends Model
{
    public function getUser()
    {
        return $this->db->table('client_account')
            ->select('no_rekening')
            ->get()
            ->getResultArray();
    }

    public function getTransaksi($no_rekening, $tahun)
    {
        return $this->db->table('v_transaksi_thn')
            ->select('saldo as jml_transaksi')
            ->where('no_rekening', $no_rekening)
            ->where('tahun', $tahun)
            ->get()
            ->getRowArray();
    }

    public function getSaldo($no_rekening, $tahun)
    {
        return $this->db->table('v_saldo_harian')
            ->select('rata_rata_harian')
            ->where('no_rekening', $no_rekening)
            ->where('tahun', $tahun)
            ->get()
            ->getRowArray();
    }

    public function edit($table, $data, $where)
    {
        return (bool) $this->db->table($table)->where($where)->update($data);
    }
}

