<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bagi_hasil extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('BagiHasil');
        if ($this->session->userdata('status') != 'login') {
            redirect(base_url('auth'));
        }
    }
    public function index()
    {
        $data['title'] = 'IAA Investment | Bagi Hasil SHU';

        $tahun = $this->input->post('tahunBagiHasil');
        if ($tahun == "") {
            $tahun = date('Y');
        }
        $data['tahun'] = $tahun;
        $data['data_bagi_hasil'] = $this->BagiHasil->data($tahun)->result_array();

        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('bagi_hasil', $data);
        $this->load->view('footer');
    }

    public function lihat_data()
    {
        $data['title'] = 'IAA Investment | Bagi Hasil SHU';
        $tahun = $this->input->post('tahunBagiHasil');
        $data['data_bagi_hasil'] = $this->BagiHasil->data($tahun)->result_array();

        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('bagi_hasil', $data);
        $this->load->view('footer');
    }
}
