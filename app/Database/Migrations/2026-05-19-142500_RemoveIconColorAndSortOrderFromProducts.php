<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveIconColorAndSortOrderFromProducts extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('products', ['icon_color', 'sort_order']);
    }

    public function down()
    {
        $this->forge->addColumn('products', [
            'icon_color' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'linear-gradient(135deg, #ff6b2c, #e55a1f)',
                'after'      => 'description',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'link',
            ],
        ]);
    }
}
