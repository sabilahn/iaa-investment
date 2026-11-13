<?php

namespace App\Controllers;

use App\Models\LoginModel;

class Users extends BaseController
{
    protected LoginModel $loginModel;

    public function __construct()
    {
        $this->loginModel = new LoginModel();
    }

    public function index()
    {
        return redirect()->to(base_url('users/list'));
    }

    public function list()
    {
        $data['title']     = 'IAA Investment | Setting';
        $data['user_list'] = $this->loginModel->user_list();

        foreach ($data['user_list'] as $i => $user) {
            $data['user_list'][$i]['action'] = '<span data-toggle="modal" data-target="#resetModal' . $user['user_id'] . ' "><button class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Kembalikan password" ><i class="fas fa-sync"></i></button></span>
            <button class="btn btn-danger" data-toggle="modal" data-target="#hapusModal' . $user['user_id'] . ' "><i class="fa fa-trash"></i></button>';
        }

        return view('header', $data)
            . view('menu')
            . view('users/list', $data)
            . view('footer');
    }

    public function tambah()
    {
        $this->db->transBegin();
        try {
            $data = [
                'user_id'  => $this->request->getPost('id_user'),
                'username' => $this->request->getPost('username'),
                'userpass' => md5((string) $this->request->getPost('userpass')),
                'role'     => 'user',
            ];

            $userAdd = $this->loginModel->tambah('user_account', $data);
            if (! $userAdd) {
                throw new \Exception('Failed');
            }

            $this->db->transCommit();
            $this->session->setFlashdata('success', 'Sukses! Data berhasil disimpan.');

            return redirect()->back();
        } catch (\Throwable $th) {
            $this->db->transRollback();

            return redirect()->back();
        }
    }

    public function hapus()
    {
        $userId = $this->request->getPost('id_hapus');
        $this->loginModel->hapus_user($userId);

        $this->session->setFlashdata('success', 'Sukses! Data berhasil dihapus.');

        return redirect()->back();
    }

    public function reset()
    {
        $this->db->transBegin();
        try {
            $data = [
                'userpass' => md5('123'),
            ];

            $idUser  = $this->request->getPost('id_reset');
            $userAdd = $this->loginModel->ubah_password(['user_id' => $idUser], $data);
            if (! $userAdd) {
                throw new \Exception('Failed');
            }

            $this->db->transCommit();
            $this->session->setFlashdata('success', 'Sukses! Password berhasil dikembalikan menjadi 12345');

            return redirect()->back();
        } catch (\Throwable $th) {
            $this->db->transRollback();

            return redirect()->back();
        }
    }

    public function activity()
    {
        $data['title']      = 'IAA Investment | Setting';
        $data['activities'] = $this->loginModel->activity_list();

        return view('header', $data)
            . view('menu')
            . view('users/activity', $data)
            . view('footer');
    }
}

