<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pesanan Berhasil - La Hair Studio">
    <title>Pesanan Berhasil - La Hair Studio</title>
    
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
        .success-mascot-container {
            text-align: center; 
            margin-bottom: 32px; 
            position: relative;
        }
        .success-mascot-img {
            width: 130px; 
            height: auto; 
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.1));
        }
        .success-badge-overlay {
            position: absolute; 
            bottom: -10px; 
            left: 50%; 
            transform: translateX(45px); 
            background: #22c55e; 
            border-radius: 50%; 
            width: 36px; 
            height: 36px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            border: 4px solid var(--bg-primary); 
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        /* Inline Rating Styles */
        .rating-container {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }
        .inline-stars {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }
        .inline-stars input { display: none; }
        .inline-stars label {
            font-size: 1.5rem;
            color: #94a3b8;
            cursor: pointer;
            transition: color 0.1s;
            padding: 0 2px;
            line-height: 1;
        }
        .inline-stars label:hover,
        .inline-stars label:hover ~ label,
        .inline-stars input:checked ~ label {
            color: #FFD700;
        }
        
        .review-form-inline {
            display: none;
            margin-top: 12px;
            background: rgba(255,255,255,0.03);
            padding: 12px;
            border-radius: 12px;
            border: 1px solid var(--border-subtle);
        }
        .review-form-inline.show {
            display: block;
        }
        
        .btn-send-inline {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-top: 6px;
        }
        
        .review-submitted-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 6px;
        }
    </style>
</head>
<body>

    <main class="lynk-container">
        
        <!-- Success Content -->
        <section class="success-section" style="padding-top: 50px; text-align: center;">
            <div class="success-mascot-container" style="margin-bottom: 24px;">
                <div style="width: 72px; height: 72px; background: rgba(139, 69, 19, 0.08); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 0;">
                    <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--primary-color)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
            </div>

            <?php if ($order['payment_status'] === 'settlement'): ?>
                <h1 class="success-title" style="margin-bottom: 4px; font-weight: 800; font-size: 1.5rem; color: var(--primary-color);">Pembayaran Berhasil!</h1>
                <p class="success-message" style="margin-bottom: 30px; color: var(--text-secondary); font-size: 0.95rem;">Pesanan kamu sedang diproses secepat kilat ⚡</p>
            <?php else: ?>
                <h1 class="success-title" style="margin-bottom: 4px; font-weight: 800; font-size: 1.75rem;">Booking Berhasil!</h1>
                <p class="success-message" style="margin-bottom: 30px; color: var(--text-secondary); font-size: 0.95rem;">Silakan lakukan pembayaran di kasir saat kedatangan.</p>
            <?php endif; ?>
            
            <div class="order-info-card" style="background: var(--bg-secondary); border: 1px solid var(--border-subtle); border-radius: 20px; padding: 24px; margin-bottom: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: var(--primary-color);"></div>
                <div class="order-number-label" style="text-align: center; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 6px;">ID Transaksi</div>
                <div class="order-number-value" style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 24px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <?= esc($order['order_number']) ?>
                </div>
                
                <div class="order-items-list" style="margin-bottom: 24px; background: rgba(255,255,255,0.02); border-radius: 12px; padding: 16px;">
                    <?php foreach ($order['items'] as $item): ?>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; font-size: 0.88rem; padding: 10px 0; border-bottom: 1px dashed rgba(255,255,255,0.1);">
                            <div style="text-align: left; min-width: 0; flex: 1;">
                                <div style="font-weight: 600; color: var(--text-primary); font-size: 0.9rem; line-height: 1.3; margin-bottom: 2px; word-break: break-word;">
                                    <?= esc($item['product_name']) ?>
                                </div>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">
                                    <?= esc($item['item_name']) ?> <span style="font-weight: 500; margin-left: 4px;">x<?= $item['quantity'] ?></span>
                                </div>
                            </div>
                            <div style="font-weight: 700; color: var(--text-primary); font-size: 0.92rem; white-space: nowrap; text-align: right; padding-top: 2px;">
                                Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="order-details">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.9rem;">
                        <span style="color: var(--text-muted);">Nama Pembeli</span>
                        <span style="font-weight: 600; color: var(--text-primary);"><?= esc($order['customer_name']) ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.9rem;">
                        <span style="color: var(--text-muted);">No. Telepon</span>
                        <span style="font-weight: 600; color: var(--text-primary);"><?= esc($order['customer_phone'] ?: '-') ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.9rem;">
                        <span style="color: var(--text-muted);">Metode Bayar</span>
                        <span style="font-weight: 600; color: var(--text-primary);"><?= $order['payment_type'] == 'pay_on_spot' ? 'Bayar di Tempat' : strtoupper(esc($order['payment_type'] ?: 'Pending')) ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-subtle);">
                        <span style="font-weight: 700; color: var(--text-primary);">Total Pembayaran</span>
                        <span style="font-size: 1.2rem; font-weight: 800; color: var(--primary-color);">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>


            <?php
            $itemsText = "";
            foreach ($order['items'] as $item) {
                $itemsText .= "- " . $item['product_name'] . " (" . $item['item_name'] . ") x" . $item['quantity'] . "\n";
            }
            
            $waMessage = "Halo Admin La Hair Studio, saya ingin konfirmasi booking saya:\n\n"
                       . "*No. Booking:* " . $order['order_number'] . "\n"
                       . "*Nama:* " . $order['customer_name'] . "\n"
                       . "*No. WhatsApp:* " . ($order['customer_phone'] ?: '-') . "\n"
                       . "*Layanan:*\n" . $itemsText . "\n"
                       . "*Total Pembayaran:* Rp " . number_format($order['total_amount'], 0, ',', '.') . "\n\n"
                       . "Mohon segera dikonfirmasi jadwal booking saya. Terima kasih!";
            
            $waUrl = "https://wa.me/628993577757?text=" . rawurlencode($waMessage);
            ?>

            <div class="action-buttons" style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 32px;">
                
                <div style="text-align: center; margin-bottom: 4px;">
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 8px;">Harap whatsapp admin kami melalui link ini.</p>
                    <a href="<?= $waUrl ?>" target="_blank" class="btn-wa-confirm" style="display: flex; align-items: center; justify-content: center; gap: 10px; background: #25d366; color: white; text-align: center; padding: 16px; border-radius: 14px; text-decoration: none; font-weight: 700; font-size: 1rem; transition: all 0.3s ease; box-shadow: 0 8px 20px rgba(37, 211, 102, 0.3);">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Konfirmasi via WhatsApp
                    </a>
                </div>
                
                <a href="/" class="btn-back-home" style="display: block; background: transparent; border: 1px solid var(--border-subtle); color: var(--text-secondary); text-align: center; padding: 16px; border-radius: 14px; text-decoration: none; font-weight: 700; font-size: 1rem; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 1.5px;">Kembali ke Beranda</a>
            </div>
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




    <div class="toast" id="toast">
        <span id="toastMessage"></span>
    </div>

    <script>
        function showReviewForm(itemId) {
            const form = document.getElementById('review-form-' + itemId);
            form.classList.add('show');
        }

        async function submitInlineReview(btn, itemId, productId) {
            const originalText = btn.textContent;
            
            // Get rating
            const ratingInput = document.querySelector(`input[name="rating-${itemId}"]:checked`);
            if (!ratingInput) {
                alert('Silakan pilih bintang dulu ⭐');
                return;
            }
            const rating = ratingInput.value;
            
            // Get text
            const text = document.getElementById('review-text-' + itemId).value; 
            
            // Use customer name from order if available, or guest
            const customerName = <?= json_encode($order['customer_name'] ?? 'Guest') ?>;
            
            // CSRF Token
            const csrfName = '<?= csrf_token() ?>';
            const csrfHash = '<?= csrf_hash() ?>';
            
            try {
                const response = await fetch('/product/review', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfHash
                    },
                    body: JSON.stringify({
                        order_id: <?= $order['id'] ?>,
                        product_id: productId,
                        rating: rating,
                        customer_name: customerName,
                        review_text: text
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message);
                    
                    // Replace the rating area with "Submitted" badge
                    const ratingArea = document.getElementById('rating-area-' + itemId);
                    ratingArea.innerHTML = `
                        <div class="review-submitted-badge">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                            Ulasan Terkirim
                        </div>
                    `;
                } else {
                    alert(result.message || 'Gagal mengirim ulasan');
                    // Reset radio button if failed? 
                    // For now, just alert.
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi');
            }
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            document.getElementById('toastMessage').textContent = message;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 2500);
        }
    </script>
</body>

</html>
