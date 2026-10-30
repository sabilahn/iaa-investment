<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TransaksiInvestasi extends CI_Model
{
       // Ambil semua transaksi dalam tahun tertentu
    public function transaksi_list($tahun)
    {
        $start = "$tahun-01-01";
        $end = "$tahun-12-31";

        return $this->db
            ->select('id_transaksi, no_rekening, no_sertifikat, jml_transaksi, tgl_transaksi, created_at') // ambil kolom penting saja
            ->from('transaksi_investasi')
            ->where('tgl_transaksi >=', $start)
            ->where('tgl_transaksi <=', $end)
            ->where('is_deleted', 0)
            ->order_by('created_at', 'DESC')
            ->get();
    }
    
     // Ambil saldo dari view berdasarkan tahun & rekening
    public function jml_transaksi($no_rekening, $tahun)
    {
        return $this->db
            ->select('saldo')
            ->from('v_transaksi_thn')
            ->where('no_rekening', $no_rekening)
            ->where('tahun', $tahun)
            ->get();
    }
    
       // Cek transaksi harian
    public function cekTransaksi($no_rekening, $tgl_transaksi)
    {
        return $this->db
            ->select('jml_transaksi')
            ->where([
                'no_rekening' => $no_rekening,
                'tgl_transaksi' => $tgl_transaksi,
                'is_deleted' => 0
            ])
            ->get('transaksi_investasi');
    }
    
      // Hitung jumlah transaksi per tahun
    public function countTransaksi($no_rekening, $tgl_transaksi)
    {
        $tahun = date('Y', strtotime($tgl_transaksi));
        return $this->db
            ->select('COUNT(id_transaksi) AS banyak_transaksi')
            ->from('transaksi_investasi')
            ->where('no_rekening', $no_rekening)
            ->where('tgl_transaksi >=', "$tahun-01-01")
            ->where('tgl_transaksi <=', "$tahun-12-31")
            ->get()
            ->row();
    }
    
    public function cekSaldo($no_rekening, $tgl_transaksi)
    {
        $tahun = date('Y', strtotime($tgl_transaksi));
    
        return $this->db
            ->select('no_rekening')
            ->where([
                'no_rekening' => $no_rekening,
                'tahun' => $tahun
            ])
            ->get('saldo_harian');
    }

  // Ambil list rekening client
    public function rekening_list()
    {
        return $this->db
            ->select('no_rekening')
            ->get('client_account');
    }
    
     // Tambah data
    public function tambah($table, $data)
    {
        return $this->db->insert($table, $data);
    }

    // Tambah dan return insert_id
    public function tambah_id($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    // Edit data
    public function edit($table, $data, $where)
    {
        return $this->db->update($table, $data, $where);
    }

    // Hapus data
    public function hapus($table, $where)
    {
        return $this->db->where($where)->delete($table);
    }

    // Hapus saldo berdasarkan transaksi dan tahun
    public function hapus_saldo($no_rekening, $tgl_transaksi)
    {
        $tahun = date('Y', strtotime($tgl_transaksi));
        return $this->db
            ->where([
                'no_rekening' => $no_rekening,
                'tahun' => $tahun
            ])
            ->delete('saldo_harian');
    }

   // List penarikan investasi berdasarkan tahun
    public function penarikan_list($tahun)
    {
        $start = "$tahun-01-01";
        $end = "$tahun-12-31";

        return $this->db
            ->select('id_penarikan, no_rekening, jml_penarikan, tgl_penarikan, created_at')
            ->from('penarikan_investasi')
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->order_by('created_at', 'DESC')
            ->get();
    }
    
     // Cek penarikan harian
    public function cekPenarikan($no_rekening, $tgl_penarikan)
    {
        return $this->db
            ->select('jml_penarikan')
            ->where([
                'no_rekening' => $no_rekening,
                'tgl_penarikan' => $tgl_penarikan
            ])
            ->get('penarikan_investasi');
    }
    
    // Cek transaksi per tahun
    public function cekTransaksi2($no_rekening, $tahun)
    {
        $start = "$tahun-01-01";
        $end = "$tahun-12-31";

        return $this->db
            ->where('no_rekening', $no_rekening)
            ->where('tgl_transaksi >=', $start)
            ->where('tgl_transaksi <=', $end)
            ->where('is_deleted', 0)
            ->get('transaksi_investasi');
    }

    // Ambil data transaksi dari view (non-kosong)
    public function getTransaksi($tahun)
    {
        return $this->db
            ->where('tahun', $tahun)
            ->where('jml_transaksi !=', '')
            ->get('v_transaksi_thn');
    }
    
    public function getWhere($table, $where)
    {
        return $this->db->get_where($table, $where);
    }
    
     // Ambil nomor sertifikat dari transaksi
    public function getSertifikat($id_transaksi)
    {
        return $this->db
            ->select('no_sertfikat')
            ->where('id_transaksi', $id_transaksi)
            ->get('transaksi_investasi');
    }

    // Ambil saldo dari view
    public function saldoInvestasi($tahun)
    {
        return $this->db
            ->where('tahun', $tahun)
            ->get('v_transaksi_thn');
    }

      // Ambil saldo detail dari view berdasarkan rekening dan tahun
    public function getSaldoId($tahun, $no_rekening)
    {
        $start = "$tahun-01-01";
        $end = "$tahun-12-31";

        return $this->db
            ->where('no_rekening', $no_rekening)
            ->where('tanggal >=', $start)
            ->where('tanggal <=', $end)
            ->get('v_saldo_investasi');
    }
    
    public function jumlah_rekening()
    {
        return $this->db->count_all('client_account');
    }
    
    public function rekening_list_limit($limit = 100, $offset = 0)
    {
        return $this->db
            ->select('no_rekening')
            ->get('client_account', $limit, $offset);
    }
    
    public function cekPenarikanTahun($no_rekening, $tahun)
    {
        $start = "$tahun-01-01";
        $end = "$tahun-12-31";
    
        return $this->db
            ->where('no_rekening', $no_rekening)
            ->where('tgl_penarikan >=', $start)
            ->where('tgl_penarikan <=', $end)
            ->get('penarikan_investasi');
    }


}
