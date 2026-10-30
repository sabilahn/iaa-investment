<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Distribusi_dan_porsi extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('DistribusiDanPorsi');
        if ($this->session->userdata('status') != 'login') {
            redirect(base_url('auth'));
        }
    }
    public function index()
    {
        $data['title'] = 'IAA Investment | Distribusi dan Porsi';
        $tahun = $this->input->post('tahunDispor');
        if ($tahun == "") {
            $tahun = date('Y');
        }
        $data['tahun'] = $tahun;
        $data['porsi_dispen'] = $this->DistribusiDanPorsi->show('porsi_dispen', $tahun)->result_array();

        $data['porsi_disda'] = $this->DistribusiDanPorsi->show('porsi_disda', $tahun)->result_array();

        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('distribusi_dan_porsi', $data);
        $this->load->view('footer');
    }
}
