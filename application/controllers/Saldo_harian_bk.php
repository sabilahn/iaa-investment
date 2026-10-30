<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Saldo_harian extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('SaldoHarian');
        $this->load->model('TransaksiInvestasi');
        $this->load->helper('url');
        if ($this->session->userdata('status') != 'login') {
            redirect(base_url('auth'));
        }
    }
    public function index()
    {
        $tahun = $this->input->post('tahun_saldo');
        if ($tahun == "") {
            $tahun = date('Y');
        }
        $data['tahun'] = $tahun;
        $data['daftar_saldo'] = $this->SaldoHarian->saldo_harian_list($tahun)->result_array();

        $data['action'] = '<a class="btn btn-primary" href="' . site_url() . 'saldo_harian/update_saldo/' . $tahun . '"><i class="fas fa-sync"> Sesuaikan Saldo</i></a>';

        $data['title'] = 'IAA Investment | Saldo Harian';
        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('saldo_harian', $data);
        $this->load->view('footer');
    }

    public function getHari($tahun)
    {
        if ($tahun != date('Y')) {
            $tgl_skrg = strtotime(date($tahun . "-12-31"));
            $tgl_awal = date('Y-m-d', strtotime($tahun . "-01-01" . "-1 days"));
            $hari_saldo_tahun = ($tgl_skrg - strtotime($tgl_awal)) / 86400;
        } else {
            $tgl_skrg = strtotime(date("Y-m-d"));
            $tgl_awal = date('Y-m-d', strtotime(date('Y') . "-01-01" . "-1 days"));
            $hari_saldo_tahun = ($tgl_skrg - strtotime($tgl_awal)) / 86400;
        }

        return $hari_saldo_tahun;
    }

    public function update_saldo_user($user, $tahun)
    {
        $saldo_skr = 0;
        $penambahan_saldo = 0;

        $from = date('Y-m-d', strtotime($tahun . "-01-01" . "-1 days"));

        if ($tahun != date('Y')) {
            $to = date($tahun . '-12-31');
        } else {
            $to = date($tahun . '-m-d');
        }
        // var_dump($to);
        while (strtotime($from) < strtotime($to)) {
            $from = mktime(0, 0, 0, date("m", strtotime($from)), date("d", strtotime($from)) + 1, date("Y", strtotime($from)));
            $from = date("Y-m-d", $from);

            $cek = $this->TransaksiInvestasi->cekTransaksi($user, $from)->result_array();
            $cek2 = $this->TransaksiInvestasi->cekPenarikan($user, $from)->result_array();
            if ($cek) {
                if($cek2){
                    foreach ($cek as $transaksi) {
                        foreach ($cek2 as $penarikan) {
                            $saldo_skr = $saldo_skr + $transaksi['jml_transaksi'] - $penarikan['jml_penarikan'] + $penambahan_saldo;
                            $penambahan_saldo = $penambahan_saldo + $transaksi['jml_transaksi'] - $penarikan['jml_penarikan'];
                        }
                    }
                }else{
                    foreach ($cek as $transaksi) {
                        $saldo_skr = $saldo_skr + $transaksi['jml_transaksi'] + $penambahan_saldo;
                        $penambahan_saldo = $penambahan_saldo + $transaksi['jml_transaksi'];
                    }
                }
            } else if ($cek2) {
                foreach ($cek2 as $penarikan) {
                    $saldo_skr = $saldo_skr - $penarikan['jml_penarikan'] + $penambahan_saldo;
                    $penambahan_saldo = $penambahan_saldo - $penarikan['jml_penarikan'];
                }
            } else {
                $saldo_skr = $saldo_skr + $penambahan_saldo;
            }
        }
        set_time_limit(500);

        return $saldo_skr;
    }
    public function update_saldo($tahun)
    {
        $users = $this->TransaksiInvestasi->rekening_list()->result_array();
        $hari = $this->getHari($tahun);

        $this->db->trans_begin();
        try {
            foreach ($users as $user) {
                $rata_harian = $this->update_saldo_user($user['no_rekening'], $tahun);
                $rata_tahun = $rata_harian / $hari;

                $update_saldo = [
                    'rata_rata_harian' => $rata_harian,
                    'rata_rata_tahun' => $rata_tahun,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->userdata('user_id')
                ];

                $this->TransaksiInvestasi->edit('saldo_harian', $update_saldo, array('no_rekening' => $user['no_rekening'], 'tahun' => $tahun));
                
                break;
                
            }
            
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Sukses! Data berhasil disimpan.');
            redirect($_SERVER['HTTP_REFERER']);
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal! Terjadi kesalahan dalam penyesuaian.');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
}
