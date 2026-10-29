<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMultiBranchInventorySystem extends Migration
{
    public function up()
    {
        // 1. Create Branches Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('branches', true);

        // 2. Insert Default Branches (Semarang & Indramayu)
        $now = date('Y-m-d H:i:s');
        $this->db->table('branches')->insertBatch([
            [
                'name'       => 'Semarang',
                'code'       => 'semarang',
                'address'    => 'Cabang Semarang',
                'phone'      => null,
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Indramayu',
                'code'       => 'indramayu',
                'address'    => 'Cabang Indramayu',
                'phone'      => null,
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 3. Create Inventory Stocks Table (Pivot per Branch)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'inventory_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'branch_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'stock' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['inventory_id', 'branch_id']);
        $this->forge->addForeignKey('inventory_id', 'inventories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('branch_id', 'branches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('inventory_stocks', true);

        // 4. Seed Existing Inventory items into inventory_stocks
        $branches = $this->db->table('branches')->get()->getResultArray();
        $semarangBranchId = null;
        $indramayuBranchId = null;

        foreach ($branches as $b) {
            if ($b['code'] === 'semarang') {
                $semarangBranchId = $b['id'];
            } elseif ($b['code'] === 'indramayu') {
                $indramayuBranchId = $b['id'];
            }
        }

        $inventories = $this->db->table('inventories')->get()->getResultArray();
        if (!empty($inventories)) {
            $stockInserts = [];
            foreach ($inventories as $inv) {
                if ($semarangBranchId) {
                    $stockInserts[] = [
                        'inventory_id' => $inv['id'],
                        'branch_id'    => $semarangBranchId,
                        'stock'        => (float)($inv['stock'] ?? 0),
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ];
                }
                if ($indramayuBranchId) {
                    $stockInserts[] = [
                        'inventory_id' => $inv['id'],
                        'branch_id'    => $indramayuBranchId,
                        'stock'        => 0.00,
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ];
                }
            }
            if (!empty($stockInserts)) {
                $this->db->table('inventory_stocks')->insertBatch($stockInserts);
            }
        }

        // 5. Add branch_id to orders table
        if (!$this->db->fieldExists('branch_id', 'orders')) {
            $this->forge->addColumn('orders', [
                'branch_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'total_amount',
                ],
            ]);
            $this->db->query('ALTER TABLE `orders` ADD CONSTRAINT `fk_orders_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
            
            // Assign existing orders to Semarang as default
            if ($semarangBranchId) {
                $this->db->table('orders')->where('branch_id IS NULL')->update(['branch_id' => $semarangBranchId]);
            }
        }

        // 6. Add branch_id to admins table
        if (!$this->db->fieldExists('branch_id', 'admins')) {
            $this->forge->addColumn('admins', [
                'branch_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'role',
                ],
            ]);
            $this->db->query('ALTER TABLE `admins` ADD CONSTRAINT `fk_admins_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
        }

        // 7. Add branch_id to order_stock_usages table
        if (!$this->db->fieldExists('branch_id', 'order_stock_usages')) {
            $this->forge->addColumn('order_stock_usages', [
                'branch_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'inventory_id',
                ],
            ]);
            $this->db->query('ALTER TABLE `order_stock_usages` ADD CONSTRAINT `fk_order_stock_usages_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('branch_id', 'order_stock_usages')) {
            $this->db->query('ALTER TABLE `order_stock_usages` DROP FOREIGN KEY `fk_order_stock_usages_branch`');
            $this->forge->dropColumn('order_stock_usages', 'branch_id');
        }

        if ($this->db->fieldExists('branch_id', 'admins')) {
            $this->db->query('ALTER TABLE `admins` DROP FOREIGN KEY `fk_admins_branch`');
            $this->forge->dropColumn('admins', 'branch_id');
        }

        if ($this->db->fieldExists('branch_id', 'orders')) {
            $this->db->query('ALTER TABLE `orders` DROP FOREIGN KEY `fk_orders_branch`');
            $this->forge->dropColumn('orders', 'branch_id');
        }

        $this->forge->dropTable('inventory_stocks', true);
        $this->forge->dropTable('branches', true);
    }
}
