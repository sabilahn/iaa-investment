<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Surat_saham extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('SuratSaham');
    }
    public function index($no_sertifikat)
    {
        $data['title'] = 'IAA Investment | Data Transaksi';
        $data['data_transaksi'] = $this->SuratSaham->getData(array('no_sertifikat' => $no_sertifikat))->row();

        $this->load->view('surat_saham/surat_saham_qrcode', $data);
    }
}
