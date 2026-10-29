<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSoldToProductsAndItems extends Migration
{
    public function up()
    {
        $fields = [
            'sold' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'is_active'
            ],
        ];
        $this->forge->addColumn('products', $fields);
        $this->forge->addColumn('product_items', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'sold');
        $this->forge->dropColumn('product_items', 'sold');
    }
}
