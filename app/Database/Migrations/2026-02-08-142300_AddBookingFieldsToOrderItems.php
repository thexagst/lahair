<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBookingFieldsToOrderItems extends Migration
{
    public function up()
    {
        $this->forge->addColumn('order_items', [
            'booking_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'subtotal',
            ],
            'booking_time' => [
                'type' => 'TIME',
                'null' => true,
                'after' => 'booking_date',
            ],
            'addon_items' => [
                'type' => 'JSON',
                'null' => true,
                'after' => 'booking_time',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('order_items', ['booking_date', 'booking_time', 'addon_items']);
    }
}   