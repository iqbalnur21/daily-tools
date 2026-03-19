<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Counters extends Seeder
{
    public function run()
    {
        $this->db->transStart();

        $data = [
            [
                'counter_id' => 1,
                'counter_name' => 'Subuh',
                'amount' => 0,
                'last_calculation' => null,
                'created_at' => '2025-02-18 15:13:15',
                'updated_at' => '2026-03-19 15:21:59',
                'deleted_at' => null,
            ],
            [
                'counter_id' => 2,
                'counter_name' => 'Zuhur',
                'amount' => 0,
                'last_calculation' => null,
                'created_at' => '2025-02-18 15:13:15',
                'updated_at' => '2026-03-19 15:21:59',
                'deleted_at' => null,
            ],
            [
                'counter_id' => 3,
                'counter_name' => 'Ashar',
                'amount' => 0,
                'last_calculation' => null,
                'created_at' => '2025-02-18 15:13:15',
                'updated_at' => '2026-03-19 15:47:06',
                'deleted_at' => null,
            ],
            [
                'counter_id' => 4,
                'counter_name' => 'Magrib',
                'amount' => 1,
                'last_calculation' => null,
                'created_at' => '2025-02-18 15:13:15',
                'updated_at' => '2026-03-19 00:46:05',
                'deleted_at' => null,
            ],
            [
                'counter_id' => 5,
                'counter_name' => 'Isya',
                'amount' => 0,
                'last_calculation' => null,
                'created_at' => '2025-02-18 15:13:15',
                'updated_at' => '2026-02-10 21:37:10',
                'deleted_at' => null,
            ],
            [
                'counter_id' => 6,
                'counter_name' => 'Ganti Puasa',
                'amount' => 5,
                'last_calculation' => null,
                'created_at' => '2025-02-18 15:13:15',
                'updated_at' => '2026-03-18 08:45:04',
                'deleted_at' => null,
            ],
            [
                'counter_id' => 7,
                'counter_name' => 'Ganti Puasa Nia',
                'amount' => 57,
                'last_calculation' => null,
                'created_at' => '2025-02-18 15:13:15',
                'updated_at' => '2026-03-19 16:37:01',
                'deleted_at' => null,
            ],
            [
                'counter_id' => 8,
                'counter_name' => 'Saldo Nia',
                'amount' => 1800000,
                'last_calculation' => null,
                'created_at' => '2026-02-19 07:18:39',
                'updated_at' => '2026-03-19 17:55:13',
                'deleted_at' => null,
            ],
            [
                'counter_id' => 9,
                'counter_name' => 'Hutang Galon',
                'amount' => 4,
                'last_calculation' => null,
                'created_at' => '2025-02-18 15:13:15',
                'updated_at' => '2026-01-05 09:05:29',
                'deleted_at' => null,
            ],
            [
                'counter_id' => 10,
                'counter_name' => 'Saldo Tira',
                'amount' => 640000,
                'last_calculation' => null,
                'created_at' => '2026-02-19 07:18:39',
                'updated_at' => '2026-03-19 16:37:14',
                'deleted_at' => null,
            ],
        ];

        $this->db->table('counters')->insertBatch($data);

        $this->db->transComplete();
    }
}
