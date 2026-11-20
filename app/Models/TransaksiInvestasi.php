<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiInvestasi extends Model
{
    // Ambil semua transaksi dalam tahun tertentu
    public function transaksi_list($tahun)
    {
        $start = "$tahun-01-01";
        $end   = "$tahun-12-31";

        return $this->db->table('transaksi_investasi')
            ->select('id_transaksi, no_rekening, no_sertifikat, jml_transaksi, tgl_transaksi, created_at')
            ->where('tgl_transaksi >=', $start)
            ->where('tgl_transaksi <=', $end)
            ->where('is_deleted', 0)
            ->orderBy('id_transaksi', 'DESC')
            ->get();
    }

    // Ambil saldo dari view berdasarkan tahun & rekening
    public function jml_transaksi($no_rekening, $tahun)
    {
        return $this->db->table('v_transaksi_thn')
            ->select('saldo')
            ->where('no_rekening', $no_rekening)
            ->where('tahun', $tahun)
            ->get();
    }

    // Cek transaksi harian
    public function cekTransaksi($no_rekening, $tgl_transaksi)
    {
        return $this->db->table('transaksi_investasi')
            ->select('jml_transaksi')
            ->where([
                'no_rekening'   => $no_rekening,
                'tgl_transaksi' => $tgl_transaksi,
                'is_deleted'    => 0,
            ])
            ->get();
    }

    // Hitung jumlah transaksi per tahun
    public function countTransaksi($no_rekening, $tgl_transaksi)
    {
        $tahun = date('Y', strtotime($tgl_transaksi));

        return $this->db->table('transaksi_investasi')
            ->select('COUNT(id_transaksi) AS banyak_transaksi')
            ->where('no_rekening', $no_rekening)
            ->where('tgl_transaksi >=', "$tahun-01-01")
            ->where('tgl_transaksi <=', "$tahun-12-31")
            ->get()
            ->getRow();
    }

    public function cekSaldo($no_rekening, $tgl_transaksi)
    {
        $tahun = date('Y', strtotime($tgl_transaksi));

        return $this->db->table('saldo_harian')
            ->select('no_rekening')
            ->where([
                'no_rekening' => $no_rekening,
                'tahun'       => $tahun,
            ])
            ->get();
    }

    // Ambil list rekening client
    public function rekening_list()
    {
        return $this->db->table('client_account')
            ->select('no_rekening')
            ->get();
    }

    // Tambah data
    public function tambah($table, $data)
    {
        return $this->db->table($table)->insert($data);
    }

    // Tambah dan return insertID
    public function tambah_id($table, $data)
    {
        $this->db->table($table)->insert($data);

        return $this->db->insertID();
    }

    // Edit data
    public function edit($table, $data, $where)
    {
        return $this->db->table($table)->where($where)->update($data);
    }

    // Hapus data
    public function hapus($table, $where)
    {
        return $this->db->table($table)->where($where)->delete();
    }

    // Hapus saldo berdasarkan transaksi dan tahun
    public function hapus_saldo($no_rekening, $tgl_transaksi)
    {
        $tahun = date('Y', strtotime($tgl_transaksi));

        return $this->db->table('saldo_harian')
            ->where([
                'no_rekening' => $no_rekening,
                'tahun'       => $tahun,
            ])
            ->delete();
    }

    // List penarikan investasi berdasarkan tahun
    public function penarikan_list($tahun)
    {
        $start = "$tahun-01-01";
        $end   = "$tahun-12-31";

        return $this->db->table('penarikan_investasi')
            ->select('id_penarikan, no_rekening, jml_penarikan, tgl_penarikan, created_at')
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    // Cek penarikan harian
    public function cekPenarikan($no_rekening, $tgl_penarikan)
    {
        return $this->db->table('penarikan_investasi')
            ->select('jml_penarikan')
            ->where([
                'no_rekening'   => $no_rekening,
                'tgl_penarikan' => $tgl_penarikan,
            ])
            ->get();
    }

    // Cek transaksi per tahun
    public function cekTransaksi2($no_rekening, $tahun)
    {
        $start = "$tahun-01-01";
        $end   = "$tahun-12-31";

        return $this->db->table('transaksi_investasi')
            ->where('no_rekening', $no_rekening)
            ->where('tgl_transaksi >=', $start)
            ->where('tgl_transaksi <=', $end)
            ->where('is_deleted', 0)
            ->get();
    }

    // Ambil data transaksi dari view (non-kosong)
    public function getTransaksi($tahun)
    {
        return $this->db->table('v_transaksi_thn')
            ->where('tahun', $tahun)
            ->where('jml_transaksi !=', '')
            ->get();
    }

    public function getWhere($table, $where)
    {
        return $this->db->table($table)->getWhere($where);
    }

    // Ambil nomor sertifikat dari transaksi
    public function getSertifikat($id_transaksi)
    {
        return $this->db->table('transaksi_investasi')
            ->select('no_sertifikat')
            ->where('id_transaksi', $id_transaksi)
            ->get();
    }

    // Ambil saldo dari view
    public function saldoInvestasi($tahun)
    {
        return $this->db->table('v_transaksi_thn')
            ->where('tahun', $tahun)
            ->get();
    }

    // Ambil saldo detail dari view berdasarkan rekening dan tahun
    public function getSaldoId($tahun, $no_rekening)
    {
        $start = "$tahun-01-01";
        $end   = "$tahun-12-31";

        return $this->db->table('v_saldo_investasi')
            ->where('no_rekening', $no_rekening)
            ->where('tanggal >=', $start)
            ->where('tanggal <=', $end)
            ->get();
    }

    public function jumlah_rekening()
    {
        return $this->db->table('client_account')->countAll();
    }

    public function rekening_list_limit($limit = 100, $offset = 0)
    {
        return $this->db->table('client_account')
            ->select('no_rekening')
            ->get($limit, $offset);
    }

    public function cekPenarikanTahun($no_rekening, $tahun)
    {
        $start = "$tahun-01-01";
        $end   = "$tahun-12-31";

        return $this->db->table('penarikan_investasi')
            ->where('no_rekening', $no_rekening)
            ->where('tgl_penarikan >=', $start)
            ->where('tgl_penarikan <=', $end)
            ->get();
    }
}

