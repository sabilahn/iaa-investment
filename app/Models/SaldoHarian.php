<?php

namespace App\Models;

use CodeIgniter\Model;

class SaldoHarian extends Model
{
    public function saldo_harian_list($tahun)
    {
        return $this->db->table('v_saldo_harian')
            ->where('tahun', $tahun)
            ->get();
    }
}

