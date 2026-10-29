<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SetupInventorySystem extends Migration
{
    public function up()
    {
        // 1. Create Inventories Table
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'stock'       => ['type' => 'INT', 'constraint' => 11, 'default' => 0], // Satuan terkecil
            'unit'        => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'pcs'], // pcs, ml, gr
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('inventories');

        // 2. Create Product Item Materials Table (Pivot)
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'product_item_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'inventory_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'quantity'        => ['type' => 'INT', 'constraint' => 11, 'default' => 1], // Jumlah bahan yang dipakai
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('product_item_id', 'product_items', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('inventory_id', 'inventories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('product_item_materials');

        // 3. Remove stock from product_items
        if ($this->db->fieldExists('stock', 'product_items')) {
            $this->forge->dropColumn('product_items', 'stock');
        }
    }

    public function down()
    {
        // Restore stock column
        $this->forge->addColumn('product_items', [
            'stock' => ['type' => 'INT', 'constraint' => 11, 'default' => 0]
        ]);
        
        $this->forge->dropTable('product_item_materials');
        $this->forge->dropTable('inventories');
    }
}
