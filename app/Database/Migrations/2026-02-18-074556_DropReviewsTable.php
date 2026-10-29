<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropReviewsTable extends Migration
{
    public function up()
    {
        $this->forge->dropTable('reviews', true);
    }

    public function down()
    {
        // Recreate reviews table if rolling back
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'customer_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'rating' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 5,
            ],
            'review_text' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_approved' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('reviews');
    }
}
