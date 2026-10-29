<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoleAndWorkerId extends Migration
{
    public function up()
    {
        // Add worker_id to orders table
        $this->forge->addColumn('orders', [
            'worker_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', 'worker_id');
    }
}
