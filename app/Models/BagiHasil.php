<?php

namespace App\Models;

use CodeIgniter\Model;

class BagiHasil extends Model
{
    public function data($tahun)
    {
        return $this->db->table('v_bagi_hasil')->getWhere(['tahun' => $tahun]);
    }
}

