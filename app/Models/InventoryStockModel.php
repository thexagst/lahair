<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryStockModel extends Model
{
    protected $table            = 'inventory_stocks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'inventory_id',
        'branch_id',
        'stock',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get stock by inventory & branch
     */
    public function getStock(int $inventoryId, int $branchId): float
    {
        $record = $this->where('inventory_id', $inventoryId)
                       ->where('branch_id', $branchId)
                       ->first();

        return $record ? (float)$record['stock'] : 0.00;
    }

    /**
     * Set or update stock for inventory & branch
     */
    public function setStock(int $inventoryId, int $branchId, float $stock): bool
    {
        $existing = $this->where('inventory_id', $inventoryId)
                         ->where('branch_id', $branchId)
                         ->first();

        if ($existing) {
            return $this->update($existing['id'], ['stock' => $stock]);
        }

        return (bool) $this->insert([
            'inventory_id' => $inventoryId,
            'branch_id'    => $branchId,
            'stock'        => $stock,
        ]);
    }

    /**
     * Deduct stock for inventory in specific branch
     */
    public function deductStock(int $inventoryId, int $branchId, float $amount): bool
    {
        $existing = $this->where('inventory_id', $inventoryId)
                         ->where('branch_id', $branchId)
                         ->first();

        $currentStock = $existing ? (float)$existing['stock'] : 0.00;
        $newStock = $currentStock - $amount;

        if ($existing) {
            return $this->update($existing['id'], ['stock' => $newStock]);
        }

        return (bool) $this->insert([
            'inventory_id' => $inventoryId,
            'branch_id'    => $branchId,
            'stock'        => $newStock,
        ]);
    }
}
