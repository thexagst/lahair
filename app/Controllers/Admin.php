<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ProductItemModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ReviewModel;

class Admin extends BaseController
{
    protected ProductModel $productModel;
    protected ProductItemModel $itemModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->itemModel = new ProductItemModel();
    }

    /**
     * Get active branch ID from session (returns null if 'all' or not set)
     */
    protected function getActiveBranchId(): ?int
    {
        $branchId = session()->get('active_branch_id');
        if ($branchId === null || $branchId === '' || $branchId === 'all') {
            return null;
        }
        return (int) $branchId;
    }

    /**
     * Dashboard - redirect to orders or select-branch
     */
    public function index()
    {
        if (!session()->has('active_branch_id')) {
            return redirect()->to('/admin/select-branch');
        }

        // Enforce Worker Restrictions
        if (session()->get('role') === 'worker') {
            return redirect()->to('/admin/orders');
        }

        return redirect()->to('/admin/orders');
    }

    /**
     * Select Branch on initial open
     */
    public function selectBranch()
    {
        $branchModel = new \App\Models\BranchModel();
        $branches    = $branchModel->getActiveBranches();

        $data = [
            'title'    => 'Pilih Cabang Kerja',
            'branches' => $branches,
        ];

        return view('admin/select_branch', $data);
    }

    /**
     * Switch Active Branch (URL segment or POST)
     */
    public function switchBranch($branchId = null)
    {
        if ($branchId === null) {
            $branchId = $this->request->getPost('branch_id') ?? $this->request->getGet('branch_id');
        }

        $branchModel = new \App\Models\BranchModel();
        
        if ($branchId === 'all' || empty($branchId)) {
            session()->set('active_branch_id', 'all');
            session()->set('active_branch_name', 'Semua Cabang');
        } else {
            $branch = $branchModel->find((int)$branchId);
            if ($branch) {
                session()->set('active_branch_id', (int)$branch['id']);
                session()->set('active_branch_name', $branch['name']);
                session()->set('active_branch_code', $branch['code']);
            }
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success'     => true,
                'branch_id'   => session()->get('active_branch_id'),
                'branch_name' => session()->get('active_branch_name'),
            ]);
        }

        $redirect = $this->request->getGet('redirect');
        if ($redirect) {
            return redirect()->to($redirect)->with('success', 'Cabang aktif: ' . session()->get('active_branch_name'));
        }

        $referer = $this->request->getServer('HTTP_REFERER');
        if ($referer && strpos($referer, 'select-branch') === false) {
            return redirect()->to($referer)->with('success', 'Cabang aktif: ' . session()->get('active_branch_name'));
        }

        return redirect()->to('/admin/orders')->with('success', 'Cabang aktif: ' . session()->get('active_branch_name'));
    }

    /**
     * Check role permissions
     */
    private function ensureSuperAdmin()
    {
        if (session()->get('role') === 'worker') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Access Denied');
        }
    }

    /**
     * List all products
     */
    public function products()
    {
        $search = $this->request->getGet('search');
        $builder = $this->productModel;

        if ($search) {
            $builder = $builder->groupStart()
                              ->like('name', $search)
                              ->orLike('category', $search)
                              ->groupEnd();
        }

        $products = $builder->orderBy('name', 'ASC')
                           ->paginate(15, 'products');
        
        // Count items for each product
        foreach ($products as &$product) {
            $product['items_count'] = $this->itemModel->where('product_id', $product['id'])->countAllResults();
        }

        $data = [
            'title'    => 'Kelola Produk',
            'products' => $products,
            'pager'    => $this->productModel->pager,
            'search'   => $search,
        ];

        return view('admin/products/index', $data);
    }

    /**
     * Show create product form
     */
    public function create()
    {
        $data = [
            'title'      => 'Tambah Produk',
            'categories' => $this->productModel->getDistinctCategories(),
        ];

        return view('admin/products/form', $data);
    }

    /**
     * Store new product
     */
    public function store()
    {
        $rules = [
            'name'        => 'required|min_length[2]|max_length[255]',
            'description' => 'permit_empty|max_length[500]',
            'category'    => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = $this->request->getPost('name');
        
        // Handle image upload
        $imagePath = null;
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/products', $newName);
            $imagePath = 'uploads/products/' . $newName;
        }
        
        $data = [
            'name'        => $name,
            'slug'        => $this->productModel->generateSlug($name),
            'description' => $this->request->getPost('description'),
            'image'       => $imagePath,
            'category'    => $this->request->getPost('category'),
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
            'sold'        => (int) $this->request->getPost('sold'),
        ];

        $this->productModel->insert($data);

        return redirect()->to('/admin/products')->with('success', 'Menu berhasil ditambahkan!');
    }

    /**
     * Show edit product form
     */
    public function edit($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Produk tidak ditemukan');
        }

        $data = [
            'title'      => 'Edit Produk',
            'product'    => $product,
            'categories' => $this->productModel->getDistinctCategories(),
        ];

        return view('admin/products/form', $data);
    }

    /**
     * Update product
     */
    public function update($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Menu tidak ditemukan');
        }

        $rules = [
            'name'        => 'required|min_length[2]|max_length[255]',
            'description' => 'permit_empty|max_length[500]',
            'category'    => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = $this->request->getPost('name');

        // Handle image upload
        $imagePath = $product['image']; // Keep existing image
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Delete old image if exists
            if ($product['image'] && file_exists(FCPATH . $product['image'])) {
                unlink(FCPATH . $product['image']);
            }
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/products', $newName);
            $imagePath = 'uploads/products/' . $newName;
        }

        $data = [
            'name'        => $name,
            'slug'        => $this->productModel->generateSlug($name),
            'description' => $this->request->getPost('description'),
            'image'       => $imagePath,
            'category'    => $this->request->getPost('category'),
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
            'sold'        => (int) $this->request->getPost('sold'),
        ];

        $this->productModel->update($id, $data);

        return redirect()->to('/admin/products')->with('success', 'Menu berhasil diupdate!');
    }

    /**
     * Delete product
     */
    public function delete($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Produk tidak ditemukan');
        }

        $this->productModel->delete($id);

        return redirect()->to('/admin/products')->with('success', 'Produk berhasil dihapus!');
    }

    // ========================================
    // PRODUCT ITEMS (Harga)
    // ========================================

    /**
     * Show product items
     */
    public function items($productId)
    {
        $product = $this->productModel->find($productId);

        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Produk tidak ditemukan');
        }

        $data = [
            'title'   => 'Daftar Harga: ' . $product['name'],
            'product' => $product,
            'items'   => $this->itemModel->getByProductAdmin($productId),
        ];

        return view('admin/products/items', $data);
    }

    /**
     * Show create item form
     */
    public function createItem($productId)
    {
        $product = $this->productModel->find($productId);

        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Produk tidak ditemukan');
        }

        $inventoryModel = new \App\Models\InventoryModel();

        $data = [
            'title'       => 'Tambah Harga',
            'product'     => $product,
            'inventories' => $inventoryModel->orderBy('name', 'ASC')->findAll(),
        ];

        return view('admin/products/item_form', $data);
    }

    /**
     * Store new item
     */
    public function storeItem($productId)
    {
        $product = $this->productModel->find($productId);

        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Menu tidak ditemukan');
        }

        $rules = [
            'name'  => 'required|min_length[2]|max_length[255]',
            'price' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle image upload
        $imagePath = null;
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/items', $newName);
            $imagePath = 'uploads/items/' . $newName;
        }

        $data = [
            'product_id'       => $productId,
            'name'             => $this->request->getPost('name'),
            'image'            => $imagePath,
            'price'            => $this->request->getPost('price'),
            'duration_minutes' => (int) $this->request->getPost('duration_minutes'),
            'sort_order'       => (int) $this->request->getPost('sort_order'),
            'is_active'        => $this->request->getPost('is_active') ? 1 : 0,
            'sold'             => (int) $this->request->getPost('sold'),
        ];

        $newItemId = $this->itemModel->insert($data);

        // Save Recipe
        $materialModel = new \App\Models\ProductItemMaterialModel();
        $materials = $this->request->getPost('materials');
        $quantities = $this->request->getPost('quantities');
        
        if ($materials && is_array($materials)) {
            foreach ($materials as $index => $invId) {
                if (!empty($invId) && isset($quantities[$index]) && $quantities[$index] > 0) {
                    $materialModel->insert([
                        'product_item_id' => $newItemId,
                        'inventory_id'    => $invId,
                        'quantity'        => $quantities[$index]
                    ]);
                }
            }
        }

        return redirect()->to('/admin/products/items/' . $productId)->with('success', 'Isi berhasil ditambahkan!');
    }

    /**
     * Show edit item form
     */
    public function editItem($productId, $itemId)
    {
        $product = $this->productModel->find($productId);
        $item = $this->itemModel->find($itemId);

        if (!$product || !$item) {
            return redirect()->to('/admin/products')->with('error', 'Data tidak ditemukan');
        }

        $materialModel = new \App\Models\ProductItemMaterialModel();
        $inventoryModel = new \App\Models\InventoryModel();

        $data = [
            'title'       => 'Edit Isi',
            'product'     => $product,
            'item'        => $item,
            'inventories' => $inventoryModel->orderBy('name', 'ASC')->findAll(),
            'materials'   => $materialModel->where('product_item_id', $itemId)->findAll(),
        ];

        return view('admin/products/item_form', $data);
    }

    /**
     * Update item
     */
    public function updateItem($productId, $itemId)
    {
        $product = $this->productModel->find($productId);
        $item = $this->itemModel->find($itemId);

        if (!$product || !$item) {
            return redirect()->to('/admin/products')->with('error', 'Data tidak ditemukan');
        }

        $rules = [
            'name'  => 'required|min_length[2]|max_length[255]',
            'price' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle image upload
        $imagePath = $item['image']; // Keep existing
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            if ($item['image'] && file_exists(FCPATH . $item['image'])) {
                unlink(FCPATH . $item['image']);
            }
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/items', $newName);
            $imagePath = 'uploads/items/' . $newName;
        }

        $data = [
            'name'             => $this->request->getPost('name'),
            'image'            => $imagePath,
            'price'            => $this->request->getPost('price'),
            'duration_minutes' => (int) $this->request->getPost('duration_minutes'),
            'sort_order'       => (int) $this->request->getPost('sort_order'),
            'is_active'        => $this->request->getPost('is_active') ? 1 : 0,
            'sold'             => (int) $this->request->getPost('sold'),
        ];

        $this->itemModel->update($itemId, $data);
        
        // Update Recipe
        $materialModel = new \App\Models\ProductItemMaterialModel();
        $materialModel->where('product_item_id', $itemId)->delete();
        
        $materials = $this->request->getPost('materials');
        $quantities = $this->request->getPost('quantities');
        
        if ($materials && is_array($materials)) {
            foreach ($materials as $index => $invId) {
                if (!empty($invId) && isset($quantities[$index]) && $quantities[$index] > 0) {
                    $materialModel->insert([
                        'product_item_id' => $itemId,
                        'inventory_id'    => $invId,
                        'quantity'        => $quantities[$index]
                    ]);
                }
            }
        }

        return redirect()->to('/admin/products/items/' . $productId)->with('success', 'Isi berhasil diupdate!');
    }

    /**
     * Delete item
     */
    public function deleteItem($productId, $itemId)
    {
        $item = $this->itemModel->find($itemId);

        if (!$item) {
            return redirect()->to('/admin/products/items/' . $productId)->with('error', 'Item tidak ditemukan');
        }

        // Delete image if exists
        if ($item['image'] && file_exists(FCPATH . $item['image'])) {
            unlink(FCPATH . $item['image']);
        }

        $this->itemModel->delete($itemId);

        return redirect()->to('/admin/products/items/' . $productId)->with('success', 'Item berhasil dihapus!');
    }

    /**
     * Delete ALL items for a product
     */
    public function deleteAllItems($productId)
    {
        $items = $this->itemModel->where('product_id', $productId)->findAll();
        
        foreach ($items as $item) {
            // Delete image if exists
            if ($item['image'] && file_exists(FCPATH . $item['image'])) {
                unlink(FCPATH . $item['image']);
            }
        }

        $this->itemModel->where('product_id', $productId)->delete();

        return redirect()->to('/admin/products/items/' . $productId)->with('success', 'Semua isi menu berhasil dihapus!');
    }

    /**
     * Import items from CSV (Create or Update)
     */
    public function importItems($productId)
    {
        $file = $this->request->getFile('csv_file');
        
        if (!$file || !$file->isValid()) {
            return redirect()->to('/admin/products/items/' . $productId)->with('error', 'File tidak valid');
        }

        if ($file->getExtension() !== 'csv') {
            return redirect()->to('/admin/products/items/' . $productId)->with('error', 'Mohon upload file .csv');
        }

        $handle = fopen($file->getTempName(), 'r');
        $headers = fgetcsv($handle); // Skip or validate headers

        $countInsert = 0;
        $countUpdate = 0;
        
        while (($row = fgetcsv($handle)) !== FALSE) {
            if (count($row) < 3) continue; // Basic validation (ID, Name, Price required min)

            // Index 0: ID
            // Index 1: Name
            // Index 2: Price
            // Index 3: Sort Order
            // Index 4: Active
            // Index 5: Sold

            $id = isset($row[0]) ? trim($row[0]) : '';
            
            // Handle Price Robustly
            $priceRaw = $row[2];
            
            // 1. Remove common currency symbols and whitespace
            $priceClean = preg_replace('/[Rp\$\s]/i', '', $priceRaw);
            
            // 2. Handle known decimal endings (.00 or ,00) which cause the "Millions" bug
            // If it ends with .00, remove it.
            if (str_ends_with($priceClean, '.00')) {
                $priceClean = substr($priceClean, 0, -3);
            }
            // If it ends with ,00, remove it.
            if (str_ends_with($priceClean, ',00')) {
                $priceClean = substr($priceClean, 0, -3);
            }
            
            // 3. Advanced Decimal Detection
            // If we still have decimals like .50 or ,50
            if (strpos($priceClean, '.') !== false && strpos($priceClean, ',') !== false) {
                // Both exist (e.g. 30.000,00 or 30,000.00)
                // The last one is the decimal separator
                if (strrpos($priceClean, ',') > strrpos($priceClean, '.')) {
                    // 30.000,50 -> Cut at comma
                    $priceClean = explode(',', $priceClean)[0];
                } else {
                    // 30,000.50 -> Cut at dot
                    $priceClean = explode('.', $priceClean)[0];
                }
            } elseif (strpos($priceClean, ',') !== false) {
                // Only comma (30,000 or 30000,50)
                // Only treat as decimal if it's NOT a thousands separator.
                // Thousands separator usually is correctly placed (3 digits).
                // Decimal usually at end (2 digits).
                if (preg_match('/,[0-9]{2}$/', $priceClean)) {
                     $priceClean = explode(',', $priceClean)[0];
                }
            } elseif (strpos($priceClean, '.') !== false) {
                // Only dot (30.000 or 30000.50)
                 if (preg_match('/\.[0-9]{2}$/', $priceClean)) {
                     $priceClean = explode('.', $priceClean)[0];
                }
            }
            
            // 4. Finally, keep only digits
            $price = (int) preg_replace('/[^0-9]/', '', $priceClean);
            
            $data = [
                'product_id' => $productId,
                'name'       => $row[1],
                'price'      => $price,
                'sort_order' => isset($row[3]) && $row[3] !== '' ? (int) $row[3] : 0,
                'is_active'  => isset($row[4]) && $row[4] !== '' ? (int) $row[4] : 1,
                'sold'       => isset($row[5]) && $row[5] !== '' ? (int) $row[5] : 0,
            ];

            if (!empty($id) && is_numeric($id)) {
                // UPDATE existing item
                // Optional: Verify this item belongs to current product for safety
                $existingItem = $this->itemModel->find($id);
                if ($existingItem && $existingItem['product_id'] == $productId) {
                    $this->itemModel->update($id, $data);
                    $countUpdate++;
                }
            } else {
                // INSERT new item
                $this->itemModel->insert($data);
                $countInsert++;
            }
        }
        fclose($handle);

        $msg = "Import selesai: $countInsert ditambah, $countUpdate diupdate.";
        return redirect()->to('/admin/products/items/' . $productId)->with('success', $msg);
    }

    /**
     * Export Real Data CSV
     */
    public function downloadItemTemplate($productId)
    {
        $product = $this->productModel->find($productId);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
        }
        
        $items = $this->itemModel->where('product_id', $productId)->orderBy('sort_order', 'ASC')->findAll();

        $filename = "export_items_" . $product['slug'] . "_" . date('Ymd_His') . ".csv";
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Headers
        fputcsv($output, ['ID (JANGAN UBAH)', 'Nama Item', 'Harga', 'Urutan', 'Aktif (1=Ya 0=Tidak)', 'Terjual']);
        
        foreach ($items as $item) {
            fputcsv($output, [
                $item['id'],
                $item['name'],
                $item['price'],
                $item['sort_order'],
                $item['is_active'],
                $item['sold']
            ]);
        }
        
        fclose($output);
        exit();
    }

    // ========================================
    // ADD-ONS MANAGEMENT
    // ========================================

    /**
     * List add-ons for a product item
     */
    public function addons($itemId)
    {
        $addonModel = new \App\Models\ProductItemAddonModel();
        $item = $this->itemModel->find($itemId);

        if (!$item) {
            return redirect()->to('/admin/products')->with('error', 'Item tidak ditemukan');
        }

        $addons = $addonModel->where('product_item_id', $itemId)->findAll();
        $product = $this->productModel->find($item['product_id']);

        $data = [
            'title'        => 'Kelola Add-ons',
            'item'         => $item,
            'productItem'  => $item,
            'addons'       => $addons,
        ];

        return view('admin/addons/index', $data);
    }

    /**
     * Show create addon form
     */
    public function createAddon($itemId)
    {
        $item = $this->itemModel->find($itemId);

        if (!$item) {
            return redirect()->to('/admin/products')->with('error', 'Item tidak ditemukan');
        }

        $inventoryModel = new \App\Models\InventoryModel();

        $data = [
            'title'       => 'Tambah Add-on',
            'item'        => $item,
            'inventories' => $inventoryModel->orderBy('name', 'ASC')->findAll(),
        ];

        return view('admin/addons/form', $data);
    }

    /**
     * Store new addon
     */
    public function storeAddon($itemId)
    {
        $item = $this->itemModel->find($itemId);

        if (!$item) {
            return redirect()->to('/admin/products')->with('error', 'Item tidak ditemukan');
        }

        $rules = [
            'name'  => 'required|min_length[2]|max_length[255]',
            'price' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $addonModel = new \App\Models\ProductItemAddonModel();
        $data = [
            'product_item_id' => $itemId,
            'name'            => $this->request->getPost('name'),
            'price'           => $this->request->getPost('price'),
            'is_required'     => $this->request->getPost('is_required') ? 1 : 0,
            'is_active'       => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $newAddonId = $addonModel->insert($data);

        // Save Addon Recipe
        $addonMaterialModel = new \App\Models\AddonMaterialModel();
        $materials = $this->request->getPost('materials');
        $quantities = $this->request->getPost('quantities');

        if ($materials && is_array($materials)) {
            foreach ($materials as $index => $invId) {
                if (!empty($invId) && isset($quantities[$index]) && $quantities[$index] > 0) {
                    $addonMaterialModel->insert([
                        'addon_id'     => $newAddonId,
                        'inventory_id' => $invId,
                        'quantity'     => $quantities[$index],
                    ]);
                }
            }
        }

        return redirect()->to('/admin/products/items/' . $itemId . '/addons')->with('success', 'Add-on berhasil ditambahkan!');
    }

    /**
     * Show edit addon form
     */
    public function editAddon($itemId, $addonId)
    {
        $item = $this->itemModel->find($itemId);
        $addonModel = new \App\Models\ProductItemAddonModel();
        $addon = $addonModel->find($addonId);

        if (!$item || !$addon) {
            return redirect()->to('/admin/products')->with('error', 'Data tidak ditemukan');
        }

        $inventoryModel = new \App\Models\InventoryModel();
        $addonMaterialModel = new \App\Models\AddonMaterialModel();

        $data = [
            'title'       => 'Edit Add-on',
            'item'        => $item,
            'addon'       => $addon,
            'inventories' => $inventoryModel->orderBy('name', 'ASC')->findAll(),
            'materials'   => $addonMaterialModel->where('addon_id', $addonId)->findAll(),
        ];

        return view('admin/addons/form', $data);
    }

    /**
     * Update addon
     */
    public function updateAddon($itemId, $addonId)
    {
        $addonModel = new \App\Models\ProductItemAddonModel();
        $addon = $addonModel->find($addonId);

        if (!$addon) {
            return redirect()->to('/admin/products/items/' . $itemId . '/addons')->with('error', 'Add-on tidak ditemukan');
        }

        $rules = [
            'name'  => 'required|min_length[2]|max_length[255]',
            'price' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'        => $this->request->getPost('name'),
            'price'       => $this->request->getPost('price'),
            'is_required' => $this->request->getPost('is_required') ? 1 : 0,
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $addonModel->update($addonId, $data);

        // Update Addon Recipe
        $addonMaterialModel = new \App\Models\AddonMaterialModel();
        $addonMaterialModel->where('addon_id', $addonId)->delete();

        $materials = $this->request->getPost('materials');
        $quantities = $this->request->getPost('quantities');

        if ($materials && is_array($materials)) {
            foreach ($materials as $index => $invId) {
                if (!empty($invId) && isset($quantities[$index]) && $quantities[$index] > 0) {
                    $addonMaterialModel->insert([
                        'addon_id'     => $addonId,
                        'inventory_id' => $invId,
                        'quantity'     => $quantities[$index],
                    ]);
                }
            }
        }

        return redirect()->to('/admin/products/items/' . $itemId . '/addons')->with('success', 'Add-on berhasil diupdate!');
    }

    /**
     * Delete addon
     */
    public function deleteAddon($itemId, $addonId)
    {
        $addonModel = new \App\Models\ProductItemAddonModel();
        
        if ($addonModel->delete($addonId)) {
            return redirect()->to('/admin/products/items/' . $itemId . '/addons')->with('success', 'Add-on berhasil dihapus!');
        }

        return redirect()->back()->with('error', 'Gagal menghapus add-on.');
    }

    // ========================================
    // OFFLINE BOOKINGS
    // ========================================

    /**
     * Show offline booking form
     */
    public function createOfflineBooking()
    {
        $itemModel = new \App\Models\ProductItemModel();
        
        $items = $itemModel->select('product_items.id, product_items.name as item_name, product_items.price, products.name as product_name')
                           ->join('products', 'products.id = product_items.product_id')
                           ->where('product_items.is_active', 1)
                           ->where('products.is_active', 1)
                           ->orderBy('products.name', 'ASC')
                           ->orderBy('product_items.sort_order', 'ASC')
                           ->findAll();
        
        $branchModel = new \App\Models\BranchModel();
        $branches    = $branchModel->getActiveBranches();
        $activeBranchId = $this->getActiveBranchId() ?? 1;

        $savedCustomerModel = new \App\Models\SavedCustomerModel();
        $savedCustomers = $savedCustomerModel->getAllSorted(200);

        $data = [
            'title'          => 'Buat Booking Offline',
            'items'          => $items,
            'branches'       => $branches,
            'activeBranchId' => $activeBranchId,
            'savedCustomers' => $savedCustomers,
        ];
        
        return view('admin/bookings/create', $data);
    }

    /**
     * Store offline booking
     */
    public function storeOfflineBooking()
    {
        $rules = [
            'customer_name' => 'required|min_length[3]',
            'customer_phone' => 'required|min_length[10]',
            'payment_status' => 'required|in_list[pending,paid]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $services = $this->request->getPost('services');
        
        if (empty($services)) {
            return redirect()->back()->withInput()->with('errors', ['Minimal harus ada 1 layanan']);
        }
        
        $orderModel = new \App\Models\OrderModel();
        $orderItemModel = new \App\Models\OrderItemModel();
        $itemModel = new \App\Models\ProductItemModel();
        $addonModel = new \App\Models\ProductItemAddonModel();
        
        // Calculate total
        $totalAmount = 0;
        foreach ($services as $service) {
            if (empty($service['item_id'])) continue;
            
            $item = $itemModel->find($service['item_id']);
            if (!$item) continue;
            
            $totalAmount += $item['price'];
            
            // Add addons price
            if (!empty($service['addons'])) {
                foreach ($service['addons'] as $addonId) {
                    $addon = $addonModel->find($addonId);
                    if ($addon) {
                        $totalAmount += $addon['price'];
                    }
                }
            }
        }
        
        // Create order
        $orderNumber = $orderModel->generateOrderNumber();
        $branchId    = (int) $this->request->getPost('branch_id');
        if (!$branchId) {
            $branchId = $this->getActiveBranchId() ?? 1;
        }
        
        $customerBirthdate = $this->request->getPost('customer_birthdate') ?: null;

        $orderData = [
            'order_number' => $orderNumber,
            'customer_name' => $this->request->getPost('customer_name'),
            'customer_phone' => $this->request->getPost('customer_phone'),
            'customer_birthdate' => $customerBirthdate,
            'customer_email' => $this->request->getPost('customer_email'),
            'notes' => $this->request->getPost('notes'),
            'total_amount' => $totalAmount,
            'subtotal_amount' => $totalAmount,
            'booking_type' => 'offline',
            'branch_id' => $branchId,
            'status' => 'confirmed',
            'payment_status' => $this->request->getPost('payment_status'),
        ];
        
        $orderId = $orderModel->insert($orderData);
        
        if (!$orderId) {
            return redirect()->back()->withInput()->with('errors', ['Gagal membuat booking']);
        }
        
        // Create order items
        foreach ($services as $service) {
            if (empty($service['item_id'])) continue;
            
            $item = $itemModel->find($service['item_id']);
            if (!$item) continue;
            
            $product = (new \App\Models\ProductModel())->find($item['product_id']);
            $itemSubtotal = $item['price'];
            
            // Prepare addons data
            $addonsData = [];
            if (!empty($service['addons'])) {
                foreach ($service['addons'] as $addonId) {
                    $addon = $addonModel->find($addonId);
                    if ($addon) {
                        $itemSubtotal += $addon['price'];
                        $addonsData[] = [
                            'id' => $addon['id'],
                            'name' => $addon['name'],
                            'price' => $addon['price']
                        ];
                    }
                }
            }
            
            $orderItemModel->insert([
                'order_id' => $orderId,
                'product_name' => $product['name'],
                'item_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => 1,
                'subtotal' => $itemSubtotal,
                'booking_date' => $service['booking_date'],
                'booking_time' => $service['booking_time'],
                'addon_items' => !empty($addonsData) ? json_encode($addonsData) : null,
            ]);
        }

        // Auto-save customer to saved_customers with birthdate
        $savedCustomerModel = new \App\Models\SavedCustomerModel();
        $savedCustomerModel->upsertCustomer(
            $this->request->getPost('customer_name'),
            $this->request->getPost('customer_phone'),
            $customerBirthdate
        );
        
        return redirect()->to('/admin/orders/view/' . $orderNumber)->with('success', 'Booking offline berhasil dibuat!');
    }

    // ========================================
    // SAVED CUSTOMERS
    // ========================================

    /**
     * AJAX search saved customers for autocomplete
     */
    public function searchSavedCustomers()
    {
        $q = $this->request->getGet('q') ?? '';
        $savedCustomerModel = new \App\Models\SavedCustomerModel();

        if (strlen(trim($q)) >= 1) {
            $results = $savedCustomerModel->search(trim($q), 15);
        } else {
            $results = $savedCustomerModel->getAllSorted(20);
        }

        return $this->response->setJSON($results);
    }

    // ========================================
    // ORDERS
    // ========================================

        public function orders()
    {
        $orderModel = new \App\Models\OrderModel();
        
        // Get filters from request (Default: NULL = All Data)
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        
        // Filter by branch: check query param first, then active branch in session
        $branchId = $this->request->getGet('branch_id');
        if ($branchId === null || $branchId === '') {
            $branchId = $this->getActiveBranchId();
        } else if ($branchId === 'all') {
            $branchId = null;
        } else {
            $branchId = (int) $branchId;
        }
        
        $orders = $orderModel->getAllOrders($startDate, $endDate, $branchId);
        
        // Calculate Stats based on the QUERY result (filtered or all)
        $totalRevenue = 0;
        $totalOrders = count($orders);
        $totalSuccess = 0;
        
        foreach ($orders as $order) {
            if ($order['status'] !== 'cancelled') {
                $totalRevenue += $order['total_amount'];
                if ($order['status'] === 'completed') {
                    $totalSuccess++;
                }
            }
        }
        
        $savedCustomerModel = new \App\Models\SavedCustomerModel();
        $upcomingBirthdays  = $savedCustomerModel->getUpcomingBirthdays(14);
        $todayBirthdays     = count(array_filter($upcomingBirthdays, fn($c) => $c['days_until_birthday'] === 0));

        $data = [
            'title'             => 'Daftar Pesanan',
            'orders'            => $orders,
            'startDate'         => $startDate,
            'endDate'           => $endDate,
            'upcomingBirthdays' => $upcomingBirthdays,
            'todayBirthdays'    => $todayBirthdays,
            'stats'             => [
                'revenue' => $totalRevenue,
                'total'   => $totalOrders,
                'success' => $totalSuccess
            ]
        ];

        return view('admin/orders/index', $data);
    }

    /**
     * View order detail
     */
    public function viewOrder($orderNumber)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->getOrderByNumber($orderNumber);

        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        // Get items
        $order = $orderModel->getOrderWithItems($order['id']);
        
        // Manually fetch worker name if assigned and not yet set
        if (empty($order['worker_name']) && !empty($order['worker_id'])) {
            $adminModel = new \App\Models\AdminModel();
            $worker = $adminModel->find($order['worker_id']);
            $order['worker_name'] = $worker ? $worker['username'] : null;
        }
        
        // Get Workers
        $adminModel = new \App\Models\AdminModel();
        $workers = $adminModel->where('role', 'worker')->findAll();

        // Get Available Items for "Add Item" feature
        $productItemModel = new \App\Models\ProductItemModel();
        $availableItems = $productItemModel->select('product_items.id, product_items.name as item_name, product_items.price, products.name as product_name')
                           ->join('products', 'products.id = product_items.product_id')
                           ->where('product_items.is_active', 1)
                           ->where('products.is_active', 1)
                           ->orderBy('products.name', 'ASC')
                           ->orderBy('product_items.sort_order', 'ASC')
                           ->findAll();

        // Load inventories for stock usage feature
        $inventoryModel   = new \App\Models\InventoryModel();
        $stockUsageModel  = new \App\Models\OrderStockUsageModel();
        $branchModel      = new \App\Models\BranchModel();

        $orderBranch = !empty($order['branch_id']) ? $branchModel->find($order['branch_id']) : null;
        $branchId    = $orderBranch ? (int)$orderBranch['id'] : 1;

        $inventories = $inventoryModel->getWithBranchStocks($branchId);
        foreach ($inventories as &$inv) {
            $inv['stock'] = $inv['current_branch_stock'] ?? 0;
        }

        $stockUsages = $stockUsageModel
            ->select('order_stock_usages.*, inventories.name as inventory_name, inventories.unit, inventories.sub_unit, inventories.unit_value, branches.name as branch_name')
            ->join('inventories', 'inventories.id = order_stock_usages.inventory_id')
            ->join('branches', 'branches.id = order_stock_usages.branch_id', 'left')
            ->where('order_number', $orderNumber)
            ->orderBy('order_stock_usages.created_at', 'ASC')
            ->findAll();

        // Birthday info detection
        $customerBirthdate = $order['customer_birthdate'] ?? null;
        if (empty($customerBirthdate) && !empty($order['customer_phone'])) {
            $sc = (new \App\Models\SavedCustomerModel())->where('phone', $order['customer_phone'])->first();
            if ($sc && !empty($sc['birthdate'])) {
                $customerBirthdate = $sc['birthdate'];
            }
        }
        $order['customer_birthdate'] = $customerBirthdate;

        $birthdayInfo = null;
        if (!empty($customerBirthdate)) {
            try {
                $bdate = new \DateTime($customerBirthdate);
                $today = new \DateTime(date('Y-m-d'));
                $todayYear = (int)$today->format('Y');
                $bThisYear = new \DateTime(sprintf('%04d-%02d-%02d', $todayYear, (int)$bdate->format('m'), (int)$bdate->format('d')));
                if ($bThisYear < $today) {
                    $bThisYear = new \DateTime(sprintf('%04d-%02d-%02d', $todayYear + 1, (int)$bdate->format('m'), (int)$bdate->format('d')));
                }
                $diff = (int)$today->diff($bThisYear)->format('%r%a');
                if ($diff >= 0 && $diff <= 7) {
                    $birthdayInfo = [
                        'is_today'       => ($diff === 0),
                        'diff_days'      => $diff,
                        'date_formatted' => $bdate->format('d M'),
                        'age_turning'    => (int)$bThisYear->format('Y') - (int)$bdate->format('Y'),
                    ];
                }
            } catch (\Exception $e) {}
        }

        $data = [
            'title'          => 'Detail Order #' . $order['order_number'],
            'order'          => $order,
            'orderBranch'    => $orderBranch,
            'workers'        => $workers,
            'shopSettings'   => $this->getShopSettings(),
            'availableItems' => $availableItems,
            'inventories'    => $inventories,
            'stockUsages'    => $stockUsages,
            'birthdayInfo'   => $birthdayInfo,
        ];

        return view('admin/orders/view', $data);
    }

    /**
     * Assign Worker & Fee (Supports up to 5 workers)
     */
    public function assignWorker($orderNumber)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->getOrderByNumber($orderNumber);

        if (!$order) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Pesanan tidak ditemukan']);
            }
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan');
        }

        $rawIds = $this->request->getPost('worker_ids');
        if (is_string($rawIds)) {
            $rawIds = explode(',', $rawIds);
        } elseif (!is_array($rawIds)) {
            $single = $this->request->getPost('worker_id');
            $rawIds = !empty($single) ? [$single] : [];
        }

        $cleanWorkerIds = [];
        if (is_array($rawIds)) {
            foreach ($rawIds as $id) {
                $idInt = (int)$id;
                if ($idInt > 0 && !in_array($idInt, $cleanWorkerIds)) {
                    $cleanWorkerIds[] = $idInt;
                }
            }
        }

        // Limit to max 5 workers
        $cleanWorkerIds = array_slice($cleanWorkerIds, 0, 5);

        $workerFee = $this->request->getPost('worker_fee');
        $cleanFee = preg_replace('/[^0-9]/', '', (string)$workerFee);

        $primaryWorkerId = !empty($cleanWorkerIds) ? $cleanWorkerIds[0] : null;

        $data = [
            'worker_id'  => $primaryWorkerId,
            'worker_fee' => $cleanFee !== '' ? $cleanFee : 0,
        ];

        $db = \Config\Database::connect();
        if ($db->fieldExists('worker_ids', 'orders')) {
            $data['worker_ids'] = !empty($cleanWorkerIds) ? implode(',', $cleanWorkerIds) : null;
        }

        $orderModel->update($order['id'], $data);

        // Sync order_workers table
        $orderWorkerModel = new \App\Models\OrderWorkerModel();
        $orderWorkerModel->syncOrderWorkers($order['id'], $cleanWorkerIds);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Penugasan pekerja berhasil disimpan.',
                'worker_ids' => $cleanWorkerIds
            ]);
        }

        return redirect()->back()->with('success', 'Penugasan pekerja berhasil disimpan.');
    }

    /**
     * Catat pemakaian stok untuk order completed (AJAX)
     */
    public function storeStockUsage($orderNumber)
    {
        $inventoryId = (int) $this->request->getPost('inventory_id');
        $quantity    = (float) $this->request->getPost('quantity');

        if (!$inventoryId || $quantity < 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak valid']);
        }

        $orderModel = new OrderModel();
        $order = $orderModel->getOrderByNumber($orderNumber);
        if (!$order) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order tidak ditemukan']);
        }
        $branchId = !empty($order['branch_id']) ? (int)$order['branch_id'] : 1;

        $inventoryModel      = new \App\Models\InventoryModel();
        $inventoryStockModel = new \App\Models\InventoryStockModel();
        $inventory = $inventoryModel->find($inventoryId);

        if (!$inventory) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item stok tidak ditemukan']);
        }

        $currentStock = $inventoryStockModel->getStock($inventoryId, $branchId);
        $unitValue = (float)($inventory['unit_value'] ?? 0);

        if ($quantity > 0) {
            if ($unitValue > 0) {
                // Stock dalam pcs, quantity dalam ml/gr → konversi ke pcs
                $pcsToDeduct    = $quantity / $unitValue;
                $availableMl    = $currentStock * $unitValue;

                if ($currentStock < $pcsToDeduct) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Stok di cabang ini tidak mencukupi (tersedia: ' . number_format($currentStock, 2, ',', '.') . ' ' . $inventory['unit'] . ' = ' . number_format($availableMl, 0, ',', '.') . ' ' . ($inventory['sub_unit'] ?? 'ml') . ')',
                    ]);
                }
                $newStock = $currentStock - $pcsToDeduct;
            } else {
                // Tidak ada unit_value: kurangi langsung
                if ($currentStock < $quantity) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Stok di cabang ini tidak mencukupi (tersedia: ' . $currentStock . ' ' . $inventory['unit'] . ')',
                    ]);
                }
                $newStock = $currentStock - $quantity;
            }
        } else {
            // Jika quantity 0, tidak ada pemotongan stok
            $newStock = $currentStock;
        }

        $usageModel = new \App\Models\OrderStockUsageModel();
        $usageModel->insert([
            'order_number' => $orderNumber,
            'inventory_id' => $inventoryId,
            'branch_id'    => $branchId,
            'quantity'     => $quantity,  // simpan dalam sub-unit (ml/gr)
        ]);

        // Update branch stock hanya jika ada pemotongan (quantity > 0)
        if ($quantity > 0) {
            $inventoryStockModel->setStock($inventoryId, $branchId, $newStock);

            // Update global stock summary in inventories table
            $db = \Config\Database::connect();
            $sum = $db->table('inventory_stocks')->where('inventory_id', $inventoryId)->selectSum('stock')->get()->getRow();
            if ($sum) {
                $inventoryModel->update($inventoryId, ['stock' => (float)$sum->stock]);
            }
        }

        $usageId = $usageModel->getInsertID();

        // Label sisa stok untuk response
        if ($unitValue > 0) {
            $fullPcs = floor($newStock);
            $sisaMl = round(($newStock - $fullPcs) * $unitValue);
            
            $stockLabel = $fullPcs . ' ' . $inventory['unit'];
            if ($sisaMl > 0) {
                $stockLabel .= ' dan ' . $sisaMl . ' ' . ($inventory['sub_unit'] ?? 'ml');
            }
        } else {
            $stockLabel = number_format($newStock, 2, ',', '.') . ' ' . $inventory['unit'];
        }

        $usageUnit = ($unitValue > 0 && !empty($inventory['sub_unit'])) ? $inventory['sub_unit'] : $inventory['unit'];

        return $this->response->setJSON([
            'success'        => true,
            'usage_id'       => $usageId,
            'inventory_name' => $inventory['name'],
            'quantity'       => $quantity,
            'unit'           => $usageUnit,
            'stock_left'     => $stockLabel,
            'message'        => 'Pemakaian stok dicatat',
        ]);
    }

    /**
     * Hapus catatan pemakaian stok dan kembalikan stok
     */
    public function deleteStockUsage($usageId)
    {
        $usageModel          = new \App\Models\OrderStockUsageModel();
        $inventoryModel      = new \App\Models\InventoryModel();
        $inventoryStockModel = new \App\Models\InventoryStockModel();

        $usage = $usageModel->find($usageId);

        if (!$usage) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $branchId = !empty($usage['branch_id']) ? (int)$usage['branch_id'] : 1;

        // Kembalikan stok jika kuantitas pemakaian > 0
        if ((float)$usage['quantity'] > 0) {
            $inventory = $inventoryModel->find($usage['inventory_id']);
            if ($inventory) {
                $currentStock = $inventoryStockModel->getStock((int)$usage['inventory_id'], $branchId);
                $unitValue = (float)($inventory['unit_value'] ?? 0);
                if ($unitValue > 0) {
                    $pcsToRestore = $usage['quantity'] / $unitValue;
                    $newStock = $currentStock + $pcsToRestore;
                } else {
                    $newStock = $currentStock + $usage['quantity'];
                }
                $inventoryStockModel->setStock((int)$usage['inventory_id'], $branchId, $newStock);

                // Update total summary in inventories table
                $db = \Config\Database::connect();
                $sum = $db->table('inventory_stocks')->where('inventory_id', $usage['inventory_id'])->selectSum('stock')->get()->getRow();
                if ($sum) {
                    $inventoryModel->update($inventory['id'], ['stock' => (float)$sum->stock]);
                }
            }
        }

        $usageModel->delete($usageId);

        $msg = (float)$usage['quantity'] > 0 ? 'Pemakaian stok dihapus, stok dikembalikan' : 'Catatan pemakaian stok dihapus';
        return $this->response->setJSON(['success' => true, 'message' => $msg]);
    }

    /**
     * Add Item to Existing Order
     */
    public function addItemToOrder($orderNumber)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->getOrderByNumber($orderNumber);

        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan');
        }

        if ($order['status'] === 'completed' || $order['status'] === 'cancelled') {
            return redirect()->back()->with('error', 'Tidak dapat menambah layanan pada pesanan yang sudah selesai/dibatalkan.');
        }

        $itemId = $this->request->getPost('product_item_id');
        
        if (empty($itemId)) {
             return redirect()->back()->with('error', 'Pilih layanan terlebih dahulu.');
        }

        $itemModel = new \App\Models\ProductItemModel();
        // Join with products table to get product name
        $item = $itemModel->select('product_items.*, products.name as product_name')
                          ->join('products', 'products.id = product_items.product_id')
                          ->find($itemId);

        if (!$item) {
             return redirect()->back()->with('error', 'Layanan tidak valid.');
        }

        $bookingDate = $this->request->getPost('booking_date');
        $bookingTime = $this->request->getPost('booking_time');

        // Add to Order Items
        $orderItemModel = new \App\Models\OrderItemModel();
        $orderItemModel->insert([
            'order_id' => $order['id'],
            'product_name' => $item['product_name'], // Snapshot product name
            'item_name' => $item['name'],           // Snapshot item name
            'price' => $item['price'],
            'quantity' => 1,
            'subtotal' => $item['price'],
            'booking_date' => $bookingDate ?: null,
            'booking_time' => $bookingTime ?: null,
        ]);

        // Recalculate Total Order Amount
        $currentTotal = $order['total_amount'];
        $newTotal = $currentTotal + $item['price'];
        
        $orderModel->update($order['id'], ['total_amount' => $newTotal]);

        return redirect()->back()->with('success', 'Layanan berhasil ditambahkan.');
    }

    /**
     * Delete Item from Order
     */
    public function deleteOrderItem($orderNumber, $itemId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->getOrderByNumber($orderNumber);

        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan');
        }

        if ($order['status'] === 'completed' || $order['status'] === 'cancelled') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus layanan pada pesanan yang sudah selesai/dibatalkan.');
        }

        $orderItemModel = new \App\Models\OrderItemModel();
        $item = $orderItemModel->where('order_id', $order['id'])
                            ->where('id', $itemId)
                            ->first();

        if (!$item) {
             return redirect()->back()->with('error', 'Layanan tidak ditemukan pada pesanan ini.');
        }

        // Calculate amount to deduct
        $deductAmount = $item['subtotal'];
        
        // Delete item
        $orderItemModel->delete($itemId);

        // Update Order Total
        $newTotal = $order['total_amount'] - $deductAmount;
        // Ensure not negative (just in case)
        if ($newTotal < 0) $newTotal = 0;
        
        $orderModel->update($order['id'], ['total_amount' => $newTotal]);

        return redirect()->back()->with('success', 'Layanan berhasil dihapus.');
    }

    /**
     * Update order status
     */
    public function updateOrderStatus($orderNumber)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->getOrderByNumber($orderNumber);

        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        $oldStatus = $order['status'];
        $newStatus = $this->request->getPost('status');
        
        if (!in_array($newStatus, ['pending', 'processing', 'completed', 'cancelled'])) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        // State Machine for Stock Management
        // processing/completed = Stock Reduced (Active)
        // pending/cancelled = Stock Available (Inactive/Reserved handling omitted, treating pending as not yet reduced)
        
        $activeStates = ['processing', 'completed'];
        $wasActive = in_array($oldStatus, $activeStates);
        $nowActive = in_array($newStatus, $activeStates);

        if (!$wasActive && $nowActive) {
            $this->adjustStock($order['id'], 'reduce');
        } elseif ($wasActive && !$nowActive) {
            $this->adjustStock($order['id'], 'restore');
        }

        $updateData = ['status' => $newStatus];
        
        $paymentType = $this->request->getPost('payment_type');
        if ($paymentType !== null) {
            $updateData['payment_type'] = $paymentType;
        }
        
        // Track Worker ID
        if (session()->get('role') === 'worker') {
            $workerId = (int) session()->get('admin_id');
            if ($workerId > 0) {
                $updateData['worker_id'] = $workerId;
                $db = \Config\Database::connect();
                if ($db->fieldExists('worker_ids', 'orders') && empty($order['worker_ids'])) {
                    $updateData['worker_ids'] = (string) $workerId;
                }
                $orderWorkerModel = new \App\Models\OrderWorkerModel();
                $existing = $orderWorkerModel->where('order_id', $order['id'])->findAll();
                if (empty($existing)) {
                    $orderWorkerModel->syncOrderWorkers($order['id'], [$workerId]);
                }
            }
        }

        // Update item prices if provided (from invoicing confirmation modal)
        $itemPrices = $this->request->getPost('item_prices');
        $orderItemModel = new \App\Models\OrderItemModel();

        if ($newStatus === 'completed' && !empty($itemPrices) && is_array($itemPrices)) {
            foreach ($itemPrices as $itemId => $rawPrice) {
                $cleanPrice = (float) preg_replace('/[^0-9.]/', '', (string)$rawPrice);
                if ($cleanPrice < 0) $cleanPrice = 0;
                
                $item = $orderItemModel->where('order_id', $order['id'])->where('id', $itemId)->first();
                if ($item) {
                    $qty = (int) ($item['quantity'] ?? 1);
                    if ($qty <= 0) $qty = 1;
                    $subtotal = $cleanPrice * $qty;
                    
                    $orderItemModel->update($itemId, [
                        'price'    => $cleanPrice,
                        'subtotal' => $subtotal,
                    ]);
                }
            }
        }

        // Calculate subtotal from all items in this order
        $allOrderItems = $orderItemModel->where('order_id', $order['id'])->findAll();
        $subtotalAmount = 0;
        foreach ($allOrderItems as $it) {
            $subtotalAmount += (float)$it['subtotal'];
        }
        if ($subtotalAmount <= 0) {
            $subtotalAmount = (float)$order['total_amount'];
        }
        $updateData['subtotal_amount'] = $subtotalAmount;

        // Process Discount if status is completed
        if ($newStatus === 'completed') {
            $discountType = $this->request->getPost('discount_type') ?? ($order['discount_type'] ?? 'none');
            $discountPercent = (float)($this->request->getPost('discount_percent') ?? ($order['discount_percent'] ?? 0));
            $discountAmount = (float)($this->request->getPost('discount_amount') ?? ($order['discount_amount'] ?? 0));

            if ($discountType === 'percent' && $discountPercent > 0) {
                if ($discountPercent > 100) $discountPercent = 100;
                $discountAmount = round(($discountPercent / 100) * $subtotalAmount);
            } elseif ($discountType === 'nominal' && $discountAmount > 0) {
                if ($discountAmount > $subtotalAmount) $discountAmount = $subtotalAmount;
                $discountPercent = ($subtotalAmount > 0) ? round(($discountAmount / $subtotalAmount) * 100, 2) : 0;
            } else {
                $discountType = 'none';
                $discountPercent = 0;
                $discountAmount = 0;
            }

            $updateData['discount_type']    = $discountType;
            $updateData['discount_percent'] = $discountPercent;
            $updateData['discount_amount']  = $discountAmount;
            $updateData['total_amount']     = max(0, $subtotalAmount - $discountAmount);
        } else {
            if (!empty($itemPrices)) {
                $updateData['total_amount'] = $subtotalAmount;
            }
        }

        $orderModel->update($order['id'], $updateData);

        // Flashdata for Invoice Modal
        if ($newStatus === 'completed') {
            session()->setFlashdata('show_invoice', true);
        }

        return redirect()->to('/admin/orders/view/' . $orderNumber)->with('success', 'Status berhasil diupdate!');
    }

    /**
     * Update order discount directly (from Invoice modal or view)
     */
    public function updateOrderDiscount($orderNumber)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->getOrderByNumber($orderNumber);

        if (!$order) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Pesanan tidak ditemukan'])->setStatusCode(404);
            }
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        $orderItemModel = new \App\Models\OrderItemModel();
        $allItems = $orderItemModel->where('order_id', $order['id'])->findAll();
        $subtotal = 0;
        foreach ($allItems as $it) {
            $subtotal += (float)$it['subtotal'];
        }
        if ($subtotal <= 0) {
            $subtotal = (float)$order['total_amount'];
        }

        $discountType = $this->request->getPost('discount_type') ?? 'none';
        $discountPercent = (float)($this->request->getPost('discount_percent') ?? 0);
        $discountAmount = (float)($this->request->getPost('discount_amount') ?? 0);

        if ($discountType === 'percent' && $discountPercent > 0) {
            if ($discountPercent > 100) $discountPercent = 100;
            $discountAmount = round(($discountPercent / 100) * $subtotal);
        } elseif ($discountType === 'nominal' && $discountAmount > 0) {
            if ($discountAmount > $subtotal) $discountAmount = $subtotal;
            $discountPercent = ($subtotal > 0) ? round(($discountAmount / $subtotal) * 100, 2) : 0;
        } else {
            $discountType = 'none';
            $discountPercent = 0;
            $discountAmount = 0;
        }

        $finalTotal = max(0, $subtotal - $discountAmount);

        $orderModel->update($order['id'], [
            'subtotal_amount'  => $subtotal,
            'discount_type'    => $discountType,
            'discount_percent' => $discountPercent,
            'discount_amount'  => $discountAmount,
            'total_amount'     => $finalTotal,
        ]);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'             => 'success',
                'subtotal'           => $subtotal,
                'discount_type'      => $discountType,
                'discount_percent'   => $discountPercent,
                'discount_amount'    => $discountAmount,
                'total_amount'       => $finalTotal,
                'formatted_subtotal' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
                'formatted_discount' => 'Rp ' . number_format($discountAmount, 0, ',', '.'),
                'formatted_total'    => 'Rp ' . number_format($finalTotal, 0, ',', '.'),
            ]);
        }

        return redirect()->to('/admin/orders/view/' . $orderNumber)->with('success', 'Diskon berhasil diperbarui!');
    }

    /**
     * Generate secure HMAC token for public e-receipt access
     */
    public static function getReceiptToken(string $orderNumber, ?string $createdAt = null): string
    {
        $encKey = config('Encryption')->key;
        $salt = !empty($encKey) ? $encKey : 'LaHairStudioAppsReceiptSaltSecret2026';
        return substr(hash_hmac('sha256', $orderNumber . '|' . ($createdAt ?? ''), $salt), 0, 10);
    }

    /**
     * E-Receipt View (Public Access with Token)
     */
    public function ereceipt($orderNumber, $tokenParam = null)
    {
        // Public access allowed for receipt sharing
        $orderModel = new OrderModel();
        
        $order = $orderModel->getOrderByNumber($orderNumber);
        if (!$order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Security Check: Akses publik wajib menyertakan token unik yang cocok spesifik per nomor invoice
        $expectedToken = self::getReceiptToken($order['order_number'], $order['created_at']);
        $token = $this->request->getGet('token') ?: $tokenParam;

        if (!$token || !hash_equals($expectedToken, (string)$token)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Akses struk tidak valid.');
        }

        // Get items with details
        $order = $orderModel->getOrderWithItems($order['id']);
        
        // Manually fetch worker name if assigned
        if (!empty($order['worker_id'])) {
            $adminModel = new \App\Models\AdminModel();
            $worker = $adminModel->find($order['worker_id']);
            $order['worker_name'] = $worker ? $worker['username'] : null;
        }

        $receiptToken = self::getReceiptToken($order['order_number'], $order['created_at']);
        $receiptUrl   = base_url('ereceipt/' . $order['order_number'] . '?token=' . $receiptToken);

        $data = [
            'order'        => $order,
            'title'        => 'Struk #' . $order['order_number'],
            'receiptToken' => $receiptToken,
            'receiptUrl'   => $receiptUrl,
        ];

        return view('admin/orders/ereceipt', $data);
    }

    // ========================================
    // SETTINGS
    // ========================================

    /**
     * Settings page
     */
    public function settings()
    {
        $this->ensureSuperAdmin();

        $data = [
            'title'        => 'Pengaturan',
            'profileBio'   => $this->getBio(),
            'socialLinks'  => $this->getSocialLinks(),
            'terms'        => $this->getTerms(),
            'shopSettings' => $this->getShopSettings(),
        ];

        return view('admin/settings', $data);
    }

    /**
     * Get Shop Settings (Opening Hours, Theme)
     */
    private function getShopSettings(): array
    {
        $configPath = WRITEPATH . 'shop_settings.json';
        if (file_exists($configPath)) {
            return json_decode(file_get_contents($configPath), true) ?? [];
        }
        return [
            'start_time' => '09:00',
            'end_time'   => '20:00',
            'theme'      => 'light'
        ];
    }

    /**
     * Save Shop Settings
     */
    public function saveShopSettings()
    {
        $this->ensureSuperAdmin();
        
        $settings = [
            'start_time' => $this->request->getPost('start_time'),
            'end_time'   => $this->request->getPost('end_time'),
            'theme'      => $this->request->getPost('theme') ?? 'light',
            'commission_percent' => $this->request->getPost('commission_percent') ?? 0,
        ];
        
        $configPath = WRITEPATH . 'shop_settings.json';
        file_put_contents($configPath, json_encode($settings, JSON_PRETTY_PRINT));
        
        return redirect()->to('/admin/settings')->with('success', 'Pengaturan Toko berhasil disimpan!');
    }





    /**
     * Save Profile Bio
     */
    public function saveBio()
    {
        $bio = $this->request->getPost('profile_bio');
        $configPath = WRITEPATH . 'profile_bio.txt';
        file_put_contents($configPath, $bio);
        
        return redirect()->to('/admin/settings')->with('success', 'Profil Bio berhasil disimpan!');
    }

    /**
     * Save Social Links
     */
    public function saveSocialLinks()
    {
        $links = [
            'tiktok'    => $this->request->getPost('tiktok_url'),
            'instagram' => $this->request->getPost('instagram_url'),
        ];
        
        $configPath = WRITEPATH . 'social_links.json';
        file_put_contents($configPath, json_encode($links));
        
        return redirect()->to('/admin/settings')->with('success', 'Link Media Sosial berhasil disimpan!');
    }

    /**
     * Save Terms and Conditions
     */
    public function saveTerms()
    {
        $terms = $this->request->getPost('terms_conditions');
        $configPath = WRITEPATH . 'terms_conditions.txt';
        file_put_contents($configPath, $terms);
        
        return redirect()->to('/admin/settings')->with('success', 'Syarat & Ketentuan berhasil disimpan!');
    }











    /**
     * Get Profile Bio
     */
    private function getBio(): string
    {
        $configPath = WRITEPATH . 'profile_bio.txt';
        if (file_exists($configPath)) {
            return trim(file_get_contents($configPath));
        }
        return 'La Hair Studio - Professional Hair Care & Styling'; // Default
    }

    /**
     * Get Social Links
     */
    private function getSocialLinks(): array
    {
        $configPath = WRITEPATH . 'social_links.json';
        if (file_exists($configPath)) {
            return json_decode(file_get_contents($configPath), true) ?? [];
        }
        return [
            'tiktok'    => 'https://www.tiktok.com/@lahairstudio',
            'instagram' => 'https://www.instagram.com/lahairstudio'
        ];
    }

    /**
     * Get Terms and Conditions
     */
    private function getTerms(): string
    {
        $configPath = WRITEPATH . 'terms_conditions.txt';
        if (file_exists($configPath)) {
            return file_get_contents($configPath);
        }
        return "Silakan isi Syarat & Ketentuan di panel admin."; // Default
    }




    // ========================================
    // ADMIN MANAGEMENT
    // ========================================

    /**
     * List all admins
     */
    public function admins()
    {
        $this->ensureSuperAdmin();
        $adminModel = new \App\Models\AdminModel();
        
        $search = $this->request->getGet('search');
        $builder = $adminModel;

        if ($search) {
            $builder = $builder->groupStart()
                              ->like('username', $search)
                              ->orLike('email', $search)
                              ->groupEnd();
        }

        $admins = $builder->orderBy('created_at', 'DESC')->paginate(20, 'admins');
        
        $data = [
            'title'  => 'Kelola Admin & Worker',
            'admins' => $admins,
            'pager'  => $adminModel->pager,
            'search' => $search,
        ];

        return view('admin/admins/index', $data);
    }

    /**
     * Show create admin form
     */
    public function createAdmin()
    {
        $this->ensureSuperAdmin();
        
        $data = [
            'title' => 'Tambah Admin/Worker',
        ];

        return view('admin/admins/form', $data);
    }

    /**
     * Store new admin
     */
    public function storeAdmin()
    {
        $this->ensureSuperAdmin();
        $adminModel = new \App\Models\AdminModel();

        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[admins.username]',
            'email'    => 'required|valid_email|is_unique[admins.email]',
            'password' => 'required|exact_length[6]|numeric',
            'role'     => 'required|in_list[admin,worker]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'), // Will be hashed by model
            'role'     => $this->request->getPost('role'),
        ];

        if ($adminModel->insert($data)) {
            return redirect()->to('/admin/admins')->with('success', 'Admin berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan admin.');
    }

    /**
     * Show edit admin form
     */
    public function editAdmin($id)
    {
        $this->ensureSuperAdmin();
        $adminModel = new \App\Models\AdminModel();
        $admin = $adminModel->find($id);

        if (!$admin) {
            return redirect()->to('/admin/admins')->with('error', 'Admin tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Admin: ' . $admin['username'],
            'admin' => $admin,
        ];

        return view('admin/admins/form', $data);
    }

    /**
     * Update admin
     */
    public function updateAdmin($id)
    {
        $this->ensureSuperAdmin();
        $adminModel = new \App\Models\AdminModel();
        $admin = $adminModel->find($id);

        if (!$admin) {
            return redirect()->to('/admin/admins')->with('error', 'Admin tidak ditemukan');
        }

        $rules = [
            'username' => "required|min_length[3]|max_length[50]|is_unique[admins.username,id,$id]",
            'email'    => "required|valid_email|is_unique[admins.email,id,$id]",
            'role'     => 'required|in_list[admin,worker]',
        ];

        // Password is optional for update
        if ($this->request->getPost('password')) {
            $rules['password'] = 'exact_length[6]|numeric';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role'     => $this->request->getPost('role'),
        ];

        // Only update password if provided
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        if ($adminModel->update($id, $data)) {
            return redirect()->to('/admin/admins')->with('success', 'Admin berhasil diupdate!');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengupdate admin.');
    }

    /**
     * Delete admin
     */
    public function deleteAdmin($id)
    {
        $this->ensureSuperAdmin();
        $adminModel = new \App\Models\AdminModel();
        
        // Prevent deleting self
        if ($id == session()->get('admin_id')) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        if ($adminModel->delete($id)) {
            return redirect()->to('/admin/admins')->with('success', 'Admin berhasil dihapus!');
        }

        return redirect()->back()->with('error', 'Gagal menghapus admin.');
    }
    private function adjustStock($orderId, $action)
    {
        $db = \Config\Database::connect();
        $orderItemModel = new \App\Models\OrderItemModel();
        $materialModel = new \App\Models\ProductItemMaterialModel();
        $addonMaterialModel = new \App\Models\AddonMaterialModel();
        
        $items = $orderItemModel->where('order_id', $orderId)->findAll();
        
        foreach ($items as $item) {
            // Find Product
            $product = $db->table('products')->where('name', $item['product_name'])->get()->getRow();
            
            if ($product) {
                // Find Product Item
                $pItem = $db->table('product_items')
                            ->select('id, sold')
                            ->where('product_id', $product->id)
                            ->where('name', $item['item_name'])
                            ->get()->getRow();
                            
                if ($pItem) {
                    $qty = $item['quantity'];
                    
                    // Update Sold Count
                    $currentSold = $pItem->sold;
                    
                    if ($action === 'reduce') {
                        $newSold = $currentSold + $qty;
                    } else { // restore
                        $newSold = max(0, $currentSold - $qty);
                    }
                    
                    $db->table('product_items')->where('id', $pItem->id)->update([
                        'sold'  => $newSold
                    ]);
                    
                    // Adjust Inventory Stock based on Product Item Recipe
                    $materials = $materialModel->where('product_item_id', $pItem->id)->findAll();
                    
                    foreach ($materials as $mat) {
                        $inventory = $db->table('inventories')->where('id', $mat['inventory_id'])->get()->getRow();
                        if ($inventory) {
                            $usage = $mat['quantity'] * $qty;
                            $unitValue = (float)($inventory->unit_value ?? 0);
                            $deduct = $unitValue > 0 ? ($usage / $unitValue) : $usage;
                            
                            if ($action === 'reduce') {
                                $newInvStock = $inventory->stock - $deduct;
                            } else {
                                $newInvStock = $inventory->stock + $deduct;
                            }
                            
                            $db->table('inventories')->where('id', $inventory->id)->update(['stock' => $newInvStock]);
                        }
                    }
                }
            }
            
            // Adjust Inventory Stock based on Add-on Recipes
            if (!empty($item['addon_items'])) {
                $addons = json_decode($item['addon_items'], true);
                if (is_array($addons)) {
                    foreach ($addons as $addon) {
                        $addonId = $addon['id'] ?? null;
                        if (!$addonId) continue;
                        
                        $addonMats = $addonMaterialModel->where('addon_id', $addonId)->findAll();
                        foreach ($addonMats as $mat) {
                            $inventory = $db->table('inventories')->where('id', $mat['inventory_id'])->get()->getRow();
                            if ($inventory) {
                                $qty = $item['quantity'];
                                $usage = $mat['quantity'] * $qty;
                                $unitValue = (float)($inventory->unit_value ?? 0);
                                $deduct = $unitValue > 0 ? ($usage / $unitValue) : $usage;
                                
                                if ($action === 'reduce') {
                                    $newInvStock = $inventory->stock - $deduct;
                                } else {
                                    $newInvStock = $inventory->stock + $deduct;
                                }
                                
                                $db->table('inventories')->where('id', $inventory->id)->update(['stock' => $newInvStock]);
                            }
                        }
                    }
                }
            }
        }
    }

    // ===================================
    // KELOLA GUDANG / INVENTORY
    // ===================================

    /**
     * List Inventory
     */
    public function inventory()
    {
        $this->ensureSuperAdmin();
        $inventoryModel = new \App\Models\InventoryModel();
        $branchModel    = new \App\Models\BranchModel();
        $branches       = $branchModel->getActiveBranches();

        $selectedBranchId = $this->request->getGet('branch_id');
        if ($selectedBranchId === null || $selectedBranchId === '') {
            $selectedBranchId = $this->getActiveBranchId();
        } else if ($selectedBranchId === 'all') {
            $selectedBranchId = null;
        } else {
            $selectedBranchId = (int) $selectedBranchId;
        }

        $inventories = $inventoryModel->getWithBranchStocks($selectedBranchId);
        
        $data = [
            'title'            => 'Gudang Stok',
            'branches'         => $branches,
            'selectedBranchId' => $selectedBranchId,
            'inventories'      => $inventories,
        ];
        
        return view('admin/inventory/index', $data);
    }
    
    /**
     * Get inventory usage history (AJAX)
     */
    public function inventoryHistory($id)
    {
        $this->ensureSuperAdmin();

        $inventoryModel = new \App\Models\InventoryModel();
        $inventory = $inventoryModel->find($id);

        if (!$inventory) {
            return $this->response->setJSON(['success' => false, 'message' => 'Bahan tidak ditemukan']);
        }

        $usageModel = new \App\Models\OrderStockUsageModel();
        $usages = $usageModel
            ->select('order_stock_usages.*, orders.customer_name, orders.customer_phone, orders.status as order_status, branches.name as branch_name')
            ->join('orders', 'orders.order_number = order_stock_usages.order_number', 'left')
            ->join('branches', 'branches.id = order_stock_usages.branch_id', 'left')
            ->where('order_stock_usages.inventory_id', $id)
            ->orderBy('order_stock_usages.created_at', 'DESC')
            ->findAll();

        $hasSubUnit = !empty($inventory['unit_value']) && (float)$inventory['unit_value'] > 0;
        $unitDisplay = ($hasSubUnit && !empty($inventory['sub_unit'])) ? $inventory['sub_unit'] : $inventory['unit'];

        $formattedData = [];
        foreach ($usages as $u) {
            $branchLabel = !empty($u['branch_name']) ? " [{$u['branch_name']}]" : '';
            $formattedData[] = [
                'id'             => $u['id'],
                'date'           => !empty($u['created_at']) ? date('d M Y, H:i', strtotime($u['created_at'])) : '-',
                'order_number'   => $u['order_number'] . $branchLabel,
                'customer_name'  => !empty($u['customer_name']) ? $u['customer_name'] : 'Offline / Walk-in',
                'customer_phone' => $u['customer_phone'] ?? '',
                'quantity'       => rtrim(rtrim(number_format((float)$u['quantity'], 2, ',', '.'), '0'), ','),
                'unit'           => $unitDisplay,
            ];
        }

        return $this->response->setJSON([
            'success'   => true,
            'item_name' => $inventory['name'],
            'unit'      => $unitDisplay,
            'total'     => count($formattedData),
            'data'      => $formattedData,
        ]);
    }
    
    /**
     * Store new inventory item
     */
    public function storeInventory()
    {
        $this->ensureSuperAdmin();
        $inventoryModel      = new \App\Models\InventoryModel();
        $inventoryStockModel = new \App\Models\InventoryStockModel();
        $branchModel         = new \App\Models\BranchModel();
        $branches            = $branchModel->getActiveBranches();
        
        $unitValue = $this->request->getPost('unit_value');
        $data = [
            'name'       => $this->request->getPost('name'),
            'stock'      => 0,
            'unit'       => $this->request->getPost('unit'),
            'sub_unit'   => $this->request->getPost('sub_unit') ?? $this->request->getPost('unit'),
            'unit_value' => ($unitValue !== null && $unitValue !== '') ? (float)$unitValue : null,
        ];
        
        $inventoryId = $inventoryModel->insert($data);
        if ($inventoryId) {
            $totalStock = 0;
            foreach ($branches as $branch) {
                // Check if specific branch stock posted, e.g. stock_1, stock_2 or default to single 'stock' input
                $branchStockInput = $this->request->getPost('stock_' . $branch['id']);
                if ($branchStockInput === null && $branch['code'] === 'semarang') {
                    $branchStockInput = $this->request->getPost('stock'); // fallback to general stock field for primary branch
                }
                $bStock = (float)($branchStockInput ?? 0);
                $inventoryStockModel->setStock($inventoryId, $branch['id'], $bStock);
                $totalStock += $bStock;
            }
            $inventoryModel->update($inventoryId, ['stock' => $totalStock]);
        }

        return redirect()->to('/admin/inventory')->with('success', 'Stok berhasil ditambahkan');
    }
    
    /**
     * Update inventory item
     */
    public function updateInventory($id)
    {
        $this->ensureSuperAdmin();
        $inventoryModel      = new \App\Models\InventoryModel();
        $inventoryStockModel = new \App\Models\InventoryStockModel();
        $branchModel         = new \App\Models\BranchModel();
        $branches            = $branchModel->getActiveBranches();
        
        $unitValue = $this->request->getPost('unit_value');
        
        $data = [];
        if ($this->request->getPost('name') !== null) {
            $data['name'] = $this->request->getPost('name');
        }
        if ($this->request->getPost('unit') !== null) {
            $data['unit'] = $this->request->getPost('unit');
        }
        if ($this->request->getPost('sub_unit') !== null) {
            $data['sub_unit'] = $this->request->getPost('sub_unit');
        }
        if ($unitValue !== null) {
            $data['unit_value'] = ($unitValue !== '') ? (float)$unitValue : null;
        }

        // Branch specific stock update
        $branchId = $this->request->getPost('branch_id');
        $branchStock = $this->request->getPost('branch_stock');

        if ($branchId !== null && $branchStock !== null) {
            $inventoryStockModel->setStock((int)$id, (int)$branchId, (float)$branchStock);
        } else {
            // Check individual branch fields (stock_1, stock_2, etc.)
            foreach ($branches as $b) {
                $bStockVal = $this->request->getPost('stock_' . $b['id']);
                if ($bStockVal !== null) {
                    $inventoryStockModel->setStock((int)$id, (int)$b['id'], (float)$bStockVal);
                }
            }
        }

        // Recalculate total stock from all branches
        $db = \Config\Database::connect();
        $sum = $db->table('inventory_stocks')->where('inventory_id', $id)->selectSum('stock')->get()->getRow();
        $totalStock = $sum ? (float)$sum->stock : 0;
        $data['stock'] = $totalStock;
        
        $inventoryModel->update($id, $data);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success'     => true,
                'message'     => 'Stok berhasil diupdate',
                'total_stock' => $totalStock,
            ]);
        }

        return redirect()->to('/admin/inventory')->with('success', 'Stok berhasil diupdate');
    }
    
    /**
     * Delete inventory item
     */
    public function deleteInventory($id)
    {
        $this->ensureSuperAdmin();
        $inventoryModel = new \App\Models\InventoryModel();
        
        // TODO: Check if used in recipes?
        
        $inventoryModel->delete($id);
        return redirect()->to('/admin/inventory')->with('success', 'Stok berhasil dihapus');
    }

    /**
     * Import Inventory from CSV
     */
    public function importInventory()
    {
        $this->ensureSuperAdmin();
        $file = $this->request->getFile('csv_file');
        
        if (!$file || !$file->isValid()) {
            return redirect()->to('/admin/inventory')->with('error', 'File tidak valid');
        }

        if ($file->getExtension() !== 'csv') {
            return redirect()->to('/admin/inventory')->with('error', 'Mohon upload file .csv');
        }

        $handle = fopen($file->getTempName(), 'r');
        $headers = fgetcsv($handle); // Skip header

        $countInsert = 0;
        $countUpdate = 0;
        
        $inventoryModel = new \App\Models\InventoryModel();
        
        // Sometimes Excel exports use ';' as separator. Let's try to detect it.
        // But for simplicity, we assume standard CSV with proper delimitation handle by fgetcsv.
        
        while (($row = fgetcsv($handle)) !== FALSE) {
            // Count might be 1 if it's a single string separated by semicolons due to wrong format
            if (count($row) === 1 && strpos($row[0], ';') !== false) {
                $row = explode(';', $row[0]);
            }
            
            if (count($row) < 3) continue; // Basic validation (ID, Name, Stock required min)

            $id = isset($row[0]) ? trim($row[0]) : '';
            $name = isset($row[1]) ? trim($row[1]) : '';
            
            // Handle Stock (prevent issues if it contains commas or strings)
            $stockRaw = isset($row[2]) ? $row[2] : '0';
            $stockClean = preg_replace('/[^0-9\-\.]/', '', str_replace(',', '.', $stockRaw));
            $stock = (float) $stockClean;
            
            $unit = isset($row[3]) && trim($row[3]) !== '' ? trim($row[3]) : 'botol';
            $sub_unit = isset($row[4]) && trim($row[4]) !== '' ? trim($row[4]) : 'ml';
            
            $unitValueRaw = isset($row[5]) ? $row[5] : '';
            $unitValueClean = preg_replace('/[^0-9\-\.]/', '', str_replace(',', '.', $unitValueRaw));
            $unitValue = $unitValueClean !== '' ? (float) $unitValueClean : null;

            if (empty($name)) continue;

            $data = [
                'name'       => $name,
                'stock'      => $stock,
                'unit'       => $unit,
                'sub_unit'   => $sub_unit,
                'unit_value' => $unitValue,
            ];

            if (!empty($id) && is_numeric($id)) {
                $existingItem = $inventoryModel->find($id);
                if ($existingItem) {
                    $inventoryModel->update($id, $data);
                    $countUpdate++;
                } else {
                    $inventoryModel->insert($data);
                    $countInsert++;
                }
            } else {
                $inventoryModel->insert($data);
                $countInsert++;
            }
        }
        fclose($handle);

        $msg = "Import selesai: $countInsert ditambah, $countUpdate diupdate.";
        return redirect()->to('/admin/inventory')->with('success', $msg);
    }

    /**
     * Export Inventory CSV Template
     */
    public function downloadInventoryTemplate()
    {
        $this->ensureSuperAdmin();
        $inventoryModel = new \App\Models\InventoryModel();
        
        $items = $inventoryModel->findAll();

        $filename = "export_inventory_" . date('Ymd_His') . ".csv";
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, ['ID (JANGAN UBAH)', 'Nama Stok', 'Jumlah Stok', 'Kemasan', 'Satuan Isi', 'Isi / Kemasan']);
        
        foreach ($items as $item) {
            fputcsv($output, [
                $item['id'],
                $item['name'],
                $item['stock'],
                $item['unit'],
                $item['sub_unit'] ?? '',
                $item['unit_value'] ?? ''
            ]);
        }
        fclose($output);
        exit();
    }

    // ===================================
    // LAPORAN KEUANGAN
    // ===================================

    /**
     * Financial Reports Page
     */
    public function financialReports()
    {
        $this->ensureSuperAdmin();
        $branchModel = new \App\Models\BranchModel();
        
        $data = [
            'title'          => 'Laporan Keuangan',
            'branches'       => $branchModel->getActiveBranches(),
            'activeBranchId' => $this->getActiveBranchId(),
        ];
        
        return view('admin/reports/financial', $data);
    }

    /**
     * Get Revenue Data (API)
     */
    public function getRevenueData()
    {
        $this->ensureSuperAdmin();
        
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');
        
        $branchId = $this->request->getGet('branch_id');
        if ($branchId === null || $branchId === '') {
            $branchId = $this->getActiveBranchId();
        } else if ($branchId === 'all') {
            $branchId = null;
        } else {
            $branchId = (int) $branchId;
        }

        $orderModel = new \App\Models\OrderModel();
        
        // Total revenue from completed/processing orders
        $revBuilder = $orderModel->selectSum('total_amount')
            ->whereIn('status', ['completed', 'processing', 'confirmed'])
            ->where('DATE(created_at) >=', $startDate)
            ->where('DATE(created_at) <=', $endDate);
        if ($branchId) {
            $revBuilder->where('branch_id', $branchId);
        }
        $revenueQuery = $revBuilder->first();
        
        $totalRevenue = $revenueQuery['total_amount'] ?? 0;
        
        // Daily breakdown with online and offline sums
        $dailyBuilder = $orderModel->select("
                DATE(created_at) as date, 
                SUM(total_amount) as total, 
                COUNT(*) as order_count,
                SUM(CASE WHEN booking_type = 'online' THEN total_amount ELSE 0 END) as online_total,
                SUM(CASE WHEN booking_type != 'online' OR booking_type IS NULL THEN total_amount ELSE 0 END) as offline_total
            ")
            ->whereIn('status', ['completed', 'processing', 'confirmed'])
            ->where('DATE(created_at) >=', $startDate)
            ->where('DATE(created_at) <=', $endDate);
        if ($branchId) {
            $dailyBuilder->where('branch_id', $branchId);
        }
        $dailyRevenue = $dailyBuilder->groupBy('DATE(created_at)')
            ->orderBy('date', 'DESC')
            ->findAll();

        // Get detailed orders within date range for daily breakdown list
        $ordersBuilder = $orderModel->select('id, order_number, customer_name, customer_phone, total_amount, booking_type, status, payment_type, payment_status, created_at, DATE(created_at) as order_date')
            ->whereIn('status', ['completed', 'processing', 'confirmed'])
            ->where('DATE(created_at) >=', $startDate)
            ->where('DATE(created_at) <=', $endDate);
        if ($branchId) {
            $ordersBuilder->where('branch_id', $branchId);
        }
        $orders = $ordersBuilder->orderBy('created_at', 'DESC')
            ->findAll();

        $orderIds = array_column($orders, 'id');
        $itemsByOrder = [];
        if (!empty($orderIds)) {
            $orderItemModel = new \App\Models\OrderItemModel();
            $items = $orderItemModel->whereIn('order_id', $orderIds)->findAll();
            foreach ($items as $item) {
                $serviceName = $item['product_name'] . (!empty($item['item_name']) ? ' (' . $item['item_name'] . ')' : '');
                $itemsByOrder[$item['order_id']][] = $serviceName;
            }
        }

        $ordersByDate = [];
        foreach ($orders as $ord) {
            $dateKey = $ord['order_date'];
            $ordersByDate[$dateKey][] = [
                'order_number'   => $ord['order_number'],
                'customer_name'  => !empty($ord['customer_name']) ? $ord['customer_name'] : 'Walk-in / Offline',
                'customer_phone' => $ord['customer_phone'] ?? '',
                'time'           => date('H:i', strtotime($ord['created_at'])),
                'booking_type'   => $ord['booking_type'] ?? 'offline',
                'status'         => $ord['status'],
                'payment_type'   => $ord['payment_type'] ?? '-',
                'services'       => !empty($itemsByOrder[$ord['id']]) ? implode(', ', $itemsByOrder[$ord['id']]) : '-',
                'total_amount'   => (float)$ord['total_amount'],
            ];
        }

        $daysIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $monthsIndo = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $formattedDaily = [];
        foreach ($dailyRevenue as $day) {
            $time = strtotime($day['date']);
            $dayName = $daysIndo[date('w', $time)];
            $dayNum = date('j', $time);
            $monthName = $monthsIndo[(int)date('n', $time)];
            $year = date('Y', $time);
            $formattedDate = "{$dayName}, {$dayNum} {$monthName} {$year}";

            $formattedDaily[] = [
                'date'           => $day['date'],
                'formatted_date' => $formattedDate,
                'order_count'    => (int)$day['order_count'],
                'total'          => (float)$day['total'],
                'online_total'   => (float)($day['online_total'] ?? 0),
                'offline_total'  => (float)($day['offline_total'] ?? 0),
                'orders'         => $ordersByDate[$day['date']] ?? [],
            ];
        }
        
        // Count orders by status
        $statusBuilder = $orderModel->select('status, COUNT(*) as count')
            ->where('DATE(created_at) >=', $startDate)
            ->where('DATE(created_at) <=', $endDate);
        if ($branchId) {
            $statusBuilder->where('branch_id', $branchId);
        }
        $ordersByStatus = $statusBuilder->groupBy('status')
            ->findAll();
        
        // Revenue by booking type
        $typeBuilder = $orderModel->select('booking_type, SUM(total_amount) as total')
            ->whereIn('status', ['completed', 'processing', 'confirmed'])
            ->where('DATE(created_at) >=', $startDate)
            ->where('DATE(created_at) <=', $endDate);
        if ($branchId) {
            $typeBuilder->where('branch_id', $branchId);
        }
        $revenueByType = $typeBuilder->groupBy('booking_type')
            ->findAll();
        
        return $this->response->setJSON([
            'total_revenue'    => $totalRevenue,
            'daily_breakdown'  => $formattedDaily,
            'orders_by_status' => $ordersByStatus,
            'revenue_by_type'  => $revenueByType,
        ]);
    }

    /**
     * Export Financial Report to Excel
     */
    public function exportFinancialReport()
    {
        $this->ensureSuperAdmin();
        
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');
        
        $branchId = $this->request->getGet('branch_id');
        if ($branchId === null || $branchId === '') {
            $branchId = $this->getActiveBranchId();
        } else if ($branchId === 'all') {
            $branchId = null;
        } else {
            $branchId = (int) $branchId;
        }

        $orderModel = new \App\Models\OrderModel();
        $orderItemModel = new \App\Models\OrderItemModel();
        
        // Get all orders in date range with branch join
        $builder = $orderModel->select('orders.*, branches.name as branch_name')
            ->join('branches', 'branches.id = orders.branch_id', 'left')
            ->where('DATE(orders.created_at) >=', $startDate)
            ->where('DATE(orders.created_at) <=', $endDate);
        if ($branchId) {
            $builder->where('orders.branch_id', $branchId);
        }
        $orders = $builder->orderBy('orders.created_at', 'DESC')->findAll();
        
        // Create CSV content
        $filename = 'Laporan_Keuangan_' . $startDate . '_to_' . $endDate . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Add BOM for Excel UTF-8 support
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Header row
        fputcsv($output, [
            'Tanggal',
            'No. Order',
            'Cabang',
            'Customer',
            'Telepon',
            'Tipe Booking',
            'Status',
            'Total',
            'Layanan'
        ]);
        
        // Data rows
        foreach ($orders as $order) {
            // Get order items
            $items = $orderItemModel->where('order_id', $order['id'])->findAll();
            $services = [];
            foreach ($items as $item) {
                $services[] = $item['product_name'] . ' - ' . $item['item_name'];
            }
            
            fputcsv($output, [
                date('d/m/Y H:i', strtotime($order['created_at'])),
                $order['order_number'],
                $order['branch_name'] ?? 'Pusat/Semarang',
                $order['customer_name'] ?? 'Walk-in / Offline',
                $order['customer_phone'] ?? '',
                $order['booking_type'] ?? 'online',
                ucfirst($order['status']),
                number_format($order['total_amount'], 0, ',', '.'),
                implode('; ', $services)
            ]);
        }
        
        fclose($output);
        exit;
    }
}
