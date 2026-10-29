<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeInvestoryStockToDecimal extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('inventories', [
            'stock' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
        ]);
    }
cob
    public function down()
    {
        $this->forge->modifyColumn('inventories', [
            'stock' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
        ]);
    }
}