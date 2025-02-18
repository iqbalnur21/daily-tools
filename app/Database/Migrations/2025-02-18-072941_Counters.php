<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Counters extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'counter_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'counter_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'amount' => [
                'type'       => 'INT',
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('counter_id', true);
        $this->forge->createTable('counters');
    }

    public function down()
    {
        $this->forge->dropTable('counters');
    }
}
