<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePeriodIntervals extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'start_date' => [ // Saya ubah jadi start_date agar lebih jelas
                'type'       => 'DATE',
            ],
            'end_date' => [
                'type'       => 'DATE',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('period_interval');
    }

    public function down()
    {
        $this->forge->dropTable('period_interval');
    }
}