<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Saldo_harian extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model(['SaldoHarian', 'TransaksiInvestasi']);
        
        if ($this->session->userdata('status') !== 'login') {
            redirect(base_url('auth'));
        }
    }

    public function index()
    {
        $tahun = $this->input->post('tahun_saldo');
        $tahun = empty($tahun) ? date('Y') : $tahun;

        $data = [
            'tahun' => $tahun,
            'daftar_saldo' => $this->SaldoHarian->saldo_harian_list($tahun)->result_array(),
            'action' => '<a class="btn btn-primary" href="' . site_url('saldo_harian/update_saldo/' . $tahun) . '"><i class="fas fa-sync"> Sesuaikan Saldo</i></a>',
            'title' => 'IAA Investment | Saldo Harian',
        ];

        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('saldo_harian', $data);
        $this->load->view('footer');
    }


    public function getHari($tahun)
    {
    
        $jumlahHari = date("L", strtotime("$tahun-01-01")) ? 366 : 365;
        
        return $jumlahHari;
    }
    
    public function update_saldo_user($user, $tahun)
    {
        $saldo = 0;
        $akumulasi = 0;
        

        // $start = new DateTime("$tahun-01-01 -1 day");
        // $end = $tahun != date('Y') ? new DateTime("$tahun-12-31") : new DateTime();

        // $period = new DatePeriod($start, new DateInterval('P1D'), $end);
        
        $start = new DateTime("$tahun-01-01");
        $end = new DateTime("$tahun-12-31");
        $period = new DatePeriod($start, new DateInterval('P1D'), $end->modify('+1 day'));

        foreach ($period as $date) {
            $tanggal = $date->format('Y-m-d');
            $transaksi = $this->TransaksiInvestasi->cekTransaksi($user, $tanggal)->result_array();
            $penarikan = $this->TransaksiInvestasi->cekPenarikan($user, $tanggal)->result_array();

            $total_transaksi = array_sum(array_column($transaksi, 'jml_transaksi'));
            $total_penarikan = array_sum(array_column($penarikan, 'jml_penarikan'));

            $saldo += $total_transaksi - $total_penarikan + $akumulasi;
            $akumulasi += $total_transaksi - $total_penarikan;
        }
        

        set_time_limit(500);
        
        return $saldo;
    }

    public function update_saldo($tahun, $offset = 0, $limit = 100)
    {
        $total = $this->TransaksiInvestasi->jumlah_rekening();
        $hari = $this->getHari($tahun);
    
        // Ambil batch rekening dari client_account
        $users = $this->TransaksiInvestasi->rekening_list_limit($limit, $offset)->result_array();
    
        if (empty($users)) {
            $this->session->set_flashdata('success', '✅ Semua data berhasil diproses.');
            redirect('saldo_harian');
            return;
        }
    
        $this->db->trans_begin();
    
        try {
            foreach ($users as $user) {
                // REF: Optimalkan pengambilan transaksi dan penarikan dalam bentuk array
                $no_rekening = $user['no_rekening'];
    
                $transaksi_all = $this->TransaksiInvestasi->cekTransaksi2($no_rekening, $tahun)->result_array();
                $penarikan_all = $this->TransaksiInvestasi->cekPenarikanTahun($no_rekening, $tahun)->result_array();
    
                // Index per tanggal agar tidak query ulang
                $transaksi_index = [];
                foreach ($transaksi_all as $row) {
                    $transaksi_index[$row['tgl_transaksi']][] = $row;
                }
    
                $penarikan_index = [];
                foreach ($penarikan_all as $row) {
                    $penarikan_index[$row['tgl_penarikan']][] = $row;
                }
    
                // Hitung saldo per hari
                $saldo_skr = 0;
                $penambahan_saldo = 0;
                
                $start = new DateTime("$tahun-01-01"); // langsung 1 Januari
                $end = new DateTime("$tahun-12-31"); // langsung 31 Desember
                $period = new DatePeriod($start, new DateInterval('P1D'), $end->modify('+1 day'));
    
                foreach ($period as $date) {
                    $tanggal = $date->format('Y-m-d');
    
                    $transaksis = isset($transaksi_index[$tanggal]) ? $transaksi_index[$tanggal] : [];
                    $penarikans = isset($penarikan_index[$tanggal]) ? $penarikan_index[$tanggal] : [];
    
                    $jml_transaksi = array_sum(array_column($transaksis, 'jml_transaksi'));
                    $jml_penarikan = array_sum(array_column($penarikans, 'jml_penarikan'));
    
                    $saldo_skr += ($jml_transaksi - $jml_penarikan) + $penambahan_saldo;
                    $penambahan_saldo += ($jml_transaksi - $jml_penarikan);
                }
    
                $rata_tahun = $saldo_skr / $hari;
    
                // Simpan ke saldo_harian
                $data_update = [
                    'rata_rata_harian' => $saldo_skr,
                    'rata_rata_tahun' => $rata_tahun,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->userdata('user_id')
                ];
    
                $this->TransaksiInvestasi->edit('saldo_harian', $data_update, [
                    'no_rekening' => $no_rekening,
                    'tahun' => $tahun
                ]);
            }
    
            $this->db->trans_commit();
            $this->session->set_flashdata('success', "✅ Batch berhasil diproses: $limit data.");
        } catch (\Throwable $e) {
            $this->db->trans_rollback();
            log_message('error', '❌ Gagal proses batch: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Batch gagal diproses.');
            redirect('saldo_harian');
            return;
        }
    
        // Lanjut ke batch berikutnya jika masih ada
        $next_offset = $offset + $limit;
        if ($next_offset < $total) {
            redirect("saldo_harian/update_saldo/$tahun/$next_offset/$limit");
        } else {
            $this->session->set_flashdata('success', 'Semua data berhasil disimpan!');
            redirect('saldo_harian');
        }
    }


}

