<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductItemModel extends Model
{
    protected $table            = 'product_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_id',
        'name',
        'image',
        'price',
        'duration_minutes',
        'sort_order',
        'is_active',
        'sold',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'product_id' => 'required|integer',
        'name'       => 'required|min_length[2]|max_length[255]',
        'price'      => 'required|numeric',
    ];

    /**
     * Get items by product ID
     */
    public function getByProduct(int $productId): array
    {
        return $this->where('product_id', $productId)
                    ->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }

    /**
     * Get all items for admin
     */
    public function getByProductAdmin(int $productId): array
    {
        return $this->where('product_id', $productId)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }
}
