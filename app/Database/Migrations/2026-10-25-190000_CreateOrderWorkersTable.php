<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderWorkersTable extends Migration
{
    public function up()
    {
        // 1. Create order_workers pivot table
        if (!$this->db->tableExists('order_workers')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'order_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'worker_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addKey('order_id');
            $this->forge->addKey('worker_id');
            $this->forge->addUniqueKey(['order_id', 'worker_id']);
            $this->forge->createTable('order_workers');
        }

        // 2. Add worker_ids column to orders if not exists (comma-separated IDs for fast lookup)
        if (!$this->db->fieldExists('worker_ids', 'orders')) {
            $this->forge->addColumn('orders', [
                'worker_ids' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                    'after'      => 'worker_id',
                ],
            ]);
        }

        // 3. Migrate existing worker_id data into order_workers table and worker_ids column
        if ($this->db->tableExists('order_workers') && $this->db->fieldExists('worker_id', 'orders')) {
            $existingOrders = $this->db->table('orders')
                ->select('id, worker_id')
                ->where('worker_id IS NOT NULL')
                ->where('worker_id >', 0)
                ->get()
                ->getResultArray();

            foreach ($existingOrders as $ord) {
                $exists = $this->db->table('order_workers')
                    ->where('order_id', $ord['id'])
                    ->where('worker_id', $ord['worker_id'])
                    ->countAllResults();

                if ($exists === 0) {
                    $this->db->table('order_workers')->insert([
                        'order_id'   => $ord['id'],
                        'worker_id'  => $ord['worker_id'],
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                $this->db->table('orders')
                    ->where('id', $ord['id'])
                    ->update(['worker_ids' => (string)$ord['worker_id']]);
            }
        }
    }

    public function down()
    {
        if ($this->db->tableExists('order_workers')) {
            $this->forge->dropTable('order_workers');
        }
        if ($this->db->fieldExists('worker_ids', 'orders')) {
            $this->forge->dropColumn('orders', 'worker_ids');
        }
    }
}
