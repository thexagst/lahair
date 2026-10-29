<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Duma Augusto">
    <meta name="designer" content="Duma Augusto - Full Stack Developer">
    <meta name="description" content="La Hair Studio - Professional Hair Care & Styling & Termurah. Treatment, Smoothing, Coloring & More.">
    <title>La Hair Studio - Professional Hair Care & Styling</title>
    
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
</head>
<?php
// Get Shop Settings for Theme
$shopSettings = [];
$configPath = WRITEPATH . 'shop_settings.json';
if (file_exists($configPath)) {
    $shopSettings = json_decode(file_get_contents($configPath), true) ?? [];
}
$themeClass = ($shopSettings['theme'] ?? 'light') === 'dark' ? 'dark-theme' : '';
?>
<body>

    <main class="lynk-container">
        
        <!-- Top Nav (Cart) -->
        <div class="top-nav" style="justify-content: flex-end; padding: 10px 20px;">
            <a href="/cart" class="cart-button" id="cartButton">
                <svg viewBox="0 0 24 24" width="22" height="22">
                    <path d="M9 22c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1zm8 0c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1zM5.5 5H22l-2.5 9H7.5L5.5 5zm0 0L5 3H2" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="cart-count" id="cartCount">0</span>
            </a>
        </div>
        
        <!-- Profile Section -->
        <section class="profile-section premium-profile">
            <div class="brand-logo-wrapper">
                <center>
                    <img 
                    src="/logo/lahair_logo_black.png" 
                    alt="La Hair Studio" 
                    class="brand-logo-img"
                    >
                </center>
            </div>
            <div class="profile-badge">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                    <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                <span>Premium Hair Studio</span>
            </div>
            <p class="profile-bio"><?= esc($profileBio ?? 'Professional Hair Care & Styling') ?></p>
        </section>

        <!-- Products Menu Section -->
        <section class="links-section">
            
            <?php 
            $categoryLabels = [
                'hair' => 'Hair Treatments',
                'treatment' => 'Body & Face Treatments',
                'coloring' => 'Coloring & Art',
                'styling' => 'Hair Styling',
                'other' => 'Layanan Lainnya',
            ];

            $categoryIcons = [
                'hair' => '<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 10.9a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/><path d="M18 10.9a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/><path d="M12 2v20"/><path d="M6 10.9h12"/><path d="M12 11c-1.8 0-3.5-.7-4.8-2L3.6 5.4a1 1 0 0 1 0-1.4l1.4-1.4c.4-.4 1-.4 1.4 0L12 8.2l5.6-5.6c.4-.4 1-.4 1.4 0l1.4 1.4c.4.4.4 1 0 1.4L16.8 9c-1.3 1.3-3 2-4.8 2Z"/></svg>',
                'treatment' => '<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5Z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>',
                'coloring' => '<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"/><path d="M12 18C15.3137 18 18 15.3137 18 12C18 8.68629 15.3137 6 12 6C8.68629 6 6 8.68629 6 12C6 15.3137 8.68629 18 12 18Z"/></svg>',
                'styling' => '<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>',
            ];

            $defaultIcon = '<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>';
            
            if (!empty($productsByCategory)): 
                foreach ($productsByCategory as $category => $products): 
            ?>
                <div class="section-title">
                    <span><?= esc($categoryLabels[$category] ?? ucfirst($category)) ?></span>
                </div>
                <div class="product-grid premium-grid">
                    <?php foreach ($products as $product): ?>
                        <a href="/s/<?= esc($product['slug']) ?>" class="link-card premium-card">
                            <?php if ($product['image']): ?>
                                <div class="link-icon link-icon-img">
                                    <?php $imgSrc = (str_starts_with($product['image'], 'http://') || str_starts_with($product['image'], 'https://')) ? $product['image'] : '/' . $product['image']; ?>
                                    <img src="<?= esc($imgSrc) ?>" alt="<?= esc($product['name']) ?>">
                                </div>
                            <?php else: ?>
                                <div class="link-icon fallback-brand-icon">
                                    <?= $categoryIcons[$category] ?? $defaultIcon ?>
                                </div>
                            <?php endif; ?>
                            <div class="link-content">
                                <h3 class="link-title"><?= esc($product['name']) ?></h3>
                                <div class="link-card-meta">
                                    <?php if ($product['sold'] > 0): ?>
                                        <span class="link-sold"><?= number_format($product['sold'], 0, ',', '.') ?>+ Terjual</span>
                                    <?php else: ?>
                                        <span class="link-sold-new">Terbaru</span>
                                    <?php endif; ?>
                                    <span class="link-action-arrow">&rarr;</span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                
            <?php 
                endforeach;
            else: 
            ?>
                <div class="empty-message premium-empty">
                    <p>Belum ada produk tersedia</p>
                    <p><small>Tambahkan produk melalui <a href="/admin">Admin Panel</a></small></p>
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

            <?= $this->include('partials/watermark') ?>
        </footer>
        
    </main>

    <a href="https://wa.me/628993577757" class="wa-float-clean" target="_blank" aria-label="WhatsApp Admin">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        <span>Book Appointment</span>
    </a>


    <script>

        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });

        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('La Hair Studio_cart') || '[]');
            const count = cart.length;
            
            const countElement = document.getElementById('cartCount');
            if (countElement) {
                countElement.textContent = count;
                countElement.style.display = count > 0 ? 'flex' : 'none';
            }
            
            const overlayCount = document.getElementById('cartCountOverlay');
            if (overlayCount) {
                overlayCount.textContent = count;
                overlayCount.style.display = count > 0 ? 'flex' : 'none';
            }
        }
    </script>

</body>
</html>
