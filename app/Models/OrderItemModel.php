<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table            = 'order_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id',
        'product_name',
        'item_name',
        'price',
        'quantity',
        'subtotal',
        'booking_date',
        'booking_time',
        'addon_items',
    ];

    protected $useTimestamps = false;
}
