<?php

namespace App\Models;

use CodeIgniter\Model;

class M_api extends Model
{
    public function getUser()
    {
        return $this->db->table('v_users')->get()->getResultArray();
    }

    public function getClient()
    {
        return $this->db->table('client_account')->get()->getResultArray();
    }

    public function getClientId($no_rekening)
    {
        return $this->db->table('client_account')
            ->where('no_rekening', $no_rekening)
            ->get()
            ->getResultArray();
    }

    public function getRataHarianId($no_rekening)
    {
        $year = date('Y');

        return $this->db->table('v_saldo_harian')
            ->where('no_rekening', $no_rekening)
            ->where('tahun <=', $year)
            ->orderBy('tahun', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getDividenId($no_rekening)
    {
        $year = date('Y');

        return $this->db->table('v_bagi_hasil')
            ->where('no_rekening', $no_rekening)
            ->where('tahun <=', $year)
            ->orderBy('tahun', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getTransaksi($no_rekening)
    {
        $year = date('Y');

        return $this->db->table('v_transaksi')
            ->where('no_rekening', $no_rekening)
            ->where('YEAR(tgl_transaksi) <=', $year)
            ->orderBy('tgl_transaksi', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getTransaksi2($no_sertifikat)
    {
        return $this->db->table('v_transaksi')
            ->where('no_sertifikat', $no_sertifikat)
            ->get()
            ->getResultArray();
    }

    public function getSaldoInvestasi($no_rekening, $year)
    {
        return $this->db->table('v_saldo_investasi')
            ->where('no_rekening', $no_rekening)
            ->where('YEAR(tanggal)', $year)
            ->orderBy('tanggal', 'ASC')
            ->get()
            ->getResultArray();
    }
}

