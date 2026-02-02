<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PeriodSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama agar bersih saat seeder dijalankan
        $this->db->table('period_interval')->truncate();

        $now = Time::now();

        $data = [
            // Data 4: 5 Jan - 8 Jan (Terbaru)
            [
                'start_date' => '2026-01-05',
                'end_date'   => '2026-01-08',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Data 3: 5 Dec - 9 Dec
            [
                'start_date' => '2025-12-05',
                'end_date'   => '2025-12-09',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Data 2: 8 Nov - 12 Nov
            [
                'start_date' => '2025-11-08',
                'end_date'   => '2025-11-12',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Data 1: 30 Sep - 6 Oct (Terlama)
            [
                'start_date' => '2025-09-30',
                'end_date'   => '2025-10-06',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Insert Batch
        $this->db->table('period_interval')->insertBatch($data);
    }
}