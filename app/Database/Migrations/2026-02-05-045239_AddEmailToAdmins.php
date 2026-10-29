<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailToAdmins extends Migration
{
    public function up()
    {
        // Check if email column exists before adding
        $fields = $this->db->getFieldNames('admins');
        
        if (!in_array('email', $fields)) {
            $this->forge->addColumn('admins', [
                'email' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                    'after' => 'username'
                ],
            ]);
        }
        
        // Check if otp column exists before adding
        if (!in_array('otp', $fields)) {
            $this->forge->addColumn('admins', [
                'otp' => [
                    'type' => 'VARCHAR',
                    'constraint' => 6,
                    'null' => true,
                ],
            ]);
        }
        
        // Check if otp_expires_at column exists before adding
        if (!in_array('otp_expires_at', $fields)) {
            $this->forge->addColumn('admins', [
                'otp_expires_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
        }
        
        // Check if role column exists before adding
        if (!in_array('role', $fields)) {
            $this->forge->addColumn('admins', [
                'role' => [
                    'type'       => 'ENUM',
                    'constraint' => ['admin', 'worker'],
                    'default'    => 'admin',
                    'after'      => 'email',
                ],
            ]);
        }
    }

    public function down()
    {
        // Only drop if exists
        $fields = $this->db->getFieldNames('admins');
        
        $columnsToDrop = [];
        if (in_array('email', $fields)) $columnsToDrop[] = 'email';
        if (in_array('otp', $fields)) $columnsToDrop[] = 'otp';
        if (in_array('otp_expires_at', $fields)) $columnsToDrop[] = 'otp_expires_at';
        if (in_array('role', $fields)) $columnsToDrop[] = 'role';
        
        if (!empty($columnsToDrop)) {
            $this->forge->dropColumn('admins', $columnsToDrop);
        }
    }
}
