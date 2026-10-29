<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table            = 'inventories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'stock',
        'unit',       // pcs, ml, gr, pack
        'unit_value', // 1 pcs/botol = berapa ml/gr (opsional)
        'sub_unit',   // ml, gr, pcs (satuan terkecil)
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get all inventories with stocks grouped by branch
     */
    public function getWithBranchStocks(?int $branchId = null): array
    {
        $inventories = $this->orderBy('name', 'ASC')->findAll();
        if (empty($inventories)) {
            return [];
        }

        $db = \Config\Database::connect();
        $builder = $db->table('inventory_stocks')
                      ->select('inventory_stocks.*, branches.name as branch_name, branches.code as branch_code')
                      ->join('branches', 'branches.id = inventory_stocks.branch_id');

        if ($branchId !== null) {
            $builder->where('inventory_stocks.branch_id', $branchId);
        }

        $stocks = $builder->get()->getResultArray();

        $stocksByInventory = [];
        foreach ($stocks as $s) {
            $invId = $s['inventory_id'];
            $bId   = $s['branch_id'];
            $bCode = $s['branch_code'];

            if (!isset($stocksByInventory[$invId])) {
                $stocksByInventory[$invId] = [
                    'by_id'   => [],
                    'by_code' => [],
                ];
            }
            $stocksByInventory[$invId]['by_id'][$bId] = (float)$s['stock'];
            $stocksByInventory[$invId]['by_code'][$bCode] = (float)$s['stock'];
        }

        foreach ($inventories as &$inv) {
            $invData = $stocksByInventory[$inv['id']] ?? ['by_id' => [], 'by_code' => []];
            $inv['branch_stocks'] = $invData['by_id'];
            $inv['stocks_by_code'] = $invData['by_code'];

            if ($branchId !== null) {
                $inv['current_branch_stock'] = $invData['by_id'][$branchId] ?? 0.00;
            }
        }

        return $inventories;
    }
}

