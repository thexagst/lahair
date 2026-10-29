<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductItemMaterialModel extends Model
{
    protected $table            = 'product_item_materials';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_item_id',
        'inventory_id',
        'quantity',
    ];

    /**
     * Get materials for a specific product item with details
     */
    public function getMaterialsByItem($itemId)
    {
        return $this->select('product_item_materials.*, inventories.name, inventories.unit')
                    ->join('inventories', 'inventories.id = product_item_materials.inventory_id')
                    ->where('product_item_id', $itemId)
                    ->findAll();
    }
}
