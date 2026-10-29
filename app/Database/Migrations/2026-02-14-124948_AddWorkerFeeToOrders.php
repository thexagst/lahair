<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWorkerFeeToOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('orders', [
            'worker_fee' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => '0.00',
                'after'      => 'worker_id'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', 'worker_fee');
    }
}
