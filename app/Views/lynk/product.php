<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= esc($product['name']) ?> - La Hair Studio Top Up Terpercaya">
    <title><?= esc($product['name']) ?> - La Hair Studio</title>
    
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
    <style>
        /* Review Section */
        .review-section {
            padding: 0 0 40px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .review-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .review-summary {
            background: var(--bg-card);
            border-radius: var(--radius);
            padding: 24px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 30px;
        }

        .summary-score {
            text-align: center;
        }
        
        .score-big {
            font-size: 3rem;
            font-weight: 800;
            color: var(--text);
            line-height: 1;
        }
        
        .score-stars {
            color: #FFD700;
            font-size: 1.2rem;
            margin: 4px 0;
        }
        
        .score-count {
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        
        .review-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 40px;
        }
        
        .review-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px;
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .reviewer-name {
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .review-date {
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        
        .review-rating {
            color: #FFD700;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }
        
        .review-text {
            font-size: 0.95rem;
            line-height: 1.5;
            color: var(--text);
        }

        /* Write Review Form */
        .write-review {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
        }
        
        .star-rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            margin-bottom: 16px;
        }
        
        .star-rating-input input {
            display: none;
        }
        
        .star-rating-input label {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }
        
        .star-rating-input label:hover,
        .star-rating-input label:hover ~ label,
        .star-rating-input input:checked ~ label {
            color: #FFD700;
        }
        
        .btn-submit-review {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: var(--radius);
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-top: 16px;
        }
    </style>
</head>
<body>

    <main class="lynk-container">
        
        <!-- Back Button - Fixed Top Left -->
        <a href="/" class="back-btn-fixed" id="backBtn" aria-label="Kembali">
            <svg viewBox="0 0 24 24" width="20" height="20">
                <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>

        <!-- Cart Button - Fixed Top Right -->
        <a href="/cart" class="cart-btn-fixed" id="cartButton" aria-label="Keranjang">
            <svg viewBox="0 0 24 24" width="20" height="20">
                <path d="M9 22c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1zm8 0c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1zM5.5 5H22l-2.5 9H7.5L5.5 5zm0 0L5 3H2" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="cart-count" id="cartCount" style="display:none">0</span>
        </a>
        
        <!-- Product Header -->
        <section class="product-detail-header">
            <?php if ($product['image']): ?>
                <div class="product-image-large">
                    <?php $productImgSrc = (str_starts_with($product['image'], 'http://') || str_starts_with($product['image'], 'https://')) ? $product['image'] : '/' . $product['image']; ?>
                    <img src="<?= esc($productImgSrc) ?>" alt="<?= esc($product['name']) ?>">
                </div>
            <?php else: ?>
                <div class="product-icon-large" style="background: linear-gradient(135deg, var(--primary-light), var(--primary-color))">
                    <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                </div>
            <?php endif; ?>
            <h1 class="product-detail-title"><?= esc($product['name']) ?></h1>
            <?php if ($product['description']): ?>
                <p class="product-detail-desc"><?= esc($product['description']) ?></p>
            <?php endif; ?>
        </section>
        
        <!-- Price List -->
        <section class="price-section">
            <h2 class="price-section-title">Pilih Produk</h2>
            
            <?php if (!empty($items)): ?>
                <div class="price-grid">
                    <?php foreach ($items as $item): ?>
                        <div class="price-card" 
                             data-id="<?= $item['id'] ?>"
                             data-name="<?= esc($item['name']) ?>"
                             data-price="<?= $item['price'] ?>"
                             data-image="<?= esc($item['image'] ?? '') ?>"
                             data-product="<?= esc($product['name']) ?>"
                             data-product-id="<?= $product['id'] ?>"
                             onclick="showItemDetail(this)">
                            <?php if (!empty($item['image'])): ?>
                                <div class="price-card-image">
                                    <?php $itemImgSrc = (str_starts_with($item['image'], 'http://') || str_starts_with($item['image'], 'https://')) ? $item['image'] : '/' . $item['image']; ?>
                                    <img src="<?= esc($itemImgSrc) ?>" alt="<?= esc($item['name']) ?>">
                                </div>
                            <?php endif; ?>
                                <div class="price-info-padding">
                                    <span class="price-name"><?= esc($item['name']) ?></span>
                                    <span class="price-value">Rp <?= number_format($item['price'], 0, ',', '.') ?></span>
                                    <?php if ($item['sold'] > 0): ?>
                                        <p style="font-size: 0.6rem; color: var(--text-muted); margin-top: 2px;">
                                            <?= number_format($item['sold'], 0, ',', '.') ?> terjual
                                        </p>
                                    <?php endif; ?>
                                </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-price">
                    <p>Belum ada daftar harga tersedia</p>
                </div>
            <?php endif; ?>
        </section>
        


        <!-- Footer -->
        <footer class="lynk-footer">
            <div class="social-icons">
                <a href="<?= esc($socialLinks['tiktok'] ?? 'https://tiktok.com/@La Hair Studio') ?>" class="social-icon" target="_blank" aria-label="TikTok">
                    <svg viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>
                </a>
                <a href="<?= esc($socialLinks['instagram'] ?? 'https://www.instagram.com/La Hair Studio') ?>" class="social-icon" target="_blank" aria-label="Instagram">
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
        </footer>
        
    </main>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <span id="toastMessage">Item ditambahkan ke keranjang!</span>
    </div>

    <div class="modal-overlay" id="itemModal" onclick="closeModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <button class="modal-close" onclick="closeModal()">&times;</button>
            <div id="modalImage" class="modal-image"></div>
            <h3 id="modalName" class="modal-title"></h3>
            <p id="modalProduct" class="modal-product"></p>
            <p id="modalPrice" class="modal-price"></p>
            
            <!-- Add-ons Section -->
            <div id="modalAddons" class="modal-addons" style="display: none; margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border-color);">
                <h4 style="font-size: 0.95rem; font-weight: 600; margin-bottom: 8px; color: var(--text-primary);">Pilihan Produk *</h4>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px;">Pilih minimal 1 produk yang ingin digunakan</p>
                <div id="addonsList"></div>
                <div id="addonError" style="color: var(--danger); font-size: 0.85rem; margin-top: 8px; display: none;">Pilih minimal 1 produk</div>
            </div>

            <button id="addToCartBtn" class="modal-add-btn" onclick="addToCartFromModal()">
                Tambahkan ke Keranjang
            </button>
        </div>
    </div>

    <a href="https://wa.me/628993577757" class="wa-float-clean" target="_blank" aria-label="WhatsApp Admin">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        <span>Book Appointment</span>
    </a>


    <script>
        let currentModalItem = null;
        let currentModalQty = 1;
        let currentAddons = [];
        let selectedAddons = [];

        // Update cart count on load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });

        async function showItemDetail(element) {
            const modal = document.getElementById('itemModal');
            const image = element.dataset.image;
            
            // Item data for cart
            currentModalItem = {
                itemId: element.dataset.id,
                name: element.dataset.name,
                price: parseFloat(element.dataset.price),
                product: element.dataset.product,
                productId: element.dataset.productId
            };

            currentModalQty = 1;
            selectedAddons = [];
            
            // Reset button state
            const btn = document.getElementById('addToCartBtn');
            if (btn) {
                btn.disabled = false;
                btn.textContent = 'Tambahkan ke Keranjang';
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
            }
            
            // SHOW MODAL (Details)
            document.getElementById('modalName').textContent = element.dataset.name;
            document.getElementById('modalProduct').textContent = element.dataset.product;
            document.getElementById('modalPrice').textContent = 'Rp ' + formatNumber(element.dataset.price);
            
            const imageContainer = document.getElementById('modalImage');
            if (image) {
                const imgUrl = (image.startsWith('http://') || image.startsWith('https://')) ? image : '/' + image;
                imageContainer.innerHTML = '<img src="' + imgUrl + '" alt="">';
                imageContainer.style.display = 'block';
            } else {
                imageContainer.style.display = 'none';
            }
            
            // Fetch and display add-ons
            await loadAddons(element.dataset.id);
            
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        async function loadAddons(itemId) {
            try {
                const response = await fetch(`/api/items/${itemId}/addons`);
                if (response.ok) {
                    currentAddons = await response.json();
                    displayAddons();
                } else {
                    currentAddons = [];
                    document.getElementById('modalAddons').style.display = 'none';
                }
            } catch (error) {
                console.error('Error loading addons:', error);
                currentAddons = [];
                document.getElementById('modalAddons').style.display = 'none';
            }
        }

        function displayAddons() {
            const addonsList = document.getElementById('addonsList');
            const modalAddons = document.getElementById('modalAddons');
            
            if (currentAddons.length === 0) {
                modalAddons.style.display = 'none';
                return;
            }
            
            modalAddons.style.display = 'block';
            
            // Determine if at least one addon is marked as required
            // Interpreted as: If any addon is flagged required, user MUST verify booking by selecting at least one addon.
            // If strict per-item requirement is needed, logic would be different.
            // Based on "Minimal pilih 1" context, we assume Group Requirement.
            const isMandatory = currentAddons.some(a => a.is_required == 1 && a.is_available !== false);
            
            // Update UI Texts
            const titleElement = modalAddons.querySelector('h4');
            const descElement = modalAddons.querySelector('p');
            const errorElement = document.getElementById('addonError');
            
            if (isMandatory) {
                titleElement.innerHTML = 'Pilihan Produk *';
                descElement.innerHTML = 'Pilih minimal 1 produk yang ingin digunakan';
                if (errorElement) errorElement.textContent = 'Pilih minimal 1 produk';
            } else {
                titleElement.innerHTML = 'Pilihan Produk';
                descElement.innerHTML = 'Pilih produk tambahan (opsional)';
                if (errorElement) errorElement.style.display = 'none';
            }
            
            // Render List
            addonsList.innerHTML = currentAddons.map(addon => {
                const isAvailable = addon.is_available !== false; // Default to true if not specified
                const disabledStyle = !isAvailable ? 'opacity: 0.5; cursor: not-allowed;' : '';
                const disabledAttr = !isAvailable ? 'disabled' : '';
                
                return `
                    <label style="display: flex; align-items: center; gap: 10px; padding: 12px; background: var(--bg-input); border: 2px solid var(--border-color); border-radius: 8px; margin-bottom: 8px; cursor: ${isAvailable ? 'pointer' : 'not-allowed'}; transition: all 0.2s; ${disabledStyle}">
                        <input 
                            type="checkbox" 
                            value="${addon.id}" 
                            onchange="toggleAddon(this, ${addon.id}, '${addon.name}', ${addon.price})" 
                            style="width: 18px; height: 18px; cursor: ${isAvailable ? 'pointer' : 'not-allowed'}; accent-color: var(--primary);"
                            ${disabledAttr}
                        >
                        <div style="flex: 1;">
                            <span style="font-size: 0.9rem; font-weight: 500; display: block;">${addon.name}</span>
                            ${addon.is_required == 1 ? '<span style="font-size: 0.7rem; color: var(--danger); font-weight: 600;">Wajib</span>' : ''}
                            ${!isAvailable ? '<span style="font-size: 0.7rem; color: #999; font-weight: 600;">Stok Habis</span>' : ''}
                        </div>
                        <span style="font-weight: 600; color: ${isAvailable ? 'var(--primary)' : '#999'}; font-size: 0.9rem;">+ Rp ${formatNumber(addon.price)}</span>
                    </label>
                `;
            }).join('');
            
            updateAddToCartButtonState();
        }

        function toggleAddon(checkbox, id, name, price) {
            if (checkbox.checked) {
                selectedAddons.push({ id, name, price });
            } else {
                selectedAddons = selectedAddons.filter(a => a.id !== id);
            }
            
            // Hide error if at least one addon is selected
            const errorDiv = document.getElementById('addonError');
            if (errorDiv && selectedAddons.length > 0) {
                errorDiv.style.display = 'none';
            }
            
            updateAddToCartButtonState();
        }
        
        function updateAddToCartButtonState() {
            const btn = document.getElementById('addToCartBtn');
            if (!btn) return;
            
            // Check if there are any AVAILABLE required addons
            const hasAvailableRequiredAddons = currentAddons.some(a => 
                a.is_required == 1 && a.is_available !== false
            );

            if (hasAvailableRequiredAddons && selectedAddons.length === 0) {
                btn.disabled = true;
                btn.textContent = 'Pilih Produk Terlebih Dahulu';
                btn.style.opacity = '0.6';
                btn.style.cursor = 'not-allowed';
            } else {
                btn.disabled = false;
                btn.textContent = 'Tambahkan ke Keranjang';
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
            }
        }

        function addToCartFromModal() {
            if (!currentModalItem) return;

            // Check if there are any AVAILABLE required addons
            const hasAvailableRequiredAddons = currentAddons.some(a => 
                a.is_required == 1 && a.is_available !== false
            );

            // Validate: only require selection if there are available required addons
            if (hasAvailableRequiredAddons && selectedAddons.length === 0) {
                const errorDiv = document.getElementById('addonError');
                if (errorDiv) {
                    errorDiv.style.display = 'block';
                }
                alert('Mohon pilih minimal 1 produk!');
                return;
            }

            let cart = JSON.parse(localStorage.getItem('La Hair Studio_cart') || '[]');
            
            // Check if item already exists
            const existingIndex = cart.findIndex(item => item.itemId === currentModalItem.itemId);
            
            if (existingIndex > -1) {
                // Item already in cart, show alert
                alert('Layanan ini sudah ada di keranjang!');
                return;
            }
            
            // Add new item with add-ons
            cart.push({
                ...currentModalItem,
                addons: selectedAddons
            });
            
            localStorage.setItem('La Hair Studio_cart', JSON.stringify(cart));
            
            updateCartCount();
            closeModal();
            showToast('Layanan ditambahkan ke keranjang!');
        }

        function closeModal(event) {
            if (event && event.target !== event.currentTarget) return;
            const modal = document.getElementById('itemModal');
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }

        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('La Hair Studio_cart') || '[]');
            const count = cart.length;
            const countElement = document.getElementById('cartCount');
            if (countElement) {
                countElement.textContent = count;
                countElement.style.display = count > 0 ? 'flex' : 'none';
            }
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            document.getElementById('toastMessage').textContent = message;
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2500);
        }

        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }


    </script>
</body>
</html>
