<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Saham_umum extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('TransaksiInvestasi');

        if ($this->session->userdata('status') != 'login') {
            redirect(base_url('auth'));
        }
    }
    public function index()
    {
        $data['title'] = 'IAA Investment | Saham Umum';

        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('saham_umum', $data);
        $this->load->view('footer');
    }

    public function getData($tahun)
    {
        echo json_encode($this->TransaksiInvestasi->saldoInvestasi($tahun)->result());
    }

    public function getDataID()
    {
        $no_rekening = $_POST['no_rekening'];
        $tahun = $_POST['tahun'];

        echo json_encode($this->TransaksiInvestasi->getSaldoId($tahun, $no_rekening)->result());
    }
}
