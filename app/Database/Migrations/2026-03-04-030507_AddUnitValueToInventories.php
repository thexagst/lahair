<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUnitValueToInventories extends Migration
{
    public function up()
    {
        // unit_value: isi per satuan kemasan (misal 1 botol = 300 ml)
        $this->forge->addColumn('inventories', [
            'unit_value' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'default'    => null,
                'after'      => 'unit',
                'comment'    => '1 pcs/botol berisi berapa unit terkecil (ml/gr)',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('inventories', 'unit_value');
    }
}

