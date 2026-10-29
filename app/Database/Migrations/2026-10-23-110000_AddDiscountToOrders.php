<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDiscountToOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('orders', [
            'subtotal_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => '0.00',
                'after'      => 'total_amount'
            ],
            'discount_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'none',
                'after'      => 'subtotal_amount'
            ],
            'discount_percent' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => '0.00',
                'after'      => 'discount_type'
            ],
            'discount_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => '0.00',
                'after'      => 'discount_percent'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', ['subtotal_amount', 'discount_type', 'discount_percent', 'discount_amount']);
    }
}
