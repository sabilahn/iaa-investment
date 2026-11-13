<?php

namespace App\Models;

use CodeIgniter\Model;

class DistribusiDanPorsi extends Model
{
    public function show($table, $tahun)
    {
        return $this->db->table($table)->getWhere(['tahun' => $tahun]);
    }
}

