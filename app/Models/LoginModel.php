<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    public function logged_id()
    {
        return session()->get('user_id');
    }

    public function cek_login($table, $username, $userpass)
    {
        $query = $this->db->table($table)
            ->select('*')
            ->where($username)
            ->where($userpass)
            ->limit(1)
            ->get();

        if ($query->getNumRows() === 0) {
            return false;
        }

        return $query->getResult();
    }

    public function tambah($table, $data)
    {
        return (bool) $this->db->table($table)->insert($data);
    }

    public function ubah_password($where, $data)
    {
        return (bool) $this->db->table('user_account')->where($where)->update($data);
    }

    public function user_list()
    {
        return $this->db->table('user_account')->get()->getResultArray();
    }

    public function user_adm()
    {
        return $this->db->table('user_account')
            ->where('role !=', 'user')
            ->get()
            ->getResultArray();
    }

    public function activity_list()
    {
        return $this->db->table('v_activity')->get()->getResultArray();
    }

    public function hapus_user($id)
    {
        return $this->db->table('user_account')
            ->where('user_id', $id)
            ->delete();
    }
}

