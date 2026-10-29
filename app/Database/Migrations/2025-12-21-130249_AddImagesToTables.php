<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImagesToTables extends Migration
{
    public function up()
    {
        // Add image column to products table
        $this->forge->addColumn('products', [
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
                'after'      => 'icon_color',
            ],
        ]);

        // Add image column to product_items table
        $this->forge->addColumn('product_items', [
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
                'after'      => 'name',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'image');
        $this->forge->dropColumn('product_items', 'image');
    }
}
