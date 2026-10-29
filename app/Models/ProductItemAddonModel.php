<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductItemAddonModel extends Model
{
    protected $table            = 'product_item_addons';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_item_id',
        'name',
        'price',
        'is_required',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get all active add-ons for a product item
     */
    public function getByProductItem(int $productItemId): array
    {
        return $this->where('product_item_id', $productItemId)
                    ->where('is_active', 1)
                    ->findAll();
    }
}
