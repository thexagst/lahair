<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveLinkFromProducts extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('products', 'link');
    }

    public function down()
    {
        $this->forge->addColumn('products', [
            'link' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
                'after'      => 'category',
            ],
        ]);
    }
}
