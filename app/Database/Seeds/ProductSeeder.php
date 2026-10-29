<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // HAIR TREATMENT
            [
                'name'        => 'Hair Spa Treatment',
                'slug'        => 'hair-spa-treatment',
                'description' => 'Perawatan rambut dengan masker & vitamin untuk rambut sehat berkilau',
                'category'    => 'treatment',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Hair Mask Treatment',
                'slug'        => 'hair-mask-treatment',
                'description' => 'Masker rambut intensif untuk menutrisi & memperbaiki rambut rusak',
                'category'    => 'treatment',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Keratin Treatment',
                'slug'        => 'keratin-treatment',
                'description' => 'Perawatan keratin untuk rambut lembut, halus & anti kusut',
                'category'    => 'treatment',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Scalp Treatment',
                'slug'        => 'scalp-treatment',
                'description' => 'Perawatan kulit kepala untuk mengatasi ketombe & rambut rontok',
                'category'    => 'treatment',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],

            // SMOOTHING & REBONDING
            [
                'name'        => 'Smoothing',
                'slug'        => 'smoothing',
                'description' => 'Meluruskan rambut dengan hasil natural & tetap bervolume',
                'category'    => 'styling',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Rebonding',
                'slug'        => 'rebonding',
                'description' => 'Meluruskan rambut permanen dengan hasil super lurus & berkilau',
                'category'    => 'styling',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Curly / Wave',
                'slug'        => 'curly-wave',
                'description' => 'Membuat rambut keriting atau bergelombang natural',
                'category'    => 'styling',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],

            // COLORING
            [
                'name'        => 'Hair Coloring',
                'slug'        => 'hair-coloring',
                'description' => 'Pewarnaan rambut dengan berbagai pilihan warna trendy',
                'category'    => 'coloring',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Highlight / Ombre',
                'slug'        => 'highlight-ombre',
                'description' => 'Pewarnaan gradasi untuk tampilan lebih stylish',
                'category'    => 'coloring',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Bleaching',
                'slug'        => 'bleaching',
                'description' => 'Pemutihan rambut untuk warna terang atau pastel',
                'category'    => 'coloring',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],

            // BASIC SERVICES
            [
                'name'        => 'Hair Cut',
                'slug'        => 'hair-cut',
                'description' => 'Potong rambut sesuai model & keinginan Anda',
                'category'    => 'basic',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Wash & Blow',
                'slug'        => 'wash-blow',
                'description' => 'Cuci rambut + blow styling untuk tampilan fresh',
                'category'    => 'basic',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('products')->insertBatch($data);
    }
}
