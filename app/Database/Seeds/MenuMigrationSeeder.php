<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\ProductModel;
use App\Models\ProductItemModel;

class MenuMigrationSeeder extends Seeder
{
    public function run()
    {
        $productModel = new ProductModel();
        $productItemModel = new ProductItemModel();

        // Disable foreign key checks temporarily
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');

        // Bersihkan data lama
        $this->db->table('product_items')->truncate();
        $this->db->table('product_item_addons')->truncate();
        $this->db->table('product_item_materials')->truncate();
        $this->db->table('products')->truncate();
        
        // Reset auto increment
        $this->db->query("ALTER TABLE products AUTO_INCREMENT = 1;");
        $this->db->query("ALTER TABLE product_items AUTO_INCREMENT = 1;");

        // Re-enable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');

        $menus = $this->getNewMenusData();

        foreach ($menus as $menu) {
            // Insert Product (Kategori Utama)
            $productModel->insert([
                'name' => $menu['name'],
                'slug' => $productModel->generateSlug($menu['name']),
                'description' => $menu['desc'],
                'category' => $menu['category'],
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            $productId = $productModel->getInsertID();

            // Insert Items (Layanan di dalam Menu)
            $sort = 1;
            foreach ($menu['items'] as $item) {
                $productItemModel->insert([
                    'product_id' => $productId,
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'duration_minutes' => $item['duration'] ?? 0,
                    'sort_order' => $sort++,
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        
        echo "Menu migration completed successfully!\n";
    }

    private function getNewMenusData()
    {
        return [
            // 1. Japanese Hair Menu
            [
                'category' => 'Japanese Hair',
                'name' => 'Japanese Hair',
                'desc' => 'Daftar menu layanan Japanese Hair',
                'items' => [
                    ['name' => 'Japanese Hair Calm Treatment (60 Menit)', 'price' => 169000, 'duration' => 60],
                    ['name' => 'Japanese Hair Relax Treatment (90 Menit)', 'price' => 225000, 'duration' => 90],
                    ['name' => 'Japanese Hair Calm Slim Treatment (90 Menit)', 'price' => 275000, 'duration' => 90],
                    ['name' => 'Japanese Hair Relax Slim Treatment (120 Menit)', 'price' => 325000, 'duration' => 120]
                ]
            ],
            // 2. Hair Treatment Menu
            [
                'category' => 'Hair Treatment',
                'name' => 'Hair Treatment',
                'desc' => 'Daftar menu layanan Hair Treatment',
                'items' => [
                    ['name' => 'Creambath BEST (S)', 'price' => 89000],
                    ['name' => 'Creambath BEST (M)', 'price' => 99000],
                    ['name' => 'Creambath BEST (L)', 'price' => 110000],
                    ['name' => 'Creambath KEUNE (S)', 'price' => 109000],
                    ['name' => 'Creambath KEUNE (M)', 'price' => 119000],
                    ['name' => 'Creambath KEUNE (L)', 'price' => 130000],
                    ['name' => 'Keratin Treatment S', 'price' => 150000],
                    ['name' => 'Keratin Treatment M', 'price' => 175000],
                    ['name' => 'Keratin Treatment L', 'price' => 185000],
                    ['name' => 'Cooling Treatment', 'price' => 150000],
                    ['name' => 'Blonde Treatment S', 'price' => 150000],
                    ['name' => 'Blonde Treatment M', 'price' => 175000],
                    ['name' => 'Blonde Treatment L', 'price' => 185000],
                    ['name' => 'Add On Catok / Blow S', 'price' => 35000],
                    ['name' => 'Add On Catok / Blow M', 'price' => 45000],
                    ['name' => 'Add On Catok / Blow L', 'price' => 50000],
                ]
            ],
            // 3. Chemical Hair Menu
            [
                'category' => 'Chemical Hair',
                'name' => 'Chemical Hair',
                'desc' => 'Daftar menu layanan Chemical Hair',
                'items' => [
                    ['name' => 'Cuci Catok / Blow S', 'price' => 85000],
                    ['name' => 'Cuci Catok / Blow M', 'price' => 95000],
                    ['name' => 'Cuci Catok / Blow L', 'price' => 105000],
                    ['name' => 'Cutting Poni', 'price' => 50000],
                    ['name' => 'Cutting Anak', 'price' => 75000],
                    ['name' => 'Cutting Dewasa', 'price' => 110000],
                    ['name' => 'Color BEST (Start From)', 'price' => 450000],
                    ['name' => 'Color KEUNE (Start From)', 'price' => 595000],
                    ['name' => 'Keratin Smooth Akar', 'price' => 550000],
                    ['name' => 'Keratin Smooth S/M/L (Start From)', 'price' => 600000],
                    ['name' => 'Blow Perm (Start From)', 'price' => 450000],
                    ['name' => 'Hair Do', 'price' => 150000],
                    ['name' => 'Make Up', 'price' => 350000],
                    ['name' => 'Hair Extension 1 Bundle', 'price' => 800000],
                    ['name' => 'Service Hair Extension (Start From)', 'price' => 4000000],
                ]
            ],
            // 4. Nails Menu
            [
                'category' => 'Nails',
                'name' => 'Nails',
                'desc' => 'Daftar menu layanan Nails',
                'items' => [
                    ['name' => 'Remove @Jari', 'price' => 5000],
                    ['name' => 'Manicure', 'price' => 110000],
                    ['name' => 'Manicure Vapo', 'price' => 150000],
                    ['name' => 'Pedicure', 'price' => 145000],
                    ['name' => 'Pedicure Jelly', 'price' => 165000],
                    ['name' => 'Gel Polish', 'price' => 150000],
                    ['name' => 'Nail Basic', 'price' => 99000],
                    ['name' => 'French Manicure', 'price' => 125000],
                    ['name' => 'Chrome', 'price' => 125000],
                    ['name' => 'Ombre', 'price' => 125000],
                    ['name' => 'Cat Eye', 'price' => 125000],
                ]
            ],
            // 5. Lashes Menu
            [
                'category' => 'Lashes',
                'name' => 'Lashes',
                'desc' => 'Daftar menu layanan Lashes',
                'items' => [
                    ['name' => 'Lash Lift', 'price' => 99000],
                    ['name' => 'Eyelash Single', 'price' => 99000],
                    ['name' => 'UV Eyelash', 'price' => 99000],
                    ['name' => 'Double Lash', 'price' => 125000],
                    ['name' => 'Y Shape', 'price' => 125000],
                    ['name' => 'W SHAPE', 'price' => 125000],
                    ['name' => 'Hybrid Lash', 'price' => 150000],
                    ['name' => 'Brow Lamination', 'price' => 150000],
                    ['name' => 'Russian', 'price' => 175000],
                    ['name' => 'Brow Bomber', 'price' => 175000],
                ]
            ],
            // 6. Waxing Menu
            [
                'category' => 'Waxing',
                'name' => 'Waxing',
                'desc' => 'Daftar menu layanan Waxing',
                'items' => [
                    ['name' => 'Underarms', 'price' => 99000],
                    ['name' => 'Legs', 'price' => 99000],
                    ['name' => 'Arms', 'price' => 99000],
                    ['name' => 'Brazilian', 'price' => 99000],
                ]
            ],
            // 7. Body Treatment Menu
            [
                'category' => 'Body Treatment',
                'name' => 'Body Treatment',
                'desc' => 'Daftar menu layanan Body Treatment',
                'items' => [
                    ['name' => 'Back Massage (30 Menit)', 'price' => 75000, 'duration' => 30],
                    ['name' => 'Refleksi (30 Menit)', 'price' => 75000, 'duration' => 30],
                    ['name' => 'Lymphatic Instan Slim (1 Part)', 'price' => 125000],
                    ['name' => 'Slim & Brightening Face Instant', 'price' => 175000],
                    ['name' => 'Dark Spot Flek Hitam (Start From)', 'price' => 1500000],
                    ['name' => 'Scar Camouflage (Start From)', 'price' => 800000],
                    ['name' => 'Strechmark Camouflage (Start From)', 'price' => 1200000],
                    ['name' => 'Instant Whitening Body 1 Part', 'price' => 500000],
                    ['name' => 'Instant Whitening Body Full Body', 'price' => 2999000],
                    ['name' => 'Pink Treatment Jadi Pink Seketika 1 Part', 'price' => 1900000],
                    ['name' => 'Pink Treatment Jadi Pink Seketika 2 Part', 'price' => 3000000],
                    ['name' => 'Pink Treatment Jadi Pink Seketika 3 Part', 'price' => 4700000],
                    ['name' => 'Pink Bright Jadi Putih Seketika 1 Part', 'price' => 1900000],
                    ['name' => 'Pink Bright Jadi Putih Seketika 2 Part', 'price' => 3000000],
                    ['name' => 'Pink Bright Jadi Putih Seketika 3 Part', 'price' => 4700000],
                    ['name' => 'Pink Bright Jadi Putih Seketika 4 Part', 'price' => 6200000],
                ]
            ],
            // 8. Course Menu
            [
                'category' => 'Course',
                'name' => 'Course',
                'desc' => 'Daftar menu layanan Course',
                'items' => [
                    ['name' => 'Pink Treatment Course', 'price' => 0],
                    ['name' => 'Strechmark Camouflage Course', 'price' => 0],
                    ['name' => 'Scar Camouflage Course', 'price' => 0],
                    ['name' => 'Lip Removal Course', 'price' => 0],
                    ['name' => 'Flek Hitam Course', 'price' => 0],
                    ['name' => 'Plasmalift Course', 'price' => 0],
                    ['name' => 'Instant Whitening Course', 'price' => 0],
                    ['name' => 'Nailsnu Course', 'price' => 0],
                ]
            ]
        ];
    }
}
