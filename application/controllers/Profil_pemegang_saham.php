<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profil_pemegang_saham extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ProfilPemegangSaham');
        $this->load->library('form_validation');
        $this->load->helper('form');
        if ($this->session->userdata('status') != 'login') {
            redirect(base_url('auth'));
        }
    }
    public function index()
    {
        $data['profiles'] = $this->ProfilPemegangSaham->profiles();

        foreach ($data['profiles'] as $i => $profile) {
            $data['profiles'][$i]['action'] = '<a href="' . site_url() . 'profil_pemegang_saham/lihat_data_anggota/' . $profile['no_rekening'] . '" class="btn btn-sm btn-info"><i class=" far fa-eye"></i></a>
            <a href="' . site_url() . 'profil_pemegang_saham/ubah_data_anggota/' . $profile['no_rekening'] . '" class=" btn btn-sm btn-primary"><i class="fas fa-pencil-alt"></i></a>
            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal' . $profile['no_rekening'] . ' "><i class="fa fa-trash"></i></button>';
        }
        $data['title'] = 'IAA Investment | Profil Pemegang Saham';

        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('profil_pemegang_saham/profil_pemegang_saham', $data);
        $this->load->view('footer');
    }
    public function show()
    {
        $res = $this->ProfilPemegangSaham->profiles();
        echo json_encode($res->result_array());
    }
    
    public function get_bank()
    {
        $banks = $this->ProfilPemegangSaham->daftar_bank();
    
        header('Content-Type: application/json');
        echo json_encode($banks);
    }

    public function tambah_data_anggota()
    {
        $data['title'] = 'IAA Investment | Profil Pemegang Saham';

        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('profil_pemegang_saham/create');
        $this->load->view('footer');
    }
    
    
    public function tambah_data()
    {
        $this->form_validation->set_rules('rekening', 'Nomor rekening', 'required|trim|exact_length[9]');
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        if ($this->form_validation->run() == true) {
            $data = [
                'no_rekening' => $_POST["rekening"],
                'nik' => $_POST["nik"],
                'nama' => strtolower($_POST["nama"]),
                'tmp_lahir' => strtolower($_POST["tmp_lahir"]),
                'tgl_lahir' => $_POST["tgl_lahir"],
                'dsn' => $_POST["dsn"],
                'ds' => $_POST["ds"],
                'rt' => $_POST["rt"],
                'rw' => $_POST["rw"],
                'kec' => $_POST["kec"],
                'kab' => $_POST["kab"],
                'nama_ibu' => $_POST["nama_ibu"],
                'pekerjaan' => $_POST["pekerjaan"],
                'gaji' => $_POST["gaji"],
                'usaha' => $_POST["usaha"],
                'omzet' => $_POST["omzet"],
                'tgl_daftar' => $_POST["tgl_daftar"],
                'no_hp' => $_POST["no_hp"],
                'id_bank' => $_POST["id_bank"],
                'rek_bank' => $_POST["rek_bank"],
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('user_id')
            ];
            $cek = $this->ProfilPemegangSaham->cek('client_account',  $_POST["rekening"]);

            if (!$cek) {
                $this->ProfilPemegangSaham->tambah('client_account', $data);
                $this->session->set_flashdata('success', 'Sukses! Data berhasil disimpan.');

                $activity_data = array(
                    'user_id' => $this->session->userdata('user_id'),
                    'activity' => 'Tambah Data Anggota Berhasil',
                    'times' => date('Y-m-d H:i:s')
                );

                $this->ProfilPemegangSaham->tambah('activity_log', $activity_data);
            } else {
                $this->session->set_flashdata('error', 'Gagal! No rekening sudah ada.');
            }
            redirect(base_url('profil_pemegang_saham'));
        } else {
            $this->session->set_flashdata('error', 'Gagal! Pastikan input anda benar!');
            redirect(base_url('profil_pemegang_saham'));
        }
    }
    public function ubah_data_anggota($no_rekening)
    {
        $data['title'] = 'IAA Investment | Profil Pemegang Saham';
        $data['profiles'] = $this->db->get_where('client_account', ['no_rekening' => $no_rekening])->result_array();

        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('profil_pemegang_saham/update', $data);
        $this->load->view('footer');
    }
    
    public function ubah_data()
{
    $this->form_validation->set_rules('nama_edt', 'Nama', 'required');

    if ($this->form_validation->run() == true) {
        $no_rekening = $_POST['rekening_edt'];
        $id_bank_field = 'id_bank_edt_' . $no_rekening;
        $id_bank = isset($_POST[$id_bank_field]) ? $_POST[$id_bank_field] : null;

        $data = [
            'nik'         => $_POST['nik_edt'],
            'nama'        => strtolower($_POST['nama_edt']),
            'tmp_lahir'   => strtolower($_POST['tmp_lahir_edt']),
            'tgl_lahir'   => $_POST['tgl_lahir_edt'],
            'dsn'         => $_POST['dsn_edt'],
            'ds'          => $_POST['ds_edt'],
            'rt'          => $_POST['rt_edt'],
            'rw'          => $_POST['rw_edt'],
            'kec'         => $_POST['kec_edt'],
            'kab'         => $_POST['kab_edt'],
            'nama_ibu'    => $_POST['nama_ibu_edt'],
            'pekerjaan'   => $_POST['pekerjaan_edt'],
            'gaji'        => $_POST['gaji_edt'],
            'usaha'       => $_POST['usaha_edt'],
            'omzet'       => $_POST['omzet_edt'],
            'tgl_daftar'  => $_POST['tgl_daftar_edt'],
            'no_hp'       => $_POST['no_hp_edt'],
            'id_bank'     => $id_bank,
            'rek_bank'    => $_POST['rek_bank_edt'],
            'updated_at'  => date('Y-m-d H:i:s'),
            'updated_by'  => $this->session->userdata('user_id')
        ];

        $where = ['no_rekening' => $no_rekening];
        $this->ProfilPemegangSaham->ubah_anggota($where, $data);

        $this->session->set_flashdata('success', 'Sukses! Data berhasil disimpan.');

        $activity_data = [
            'user_id'  => $this->session->userdata('user_id'),
            'activity' => 'Ubah Data Anggota Berhasil',
            'times'    => date('Y-m-d H:i:s')
        ];

        $this->ProfilPemegangSaham->tambah('activity_log', $activity_data);
        redirect(base_url('profil_pemegang_saham'));
    } else {
        $this->session->set_flashdata('error', 'Gagal! Pastikan input anda benar!');
        redirect(base_url('profil_pemegang_saham'));
    }
}
    public function lihat_data_anggota($no_rekening)
    {
        $data['title'] = 'IAA Investment | Profil Pemegang Saham';
        $data['profiles'] = $this->db->get_where('client_account', ['no_rekening' => $no_rekening])->result_array();

        $this->load->view('header', $data);
        $this->load->view('menu');
        $this->load->view('profil_pemegang_saham/read', $data);
        $this->load->view('footer');
    }
    public function hapus_anggota()
    {
        $no_rekening = $this->input->post('rekening');
        $where = array('no_rekening' => $no_rekening);

        $cek = $this->ProfilPemegangSaham->cek('transaksi_investasi', $no_rekening);
        if (!$cek) {
            $this->ProfilPemegangSaham->hapus_anggota($where, 'client_account');
            $this->session->set_flashdata('success', 'Sukses! Data berhasil dihapus.');

            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Hapus Data Anggota Berhasil',
                'times' => date('Y-m-d H:i:s')
            );

            $this->ProfilPemegangSaham->tambah('activity_log', $activity_data);
        } else {
            $this->session->set_flashdata('error', 'Gagal! Data tidak dapat dihapus.');
            $activity_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'activity' => 'Hapus Data Anggota Gagal',
                'times' => date('Y-m-d H:i:s')
            );

            $this->ProfilPemegangSaham->tambah('activity_log', $activity_data);
        }

        redirect(base_url('profil_pemegang_saham'));
    }
}
