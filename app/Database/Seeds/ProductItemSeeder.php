<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\ProductModel;
use App\Models\ProductItemModel;

class ProductItemSeeder extends Seeder
{
    public function run()
    {
        $productModel = new ProductModel();
        
        $products = $productModel->findAll();
        
        if (empty($products)) {
            // Run ProductSeeder if no products exist
            $this->call('ProductSeeder');
            $products = $productModel->findAll();
        }

        $items = [];
        $now = date('Y-m-d H:i:s');

        foreach ($products as $product) {
            // Define dummy items based on category
            $dummyItems = [];
            
            switch ($product['category']) {
                case 'basic':
                    if (strpos(strtolower($product['name']), 'cut') !== false) {
                        $dummyItems = [
                            ['name' => 'Men Cut', 'price' => 50000],
                            ['name' => 'Women Cut', 'price' => 70000],
                            ['name' => 'Kids Cut', 'price' => 40000],
                        ];
                    } else { // Wash & Blow
                        $dummyItems = [
                            ['name' => 'Short Hair', 'price' => 40000],
                            ['name' => 'Medium Hair', 'price' => 50000],
                            ['name' => 'Long Hair', 'price' => 60000],
                        ];
                    }
                    break;
                case 'coloring':
                    $dummyItems = [
                        ['name' => 'Short Hair', 'price' => 300000],
                        ['name' => 'Medium Hair', 'price' => 450000],
                        ['name' => 'Long Hair', 'price' => 600000],
                        ['name' => 'Extra Long', 'price' => 800000],
                    ];
                    break;
                case 'treatment':
                case 'styling':
                default:
                    $dummyItems = [
                        ['name' => 'Short Hair', 'price' => 150000],
                        ['name' => 'Medium Hair', 'price' => 200000],
                        ['name' => 'Long Hair', 'price' => 250000],
                    ];
                    break;
            }

            foreach ($dummyItems as $idx => $item) {
                // Check if already exists to prevent duplicate on re-run
                $exists = $this->db->table('product_items')
                                   ->where('product_id', $product['id'])
                                   ->where('name', $item['name'])
                                   ->countAllResults();

                if ($exists == 0) {
                    $items[] = [
                        'product_id' => $product['id'],
                        'name'       => $item['name'],
                        'price'      => $item['price'],
                        'image'      => null, // Optional
                        'sort_order' => $idx + 1,
                        'is_active'  => 1,
                        'sold'       => rand(0, 50),
                        'stock'      => 100, // Stock per item
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        if (!empty($items)) {
            $this->db->table('product_items')->insertBatch($items);
            echo "Inserted " . count($items) . " product items.\n";
        } else {
            echo "No new product items to insert.\n";
        }
    }
}
