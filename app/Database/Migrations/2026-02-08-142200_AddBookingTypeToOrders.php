<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBookingTypeToOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('orders', [
            'booking_type' => [
                'type'       => 'ENUM',
                'constraint' => ['online', 'offline'],
                'default'    => 'online',
                'after'      => 'status',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', 'booking_type');
    }
}
