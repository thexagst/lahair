<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="author" content="Duma Augusto">
    <meta name="designer" content="Duma Augusto - Full Stack Developer">
    <meta name="description" content="Checkout - La Hair Studio">
    <title>Booking Appointment - La Hair Studio</title>
    
    <link rel="icon" type="image/png" href="/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon/favicon.svg" />
    <link rel="shortcut icon" href="/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="La Hair" />
    <link rel="manifest" href="/favicon/site.webmanifest" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Air Datepicker CSS -->
    <link href="https://cdn.jsdelivr.net/npm/air-datepicker@3.5.3/air-datepicker.css" rel="stylesheet" />

    <?php
    // Get Shop Settings
    $settings = [];
    $configPath = WRITEPATH . 'shop_settings.json';
    if (file_exists($configPath)) {
        $settings = json_decode(file_get_contents($configPath), true) ?? [];
    }
    ?>
    <script>
        const shopSettings = <?= json_encode($settings) ?>;
    </script>
    
    <style>
        /* ===== CHECKOUT — CLEAN MINIMAL ===== */
        :root {
            --co-text: #111111;
            --co-muted: #888888;
            --co-border: #e8e8e8;
            --co-bg: #f9f9f9;
            --co-white: #ffffff;
            --co-radius: 10px;
        }

        .checkout-page-wrap {
            max-width: 440px;
            margin: 0 auto;
            padding: 60px 16px 40px;
        }

        /* Back button */
        .co-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            color: var(--co-muted);
            margin-bottom: 24px;
            transition: color 0.2s;
        }
        .co-back:hover { color: var(--co-text); }
        .co-back svg { stroke: currentColor; }

        /* Page title */
        .co-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--co-text);
            margin-bottom: 4px;
        }
        .co-subtitle {
            font-size: 0.83rem;
            color: var(--co-muted);
            margin-bottom: 28px;
        }
        /* Section card */
        .co-section {
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(8px);
            border: 1px solid var(--co-border);
            border-radius: 14px;
            padding: 14px;
            margin-bottom: 12px;
        }
        .co-section-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--co-muted);
            margin-bottom: 12px;
        }

        /* Booking item card */
        .booking-item-card {
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(8px);
            border: 1px solid var(--co-border);
            border-radius: 14px;
            padding: 14px;
            margin-bottom: 12px;
        }
        .booking-item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--co-border);
        }
        .booking-item-info { flex: 1; }
        .booking-item-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--co-text);
            margin-bottom: 2px;
        }
        .booking-item-subtitle {
            font-size: 0.8rem;
            color: var(--co-muted);
            margin-bottom: 4px;
        }
        .booking-item-price {
            font-size: 1rem;
            font-weight: 700;
            color: var(--co-text);
            white-space: nowrap;
        }
        .duration-badge {
            display: inline-block;
            background: #f0f0f0;
            color: #555;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 0.73rem;
            font-weight: 500;
        }

        /* Booking fields grid */
        .booking-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }
        @media (max-width: 380px) {
            .booking-fields { grid-template-columns: 1fr; }
        }

        /* Form elements */
        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: var(--co-text);
            font-size: 0.8rem;
        }
        .form-group { margin-bottom: 10px; }
        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--co-border);
            border-radius: var(--co-radius);
            font-size: 15px;
            font-family: 'Outfit', sans-serif;
            background: #fafafa;
            color: var(--co-text);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: #aaa;
            background: white;
            box-shadow: 0 0 0 3px rgba(0,0,0,0.04);
        }
        .form-textarea { resize: vertical; min-height: 80px; }

        /* Addons */
        .addons-section {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid var(--co-border);
        }
        .addons-title {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--co-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 8px;
        }
        .addon-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            background: #f7f7f7;
            border: 1px solid transparent;
            border-radius: 8px;
            margin-bottom: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .addon-checkbox:hover {
            background: #f0f0f0;
            border-color: #ddd;
        }
        .addon-checkbox input[type="checkbox"] {
            width: 16px; height: 16px;
            cursor: pointer;
            accent-color: #111;
        }
        .addon-label { flex: 1; font-size: 0.85rem; color: var(--co-text); }
        .addon-price { font-weight: 600; color: var(--co-text); font-size: 0.83rem; }
        .addon-disabled { opacity: 0.45; cursor: not-allowed !important; }

        /* Subtotal */
        .item-subtotal {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed var(--co-border);
        }
        .item-subtotal-label { font-size: 0.8rem; color: var(--co-muted); }
        .item-subtotal-value { font-size: 0.95rem; font-weight: 700; color: var(--co-text); }

        /* Total box */
        .order-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 16px;
            background: #111111;
            border-radius: 12px;
            margin-top: 12px;
            margin-bottom: 12px;
        }
        .order-total span:first-child { font-size: 0.85rem; color: rgba(255,255,255,0.7); }
        .order-total span:last-child { font-size: 1.1rem; font-weight: 700; color: white; }

        /* Submit */
        .submit-order-btn {
            width: 100%;
            padding: 15px;
            background: #111111;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Outfit', sans-serif;
            letter-spacing: 0.02em;
            transition: opacity 0.2s, transform 0.2s;
        }
        .submit-order-btn:hover { opacity: 0.88; transform: translateY(-1px); }
        .submit-order-btn:active { transform: translateY(0); }
        .submit-order-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        .no-addons-message { font-size: 0.8rem; color: var(--co-muted); }

        /* Select2 overrides */
        .select2-container--default .select2-selection--single {
            height: 44px !important;
            border: 1px solid var(--co-border) !important;
            border-radius: var(--co-radius) !important;
            padding: 6px 12px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px !important; padding-left: 0 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 42px !important; }
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #aaa !important; box-shadow: none !important;
        }
        .select2-dropdown { border: 1px solid #ddd !important; border-radius: 10px !important; }
        .select2-results__option--highlighted { background-color: #111 !important; }

        /* Air Datepicker */
        :root {
            --adp-background-color-selected: #111111;
            --adp-background-color-selected-hover: #333;
            --adp-accent-color: #111111;
            --adp-color-current-date: #111111;
            --adp-day-name-color: #888;
            --adp-btn-color: #111111;
            --adp-font-family: 'Outfit', sans-serif;
        }
        .air-datepicker-global-container { z-index: 10000; }
    </style>
</head>
<body>

    <main class="lynk-container">
        
        <!-- Back Button -->
        <a href="/cart" class="co-back">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Kembali ke Keranjang
        </a>

        <!-- Checkout Header -->
        <h1 class="co-title">Booking Appointment</h1>
        <p class="co-subtitle">Pilih tanggal dan waktu untuk setiap layanan</p>

        <!-- Booking Items -->
        <div class="co-section-label">Detail Booking</div>
        <div id="bookingItems"></div>
        
        <!-- Customer Form -->
        <div class="co-section-label" style="margin-top:12px;">Data Diri & Lokasi Salon</div>
        <form id="checkoutForm">
            <div class="co-section">
                <!-- Branch Location Selector -->
                <div class="form-group">
                    <label class="form-label">Pilih Lokasi Salon *</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 6px;">
                        <label id="labelBranch1" style="display: flex; align-items: flex-start; gap: 8px; padding: 10px 12px; border: 1.5px solid #111; border-radius: 10px; cursor: pointer; background: #fff;">
                            <input type="radio" name="branch_id" value="1" checked onchange="handleBranchChange(1)" style="margin-top: 3px;">
                            <div>
                                <div style="font-weight: 700; font-size: 0.88rem; color: #111; display: flex; align-items: center; gap: 4px;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                    <span>Semarang</span>
                                </div>
                                <div style="font-size: 0.72rem; color: #666; line-height: 1.3; margin-top: 2px;">Plaza Taman Niaga BSB</div>
                            </div>
                        </label>
                        <label id="labelBranch2" style="display: flex; align-items: flex-start; gap: 8px; padding: 10px 12px; border: 1.5px solid #e5e7eb; border-radius: 10px; cursor: pointer; background: #fff;">
                            <input type="radio" name="branch_id" value="2" onchange="handleBranchChange(2)" style="margin-top: 3px;">
                            <div>
                                <div style="font-weight: 700; font-size: 0.88rem; color: #111; display: flex; align-items: center; gap: 4px;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                    <span>Indramayu</span>
                                </div>
                                <div style="font-size: 0.72rem; color: #666; line-height: 1.3; margin-top: 2px;">Cabang Indramayu</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="customer_name" id="customerName" class="form-input" required placeholder="Nama lengkap">
                </div>
                <div class="form-group">
                    <label class="form-label">No. WhatsApp *</label>
                    <input type="tel" name="customer_phone" id="customerPhone" class="form-input" required placeholder="08xxxxxxxxxx">
                </div>
                <div class="form-group">
                    <label class="form-label">Email <span style="color:#aaa;font-weight:400">(Opsional)</span></label>
                    <input type="email" name="customer_email" id="customerEmail" class="form-input" placeholder="email@example.com">
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Catatan <span style="color:#aaa;font-weight:400">(Opsional)</span></label>
                    <textarea name="notes" id="notes" class="form-textarea" rows="3" placeholder="Catatan tambahan"></textarea>
                </div>
            </div>

            <div class="order-total">
                <span>Total Pembayaran</span>
                <span id="grandTotal">Rp 0</span>
            </div>

            <button type="submit" class="submit-order-btn" id="submitBtn">Konfirmasi Booking</button>
        </form>
        <!-- Footer -->
        <footer class="lynk-footer">
            <div class="social-icons">
                <a href="<?= esc($socialLinks['tiktok'] ?? 'https://tiktok.com/@lahair') ?>" class="social-icon" target="_blank" aria-label="TikTok">
                    <svg viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>
                </a>
                <a href="<?= esc($socialLinks['instagram'] ?? 'https://www.instagram.com/lahair') ?>" class="social-icon" target="_blank" aria-label="Instagram">
                    <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.162 6.162 6.162 6.162-2.759 6.162-6.162-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                </a>
            </div>

            <div class="footer-address" style="text-align: center; margin-bottom: 20px;">
                <a href="https://maps.google.com/?q=La+Hair+Studio+Plaza+Taman+Niaga+Semarang" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(0,0,0,0.03); color: var(--text-primary); padding: 10px 18px; border-radius: 50px; text-decoration: none; font-size: 0.85rem; font-weight: 500; transition: all 0.2s; border: 1px solid rgba(0,0,0,0.08); box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary);">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    Plaza Taman Niaga, BSB Lt. 2
                </a>
            </div>
            <div class="footer-logo">
                <span>Powered by <a href="/">La Hair Studio</a></span>
            </div>
            <p>© <?= date('Y') ?> La Hair Studio. All rights reserved.</p>

            <?= $this->include('partials/watermark') ?>
        </footer>
        
    </main>

    <a href="https://wa.me/628993577757" class="wa-float-clean" target="_blank" aria-label="WhatsApp Admin">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        <span>Contact Us</span>
    </a>


    <script>

        let csrfToken = '<?= csrf_hash() ?>';
        let bookingData = {}; // Store booking info per item

        document.addEventListener('DOMContentLoaded', function() {
            loadBookingItems();
            
            document.getElementById('checkoutForm').addEventListener('submit', submitBooking);
        });

        async function loadBookingItems() {
            const cart = JSON.parse(localStorage.getItem('La Hair Studio_cart') || '[]');
            
            if (cart.length === 0) {
                window.location.href = '/cart';
                return;
            }
            
            const container = document.getElementById('bookingItems');
            let html = '';
            
            for (let i = 0; i < cart.length; i++) {
                const item = cart[i];
                const itemKey = `item_${i}`;
                
                // Initialize booking data with add-ons from cart
                bookingData[itemKey] = {
                    cartIndex: i,
                    basePrice: item.price,
                    selectedAddons: item.addons || [], // Use add-ons from cart
                    bookingDate: '',
                    bookingTime: ''
                };
                
                // Fetch add-ons for this item
                const addons = await fetchAddons(item.itemId);
                
                html += `
                    <div class="booking-item-card">
                        <div class="booking-item-header">
                            <div class="booking-item-info">
                                <div class="booking-item-title">${item.product}</div>
                                <div class="booking-item-subtitle">${item.name}</div>
                                ${item.duration ? `<span class="duration-badge">${item.duration} menit</span>` : ''}
                            </div>
                            <div class="booking-item-price">Rp ${formatNumber(item.price)}</div>
                        </div>
                        
                        <div class="booking-fields">
                            <div class="form-group" style="margin: 0;">
                                <label class="form-label">Tanggal Booking *</label>
                                <input 
                                    type="text" 
                                    class="form-input datepicker-input" 
                                    id="date_${itemKey}"
                                    placeholder="Pilih Tanggal"
                                    readonly
                                    required
                                >
                            </div>
                            
                            <div class="form-group" style="margin: 0;">
                                <label class="form-label">Waktu Booking *</label>
                                <input 
                                    type="text" 
                                    class="form-input timepicker-input" 
                                    id="time_${itemKey}"
                                    placeholder="Pilih Waktu"
                                    readonly
                                    required
                                >
                            </div>
                        </div>
                        
                        ${addons.length > 0 ? `
                            <div class="addons-section">
                                <div class="addons-title">Tambahan Layanan (Opsional)</div>
                                ${addons.map(addon => {
                                    // Check if this addon was already selected
                                    const isSelected = (item.addons || []).some(a => a.id == addon.id);
                                    const isAvailable = addon.is_available !== false; // Default to true if not specified
                                    const disabledAttr = !isAvailable ? 'disabled' : '';
                                    const disabledClass = !isAvailable ? 'addon-disabled' : '';
                                    
                                    return `
                                        <label class="addon-checkbox ${disabledClass}">
                                            <input 
                                                type="checkbox" 
                                                value="${addon.id}"
                                                data-price="${addon.price}"
                                                data-name="${addon.name}"
                                                onchange="toggleAddon('${itemKey}', this)"
                                                ${isSelected ? 'checked' : ''}
                                                ${disabledAttr}
                                            >
                                            <span class="addon-label">
                                                ${addon.name}
                                                ${!isAvailable ? '<span style="font-size: 0.7rem; color: #999; margin-left: 6px;">(Stok Habis)</span>' : ''}
                                            </span>
                                            <span class="addon-price" style="color: ${isAvailable ? 'var(--primary)' : '#999'};">+ Rp ${formatNumber(addon.price)}</span>
                                        </label>
                                    `;
                                }).join('')}
                            </div>
                        ` : '<div class="addons-section"><p class="no-addons-message">Tidak ada tambahan layanan tersedia</p></div>'}
                        
                        <div class="item-subtotal">
                            <span class="item-subtotal-label">Subtotal</span>
                            <span class="item-subtotal-value" id="subtotal_${itemKey}">Rp ${formatNumber(item.price)}</span>
                        </div>
                    </div>
                `;
            }
            
            container.innerHTML = html;
            
            // Initialize Flatpickr for each item
            for (let i = 0; i < cart.length; i++) {
                const itemKey = `item_${i}`;
                initializeFormControls(itemKey);
                // Update subtotal to include pre-selected add-ons
                updateItemSubtotal(itemKey);
            }
            
            updateGrandTotal();
        }

        let currentSelectedBranch = 1;

        function handleBranchChange(branchId) {
            currentSelectedBranch = branchId;
            const l1 = document.getElementById('labelBranch1');
            const l2 = document.getElementById('labelBranch2');
            if (l1 && l2) {
                if (branchId === 1) {
                    l1.style.borderColor = '#111';
                    l2.style.borderColor = '#e5e7eb';
                } else {
                    l1.style.borderColor = '#e5e7eb';
                    l2.style.borderColor = '#111';
                }
            }
            // Re-render booking items to refresh addon stock status for selected branch
            renderBookingItems();
        }

        async function fetchAddons(itemId) {
            try {
                const response = await fetch(`/api/items/${itemId}/addons?branch_id=${currentSelectedBranch}`);
                if (response.ok) {
                    return await response.json();
                }
            } catch (error) {
                console.error('Error fetching addons:', error);
            }
            return [];
        }

        function generateTimeSlots() {
            const slots = [];
            // Generate 30-minute intervals from 09:00 to 20:00
            for (let hour = 9; hour <= 20; hour++) {
                for (let minute = 0; minute < 60; minute += 30) {
                    if (hour === 20 && minute > 0) break; // Stop at 20:00
                    const time = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
                    slots.push(`<option value="${time}">${time}</option>`);
                }
            }
            return slots.join('');
        }

        function getTodayDate() {
            const today = new Date();
            return today.toISOString().split('T')[0];
        }

        function updateBookingDate(itemKey, date) {
            bookingData[itemKey].bookingDate = date;
            // TODO: Fetch available time slots for this date
        }

        function updateBookingTime(itemKey, time) {
            bookingData[itemKey].bookingTime = time;
        }

        function toggleAddon(itemKey, checkbox) {
            const addonId = checkbox.value;
            const addonPrice = parseFloat(checkbox.dataset.price);
            const addonName = checkbox.dataset.name;
            
            if (checkbox.checked) {
                bookingData[itemKey].selectedAddons.push({
                    id: addonId,
                    name: addonName,
                    price: addonPrice
                });
            } else {
                bookingData[itemKey].selectedAddons = bookingData[itemKey].selectedAddons.filter(
                    addon => addon.id !== addonId
                );
            }
            
            updateItemSubtotal(itemKey);
            updateGrandTotal();
        }

        function updateItemSubtotal(itemKey) {
            const data = bookingData[itemKey];
            const addonsTotal = data.selectedAddons.reduce((sum, addon) => sum + addon.price, 0);
            const subtotal = data.basePrice + addonsTotal;
            
            document.getElementById(`subtotal_${itemKey}`).textContent = 'Rp ' + formatNumber(subtotal);
        }

        function updateGrandTotal() {
            let total = 0;
            Object.values(bookingData).forEach(data => {
                const addonsTotal = data.selectedAddons.reduce((sum, addon) => sum + addon.price, 0);
                total += data.basePrice + addonsTotal;
            });
            
            document.getElementById('grandTotal').textContent = 'Rp ' + formatNumber(total);
        }

        async function submitBooking(e) {
            e.preventDefault();
            
            // Validate all items have booking date and time
            for (const [key, data] of Object.entries(bookingData)) {
                if (!data.bookingDate || !data.bookingTime) {
                    alert('Mohon lengkapi tanggal dan waktu booking untuk semua layanan');
                    return;
                }
            }
            
            const cart = JSON.parse(localStorage.getItem('La Hair Studio_cart') || '[]');
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Memproses...';
            
            // Prepare items with booking data
            const items = cart.map((item, index) => {
                const itemKey = `item_${index}`;
                const data = bookingData[itemKey];
                
                return {
                    ...item,
                    booking_date: data.bookingDate,
                    booking_time: data.bookingTime,
                    addons: data.selectedAddons
                };
            });
            
            const formData = {
                customer_name: document.getElementById('customerName').value,
                customer_phone: document.getElementById('customerPhone').value,
                customer_email: document.getElementById('customerEmail').value,
                branch_id: currentSelectedBranch,
                notes: document.getElementById('notes').value,
                items: items
            };
            
            try {
                const response = await fetch('/checkout/process', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        '<?= csrf_header() ?>': csrfToken
                    },
                    body: JSON.stringify(formData)
                });
                
                const result = await response.json();
                
                if (result.csrf_token) {
                    csrfToken = result.csrf_token;
                }
                
                if (result.success) {
                    // Clear cart
                    localStorage.removeItem('La Hair Studio_cart');
                    
                    // Redirect to payment or success page
                    // Redirect to success page directly
                    window.location.href = '/checkout/success/' + result.order_number;
                } else {
                    alert(result.message || 'Terjadi kesalahan');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Konfirmasi Booking';
                }
            } catch (error) {
                alert('Terjadi kesalahan. Silakan coba lagi.');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Konfirmasi Booking';
            }
        }

        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }
    </script>
    
    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- Air Datepicker JS -->
    <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.5.3/air-datepicker.js"></script>
    
    <script>
        const localeID = {
            days: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
            daysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            daysMin: ['Mi', 'Sn', 'Sl', 'Ra', 'Ka', 'Ju', 'Sa'],
            months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            today: 'Hari Ini',
            clear: 'Hapus',
            dateFormat: 'yyyy-MM-dd',
            timeFormat: 'HH:mm',
            firstDay: 1
        };

        const timePickers = {};

        // Initialize form controls after items are loaded
        function initializeFormControls(itemKey) {
            // Initialize Date Picker
            new AirDatepicker(`#date_${itemKey}`, {
                locale: localeID,
                minDate: new Date(),
                autoClose: true,
                isMobile: true,
                onSelect: function({date, formattedDate}) {
                    bookingData[itemKey].bookingDate = formattedDate;
                    updateTimeConstraints(date, itemKey);
                }
            });
            
            // Initialize Time Picker
            timePickers[itemKey] = new AirDatepicker(`#time_${itemKey}`, {
                locale: localeID,
                timepicker: true,
                onlyTimepicker: true,
                minHours: parseInt(shopSettings.start_time?.split(':')[0] || 9), 
                maxHours: parseInt(shopSettings.end_time?.split(':')[0] || 20),
                // Initially block selection with a far future minDate
                minDate: new Date('2099-12-31'), 
                minutesStep: 30,
                isMobile: true,
                onSelect: function({date, formattedDate}) {
                    bookingData[itemKey].bookingTime = formattedDate;
                }
            });
        }

        function updateTimeConstraints(selectedDate, itemKey) {
            const timeInput = document.getElementById(`time_${itemKey}`);
            const timePicker = timePickers[itemKey];

            if (!selectedDate) {
                // If date cleared, block time selection again
                if (timePicker) {
                    timePicker.clear();
                    timePicker.update({ minDate: new Date('2099-12-31') });
                }
                return;
            }

            // (Input enabling removed as we rely on minDate)

            const now = new Date();
            
            if (!timePicker) return;

            // Check if selected date is Today
            const isToday = selectedDate.getDate() === now.getDate() &&
                            selectedDate.getMonth() === now.getMonth() &&
                            selectedDate.getFullYear() === now.getFullYear();

            if (isToday) {
                // Calculate next 30-min slot
                let minTime = new Date();
                const minutes = minTime.getMinutes();
                
                if (minutes < 30) {
                    minTime.setMinutes(30);
                    minTime.setSeconds(0);
                    minTime.setMilliseconds(0);
                } else {
                    minTime.setHours(minTime.getHours() + 1);
                    minTime.setMinutes(0);
                    minTime.setSeconds(0);
                    minTime.setMilliseconds(0);
                }
                
                // If rounded time is before 09:00, clamp to 09:00 (for cases where user books early morning)
                // But wait, minHours is 9. So if now is 07:00, minTime becomes 07:30. 
                // We should let minHours handle the 9am floor, OR explicit check.
                // We just need to ensure we don't accidentally set minDate to 07:30 which is valid > now but invalid < 9.
                // AirDatepicker uses intersection of constraints.
                
                // However, setting minDate will restrict time components.
                // Construct a full Date object using the SELECTED DATE but with ROUNDED TIME.
                // Wait, time picker is "onlyTimepicker", does it care about the date part of minDate? 
                // Yes, it compares timestamps if minDate is provided.
                // Since "onlyTimepicker" usually ignores the date part visually, logic-wise it might compare full date.
                // Let's assume we need to set minDate to match the selected date + calculated time.
                
                const constraintDate = new Date(selectedDate);
                constraintDate.setHours(minTime.getHours());
                constraintDate.setMinutes(minTime.getMinutes());
                constraintDate.setSeconds(0);

                timePicker.update({
                    minDate: constraintDate,
                    startDate: constraintDate // Set default view to the allowed start time
                });
            } else {
                // For Future dates, reset minDate to null (so it falls back to minHours/maxHours only)
                // And reset startup view to 09:00? Or just let it be.
                // Better to clear startDate to avoid sticking to previous constraint
                timePicker.update({
                    minDate: null,
                    startDate: null, 
                    minHours: 9,
                    maxHours: 20
                });
            }
            
            // Clear current time selection if it becomes invalid? 
            // Better to let user re-select or keep it if valid.
            // AirDatepicker doesn't auto-clear usually.
            timePicker.clear();
        }
        
        // Auto-focus on first input after page load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const firstInput = document.querySelector('#customerName');
                if (firstInput) {
                    firstInput.focus();
                }
            }, 500);
        });
    </script>

</body>
</html>
