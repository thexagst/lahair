<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAddonMaterials extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'addon_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'inventory_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'quantity'        => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('addon_id', 'product_item_addons', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('inventory_id', 'inventories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('addon_materials');
    }

    public function down()
    {
        $this->forge->dropTable('addon_materials');
    }
}
