<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderWorkerModel extends Model
{
    protected $table = 'order_workers';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'order_id',
        'worker_id',
        'created_at',
    ];
    protected $useTimestamps = false;

    /**
     * Auto-ensure order_workers table and orders.worker_ids exist
     */
    public function ensureTableExists(): void
    {
        try {
            if (!$this->db->tableExists($this->table)) {
                $forge = \Config\Database::forge();
                $forge->addField([
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
                $forge->addKey('id', true);
                $forge->addKey('order_id');
                $forge->addKey('worker_id');
                $forge->addUniqueKey(['order_id', 'worker_id']);
                $forge->createTable($this->table, true);
            }

            if (!$this->db->fieldExists('worker_ids', 'orders')) {
                $forge = \Config\Database::forge();
                $forge->addColumn('orders', [
                    'worker_ids' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 255,
                        'null'       => true,
                        'after'      => 'worker_id',
                    ],
                ]);
            }
        } catch (\Throwable $e) {
            log_message('error', 'ensureTableExists error: ' . $e->getMessage());
        }
    }

    /**
     * Get workers assigned to an order
     */
    public function getWorkersByOrderId(int $orderId): array
    {
        $this->ensureTableExists();

        if (!$this->db->tableExists($this->table)) {
            return [];
        }

        return $this->select('order_workers.*, order_workers.worker_id as id, admins.username, admins.role')
                    ->join('admins', 'admins.id = order_workers.worker_id', 'left')
                    ->where('order_workers.order_id', $orderId)
                    ->orderBy('order_workers.id', 'ASC')
                    ->findAll();
    }

    /**
     * Batch get workers for multiple orders (avoids N+1 query)
     * Returns: [order_id => [worker1, worker2, ...]]
     */
    public function getWorkersForOrders(array $orderIds): array
    {
        if (empty($orderIds)) {
            return [];
        }

        $this->ensureTableExists();

        if (!$this->db->tableExists($this->table)) {
            return [];
        }

        $rows = $this->select('order_workers.*, order_workers.worker_id as id, admins.username, admins.role')
                     ->join('admins', 'admins.id = order_workers.worker_id', 'left')
                     ->whereIn('order_workers.order_id', $orderIds)
                     ->orderBy('order_workers.id', 'ASC')
                     ->findAll();

        $result = [];
        foreach ($rows as $row) {
            $orderId = $row['order_id'];
            if (!isset($result[$orderId])) {
                $result[$orderId] = [];
            }
            $result[$orderId][] = $row;
        }

        return $result;
    }

    /**
     * Sync assigned workers for an order (max 5)
     */
    public function syncOrderWorkers(int $orderId, array $workerIds): array
    {
        $this->ensureTableExists();

        if (!$this->db->tableExists($this->table)) {
            return [];
        }

        // Clean & unique IDs, limit to max 5
        $cleanIds = [];
        foreach ($workerIds as $wid) {
            $intId = (int)$wid;
            if ($intId > 0 && !in_array($intId, $cleanIds)) {
                $cleanIds[] = $intId;
            }
        }
        $cleanIds = array_slice($cleanIds, 0, 5);

        // Delete previous assignments for this order
        $this->where('order_id', $orderId)->delete();

        // Insert new ones
        $inserted = [];
        foreach ($cleanIds as $wid) {
            $this->insert([
                'order_id'   => $orderId,
                'worker_id'  => $wid,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $inserted[] = $wid;
        }

        return $inserted;
    }
}
