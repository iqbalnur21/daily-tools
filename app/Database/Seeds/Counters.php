<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Counters extends Seeder
{
    public function run()
    {
        $data = [
            [
                'counter_name' => 'Subuh',
                'amount' => 0,
            ],
            [
                'counter_name' => 'Zuhur',
                'amount' => 0,
            ],
            [
                'counter_name' => 'Ashar',
                'amount' => 0,
            ],
            [
                'counter_name' => 'Magrib',
                'amount' => 0,
            ],
            [
                'counter_name' => 'Isya',
                'amount' => 0,
            ],
            [
                'counter_name' => 'Ganti Puasa',
                'amount' => 0,
            ],
            [
                'counter_name' => 'Hutang Galon',
                'amount' => 0,
            ],
        ];
        $this->db->table('counters')->insertBatch($data);
    }
}
