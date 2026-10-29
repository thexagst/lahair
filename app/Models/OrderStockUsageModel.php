<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderStockUsageModel extends Model
{
    protected $table            = 'order_stock_usages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_number',
        'inventory_id',
        'quantity',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // no updated_at column
}

