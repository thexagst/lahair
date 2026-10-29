<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\AdminModel;

class ResetAdminPassword extends Seeder
{
    public function run()
    {
        $adminModel = new AdminModel();

        $admin = $adminModel->where('username', 'admin')->first();

        if (!$admin) {
            echo "Admin user not found.\n";
            return;
        }

        // Use model so the beforeUpdate callback hashes the PIN
        $adminModel->update($admin['id'], ['password' => '111111']);

        echo "Admin PIN reset successfully!\n";
        echo "Username : admin\n";
        echo "PIN      : 111111\n";
    }
}
