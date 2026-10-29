<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateAdminSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Update existing admin
        $db->table('admins')
           ->where('username', 'admin')
           ->update([
               'email' => 'admin@lahairstudio.com',
               'role'  => 'admin'
           ]);
        
        echo "Admin user updated successfully!\n";
        echo "Username: admin\n";
        echo "Password: admin123\n";
        echo "Email: admin@lahairstudio.com\n";
        echo "Role: admin\n";
    }
}
