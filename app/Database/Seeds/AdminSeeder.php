<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\AdminModel;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $adminModel = new AdminModel();

        $data = [
            'username' => 'admin',
            'email'    => 'admin@lahairstudio.com',
            'password' => '111111', // Will be hashed by AdminModel
            'role'     => 'admin',
        ];

        $existing = $adminModel->where('username', 'admin')->first();

        if (!$existing) {
            $adminModel->insert($data);
            echo "Admin user created successfully!\n";
        } else {
            // Update including password so it gets re-hashed
            $adminModel->update($existing['id'], $data);
            echo "Admin user updated successfully!\n";
        }

        echo "Username : admin\n";
        echo "PIN      : 111111\n";
        echo "Email    : admin@lahairstudio.com\n";
        echo "Role     : admin\n";
    }
}
