<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_api extends CI_Model
{
    public function getUser()
    {
        return $this->db->query("SELECT * FROM v_users")->result_array();
    }

    public function getClient()
    {
        return $this->db->query("SELECT * FROM client_account")->result_array();
    }

    public function getClientId($no_rekening)
    {
        return $this->db->query("SELECT * FROM client_account where no_rekening = '" . $no_rekening . "'")->result_array();
    }

    public function getRataHarianId($no_rekening)
    {
        $year = date('Y');
        return $this->db->query("SELECT * FROM v_saldo_harian where no_rekening = '" . $no_rekening . "' and
        tahun <= '" . $year . "' order by tahun DESC")->result_array();
    }

    public function getDividenId($no_rekening)
    {
        $year = date('Y');
        return $this->db->query("SELECT * FROM v_bagi_hasil where no_rekening = '" . $no_rekening . "' and
        tahun <= '" . $year . "' order by tahun DESC")->result_array();
    }

    public function getTransaksi($no_rekening)
    {
        $year = date('Y');
        return $this->db->query("SELECT * FROM v_transaksi where no_rekening = '" . $no_rekening . "' and year(tgl_transaksi) <= '" . $year . "'
        order by tgl_transaksi DESC")->result_array();
    }

    public function getTransaksi2($no_sertifikat)
    {
        return $this->db->query("SELECT * FROM v_transaksi where no_sertifikat = '" . $no_sertifikat . "'")->result_array();
    }

    public function getSaldoInvestasi($no_rekening, $year)
    {
        return $this->db->query("SELECT * FROM v_saldo_investasi where no_rekening = '" . $no_rekening . "'
        and year(tanggal) = '" . $year . "' order by tanggal asc")->result_array();
    }
}
