<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStockToProductItems extends Migration
{
    public function up()
    {
        // Add stock column to product_items table
        $this->forge->addColumn('product_items', [
            'stock' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'sold',
            ],
        ]);
    }

    public function down()
    {
        // Remove column from product_items
        $this->forge->dropColumn('product_items', 'stock');
    }
}
