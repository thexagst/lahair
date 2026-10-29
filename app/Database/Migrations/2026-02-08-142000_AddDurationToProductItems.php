<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDurationToProductItems extends Migration
{
    public function up()
    {
        $this->forge->addColumn('product_items', [
            'duration_minutes' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 60,
                'after'      => 'price',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('product_items', 'duration_minutes');
    }
}
