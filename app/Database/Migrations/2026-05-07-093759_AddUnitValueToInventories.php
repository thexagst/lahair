<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUnitValueToInventories extends Migration
{
    public function up()
    {
        // Modifikasi unit_value agar default 0.00
        $this->forge->modifyColumn('inventories', [
            'unit_value' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
        ]);

        // Tambah sub_unit
        $this->forge->addColumn('inventories', [
            'sub_unit' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'ml',
                'after'      => 'unit_value',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('inventories', 'sub_unit');
    }
}
