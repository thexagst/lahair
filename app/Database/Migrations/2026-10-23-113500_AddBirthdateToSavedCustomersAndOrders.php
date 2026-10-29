<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBirthdateToSavedCustomersAndOrders extends Migration
{
    public function up()
    {
        // Add birthdate to saved_customers table
        if ($this->db->tableExists('saved_customers')) {
            if (!$this->db->fieldExists('birthdate', 'saved_customers')) {
                $this->forge->addColumn('saved_customers', [
                    'birthdate' => [
                        'type'       => 'DATE',
                        'null'       => true,
                        'default'    => null,
                        'after'      => 'phone',
                    ],
                ]);
            }
        }

        // Add customer_birthdate to orders table
        if ($this->db->tableExists('orders')) {
            if (!$this->db->fieldExists('customer_birthdate', 'orders')) {
                $this->forge->addColumn('orders', [
                    'customer_birthdate' => [
                        'type'       => 'DATE',
                        'null'       => true,
                        'default'    => null,
                        'after'      => 'customer_phone',
                    ],
                ]);
            }
        }
    }

    public function down()
    {
        if ($this->db->tableExists('saved_customers') && $this->db->fieldExists('birthdate', 'saved_customers')) {
            $this->forge->dropColumn('saved_customers', 'birthdate');
        }

        if ($this->db->tableExists('orders') && $this->db->fieldExists('customer_birthdate', 'orders')) {
            $this->forge->dropColumn('orders', 'customer_birthdate');
        }
    }
}
