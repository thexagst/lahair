<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Homepage
$routes->get('/', 'Home::index');

// Product Detail Page
$routes->get('s/(:segment)', 'Home::product/$1');


// Cart & Checkout
$routes->get('cart', 'Home::cart');
$routes->get('checkout', 'Home::checkout');
$routes->post('checkout/process', 'Home::processCheckout');
$routes->get('checkout/success/(:segment)', 'Home::orderSuccess/$1');
$routes->match(['get', 'post'], 'payment/notification', 'Home::midtransNotification');
$routes->get('terms', 'Home::terms');

// API Routes
$routes->get('api/items/(:num)/addons', 'Home::getItemAddons/$1');
$routes->get('api/products/(:num)/items', 'Home::getProductItems/$1');

// Public E-Receipt
$routes->get('ereceipt/(:segment)', 'Admin::ereceipt/$1');
$routes->get('ereceipt/(:segment)/(:segment)', 'Admin::ereceipt/$1/$2');


// Auth Routes (Admin)
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::authenticate');
$routes->get('logout', 'Auth::logout');
$routes->get('auth/forgot-password', 'Auth::forgotPassword');
$routes->post('auth/forgot-password/send', 'Auth::sendOtp');
$routes->get('auth/forgot-password/verify', 'Auth::verifyOtpPage');
$routes->post('auth/forgot-password/verify', 'Auth::verifyOtp');
$routes->get('auth/forgot-password/reset', 'Auth::resetPasswordPage');
$routes->post('auth/forgot-password/reset', 'Auth::doResetPassword');



// Admin Routes
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Admin::index');
    
    // Branch Selection & Switch
    $routes->get('select-branch', 'Admin::selectBranch');
    $routes->get('switch-branch/(:segment)', 'Admin::switchBranch/$1');
    $routes->post('switch-branch', 'Admin::switchBranch');
    
    // Products
    $routes->get('products', 'Admin::products');
    $routes->get('products/create', 'Admin::create');
    $routes->post('products/store', 'Admin::store');
    $routes->get('products/edit/(:num)', 'Admin::edit/$1');
    $routes->post('products/update/(:num)', 'Admin::update/$1');
    $routes->post('products/delete/(:num)', 'Admin::delete/$1');
    
    // Product Items (Harga)
    $routes->get('products/items/(:num)', 'Admin::items/$1');
    $routes->get('products/items/(:num)/create', 'Admin::createItem/$1');
    $routes->post('products/items/(:num)/store', 'Admin::storeItem/$1');
    $routes->get('products/items/(:num)/edit/(:num)', 'Admin::editItem/$1/$2');
    $routes->post('products/items/(:num)/update/(:num)', 'Admin::updateItem/$1/$2');
    $routes->post('products/items/(:num)/delete/(:num)', 'Admin::deleteItem/$1/$2');
    $routes->post('products/items/(:num)/delete-all', 'Admin::deleteAllItems/$1');
    $routes->post('products/items/(:num)/import', 'Admin::importItems/$1');
    $routes->get('products/items/(:num)/template', 'Admin::downloadItemTemplate/$1');
    
    // Product Item Add-ons
    $routes->get('products/items/(:num)/addons', 'Admin::addons/$1');
    $routes->get('products/items/(:num)/addons/create', 'Admin::createAddon/$1');
    $routes->post('products/items/(:num)/addons/store', 'Admin::storeAddon/$1');
    $routes->get('products/items/(:num)/addons/edit/(:num)', 'Admin::editAddon/$1/$2');
    $routes->post('products/items/(:num)/addons/update/(:num)', 'Admin::updateAddon/$1/$2');
    $routes->post('products/items/(:num)/addons/delete/(:num)', 'Admin::deleteAddon/$1/$2');
    
    // Offline Bookings
    $routes->get('bookings/create', 'Admin::createOfflineBooking');
    $routes->post('bookings/store', 'Admin::storeOfflineBooking');
    
    // Saved Customers (Autocomplete API)
    $routes->get('customers/search', 'Admin::searchSavedCustomers');
    
    // Orders
    $routes->get('orders', 'Admin::orders');
    $routes->get('orders/view/(:segment)', 'Admin::viewOrder/$1');
    $routes->post('orders/status/(:segment)', 'Admin::updateOrderStatus/$1');
    $routes->post('orders/update-discount/(:segment)', 'Admin::updateOrderDiscount/$1');
    $routes->post('orders/assign/(:segment)', 'Admin::assignWorker/$1');
    $routes->post('orders/add-item/(:segment)', 'Admin::addItemToOrder/$1');    
    $routes->post('orders/delete-item/(:segment)/(:num)', 'Admin::deleteOrderItem/$1/$2');
    $routes->post('orders/stock-usage/(:segment)', 'Admin::storeStockUsage/$1');
    $routes->post('orders/stock-usage/delete/(:num)', 'Admin::deleteStockUsage/$1');



    
    // Admin Management
    $routes->get('admins', 'Admin::admins');
    $routes->get('admins/create', 'Admin::createAdmin');
    $routes->post('admins/store', 'Admin::storeAdmin');
    $routes->get('admins/edit/(:num)', 'Admin::editAdmin/$1');
    $routes->post('admins/update/(:num)', 'Admin::updateAdmin/$1');
    $routes->post('admins/delete/(:num)', 'Admin::deleteAdmin/$1');

    // Inventory
    $routes->get('inventory', 'Admin::inventory');
    $routes->get('inventory/history/(:num)', 'Admin::inventoryHistory/$1');
    $routes->post('inventory/store', 'Admin::storeInventory');
    $routes->post('inventory/update/(:num)', 'Admin::updateInventory/$1');
    $routes->post('inventory/delete/(:num)', 'Admin::deleteInventory/$1');
    $routes->post('inventory/import', 'Admin::importInventory');
    $routes->get('inventory/template', 'Admin::downloadInventoryTemplate');


    // Financial Reports
    $routes->get('reports/financial', 'Admin::financialReports');
    $routes->get('reports/revenue-data', 'Admin::getRevenueData');
    $routes->get('reports/export-excel', 'Admin::exportFinancialReport');

    // Settings
    $routes->get('settings', 'Admin::settings');

    $routes->post('settings/banner', 'Admin::saveBanner');
    $routes->post('settings/bio', 'Admin::saveBio');
    $routes->post('settings/social', 'Admin::saveSocialLinks');
    $routes->post('settings/terms', 'Admin::saveTerms');

    $routes->post('settings/bio', 'Admin::saveBio');
    $routes->post('settings/social', 'Admin::saveSocialLinks');
    $routes->post('settings/terms', 'Admin::saveTerms');

    // Attendance System
    $routes->get('attendance', 'Attendance::index');
    $routes->get('attendance/scan', 'Attendance::scan');
    $routes->post('attendance/process-scan', 'Attendance::processScan');
    $routes->get('attendance/report', 'Attendance::report');
    $routes->get('attendance/holidays', 'Attendance::holidays');
    $routes->post('attendance/holidays/store', 'Attendance::storeHoliday');
    $routes->post('attendance/holidays/delete/(:num)', 'Attendance::deleteHoliday/$1');

});
