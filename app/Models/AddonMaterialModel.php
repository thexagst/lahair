<?php

namespace App\Models;

use CodeIgniter\Model;

class AddonMaterialModel extends Model
{
    protected $table            = 'addon_materials';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'addon_id',
        'inventory_id',
        'quantity',
    ];

    /**
     * Get materials for a specific addon with inventory details
     */
    public function getMaterialsByAddon($addonId)
    {
        return $this->select('addon_materials.*, inventories.name, inventories.unit')
                    ->join('inventories', 'inventories.id = addon_materials.inventory_id')
                    ->where('addon_id', $addonId)
                    ->findAll();
    }
}
