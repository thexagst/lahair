<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsRequiredToProductItemAddons extends Migration
{
    public function up()
    {
        $this->forge->addColumn('product_item_addons', [
            'is_required' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0, // 0 = Optional, 1 = Required
                'after'      => 'price'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('product_item_addons', 'is_required');
    }
}
