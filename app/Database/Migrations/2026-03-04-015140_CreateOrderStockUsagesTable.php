<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderStockUsagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'order_number' => ['type' => 'VARCHAR', 'constraint' => 100],
            'inventory_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'quantity'     => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 1],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('inventory_id', 'inventories', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('order_stock_usages');
    }

    public function down()
    {
        $this->forge->dropTable('order_stock_usages');
    }
}


