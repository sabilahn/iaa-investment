<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratSaham extends Model
{
    public function getData($where)
    {
        return $this->db->table('v_transaksi')->getWhere($where);
    }
}

