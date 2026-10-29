<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ProductItemModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;


class Home extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();


        
        $data = [
            'productsByCategory' => $productModel->getActiveProductsByCategory(),
            'profileBio'         => $this->getBio(),
            'socialLinks'        => $this->getSocialLinks(),
        ];
        
        return view('lynk/index', $data);
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
     * Product detail page - shows all prices
     */
    public function product($slug)
    {
        $productModel = new ProductModel();
        $itemModel = new ProductItemModel();
        
        $product = $productModel->where('slug', $slug)
                                ->where('is_active', 1)
                                ->first();
        
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $data = [
            'product'     => $product,
            'items'       => $itemModel->getByProduct($product['id']),
            'socialLinks' => $this->getSocialLinks(),
            'profileBio'  => $this->getBio(),
        ];
        
        return view('lynk/product', $data);
    }

    /**
     * API: Get add-ons for a product item with stock availability status
     */
    public function getItemAddons($itemId)
    {
        $addonModel         = new \App\Models\ProductItemAddonModel();
        $addonMaterialModel = new \App\Models\AddonMaterialModel();
        $inventoryModel     = new \App\Models\InventoryModel();
        $stockModel         = new \App\Models\InventoryStockModel();
        $branchModel        = new \App\Models\BranchModel();
        
        $branchId = $this->request->getGet('branch_id');
        $city = $this->request->getGet('city');
        if (!$branchId && $city) {
            $b = $branchModel->getByCode($city);
            if ($b) $branchId = $b['id'];
        }
        $branchId = $branchId ? (int)$branchId : 1; // default to Semarang if not specified

        // Get all addons for this item
        $addons = $addonModel->getByProductItem($itemId);
        
        // If no addons exist, return empty array
        if (empty($addons)) {
            return $this->response->setJSON([]);
        }
        
        // Add stock availability status to each addon based on selected branch
        $addonsWithStatus = array_map(function($addon) use ($addonMaterialModel, $inventoryModel, $stockModel, $branchId) {
            // Get required materials for this addon
            $materials = $addonMaterialModel->where('addon_id', $addon['id'])->findAll();
            
            // Default: available
            $isAvailable = true;
            $outOfStockMaterials = [];
            
            // If addon has material requirements, check branch stock
            if (!empty($materials)) {
                foreach ($materials as $material) {
                    $inventory = $inventoryModel->find($material['inventory_id']);
                    $branchStock = $stockModel->getStock($material['inventory_id'], $branchId);
                    
                    // If material stock in branch <= 0, mark as unavailable
                    if (!$inventory || $branchStock <= 0) {
                        $isAvailable = false;
                        $outOfStockMaterials[] = $inventory['name'] ?? 'Unknown';
                    }
                }
            }
            
            // Add availability flag to addon
            $addon['is_available'] = $isAvailable;
            $addon['stock_status'] = $isAvailable ? 'available' : 'out_of_stock';
            
            return $addon;
        }, $addons);
        
        return $this->response->setJSON($addonsWithStatus);
    }

    /**
     * API: Get items by product ID
     */
    public function getProductItems($productId)
    {
        $itemModel = new ProductItemModel();
        $items = $itemModel->where('product_id', $productId)
                           ->where('is_active', 1)
                           ->findAll();
        
        return $this->response->setJSON($items);
    }

    /**
     * Cart page
     */
    public function cart()
    {
        $data = [
            'socialLinks' => $this->getSocialLinks(),
            'profileBio'  => $this->getBio(),
        ];
        return view('lynk/cart', $data);
    }

    /**
     * Checkout page
     */
    public function checkout()
    {
        $data = [
            'socialLinks' => $this->getSocialLinks(),
            'profileBio'  => $this->getBio(),
        ];
        return view('lynk/checkout', $data);
    }

    /**
     * Process checkout
     */
    public function processCheckout()
    {
        $json = $this->request->getJSON(true);
        
        if (!$json) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak valid',
                'csrf_token' => csrf_hash()
            ]);
        }

        // Validate required fields
        if (empty($json['customer_name']) || empty($json['customer_phone'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nama dan No. WhatsApp harus diisi',
                'csrf_token' => csrf_hash()
            ]);
        }

        if (empty($json['items']) || !is_array($json['items'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Keranjang kosong',
                'csrf_token' => csrf_hash()
            ]);
        }

        $orderModel = new OrderModel();
        $orderItemModel = new OrderItemModel();

        // Calculate total (including add-ons)
        $totalAmount = 0;
        foreach ($json['items'] as $item) {
            $itemTotal = $item['price'];
            
            // Add add-ons price
            if (!empty($item['addons'])) {
                foreach ($item['addons'] as $addon) {
                    $itemTotal += $addon['price'];
                }
            }
            
            $totalAmount += $itemTotal;
        }

        // Create order
        $orderNumber = $orderModel->generateOrderNumber();
        
        $branchId = !empty($json['branch_id']) ? (int)$json['branch_id'] : 1;
        
        $orderData = [
            'order_number'    => $orderNumber,
            'customer_name'   => $json['customer_name'],
            'customer_phone'  => $json['customer_phone'],
            'customer_email'  => $json['customer_email'] ?? null,
            'notes'           => $json['notes'] ?? null,
            'total_amount'    => $totalAmount,
            'booking_type'    => 'online',
            'branch_id'       => $branchId,
            'status'          => 'pending',
            'payment_status'  => 'pending',
            'payment_type'    => 'pay_on_spot', // Default for offline/manual payment
        ];

        $orderId = $orderModel->insert($orderData);

        if (!$orderId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal membuat pesanan',
                'csrf_token' => csrf_hash()
            ]);
        }

        // Create order items with booking data
        foreach ($json['items'] as $item) {
            $itemSubtotal = $item['price'];
            
            // Calculate add-ons total
            $addonsData = [];
            if (!empty($item['addons'])) {
                foreach ($item['addons'] as $addon) {
                    $itemSubtotal += $addon['price'];
                    $addonsData[] = [
                        'id' => $addon['id'],
                        'name' => $addon['name'],
                        'price' => $addon['price']
                    ];
                }
            }
            
            $orderItemModel->insert([
                'order_id'     => $orderId,
                'product_name' => $item['product'],
                'item_name'    => $item['name'],
                'price'        => $item['price'],
                'quantity'     => 1,
                'subtotal'     => $itemSubtotal,
                'booking_date' => $item['booking_date'] ?? null,
                'booking_time' => $item['booking_time'] ?? null,
                'addon_items'  => !empty($addonsData) ? json_encode($addonsData) : null,
            ]);
        }

        // No Midtrans integration needed. 
        // Order is created as pending, customer pays at location.

        return $this->response->setJSON([
            'success'      => true,
            'order_number' => $orderNumber,
            'message'      => 'Booking berhasil dibuat! Silakan datang sesuai jadwal.',
            'csrf_token'   => csrf_hash()
        ]);
    }

    /**
     * Midtrans Notification Handler (Webhook)
     */
    public function midtransNotification()
    {
        $midtrans = new \App\Libraries\MidtransService();
        // Since we are using the official SDK, we can use their built-in notification handler
        // But for simplicity and to avoid SDK issues with CI4 output buffering, we handle it manually
        
        $json = $this->request->getJSON(true);
    
    if (!$json) {
        if ($this->request->getMethod() === 'get') {
            return $this->response->setJSON(['status' => 'OK', 'message' => 'Notification endpoint is active. Use POST for actual webhooks.']);
        }
        return $this->response->setStatusCode(400);
    }

    $orderNumber = $json['order_id'] ?? '';

    $orderModel = new OrderModel();
    $order = $orderModel->where('order_number', $orderNumber)->first();

    if (!$order) {
        return $this->response->setStatusCode(404, 'Order not found');
    }
        $transactionStatus = $json['transaction_status'];
        $paymentType = $json['payment_type'];
        $fraudStatus = $json['fraud_status'] ?? 'accept';

        $newStatus = $order['status'];
        $newPaymentStatus = $order['payment_status'];

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $newPaymentStatus = 'challenge';
            } else if ($fraudStatus == 'accept') {
                $newPaymentStatus = 'settlement';
                $newStatus = 'processing';
            }
        } else if ($transactionStatus == 'settlement') {
            $newPaymentStatus = 'settlement';
            $newStatus = 'processing';
        } else if ($transactionStatus == 'pending') {
            $newPaymentStatus = 'pending';
        } else if ($transactionStatus == 'deny') {
            $newPaymentStatus = 'deny';
        } else if ($transactionStatus == 'expire') {
            $newPaymentStatus = 'expire';
            $newStatus = 'cancelled';
        } else if ($transactionStatus == 'cancel') {
            $newPaymentStatus = 'cancel';
            $newStatus = 'cancelled';
        }

        if ($newStatus == 'processing' && $order['status'] != 'processing') {
            // Reduce Stock Logic (Multi-Branch Inventory System)
            $db = \Config\Database::connect();
            $orderItemModel = new \App\Models\OrderItemModel();
            $materialModel = new \App\Models\ProductItemMaterialModel();
            $addonMaterialModel = new \App\Models\AddonMaterialModel();
            $inventoryStockModel = new \App\Models\InventoryStockModel();
            
            $branchId = !empty($order['branch_id']) ? (int)$order['branch_id'] : 1;
            $items = $orderItemModel->where('order_id', $order['id'])->findAll();

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
                        // Increase sold count
                        $newSold = $pItem->sold + $item['quantity'];
                        
                        $db->table('product_items')->where('id', $pItem->id)->update([
                            'sold'  => $newSold
                        ]);
                        
                        // Reduce Inventory based on Product Item Recipe
                        $materials = $materialModel->where('product_item_id', $pItem->id)->findAll();
                        
                        foreach ($materials as $mat) {
                            $invId = (int)$mat['inventory_id'];
                            $inventory = $db->table('inventories')->where('id', $invId)->get()->getRow();
                            if ($inventory) {
                                $usage = $mat['quantity'] * $item['quantity'];
                                $inventoryStockModel->deductStock($invId, $branchId, $usage);

                                // Recalculate global stock in inventories table
                                $sum = $db->table('inventory_stocks')->where('inventory_id', $invId)->selectSum('stock')->get()->getRow();
                                if ($sum) {
                                    $db->table('inventories')->where('id', $invId)->update(['stock' => (float)$sum->stock]);
                                }
                            }
                        }
                    }
                }
                
                // Reduce Inventory based on Add-on Recipes
                if (!empty($item['addon_items'])) {
                    $addons = json_decode($item['addon_items'], true);
                    if (is_array($addons)) {
                        foreach ($addons as $addon) {
                            $addonId = $addon['id'] ?? null;
                            if (!$addonId) continue;
                            
                            $addonMats = $addonMaterialModel->where('addon_id', $addonId)->findAll();
                            foreach ($addonMats as $mat) {
                                $invId = (int)$mat['inventory_id'];
                                $inventory = $db->table('inventories')->where('id', $invId)->get()->getRow();
                                if ($inventory) {
                                    $usage = $mat['quantity'] * $item['quantity'];
                                    $inventoryStockModel->deductStock($invId, $branchId, $usage);

                                    // Recalculate global stock in inventories table
                                    $sum = $db->table('inventory_stocks')->where('inventory_id', $invId)->selectSum('stock')->get()->getRow();
                                    if ($sum) {
                                        $db->table('inventories')->where('id', $invId)->update(['stock' => (float)$sum->stock]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        $orderModel->update($order['id'], [
            'status'         => $newStatus,
            'payment_status' => $newPaymentStatus,
            'payment_type'   => $paymentType
        ]);

        return $this->response->setJSON(['status' => 'OK']);
    }

    /**
     * Order success page
     */
    public function orderSuccess($orderNumber)
    {
        $orderModel = new OrderModel();
        
        $order = $orderModel->where('order_number', $orderNumber)->first();
        
        if (!$order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Handle Midtrans Redirect Parameters if they exist
        $status_code = $this->request->getGet('status_code');
        $transaction_status = $this->request->getGet('transaction_status');

        if ($status_code == '200' && $transaction_status == 'settlement') {
            // Update the array for immediate visual feedback in the view
            $order['payment_status'] = 'settlement';
            $order['status'] = 'processing';
        }

        $orderItemModel = new OrderItemModel();
        $items = $orderItemModel->where('order_id', $order['id'])->findAll();
        
        // Add review status to each item
        foreach ($items as &$item) {
            // Find product id by name - simple approach since we store product name
            $productModel = new ProductModel();
            $product = $productModel->where('name', $item['product_name'])->first();
            
            $item['product_id'] = $product ? $product['id'] : null;
            $item['has_reviewed'] = false;
        }
        
        $order['items'] = $items;
        
        return view('lynk/success', [
            'order'       => $order,
            'socialLinks' => $this->getSocialLinks(),
            'profileBio'  => $this->getBio(),
        ]);
    }
    /**
     * Terms and Conditions page
     */
    public function terms()
    {
        $data = [
            'title'       => 'Syarat & Ketentuan',
            'terms'       => $this->getTerms(),
            'socialLinks' => $this->getSocialLinks(),
            'profileBio'  => $this->getBio(),
        ];
        
        return view('lynk/terms', $data);
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
        return "Syarat & Ketentuan belum diisi.";
    }





}
