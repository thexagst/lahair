<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'slug',
        'description',
        'image',
        'category',
        'is_active',
        'sold',
        'stock',
        'track_stock',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[255]',
        'slug' => 'required|max_length[255]',
    ];

    /**
     * Get active products grouped by category (for menu display)
     */
    public function getActiveProductsByCategory(): array
    {
        $products = $this->where('is_active', 1)
                         ->orderBy('category', 'ASC')
                         ->orderBy('name', 'ASC')
                         ->findAll();

        $grouped = [];
        foreach ($products as $product) {
            $category = $product['category'] ?? 'other';
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][] = $product;
        }

        return $grouped;
    }

    /**
     * Get all products for admin
     */
    public function getAllProducts(): array
    {
        return $this->orderBy('category', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Generate slug from name
     */
    public function generateSlug(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }

    /**
     * Get distinct categories
     */
    public function getDistinctCategories(): array
    {
        $products = $this->select('category')
                         ->distinct()
                         ->orderBy('category', 'ASC')
                         ->findAll();
        
        $categories = [];
        foreach ($products as $p) {
            if (!empty($p['category'])) {
                $categories[] = $p['category'];
            }
        }
        return $categories;
    }
}
