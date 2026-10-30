<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PenambahanSaldo extends CI_Model
{
    public function getUser()
    {
        return $this->db->query("SELECT no_rekening FROM client_account")->result_array();
    }
    public function getTransaksi($no_rekening, $tahun)
    {
        return $this->db->query("SELECT saldo as jml_transaksi FROM v_transaksi_thn WHERE no_rekening = '" . $no_rekening . "' AND tahun = '" . $tahun . "'")->row_array();
    }
    public function getSaldo($no_rekening, $tahun)
    {
        return $this->db->query("SELECT rata_rata_harian FROM v_saldo_harian WHERE no_rekening = '" . $no_rekening . "' AND tahun = '" . $tahun . "'")->row_array();
    }
    public function edit($table, $data, $where)
    {
        if ($this->db->update($table, $data, $where)) {
            return true;
        }
        return false;
    }
}
