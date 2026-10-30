<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Penarikan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('TransaksiInvestasi');
    }
    public function index($id)
    {
        $data['title'] = 'IAA Investment | Data Transaksi';
        $data['data_penarikan'] = $this->TransaksiInvestasi->getWhere('v_penarikan_investasi', ['id_penarikan' => $id])->row();

        $this->load->view('surat_saham/surat_keterangan_qrcode', $data);
    }
}
