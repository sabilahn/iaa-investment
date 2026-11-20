<?php

namespace App\Controllers;

use App\Models\LoginModel;

class Auth extends BaseController
{
    protected LoginModel $loginModel;

    public function __construct()
    {
        $this->loginModel = new LoginModel();
    }

    public function index()
    {
        if ($this->loginModel->logged_id()) {
            return redirect()->to(base_url('dashboard'));
        }

        $data['title']     = 'IAA Investment | Login Page';
        $data['user_list'] = $this->loginModel->user_adm();

        return view('users/login', $data);
    }

    public function login()
    {
        $rules = [
            'username' => 'required',
            'userpass' => 'required',
        ];

        if ($this->validate($rules)) {
            $username = (string) $this->request->getPost('username');
            $userpass = md5((string) $this->request->getPost('userpass'));

            $cek = $this->loginModel->cek_login(
                'user_account',
                ['username' => $username],
                ['userpass' => $userpass]
            );

            if ($cek !== false) {
                foreach ($cek as $auth) {
                    $sessionData = [
                        'user_id'  => $auth->user_id,
                        'username' => $auth->username,
                        'userpass' => $auth->userpass,
                        'role'     => $auth->role,
                        'status'   => 'login',
                    ];

                    $activityData = [
                        'user_id'  => $auth->user_id,
                        'activity' => 'Login Berhasil',
                        'times'    => date('Y-m-d H:i:s'),
                    ];

                    $this->loginModel->tambah('activity_log', $activityData);
                    $this->session->set($sessionData);

                    return redirect()->to(base_url('dashboard'));
                }
            }

            $this->session->setFlashdata(
                'gagal',
                '<div class=text-center><i class="fa fa-exclamation-circle"></i> Username atau password salah</div>'
            );

            return redirect()->to(base_url('auth'));
        }

        return redirect()->to(base_url('auth'));
    }

    public function ubah_password()
    {
        if ($this->session->get('status') !== 'login') {
            return redirect()->to(base_url('auth'));
        }

        return view('users/ubah_pass');
    }

    public function update_password()
    {
        $this->db->transBegin();
        try {
            $sessionId   = $this->session->get('user_id');
            $sessionPass = $this->session->get('userpass');

            $rules = [
                'o_userpass' => 'required|trim',
                'n_userpass' => 'required|trim|matches[c_userpass]',
                'c_userpass' => 'required|trim|matches[n_userpass]',
            ];

            $oUserpass = md5((string) $this->request->getPost('o_userpass'));
            $nUserpass = (string) $this->request->getPost('n_userpass');

            if ($this->validate($rules)) {
                if ($oUserpass === $sessionPass) {
                    $userpass = ['userpass' => md5($nUserpass)];
                    $where    = ['user_id' => $sessionId];
                    $this->loginModel->ubah_password($where, $userpass);

                    $activityData = [
                        'user_id'  => $sessionId,
                        'activity' => 'Ubah Password Berhasil',
                        'times'    => date('Y-m-d H:i:s'),
                    ];

                    $this->loginModel->tambah('activity_log', $activityData);
                    $this->db->transCommit();

                    return redirect()->to(base_url('auth/logout'));
                }

                $this->db->transRollback();
                $this->session->setFlashdata('error1', 'Password lama salah!');

                return view('users/ubah_pass');
            }

            $this->db->transRollback();
            $this->session->setFlashdata('error', 'Terjadi kesalahan! Pastikan input anda benar!');

            return view('users/ubah_pass');
        } catch (\Throwable $th) {
            $this->db->transRollback();

            return redirect()->to(base_url('dashboard'));
        }
    }

    public function logout()
    {
        if ($this->session->get('user_id')) {
            $activityData = [
                'user_id'  => $this->session->get('user_id'),
                'activity' => 'Logout',
                'times'    => date('Y-m-d H:i:s'),
            ];

            $this->loginModel->tambah('activity_log', $activityData);
        }

        $this->session->destroy();

        return redirect()->to(base_url('auth'));
    }
}

