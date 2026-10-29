<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMidtransToOrders extends Migration
{
    public function up()
    {
        $fields = [
            'redirect_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'total_amount'
            ],
            'payment_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'redirect_url'
            ],
            'payment_status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'after'      => 'payment_type'
            ]
        ];
        $this->forge->addColumn('orders', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', ['redirect_url', 'payment_type', 'payment_status']);
    }
}
