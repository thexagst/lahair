<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_birthdate',
        'customer_email',
        'game_id',
        'notes',
        'total_amount',
        'subtotal_amount',
        'discount_type',
        'discount_percent',
        'discount_amount',
        'redirect_url',
        'payment_type',
        'payment_status',
        'status',
        'booking_type',
        'branch_id',
        'worker_id',
        'worker_ids',
        'worker_fee',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Generate unique order number (Sequential: KGA + YYMM + 00001)
     */
    public function generateOrderNumber(): string
    {
        $prefix = 'LHS';
        $dateCode = date('ym'); // e.g. 2601
        $base = $prefix . $dateCode;
        
        // Find last order with this prefix
        $lastOrder = $this->like('order_number', $base, 'after')
                          ->orderBy('id', 'DESC')
                          ->first();
        
        if ($lastOrder) {
            // Extract sequence (last 5 digits)
            $lastSequence = (int) substr($lastOrder['order_number'], -5);
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }
        
        // Format: KGA260100001
        return $base . str_pad($nextSequence, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get orders with items
     */
    public function getOrderWithItems(int $orderId): ?array
    {
        $order = $this->find($orderId);
        
        if (!$order) {
            return null;
        }

        $itemModel = new OrderItemModel();
        $order['items'] = $itemModel->where('order_id', $orderId)->findAll();
        
        // Fetch assigned workers (up to 5)
        $workers = [];
        $orderWorkerModel = new \App\Models\OrderWorkerModel();
        $workers = $orderWorkerModel->getWorkersByOrderId($orderId);

        // Fallback if order_workers table has no entries for this order
        if (empty($workers)) {
            $adminModel = new \App\Models\AdminModel();
            if (!empty($order['worker_ids'])) {
                $ids = array_filter(array_map('intval', explode(',', (string)$order['worker_ids'])));
                if (!empty($ids)) {
                    $workers = $adminModel->whereIn('id', $ids)->findAll();
                    if (!empty($workers)) {
                        $orderWorkerModel->syncOrderWorkers($orderId, $ids);
                    }
                }
            } elseif (!empty($order['worker_id'])) {
                $single = $adminModel->find($order['worker_id']);
                if ($single) {
                    $workers = [$single];
                }
            }
        }

        $order['workers'] = $workers;
        $order['worker_names'] = !empty($workers) ? implode(', ', array_column($workers, 'username')) : null;
        $order['worker_name'] = !empty($workers) ? $workers[0]['username'] : null;

        return $order;
    }

    /**
     * Get order by order number
     */
    public function getOrderByNumber(string $orderNumber): ?array
    {
        return $this->where('order_number', $orderNumber)->first();
    }

    /**
     * Get paginated orders for admin
     */
    public function getPaginatedOrders(int $perPage = 20, $startDate = null, $endDate = null, $branchId = null): array
    {
        $builder = $this->select('orders.*, admins.username as worker_name, branches.name as branch_name')
                        ->join('admins', 'admins.id = orders.worker_id', 'left')
                        ->join('branches', 'branches.id = orders.branch_id', 'left')
                        ->orderBy('orders.created_at', 'DESC');
        
        if ($startDate && $endDate) {
            $builder->where('orders.created_at >=', $startDate . ' 00:00:00');
            $builder->where('orders.created_at <=', $endDate . ' 23:59:59');
        }

        if ($branchId) {
            $builder->where('orders.branch_id', $branchId);
        }
        
        $orders = $builder->paginate($perPage, 'orders');

        if (!empty($orders)) {
            $orderIds = array_column($orders, 'id');
            $orderWorkerModel = new \App\Models\OrderWorkerModel();
            $workersByOrder = $orderWorkerModel->getWorkersForOrders($orderIds);

            foreach ($orders as &$ord) {
                $assigned = $workersByOrder[$ord['id']] ?? [];
                if (empty($assigned) && !empty($ord['worker_name'])) {
                    $assigned = [['id' => $ord['worker_id'] ?? 0, 'username' => $ord['worker_name']]];
                }
                $ord['workers'] = $assigned;
                $ord['worker_names'] = !empty($assigned) ? implode(', ', array_column($assigned, 'username')) : ($ord['worker_name'] ?? null);
            }
        }

        return $orders;
    }

    /**
     * Get summary stats across all matching orders
     */
    public function getOrderStats($startDate = null, $endDate = null, $branchId = null): array
    {
        $builder = $this->db->table($this->table)
                            ->select('
                                COUNT(id) as total,
                                SUM(CASE WHEN status != "cancelled" THEN total_amount ELSE 0 END) as revenue,
                                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as success
                            ');

        if ($startDate && $endDate) {
            $builder->where('created_at >=', $startDate . ' 00:00:00');
            $builder->where('created_at <=', $endDate . ' 23:59:59');
        }

        if ($branchId) {
            $builder->where('branch_id', $branchId);
        }

        $row = $builder->get()->getRowArray();

        return [
            'revenue' => (float)($row['revenue'] ?? 0),
            'total'   => (int)($row['total'] ?? 0),
            'success' => (int)($row['success'] ?? 0),
        ];
    }

    public function getAllOrders($startDate = null, $endDate = null, $branchId = null): array
    {
        $builder = $this->select('orders.*, admins.username as worker_name, branches.name as branch_name')
                        ->join('admins', 'admins.id = orders.worker_id', 'left')
                        ->join('branches', 'branches.id = orders.branch_id', 'left')
                        ->orderBy('orders.created_at', 'DESC');
        
        if ($startDate && $endDate) {
            $builder->where('orders.created_at >=', $startDate . ' 00:00:00');
            $builder->where('orders.created_at <=', $endDate . ' 23:59:59');
        }

        if ($branchId) {
            $builder->where('orders.branch_id', $branchId);
        }
        
        $orders = $builder->findAll();

        if (!empty($orders)) {
            $orderIds = array_column($orders, 'id');
            $orderWorkerModel = new \App\Models\OrderWorkerModel();
            $workersByOrder = $orderWorkerModel->getWorkersForOrders($orderIds);

            foreach ($orders as &$ord) {
                $assigned = $workersByOrder[$ord['id']] ?? [];
                if (empty($assigned) && !empty($ord['worker_name'])) {
                    $assigned = [['id' => $ord['worker_id'] ?? 0, 'username' => $ord['worker_name']]];
                }
                $ord['workers'] = $assigned;
                $ord['worker_names'] = !empty($assigned) ? implode(', ', array_column($assigned, 'username')) : ($ord['worker_name'] ?? null);
            }
        }

        return $orders;
    }


}
