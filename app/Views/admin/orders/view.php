<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="page-header" style="flex-wrap: wrap; gap: 8px;">
    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
        <a href="/admin/orders" class="btn btn-icon" title="Kembali" style="display: flex; align-items: center; justify-content: center;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        </a>
        <h1 class="page-title" style="font-size: clamp(1rem, 3vw, 1.3rem);">Order #<?= esc($order['order_number']) ?></h1>
        <span class="badge badge-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span>
        <?php if (!empty($orderBranch)): ?>
            <span class="badge" style="display: inline-flex; align-items: center; gap: 5px; background: var(--primary-light, #fdf5f2); color: var(--primary, #8B4513); border: 1px solid rgba(139, 69, 19, 0.2); font-weight: 600;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Cabang <?= esc($orderBranch['name']) ?></span>
            </span>
        <?php endif; ?>
    </div>
</div>



<div class="app-grid">
    <!-- LEFT: Main Content (Items) -->
    <div class="main-content">
        <div class="card">
            <!-- Header -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                 <h3 class="card-title" style="margin-bottom: 0; border: none;">Detail Pesanan</h3>
                 <span style="font-size: 0.85rem; color: var(--text-muted); background: var(--bg-input); padding: 4px 12px; border-radius: 20px;">
                    <?= date('d M Y • H:i', strtotime($order['created_at'])) ?>
                 </span>
            </div>

            <!-- Customer Data Grid (Top) -->
            <div style="background: var(--bg-input); padding: 24px; border-radius: 12px; margin-bottom: 30px;">
                <h4 style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; margin-bottom: 20px; letter-spacing: 0.1em; font-weight: 700;">Informasi Pelanggan</h4>
                
                <!-- Birthday Special Alert Banner -->
                <?php if (!empty($birthdayInfo)): ?>
                    <?php
                        $isToday = $birthdayInfo['is_today'];
                        $diffDays = $birthdayInfo['diff_days'];
                        $ageTurning = $birthdayInfo['age_turning'];
                        $custName = trim($order['customer_name'] ?? 'Kakak');
                        $cleanPhone = preg_replace('/\D/', '', $order['customer_phone'] ?? '');
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                        $msgText = "Halo Kak {$custName},\n\nKami segenap tim La Hair Studio mengucapkan selamat ulang tahun. Semoga senantiasa diberikan kesehatan, kebahagiaan, dan kelancaran dalam segala aktivitas.\n\nSebagai bentuk apresiasi atas kepercayaan Kakak kepada La Hair Studio, kami memberikan penawaran istimewa berupa Diskon Spesial Ulang Tahun sebesar 20% untuk seluruh layanan perawatan rambut di salon kami.\n\nUntuk menikmati penawaran ini, Kakak cukup menunjukkan pesan ini kepada staf kami saat berkunjung.\n\nTerima kasih atas kepercayaannya pada La Hair Studio. Kami nantikan kedatangan Kakak kembali.";
                        $bdayWaUrl = "https://wa.me/{$cleanPhone}?text=" . rawurlencode($msgText);
                    ?>
                    <div style="margin-bottom: 20px; padding: 14px 18px; border-radius: 10px; background: linear-gradient(135deg, rgba(254, 243, 199, 0.5) 0%, rgba(255, 237, 213, 0.7) 100%); border: 1px solid rgba(234, 88, 12, 0.35); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 38px; height: 38px; border-radius: 50%; background: #ea580c; color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12v10H4V12"/><path d="M2 7h20v5H2z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #9a3412; font-size: 0.95rem;">
                                    <?= $isToday ? "Customer Sedang Ulang Tahun Hari Ini" : "Ulang Tahun Customer Dalam {$diffDays} Hari Lagi ({$birthdayInfo['date_formatted']})" ?>
                                </div>
                                <div style="font-size: 0.8rem; color: #7c2d12; margin-top: 2px;">
                                    <?= $isToday ? "Hari ini {$custName} berulang tahun" . ($ageTurning > 0 ? " ke-{$ageTurning}" : "") . ". Berikan ucapan dan diskon atau penawaran terbaik saat pembayaran." : "Ulang tahun" . ($ageTurning > 0 ? " ke-{$ageTurning}" : "") . " sebentar lagi. Kirimkan penawaran spesial via WhatsApp sekarang." ?>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span class="badge" style="background: white; color: #c2410c; border: 1px solid rgba(234, 88, 12, 0.3); font-weight: 700; font-size: 0.76rem; padding: 5px 12px; border-radius: 20px;">
                                <?= $isToday ? 'Hari Ini' : "H-{$diffDays}" ?>
                            </span>
                            <a href="<?= $bdayWaUrl ?>" target="_blank" class="btn btn-sm" style="display: inline-flex; align-items: center; gap: 6px; background: #25D366; color: white; padding: 7px 14px; font-size: 0.8rem; font-weight: 600; border-radius: 6px; text-decoration: none; box-shadow: 0 1px 3px rgba(37,211,102,0.3);">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.983z"/></svg>
                                Kirim Promo Ultah
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="customer-info-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px;">
                    <!-- Name -->
                    <div style="display: flex; gap: 14px;">
                        <div style="width: 42px; height: 42px; background: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.03); color: var(--primary);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                        <div>
                            <div class="info-label" style="font-size: 0.8rem; margin-bottom: 4px;">Nama Pelanggan</div>
                            <div class="info-value" style="font-size: 0.95rem;"><?= esc($order['customer_name']) ?></div>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div style="display: flex; gap: 14px;">
                        <div style="width: 42px; height: 42px; background: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.03); color: var(--success);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </div>
                        <div>
                            <div class="info-label" style="font-size: 0.8rem; margin-bottom: 4px;">WhatsApp</div>
                            <div class="info-value">
                                <a href="https://wa.me/<?= preg_replace('/^0/', '62', $order['customer_phone']) ?>" target="_blank" style="text-decoration: none; color: var(--text); display: flex; align-items: center; gap: 4px; transition: color 0.2s;">
                                    <?= esc($order['customer_phone']) ?> 
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="opacity: 0.5;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Birthdate -->
                    <?php if (!empty($order['customer_birthdate'])): ?>
                    <div style="display: flex; gap: 14px;">
                        <div style="width: 42px; height: 42px; background: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.03); color: #ea580c;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </div>
                        <div>
                            <div class="info-label" style="font-size: 0.8rem; margin-bottom: 4px;">Tanggal Lahir</div>
                            <div class="info-value" style="font-size: 0.95rem; font-weight: 600;">
                                <?= date('d M Y', strtotime($order['customer_birthdate'])) ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Roblox/Game ID -->
                    <?php if ($order['game_id']): ?>
                    <div style="display: flex; gap: 14px;">
                        <div style="width: 42px; height: 42px; background: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.03); color: #8b5cf6;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                        </div>
                        <div>
                            <div class="info-label" style="font-size: 0.8rem; margin-bottom: 4px;">Roblox Class</div>
                            <div class="info-value" style="font-size: 0.95rem;"><?= esc($order['game_id']) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Notes -->
                <?php if ($order['notes']): ?>
                <div style="margin-top: 24px; padding-top: 20px; border-top: 1px dashed rgba(0,0,0,0.1);">
                     <div class="info-label" style="margin-bottom: 10px;">Catatan Tambahan</div>
                     <div style="background: white; padding: 16px; border-radius: 10px; font-size: 0.9rem; color: var(--text-sidebar); border: 1px solid rgba(0,0,0,0.05); line-height: 1.6;">
                        <?= esc($order['notes']) ?>
                     </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Service Table -->
            <!-- Service Table -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h4 style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em; padding-left: 4px; font-weight: 700; margin: 0;">Rincian Layanan</h4>
                <?php if ($order['status'] !== 'completed' && $order['status'] !== 'cancelled'): ?>
                <button type="button" class="btn btn-sm btn-primary" onclick="openAddItemModal()" style="font-size: 0.8rem; padding: 6px 12px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    <span>Tambah Layanan</span>
                </button>
                <?php endif; ?>
            </div>
            <div style="border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
                <table class="table service-table">
                    <thead>
                        <tr>
                            <th style="background: var(--bg-input); border-bottom: 1px solid var(--border);">Layanan</th>
                            <th style="background: var(--bg-input); border-bottom: 1px solid var(--border);">Jadwal</th>
                            <th style="background: var(--bg-input); border-bottom: 1px solid var(--border);">Harga</th>
                            <?php if ($order['status'] !== 'completed' && $order['status'] !== 'cancelled'): ?>
                                <th style="background: var(--bg-input); border-bottom: 1px solid var(--border); width: 50px;"></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['items'] as $item): ?>
                            <tr>
                                <td style="padding: 16px;" data-label="Layanan">
                                    <div style="font-weight: 600; color: var(--text); font-size: 0.95rem;"><?= esc($item['product_name']) ?></div>
                                    <div style="color: var(--text-muted); font-size: 0.85rem; margin-top: 4px;"><?= esc($item['item_name']) ?></div>
                                    
                                    <?php if ($item['addon_items']): ?>
                                        <div style="margin-top: 8px; font-size: 0.85rem; background: var(--success-bg); display: inline-block; padding: 4px 10px; border-radius: 6px; border: 1px solid rgba(22, 101, 52, 0.1);">
                                            <?php foreach (json_decode($item['addon_items'], true) as $addon): ?>
                                                <div style="color: #166534; font-weight: 500;">+ <?= esc($addon['name']) ?></div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 16px;" data-label="Jadwal">
                                    <?php if ($item['booking_date']): ?>
                                        <div style="font-weight: 500;"><?= date('d M Y', strtotime($item['booking_date'])) ?></div>
                                        <div style="color: var(--text-muted); font-size: 0.85rem; margin-top: 2px;">Pukul <?= date('H:i', strtotime($item['booking_time'])) ?></div>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted);">-</span>
                                    <?php endif; ?>
                                </td>
                                </td>
                                <td style="padding: 16px; font-weight: 600; color: var(--text);" data-label="Harga">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                <?php if ($order['status'] !== 'completed' && $order['status'] !== 'cancelled'): ?>
                                <td style="padding: 16px; text-align: center;" data-label="">
                                    <form action="/admin/orders/delete-item/<?= $order['order_number'] ?>/<?= $item['id'] ?>" method="post" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-icon" style="width: 32px; height: 32px; color: #ef4444; background: rgba(239, 68, 68, 0.1);" onclick="return confirm('Hapus item ini?')">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="<?= ($order['status'] !== 'completed' && $order['status'] !== 'cancelled') ? '4' : '3' ?>" style="padding: 0; border-top: 1px solid var(--border); background: #fafafa;">
                                <div style="padding: 16px 20px;">
                                    <?php if (!empty($order['discount_amount']) && (float)$order['discount_amount'] > 0): ?>
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 0.88rem;">
                                        <span style="color: var(--text-muted);">Subtotal Layanan</span>
                                        <span style="font-weight: 600; color: var(--text);">Rp <?= number_format($order['subtotal_amount'] ?? $order['total_amount'], 0, ',', '.') ?></span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; font-size: 0.88rem; color: #16a34a;">
                                        <span style="display: flex; align-items: center; gap: 6px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="19" y1="5" x2="5" y2="19"></line><circle cx="6.5" cy="6.5" r="2.5"></circle><circle cx="17.5" cy="17.5" r="2.5"></circle></svg>
                                            <span>Diskon <?= ($order['discount_type'] === 'percent' && (float)$order['discount_percent'] > 0) ? '(' . (float)$order['discount_percent'] . '%)' : '' ?></span>
                                        </span>
                                        <span style="font-weight: 700;">-Rp <?= number_format($order['discount_amount'], 0, ',', '.') ?></span>
                                    </div>
                                    <div style="border-top: 1px dashed var(--border); margin-bottom: 12px;"></div>
                                    <?php endif; ?>

                                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div style="width: 38px; height: 38px; border-radius: 8px; background: rgba(139, 69, 19, 0.08); display: flex; align-items: center; justify-content: center; color: var(--primary); flex-shrink: 0;">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                                            </div>
                                            <div>
                                                <div style="font-weight: 700; color: var(--text); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.04em;">Total Pembayaran</div>
                                                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">
                                                    Metode: <strong style="color: var(--text);"><?= $order['payment_type'] ? esc($order['payment_type']) : 'Belum Dipilih' ?></strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="text-align: right;">
                                            <div style="font-size: 1.45rem; font-weight: 800; color: var(--primary); line-height: 1.2;">
                                                Rp <?= number_format($order['total_amount'], 0, ',', '.') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- RIGHT: Sidebar (Status & Worker) -->
    <div class="sidebar">
        
        <!-- Status Card -->
        <?php if ($order['status'] !== 'completed' && $order['status'] !== 'cancelled'): ?>
        <div class="card" style="margin-bottom: 24px;">
            <h3 class="card-title">Update Status & Pembayaran</h3>
            <form action="/admin/orders/status/<?= $order['order_number'] ?>" method="post" id="statusForm">
                <?= csrf_field() ?>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="payment_type" id="paymentTypeSelect" class="form-select">
                        <option value="">-- Belum Dipilih --</option>
                        <option value="CASH" <?= $order['payment_type'] == 'CASH' ? 'selected' : '' ?>>CASH</option>
                        <option value="BANK" <?= $order['payment_type'] == 'BANK' ? 'selected' : '' ?>>BANK</option>
                        <option value="QRIS" <?= $order['payment_type'] == 'QRIS' ? 'selected' : '' ?>>QRIS</option>
                        <option value="CREDIT CARD" <?= $order['payment_type'] == 'CREDIT CARD' ? 'selected' : '' ?>>CREDIT CARD</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label class="form-label">Status Pesanan</label>
                    <select name="status" id="statusSelect" class="form-select" style="font-weight: 500;" onchange="onStatusChange()">
                        <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                
                <button type="button" class="btn btn-primary" id="btnSubmitStatus" style="width: 100%; font-weight: 600;" onclick="submitStatusForm()">Simpan Perubahan</button>
            </form>
        </div>
        <script>
        function onStatusChange() {
            var statusSelect = document.getElementById('statusSelect');
            var submitBtn = document.getElementById('btnSubmitStatus');
            if (submitBtn) {
                if (statusSelect.value === 'completed') {
                    submitBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px; vertical-align:text-bottom;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>Selesaikan & Buat Invoice';
                    submitBtn.style.backgroundColor = 'var(--primary)';
                } else {
                    submitBtn.textContent = 'Simpan Perubahan';
                }
            }
        }

        function submitStatusForm() {
            var statusSelect = document.getElementById('statusSelect');
            var paymentTypeSelect = document.getElementById('paymentTypeSelect');
            var newVal = statusSelect.value;
            var oldValue = '<?= $order['status'] ?>';
            
            var workerIdInput = document.getElementById('workerIdInput');
            var assignedWorkerId = workerIdInput ? workerIdInput.value : '';

            if (newVal === 'completed') {
                if (!assignedWorkerId) {
                    showToastMsg('Mohon tugaskan pekerja terlebih dahulu', 'error');
                    statusSelect.value = oldValue;
                    onStatusChange();
                    return;
                }
                openCompleteInvoiceModal();
                return;
            }

            document.getElementById('statusForm').submit();
        }
        
        function showToastMsg(msg, type) {
            var color = type === 'success' ? '#2ecc71' : '#e74c3c';
            if (typeof Toastify !== 'undefined') {
                Toastify({
                    text: msg,
                    duration: 2500,
                    gravity: 'top',
                    position: 'right',
                    style: {
                        background: '#fff',
                        color: '#333',
                        borderLeft: '5px solid ' + color,
                        borderRadius: '8px',
                        boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                        padding: '14px 18px',
                        fontWeight: '500'
                    }
                }).showToast();
            } else {
                alert(msg);
            }
        }
        </script>
        <?php endif; ?>

        <!-- Read Only Status & Payment Card -->
        <?php if ($order['status'] === 'completed' || $order['status'] === 'cancelled'): ?>
        <div class="card" style="margin-bottom: 24px; background: #fafafa; border: 1px solid var(--border);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <h3 class="card-title" style="margin: 0; border: none; font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; padding: 0;">Status & Pembayaran</h3>
            </div>
            
            <div style="font-size: 0.9rem; color: var(--text);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="color: var(--text-muted);">Status</span>
                    <span style="font-weight: 600; color: var(--text);"><?= ucfirst($order['status']) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="color: var(--text-muted);">Pembayaran</span>
                    <span style="font-weight: 600; color: var(--text);"><?= $order['payment_type'] ? esc($order['payment_type']) : '-' ?></span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Total</span>
                    <span style="font-weight: 600; color: var(--success);">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Worker Assignment -->
        <?php 
            $assignedList = [];
            if (!empty($order['workers'])) {
                foreach ($order['workers'] as $w) {
                    $assignedList[] = [
                        'id'   => (string)($w['worker_id'] ?? $w['id']),
                        'name' => $w['username'] ?? $w['name'] ?? 'Pekerja'
                    ];
                }
            } elseif (!empty($order['worker_name']) && !empty($order['worker_id'])) {
                $assignedList[] = [
                    'id'   => (string)$order['worker_id'],
                    'name' => $order['worker_name']
                ];
            }
            $hasAssigned = !empty($assignedList);
            $assignedCount = count($assignedList);
        ?>
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid var(--border);">
                <h3 class="card-title" style="margin: 0; padding: 0; border: none;">Penugasan</h3>
                <span id="workerCountBadge" style="font-size: 0.75rem; font-weight: 600; padding: 3px 9px; border-radius: 12px; background: <?= $hasAssigned ? 'rgba(34, 197, 94, 0.1)' : 'var(--bg-input)' ?>; color: <?= $hasAssigned ? '#16a34a' : 'var(--text-muted)' ?>; transition: all 0.2s;">
                    <span id="assignedCount"><?= $assignedCount ?></span>/5 Pekerja
                </span>
            </div>
            
            <?php if ($order['status'] === 'completed' || $order['status'] === 'cancelled'): ?>
                <!-- Read Only View for Finalized Orders -->
                 <div style="text-align: center; padding: 10px 0;">
                    <?php if (!empty($order['workers'])): ?>
                        <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px;">
                            <?php foreach ($order['workers'] as $idx => $w): ?>
                                <div style="display: flex; align-items: center; gap: 10px; padding: 8px 12px; background: var(--bg-input); border-radius: 8px; border: 1px solid var(--border); text-align: left;">
                                    <div style="width: 32px; height: 32px; background: var(--primary); color: white; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; flex-shrink: 0;">
                                        <?= strtoupper(substr($w['username'] ?? 'Stylist', 0, 2)) ?>
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <div style="font-weight: 600; font-size: 0.88rem; color: var(--text); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= esc($w['username'] ?? 'Stylist') ?></div>
                                        <div style="font-size: 0.72rem; color: var(--text-muted);">Stylist #<?= $idx + 1 ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php elseif (!empty($order['worker_name'])): ?>
                        <div style="width: 50px; height: 50px; background: var(--bg-input); color: var(--primary); border: 2px solid var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 700; margin: 0 auto 10px;">
                            <?= strtoupper(substr($order['worker_name'], 0, 2)) ?>
                        </div>
                        <h4 style="margin: 0 0 16px; font-size: 1rem; color: var(--text);"><?= esc($order['worker_name']) ?></h4>
                    <?php else: ?>
                        <div style="padding: 15px; color: var(--text-muted); font-style: italic; font-size: 0.88rem;">
                            Belum ada pekerja yang ditugaskan.
                        </div>
                    <?php endif; ?>

                    <div style="padding: 10px 12px; background: rgba(0,0,0,0.03); border-radius: 8px; font-size: 0.8rem; color: var(--text-muted); border: 1px dashed var(--border); display: flex; align-items: start; gap: 8px; text-align: left;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-top: 2px; flex-shrink: 0;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <div>
                            <span style="display: block; margin-bottom: 2px; font-weight: 600;">Penugasan Terkunci</span>
                            Pesanan ini sudah <strong><?= ucfirst($order['status']) ?></strong>.
                        </div>
                    </div>

                    <!-- Invoice / Struk Button -->
                    <button type="button" class="btn btn-outline-primary" style="width: 100%; margin-top: 14px; display: flex; align-items: center; justify-content: center; gap: 8px;" onclick="openInvoiceModal()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        Invoice / Struk
                    </button>
                </div>

            <?php else: ?>
                <!-- Editable View (Drag & Drop + Click to Assign, Max 5) -->
                <!-- Draggable Workers List (Compact) -->
                <div style="margin-bottom: 16px;">
                    <input type="text" id="workerSearch" class="form-input form-sm" placeholder="Cari pekerja..." style="margin-bottom: 8px;">
                    <div class="worker-list" id="workerList">
                        <?php if (!empty($workers)): ?>
                            <?php foreach ($workers as $worker): 
                                $isChipAssigned = in_array((string)$worker['id'], array_column($assignedList, 'id'));
                            ?>
                                <div class="worker-chip <?= $isChipAssigned ? 'is-assigned' : '' ?>" draggable="true" data-id="<?= $worker['id'] ?>" data-name="<?= esc($worker['username']) ?>" title="Klik atau tarik untuk menugaskan">
                                    <div class="avatar-xs"><?= strtoupper(substr($worker['username'], 0, 2)) ?></div>
                                    <span><?= esc($worker['username']) ?></span>
                                    <span class="chip-add-plus" style="font-size: 0.75rem; opacity: 0.5; margin-left: 2px;">+</span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Worker Drop Zone -->
                <div class="drop-zone" id="dropZone" style="display: flex; flex-direction: column; gap: 8px; justify-content: center; min-height: 90px; padding: 12px;">
                    <div class="drop-placeholder" id="dropPlaceholder" style="<?= $hasAssigned ? 'display: none;' : '' ?>">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--text-muted); opacity: 0.5;"><circle cx="12" cy="12" r="10"/><path d="M16 12l-4 4-4-4"/><line x1="12" y1="8" x2="12" y2="16"/></svg>
                        <p style="margin: 4px 0 2px; font-size: 0.84rem; color: var(--text-muted); font-weight: 500;">Tarik atau klik pekerja ke sini</p>
                        <span style="font-size: 0.73rem; color: var(--text-muted); opacity: 0.8;">Bisa lebih dari 1 (Maks. 5 orang)</span>
                    </div>

                    <!-- Container for assigned workers list -->
                    <div id="assignedWorkersContainer" style="width: 100%; display: <?= $hasAssigned ? 'flex' : 'none' ?>; flex-direction: column; gap: 6px;">
                        <?php if ($hasAssigned): ?>
                            <?php foreach ($assignedList as $idx => $w): 
                                $initials = strtoupper(substr($w['name'], 0, 2));
                            ?>
                                <div class="assigned-worker-row" data-id="<?= esc($w['id']) ?>">
                                    <div style="display: flex; align-items: center; gap: 10px; min-width: 0;">
                                        <div style="width: 32px; height: 32px; background: var(--primary); color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; flex-shrink: 0;">
                                            <?= esc($initials) ?>
                                        </div>
                                        <div style="min-width: 0; text-align: left;">
                                            <div style="font-weight: 600; font-size: 0.86rem; color: var(--text); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                <?= esc($w['name']) ?>
                                            </div>
                                            <div style="font-size: 0.72rem; color: var(--text-muted); line-height: 1.1;">Stylist #<?= $idx + 1 ?></div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-remove-worker" onclick="window.removeWorker('<?= esc($w['id']) ?>')" title="Hapus pekerja ini">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div id="dropSlotHint" style="display: <?= ($hasAssigned && $assignedCount < 5) ? 'block' : 'none' ?>; width: 100%; padding: 6px 10px; border: 1.5px dashed var(--border); border-radius: 8px; font-size: 0.78rem; color: var(--text-muted); text-align: center; cursor: pointer; transition: all 0.2s;">
                        + Tarik / klik pekerja untuk menambah (<span id="slotsLeft"><?= max(0, 5 - $assignedCount) ?></span> slot lagi)
                    </div>
                </div>

                <form action="/admin/orders/assign/<?= $order['order_number'] ?>" method="post" id="assignForm" style="margin-top: 15px;" 
                      data-initial-workers='<?= htmlspecialchars(json_encode($assignedList), ENT_QUOTES, 'UTF-8') ?>'
                      data-worker-name="<?= esc(!empty($order['worker_names']) ? $order['worker_names'] : ($order['worker_name'] ?? '')) ?>"
                      data-silent="true">
                    <?= csrf_field() ?>
                    <div id="workerHiddenInputs">
                        <?php foreach ($assignedList as $w): ?>
                            <input type="hidden" name="worker_ids[]" value="<?= esc($w['id']) ?>">
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="worker_id" id="workerIdInput" value="<?= !empty($assignedList) ? esc($assignedList[0]['id']) : ($order['worker_id'] ?? '') ?>">
                    <input type="hidden" name="worker_fee" id="workerFeeInput" value="0">
                    <button type="submit" class="btn btn-secondary" style="width: 100%; margin-top: 10px; display: none;">Simpan</button>
                </form>

            <?php endif; ?>

        </div>



        <?php if ($order['status'] === 'completed'): ?>

        <!-- Stock Usage Card -->

        <div class="card" style="margin-top: 18px;">

            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">

                <h3 class="card-title" style="margin: 0;">Pemakaian Stok</h3>

                <span data-stock-badge style="font-size: 0.72rem; <?= empty($stockUsages) ? 'background: rgba(245,158,11,0.12); color: #d97706;' : 'background: rgba(34,197,94,0.12); color: #16a34a;' ?> padding: 3px 10px; border-radius: 20px; font-weight: 600;">
                    <?= empty($stockUsages) ? 'Belum Dicatat' : count($stockUsages) . ' item' ?>
                </span>

            </div>



            <div>

                <div style="margin-bottom: 8px;">

                    <label style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-bottom: 4px;">Bahan</label>

                    <select id="stockInventoryId" class="form-select" style="width: 100%; font-size: 0.88rem;" onchange="updateStockUnit(this)">
                        <option value="">-- Cari atau pilih bahan --</option>

                        <?php foreach ($inventories as $inv): 
                            $hasSubUnit = !empty($inv['unit_value']) && (float)$inv['unit_value'] > 0;
                            $inputUnit = $hasSubUnit && !empty($inv['sub_unit']) ? $inv['sub_unit'] : $inv['unit'];
                        ?>

                            <option value="<?= $inv['id'] ?>"

                                data-unit="<?= esc($inputUnit) ?>"

                                data-unit-value="<?= $inv['unit_value'] ?? '' ?>"

                                data-stock="<?= $inv['stock'] ?>">

                                <?php if ($hasSubUnit): ?>

                                    <?= esc($inv['name']) ?> (<?= number_format($inv['stock'], 1, ',', '.') ?> <?= esc($inv['unit']) ?> &middot; 1 <?= esc($inv['unit']) ?> = <?= number_format((float)$inv['unit_value'], 0, ',', '.') ?> <?= esc($inv['sub_unit'] ?? 'ml') ?>)

                                <?php else: ?>

                                    <?= esc($inv['name']) ?> (<?= number_format($inv['stock'], 1, ',', '.') ?> <?= esc($inv['unit']) ?>)

                                <?php endif; ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div style="display: flex; gap: 8px; align-items: flex-end;">

                    <div style="flex: 1;">

                        <label style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-bottom: 4px;">

                            Berapa <span id="stockUnitLabel" style="font-weight: 700; color: var(--primary);">...</span>

                        </label>

                        <input type="number" id="stockQtyInput" class="form-input" placeholder="0" min="0" step="any"

                               style="font-size: 0.88rem;"

                               onkeydown="if(event.key==='Enter'){event.preventDefault();saveStockUsage();}">

                    </div>

                    <button type="button" onclick="saveStockUsage()" class="btn btn-primary"

                            style="padding: 10px 14px; white-space: nowrap; font-size: 0.85rem; height: 42px;">

                        ↵

                    </button>

                </div>

                <div id="stockErrorMsg" style="font-size: 0.8rem; color: #ef4444; margin-top: 6px; display: none;"></div>

            </div>



            <div id="stockUsageList" style="margin-top: 14px; <?= empty($stockUsages) ? 'display:none;' : '' ?>">

                <div style="font-size: 0.72rem; color: var(--text-muted); margin-bottom: 8px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border-top: 1px solid var(--border); padding-top: 12px;">Sudah Dicatat</div>

                <ul id="stockUsageItems" style="list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 6px;">

                    <?php foreach ($stockUsages as $usage): 
                        $hasSubUnit = !empty($usage['unit_value']) && (float)$usage['unit_value'] > 0;
                        $displayUnit = $hasSubUnit && !empty($usage['sub_unit']) ? $usage['sub_unit'] : $usage['unit'];
                    ?>

                    <li data-id="<?= $usage['id'] ?>" style="display: flex; align-items: center; justify-content: space-between; background: var(--bg-input); border-radius: 8px; padding: 8px 10px; font-size: 0.85rem;">

                        <div>

                            <span style="font-weight: 600;"><?= esc($usage['inventory_name']) ?></span>

                            <span style="color: var(--text-muted); margin-left: 6px;">× <?= rtrim(rtrim(number_format((float)$usage['quantity'], 2, ',', '.'), '0'), ',') ?> <?= esc($displayUnit) ?></span>

                        </div>

                        <button type="button" onclick="deleteStockUsage(<?= $usage['id'] ?>, this)"

                                style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.8rem; padding: 0 4px; font-weight: 500;" title="Hapus">Hapus</button>

                    </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        </div>

        <?php endif; ?>



    </div>



</div>

<style>
    /* ── Layout Grid ── */
    .app-grid {
        display: grid;
        grid-template-columns: 3fr 1fr;
        gap: 24px;
        align-items: start;
    }
    
    @media (max-width: 1080px) {
        .app-grid {
            grid-template-columns: 1fr;
        }
        /* On tablet: sidebar moves below main */
        .app-grid .sidebar {
            order: 2;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .app-grid .main-content {
            order: 1;
        }
    }

    /* ── Cards ── */
    .card { padding: 20px; border-radius: 12px; }
    .card-title { font-size: 1rem; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid var(--border); }
    
    .info-label { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 2px; }
    .info-value { font-weight: 500; }

    /* ── Service Table – Responsive ── */
    .service-table { width: 100%; }
    @media (max-width: 600px) {
        /* Hide regular table, use card layout */
        .service-table thead { display: none; }
        .service-table tbody tr {
            display: block;
            border: 1px solid var(--border);
            border-radius: 10px;
            margin-bottom: 10px;
            padding: 12px;
            background: var(--bg-card);
        }
        .service-table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0 !important;
            border: none !important;
            font-size: 0.88rem;
        }
        .service-table tbody td::before {
            content: attr(data-label);
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            flex-shrink: 0;
            margin-right: 10px;
        }
        .service-table tfoot tr {
            display: block;
            padding: 0;
            background: #fafafa;
            border-radius: 10px;
            border: 1px solid var(--border);
        }
        .service-table tfoot td {
            display: block !important;
            border: none !important;
            background: transparent !important;
            padding: 0 !important;
        }
    }

    /* ── Drop Zone ── */
    .drop-zone {
        border: 2px dashed var(--border);
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        background: var(--bg-body);
        transition: all 0.2s;
        min-height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .drop-zone.drag-over { border-color: var(--primary); background: rgba(255, 107, 44, 0.05); }
    .drop-placeholder p { margin: 5px 0 0; font-size: 0.85rem; color: var(--text-muted); }

    .selected-worker-preview { 
        display: flex; 
        align-items: center; 
        gap: 10px; 
        position: relative; 
        width: 100%;
        padding-right: 20px; 
    }
    .worker-avatar { width: 40px; height: 40px; background: var(--primary); color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; }
    .worker-role { font-size: 0.75rem; color: var(--text-muted); }
    
    /* ── Worker Chips ── */
    .worker-list { display: flex; flex-wrap: wrap; gap: 8px; max-height: 200px; overflow-y: auto; }
    .worker-chip {
        display: flex; align-items: center; gap: 8px;
        padding: 6px 12px;
        background: var(--bg-input);
        border: 1px solid var(--border);
        border-radius: 20px;
        font-size: 0.85rem;
        cursor: grab;
        user-select: none;
    }
    .worker-chip:hover { border-color: var(--primary); }
    .worker-chip.is-assigned {
        opacity: 0.6;
        border-color: #22c55e;
        background: rgba(34, 197, 94, 0.08);
    }
    .worker-chip.is-assigned .chip-add-plus {
        display: none;
    }
    .worker-chip.is-assigned::after {
        content: "✓";
        font-size: 0.75rem;
        color: #16a34a;
        font-weight: 700;
        margin-left: 2px;
    }
    .avatar-xs { width: 20px; height: 20px; background: #64748b; color: white; border-radius: 50%; font-size: 0.6rem; display: flex; align-items: center; justify-content: center; }

    .assigned-worker-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--bg-card, #fff);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 6px 10px;
        transition: all 0.2s;
    }
    .assigned-worker-row:hover {
        border-color: var(--primary);
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }
    .btn-remove-worker {
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .btn-remove-worker:hover {
        color: #ef4444;
        background: rgba(239, 68, 68, 0.1);
    }

    .profit-summary { background: rgba(34, 197, 94, 0.1); padding: 10px; border-radius: 8px; margin-top: 10px; }
    .profit-summary .row { display: flex; justify-content: space-between; font-size: 0.85rem; color: #166534; font-weight: 500; }
    
    .btn-icon { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: var(--bg-input); color: var(--text); padding: 0; }
    
    /* Hide Number Input Spinners */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
    
    .btn-remove {
        position: absolute;
        top: -8px;
        right: -8px;
        background: var(--bg-body);
        border: 1px solid var(--border);
        color: var(--text-muted);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        padding: 0;
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .btn-remove:hover { background: white; color: #ef4444; border-color: #ef4444; }

    /* ── SortableJS ── */
    .sortable-ghost { opacity: 0.4; background: #e2e8f0; border: 1px dashed #94a3b8; }
    .sortable-drag { cursor: grabbing; opacity: 1; background: white; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); transform: scale(1.05); }

    /* ── Customer Info Grid responsive ── */
    @media (max-width: 480px) {
        .customer-info-grid {
            grid-template-columns: 1fr !important;
        }
    }

    /* ── Page header on small screens ── */
    @media (max-width: 480px) {
        .page-header { padding: 10px 0; }
        .page-title { font-size: 1rem !important; }
    }
</style>

<!-- Load Logic Script -->
<script src="/js/order-assign.js?v=<?= time() ?>"></script>

<!-- Add Item Modal -->
<div id="addItemModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); backdrop-filter: blur(2px);">
    <div class="modal-content" style="background-color: var(--bg-card); margin: 10% auto; padding: 24px; border: 1px solid var(--border); width: 90%; max-width: 500px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h3 style="margin: 0; font-size: 1.1rem; color: var(--text);">Tambah Layanan</h3>
            <span class="close" onclick="closeAddItemModal()" style="color: var(--text-muted); font-size: 24px; line-height: 1; cursor: pointer;">&times;</span>
        </div>
        
        <form action="/admin/orders/add-item/<?= $order['order_number'] ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label">Pilih Layanan / Produk</label>
                <select name="product_item_id" id="addItemSelect" class="form-select" required style="width: 100%;">
                    <option value="">-- Pilih Layanan --</option>
                    <?php if(!empty($availableItems)): ?>
                        <?php foreach($availableItems as $item): ?>
                             <option value="<?= $item['id'] ?>">
                                <?= esc($item['product_name']) ?> - <?= esc($item['item_name']) ?> (Rp <?= number_format($item['price'], 0, ',', '.') ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px;">
                <div class="form-group" style="margin: 0;">
                    <label class="form-label">Tanggal *</label>
                    <input type="date" name="booking_date" class="form-input" required>
                </div>
                
                <div class="form-group" style="margin: 0;">
                    <label class="form-label">Waktu *</label>
                    <input type="time" name="booking_time" class="form-input" required min="09:00" max="20:00">
                </div>
            </div>
            
            <div style="text-align: right;">
                <button type="button" class="btn btn-secondary" onclick="closeAddItemModal()" style="margin-right: 8px;">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddItemModal() {
    document.getElementById('addItemModal').style.display = 'block';
    
    // Initialize Select2 when modal opens
    setTimeout(() => {
        $('#addItemSelect').select2({
            placeholder: "Cari layanan...",
            allowClear: true,
            dropdownParent: $('#addItemModal'),
            width: '100%'
        });
    }, 100);
}

function closeAddItemModal() {
    document.getElementById('addItemModal').style.display = 'none';
    // Optional: Destroy or reset
    $('#addItemSelect').val(null).trigger('change');
}

// Close modal when clicking outside
window.onclick = function(event) {
    var modal = document.getElementById('addItemModal');
    if (event.target == modal) {
        closeAddItemModal();
    }
    var invoiceModal = document.getElementById('invoiceModal');
    if (event.target == invoiceModal) {
        closeInvoiceModal();
    }
    var completeModal = document.getElementById('completeInvoiceModal');
    if (event.target == completeModal) {
        closeCompleteInvoiceModal();
    }
}

// Check for Flash Data to open Invoice Modal automatically & init Select2
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('flagShowInvoice')) {
         openInvoiceModal();
    }

    // Initialize Select2 for Stock Usage
    if (typeof $ !== 'undefined' && $('#stockInventoryId').length) {
        $('#stockInventoryId').select2({
            placeholder: "-- Cari atau pilih bahan --",
            allowClear: true,
            width: '100%'
        }).on('change', function() {
            updateStockUnit(this);
        });
    }
});

// Auto-focus Select2 search field when opened
$(document).on('select2:open', function() {
    var searchField = document.querySelector('.select2-search__field');
    if (searchField) {
        searchField.focus();
    }
});
</script>

<?php if(session()->getFlashdata('show_invoice')): ?>
    <div id="flagShowInvoice" style="display:none;"></div>
<?php endif; ?>

<!-- Complete Order & Invoice Modal (Price Input) -->
<div id="completeInvoiceModal" class="modal" style="display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); backdrop-filter: blur(3px);">
    <div class="modal-content" style="background-color: var(--bg-card); margin: 3% auto 5%; padding: 0; border: 1px solid var(--border); width: 92%; max-width: 620px; border-radius: 16px; box-shadow: 0 15px 35px rgba(0,0,0,0.15); overflow: hidden;">
        <!-- Modal Header -->
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: var(--bg-input);">
            <div>
                <h3 style="margin: 0; font-size: 1.15rem; color: var(--text); font-weight: 700;">Konfirmasi Harga & Selesaikan Invoice</h3>
                <p style="margin: 4px 0 0; font-size: 0.8rem; color: var(--text-muted);">Periksa atau sesuaikan harga setiap layanan sebelum invoice & struk diterbitkan.</p>
            </div>
            <span class="close" onclick="closeCompleteInvoiceModal()" style="color: var(--text-muted); font-size: 26px; line-height: 1; cursor: pointer; padding: 0 4px;">&times;</span>
        </div>
        
        <div style="padding: 24px;">
            <!-- Customer & Worker Summary Bar -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; background: var(--bg-input); padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; border: 1px solid var(--border);">
                <div>
                    <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Pelanggan</div>
                    <div style="font-size: 0.95rem; font-weight: 600; color: var(--text); margin-top: 3px;">
                        <?= esc($order['customer_name']) ?> <span style="font-size: 0.8rem; font-weight: normal; color: var(--text-muted);">(#<?= esc($order['order_number']) ?>)</span>
                    </div>
                </div>
                <div>
                    <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Pekerja Bertugas</div>
                    <div id="modalWorkerDisplay" style="font-size: 0.95rem; font-weight: 600; color: var(--primary); margin-top: 3px;">
                        <?= esc(!empty($order['worker_names']) ? $order['worker_names'] : ($order['worker_name'] ?? '-')) ?>
                    </div>
                </div>
                <div style="grid-column: span 2; padding-top: 10px; border-top: 1px dashed rgba(0,0,0,0.08);">
                    <label style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; display: block; margin-bottom: 6px;">
                        Metode Pembayaran *
                    </label>
                    <select id="modalPaymentType" class="form-select" style="font-weight: 600;">
                        <option value="">-- Pilih Metode Pembayaran --</option>
                        <option value="CASH" <?= $order['payment_type'] == 'CASH' ? 'selected' : '' ?>>CASH</option>
                        <option value="BANK" <?= $order['payment_type'] == 'BANK' ? 'selected' : '' ?>>BANK</option>
                        <option value="QRIS" <?= $order['payment_type'] == 'QRIS' ? 'selected' : '' ?>>QRIS</option>
                        <option value="CREDIT CARD" <?= $order['payment_type'] == 'CREDIT CARD' ? 'selected' : '' ?>>CREDIT CARD</option>
                    </select>
                </div>
            </div>

            <!-- Items Price Input Table -->
            <label style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 8px;">
                Daftar Layanan & Input Harga
            </label>
            <div style="border: 1px solid var(--border); border-radius: 10px; overflow: hidden; margin-bottom: 18px; max-height: 280px; overflow-y: auto;">
                <table class="table" style="margin-bottom: 0;">
                    <thead>
                        <tr style="background: var(--bg-input); position: sticky; top: 0; z-index: 1;">
                            <th style="padding: 10px 14px; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); border-bottom: 1px solid var(--border);">Layanan</th>
                            <th style="padding: 10px 14px; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-align: right; width: 175px; border-bottom: 1px solid var(--border);">Harga (Rp)</th>
                            <th style="padding: 10px 14px; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-align: right; width: 125px; border-bottom: 1px solid var(--border);">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td style="padding: 12px 14px; vertical-align: middle;">
                                <div style="font-weight: 600; font-size: 0.9rem; color: var(--text);"><?= esc($item['product_name']) ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);"><?= esc($item['item_name']) ?></div>
                                <?php if ($item['addon_items']): ?>
                                    <div style="margin-top: 4px;">
                                        <?php foreach (json_decode($item['addon_items'], true) as $addon): ?>
                                            <span style="display: inline-block; font-size: 0.75rem; color: #166534; background: #dcfce7; padding: 2px 6px; border-radius: 4px; margin-right: 4px;">+ <?= esc($addon['name']) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ((int)$item['quantity'] > 1): ?>
                                    <div style="font-size: 0.75rem; color: var(--primary); font-weight: 600; margin-top: 2px;">Qty: <?= $item['quantity'] ?></div>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px 14px; text-align: right; vertical-align: middle;">
                                <div style="display: inline-flex; align-items: center; background: white; border: 1px solid var(--border); border-radius: 6px; padding: 2px 8px; width: 100%; max-width: 150px; box-sizing: border-box;">
                                    <span style="font-size: 0.8rem; color: var(--text-muted); margin-right: 4px; user-select: none;">Rp</span>
                                    <input type="number" 
                                           class="form-input invoice-item-price-input" 
                                           data-id="<?= $item['id'] ?>" 
                                           data-qty="<?= $item['quantity'] ?>" 
                                           value="<?= (int)$item['price'] ?>" 
                                           min="0" 
                                           step="1000" 
                                           style="width: 100%; padding: 4px 0; border: none; text-align: right; font-weight: 600; font-size: 0.9rem; outline: none; background: transparent;" 
                                           oninput="recalcCompleteInvoiceTotal()">
                                </div>
                            </td>
                            <td style="padding: 12px 14px; text-align: right; vertical-align: middle; font-weight: 600; color: var(--text);">
                                <span id="modal-subtotal-<?= $item['id'] ?>">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Diskon Section (Nominal & Persen) -->
            <div style="background: var(--bg-input); border: 1px solid var(--border); border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                    <label style="font-size: 0.78rem; font-weight: 700; color: var(--text); text-transform: uppercase; letter-spacing: 0.05em; margin: 0; display: flex; align-items: center; gap: 6px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="19" y1="5" x2="5" y2="19"></line><circle cx="6.5" cy="6.5" r="2.5"></circle><circle cx="17.5" cy="17.5" r="2.5"></circle></svg>
                        Diskon Pesanan
                    </label>
                    
                    <!-- Toggle Diskon Type: None / Persen / Nominal -->
                    <div style="display: inline-flex; background: #e5e7eb; border-radius: 8px; padding: 3px; gap: 3px;">
                        <button type="button" class="discount-tab-btn active" id="tabDiscountNone" onclick="setDiscountType('none')" style="display: inline-flex; align-items: center; gap: 5px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                            <span>Tanpa Diskon</span>
                        </button>
                        <button type="button" class="discount-tab-btn" id="tabDiscountPercent" onclick="setDiscountType('percent')" style="display: inline-flex; align-items: center; gap: 5px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="19" y1="5" x2="5" y2="19"></line><circle cx="6.5" cy="6.5" r="2.5"></circle><circle cx="17.5" cy="17.5" r="2.5"></circle></svg>
                            <span>Persen (%)</span>
                        </button>
                        <button type="button" class="discount-tab-btn" id="tabDiscountNominal" onclick="setDiscountType('nominal')" style="display: inline-flex; align-items: center; gap: 5px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="2" y="4" width="20" height="16" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                            <span>Nominal (Rp)</span>
                        </button>
                    </div>
                </div>

                <!-- Hidden Discount Type Input -->
                <input type="hidden" id="modalDiscountType" value="<?= esc($order['discount_type'] ?? 'none') ?>">

                <!-- Percent Input Row -->
                <div id="discountPercentRow" style="display: none; margin-top: 12px;">
                    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <div style="position: relative; width: 140px;">
                            <input type="number" id="modalDiscountPercentInput" class="form-input" min="0" max="100" step="1" placeholder="0" value="<?= (float)($order['discount_percent'] ?? 0) ?>" style="padding-right: 32px; font-weight: 700; font-size: 1rem; text-align: right;" oninput="onDiscountPercentChange()">
                            <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-weight: 700; color: var(--text-muted); pointer-events: none;">%</span>
                        </div>
                        <!-- Quick Chips -->
                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                            <button type="button" class="chip-btn" onclick="applyQuickPercent(5)">5%</button>
                            <button type="button" class="chip-btn" onclick="applyQuickPercent(10)">10%</button>
                            <button type="button" class="chip-btn" onclick="applyQuickPercent(15)">15%</button>
                            <button type="button" class="chip-btn" onclick="applyQuickPercent(20)">20%</button>
                            <button type="button" class="chip-btn" onclick="applyQuickPercent(50)">50%</button>
                        </div>
                    </div>
                </div>

                <!-- Nominal Input Row -->
                <div id="discountNominalRow" style="display: none; margin-top: 12px;">
                    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; background: white; border: 1px solid var(--border); border-radius: 8px; padding: 2px 10px; width: 180px;">
                            <span style="font-weight: 700; color: var(--text-muted); font-size: 0.85rem; margin-right: 4px;">Rp</span>
                            <input type="number" id="modalDiscountNominalInput" class="form-input" min="0" step="1000" placeholder="0" value="<?= (int)($order['discount_amount'] ?? 0) ?>" style="border: none; padding: 6px 0; font-weight: 700; font-size: 1rem; text-align: right; width: 100%; outline: none; background: transparent;" oninput="onDiscountNominalChange()">
                        </div>
                        <!-- Quick Chips -->
                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                            <button type="button" class="chip-btn" onclick="applyQuickNominal(10000)">10rb</button>
                            <button type="button" class="chip-btn" onclick="applyQuickNominal(20000)">20rb</button>
                            <button type="button" class="chip-btn" onclick="applyQuickNominal(50000)">50rb</button>
                            <button type="button" class="chip-btn" onclick="applyQuickNominal(100000)">100rb</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grand Total Breakdown Box -->
            <div style="background: var(--bg-input); border-radius: 12px; border: 1px solid var(--border); padding: 16px 20px; margin-bottom: 22px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; font-size: 0.85rem;">
                    <span style="color: var(--text-muted);">Subtotal Layanan</span>
                    <span id="completeInvoiceSubtotalDisplay" style="font-weight: 600; color: var(--text);">Rp <?= number_format($order['subtotal_amount'] ?? $order['total_amount'], 0, ',', '.') ?></span>
                </div>
                <div id="completeInvoiceDiscountRow" style="display: none; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 0.85rem; color: #16a34a;">
                    <span id="completeInvoiceDiscountLabel">Diskon</span>
                    <span id="completeInvoiceDiscountDisplay" style="font-weight: 700;">-Rp 0</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: baseline; padding-top: 10px; border-top: 1px dashed var(--border);">
                    <div>
                        <div style="font-size: 0.85rem; color: var(--text); font-weight: 700; text-transform: uppercase;">Total Tagihan Akhir</div>
                        <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 1px;">Nominal yang dibayar pelanggan</div>
                    </div>
                    <div id="completeInvoiceTotalDisplay" style="font-size: 1.4rem; font-weight: 800; color: var(--primary);">
                        Rp <?= number_format($order['total_amount'], 0, ',', '.') ?>
                    </div>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="btn btn-secondary" onclick="closeCompleteInvoiceModal()" style="padding: 10px 20px;">
                    Batal
                </button>
                <button type="button" class="btn btn-primary" id="btnCompleteInvoiceSubmit" onclick="submitCompleteInvoiceOrder()" style="padding: 10px 22px; display: inline-flex; align-items: center; gap: 8px; font-weight: 600;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Selesaikan & Terbitkan Invoice
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// State for discount calculation
let currentOrderSubtotal = <?= (float)($order['subtotal_amount'] ?? $order['total_amount'] ?? 0) ?>;
let activeDiscountType = '<?= esc($order['discount_type'] ?? 'none') ?>';
let activeDiscountPercent = <?= (float)($order['discount_percent'] ?? 0) ?>;
let activeDiscountAmount = <?= (float)($order['discount_amount'] ?? 0) ?>;

function setDiscountType(type) {
    activeDiscountType = type;
    document.getElementById('modalDiscountType').value = type;

    // Toggle tab active classes
    ['None', 'Percent', 'Nominal'].forEach(t => {
        const btn = document.getElementById('tabDiscount' + t);
        if (btn) btn.classList.remove('active');
    });

    const activeBtn = document.getElementById('tabDiscount' + (type === 'percent' ? 'Percent' : (type === 'nominal' ? 'Nominal' : 'None')));
    if (activeBtn) activeBtn.classList.add('active');

    const percentRow = document.getElementById('discountPercentRow');
    const nominalRow = document.getElementById('discountNominalRow');

    if (type === 'percent') {
        if (percentRow) percentRow.style.display = 'block';
        if (nominalRow) nominalRow.style.display = 'none';
        const pInput = document.getElementById('modalDiscountPercentInput');
        if (pInput && (!pInput.value || pInput.value === '0')) pInput.value = '10';
        onDiscountPercentChange();
    } else if (type === 'nominal') {
        if (percentRow) percentRow.style.display = 'none';
        if (nominalRow) nominalRow.style.display = 'block';
        onDiscountNominalChange();
    } else {
        if (percentRow) percentRow.style.display = 'none';
        if (nominalRow) nominalRow.style.display = 'none';
        activeDiscountPercent = 0;
        activeDiscountAmount = 0;
        recalcCompleteInvoiceTotal();
    }
}

function applyQuickPercent(val) {
    const input = document.getElementById('modalDiscountPercentInput');
    if (input) {
        input.value = val;
        onDiscountPercentChange();
    }
}

function applyQuickNominal(val) {
    const input = document.getElementById('modalDiscountNominalInput');
    if (input) {
        input.value = val;
        onDiscountNominalChange();
    }
}

function onDiscountPercentChange() {
    const input = document.getElementById('modalDiscountPercentInput');
    let percent = parseFloat(input ? input.value : 0) || 0;
    if (percent < 0) percent = 0;
    if (percent > 100) percent = 100;
    activeDiscountPercent = percent;
    activeDiscountAmount = Math.round((percent / 100) * currentOrderSubtotal);
    recalcCompleteInvoiceTotal();
}

function onDiscountNominalChange() {
    const input = document.getElementById('modalDiscountNominalInput');
    let nominal = parseFloat(input ? input.value : 0) || 0;
    if (nominal < 0) nominal = 0;
    if (nominal > currentOrderSubtotal) nominal = currentOrderSubtotal;
    activeDiscountAmount = nominal;
    activeDiscountPercent = currentOrderSubtotal > 0 ? ((nominal / currentOrderSubtotal) * 100).toFixed(1) : 0;
    recalcCompleteInvoiceTotal();
}

function openCompleteInvoiceModal() {
    // Sync worker display
    var workerName = '';
    if (typeof window.getAssignedWorkerNames === 'function') {
        workerName = window.getAssignedWorkerNames();
    }
    if (!workerName && document.getElementById('selectedName')) {
        workerName = document.getElementById('selectedName').textContent.trim();
    }
    if (!workerName) {
        workerName = '<?= esc($order['worker_names'] ?? $order['worker_name'] ?? '') ?>';
    }
    if (document.getElementById('modalWorkerDisplay')) {
        document.getElementById('modalWorkerDisplay').textContent = workerName || '-';
    }

    // Sync payment type
    var currentPaymentType = document.getElementById('paymentTypeSelect').value;
    if (document.getElementById('modalPaymentType')) {
        document.getElementById('modalPaymentType').value = currentPaymentType;
    }

    // Set initial discount state
    const savedType = '<?= esc($order['discount_type'] ?? 'none') ?>';
    setDiscountType(savedType);

    recalcCompleteInvoiceTotal();
    document.getElementById('completeInvoiceModal').style.display = 'block';
}

function closeCompleteInvoiceModal() {
    document.getElementById('completeInvoiceModal').style.display = 'none';
    var oldValue = '<?= $order['status'] ?>';
    var statusSelect = document.getElementById('statusSelect');
    if (statusSelect && oldValue !== 'completed') {
        statusSelect.value = oldValue;
        if (typeof onStatusChange === 'function') {
            onStatusChange();
        }
    }
}

function formatRupiah(num) {
    return 'Rp ' + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function recalcCompleteInvoiceTotal() {
    var inputs = document.querySelectorAll('.invoice-item-price-input');
    var rawSubtotal = 0;
    inputs.forEach(function(inp) {
        var id = inp.dataset.id;
        var qty = parseFloat(inp.dataset.qty) || 1;
        var price = parseFloat(inp.value) || 0;
        var subtotal = price * qty;
        rawSubtotal += subtotal;
        
        var subtotalEl = document.getElementById('modal-subtotal-' + id);
        if (subtotalEl) {
            subtotalEl.textContent = formatRupiah(subtotal);
        }
    });

    currentOrderSubtotal = rawSubtotal;

    // Recalculate discount based on updated subtotal
    if (activeDiscountType === 'percent') {
        activeDiscountAmount = Math.round((activeDiscountPercent / 100) * currentOrderSubtotal);
    } else if (activeDiscountType === 'nominal') {
        if (activeDiscountAmount > currentOrderSubtotal) activeDiscountAmount = currentOrderSubtotal;
    } else {
        activeDiscountAmount = 0;
        activeDiscountPercent = 0;
    }

    var grandTotal = Math.max(0, currentOrderSubtotal - activeDiscountAmount);

    // Update displays
    var subtotalDisplay = document.getElementById('completeInvoiceSubtotalDisplay');
    if (subtotalDisplay) subtotalDisplay.textContent = formatRupiah(currentOrderSubtotal);

    var discountRow = document.getElementById('completeInvoiceDiscountRow');
    var discountLabel = document.getElementById('completeInvoiceDiscountLabel');
    var discountDisplay = document.getElementById('completeInvoiceDiscountDisplay');

    if (activeDiscountAmount > 0) {
        if (discountRow) discountRow.style.display = 'flex';
        if (discountLabel) {
            discountLabel.textContent = 'Diskon ' + (activeDiscountType === 'percent' ? '(' + activeDiscountPercent + '%)' : '');
        }
        if (discountDisplay) discountDisplay.textContent = '-' + formatRupiah(activeDiscountAmount);
    } else {
        if (discountRow) discountRow.style.display = 'none';
    }

    var totalDisplay = document.getElementById('completeInvoiceTotalDisplay');
    if (totalDisplay) {
        totalDisplay.textContent = formatRupiah(grandTotal);
    }
}

function submitCompleteInvoiceOrder() {
    var modalPaymentType = document.getElementById('modalPaymentType');
    if (modalPaymentType && !modalPaymentType.value) {
        showToastMsg('Mohon pilih metode pembayaran terlebih dahulu', 'error');
        modalPaymentType.focus();
        return;
    }
    
    // Sync payment method back to main form
    if (modalPaymentType) {
        document.getElementById('paymentTypeSelect').value = modalPaymentType.value;
    }

    // Set status to completed
    document.getElementById('statusSelect').value = 'completed';

    // Attach item prices & discount as hidden inputs to statusForm
    var statusForm = document.getElementById('statusForm');
    
    // Remove existing dynamic inputs if any
    var existingInputs = statusForm.querySelectorAll('.dynamic-price-input, .dynamic-discount-input');
    existingInputs.forEach(function(el) { el.remove(); });

    // Item prices
    var inputs = document.querySelectorAll('.invoice-item-price-input');
    inputs.forEach(function(inp) {
        var hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.className = 'dynamic-price-input';
        hidden.name = 'item_prices[' + inp.dataset.id + ']';
        hidden.value = inp.value;
        statusForm.appendChild(hidden);
    });

    // Discount fields
    var discountFields = [
        { name: 'discount_type', value: activeDiscountType },
        { name: 'discount_percent', value: activeDiscountPercent },
        { name: 'discount_amount', value: activeDiscountAmount },
        { name: 'subtotal_amount', value: currentOrderSubtotal }
    ];

    discountFields.forEach(function(f) {
        var hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.className = 'dynamic-discount-input';
        hidden.name = f.name;
        hidden.value = f.value;
        statusForm.appendChild(hidden);
    });

    var submitBtn = document.getElementById('btnCompleteInvoiceSubmit');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Memproses...';
    }

    statusForm.submit();
}
</script>

<!-- Invoice / E-Receipt Modal -->
<div id="invoiceModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); backdrop-filter: blur(2px);">
    <div class="modal-content" style="background-color: var(--bg-card); margin: 3% auto; padding: 0; border: 1px solid var(--border); width: 92%; max-width: 500px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="padding: 18px 22px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: var(--bg-input);">
            <div style="display: flex; align-items: center; gap: 8px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                <h3 style="margin: 0; font-size: 1.1rem; color: var(--text); font-weight: 700;">Kirim Invoice & Struk</h3>
            </div>
            <span class="close" onclick="closeInvoiceModal()" style="color: var(--text-muted); font-size: 24px; line-height: 1; cursor: pointer;">&times;</span>
        </div>
        
        <div style="padding: 22px;">
            <!-- Order Summary Card inside Invoice Modal -->
            <div style="background: var(--bg-input); border-radius: 12px; padding: 14px 16px; margin-bottom: 16px; border: 1px solid var(--border);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 0.83rem;">
                    <span style="color: var(--text-muted);">Pelanggan</span>
                    <span style="font-weight: 600;"><?= esc($order['customer_name']) ?> (#<?= esc($order['order_number']) ?>)</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 0.83rem;">
                    <span style="color: var(--text-muted);">Subtotal</span>
                    <span id="invModalSubtotal" style="font-weight: 600;">Rp <?= number_format($order['subtotal_amount'] ?? $order['total_amount'], 0, ',', '.') ?></span>
                </div>
                <div id="invModalDiscountRow" style="display: <?= (!empty($order['discount_amount']) && (float)$order['discount_amount'] > 0) ? 'flex' : 'none' ?>; justify-content: space-between; margin-bottom: 6px; font-size: 0.83rem; color: #16a34a;">
                    <span id="invModalDiscountLabel">Diskon <?= ($order['discount_type'] === 'percent' && (float)$order['discount_percent'] > 0) ? '(' . (float)$order['discount_percent'] . '%)' : '' ?></span>
                    <span id="invModalDiscountVal" style="font-weight: 700;">-Rp <?= number_format($order['discount_amount'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; padding-top: 8px; border-top: 1px dashed var(--border); font-size: 0.95rem;">
                    <span style="font-weight: 700;">Total Tagihan</span>
                    <span id="invModalTotal" style="font-weight: 800; color: var(--primary); font-size: 1.15rem;">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
                </div>
            </div>

            <!-- Accordion / Section to Adjust Discount directly from Invoice Modal -->
            <div style="background: white; border: 1px solid var(--border); border-radius: 10px; padding: 12px 14px; margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;" onclick="toggleInvDiscountPanel()">
                    <span style="font-size: 0.8rem; font-weight: 700; color: var(--primary); display: flex; align-items: center; gap: 6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="5" x2="5" y2="19"></line><circle cx="6.5" cy="6.5" r="2.5"></circle><circle cx="17.5" cy="17.5" r="2.5"></circle></svg>
                        Ubah / Tambah Diskon (Opsional)
                    </span>
                    <svg id="invDiscountChevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="transition: transform 0.2s;"><path d="M6 9l6 6 6-6"/></svg>
                </div>

                <div id="invDiscountPanel" style="display: none; margin-top: 12px; padding-top: 10px; border-top: 1px dashed var(--border);">
                    <div style="display: flex; gap: 4px; margin-bottom: 10px; background: #f3f4f6; padding: 3px; border-radius: 6px;">
                        <button type="button" class="discount-tab-btn <?= ($order['discount_type'] ?? 'none') === 'none' ? 'active' : '' ?>" id="invTabNone" onclick="setInvDiscountMode('none')" style="display: inline-flex; align-items: center; gap: 4px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                            <span>Tanpa Diskon</span>
                        </button>
                        <button type="button" class="discount-tab-btn <?= ($order['discount_type'] ?? 'none') === 'percent' ? 'active' : '' ?>" id="invTabPercent" onclick="setInvDiscountMode('percent')" style="display: inline-flex; align-items: center; gap: 4px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="19" y1="5" x2="5" y2="19"></line><circle cx="6.5" cy="6.5" r="2.5"></circle><circle cx="17.5" cy="17.5" r="2.5"></circle></svg>
                            <span>Persen (%)</span>
                        </button>
                        <button type="button" class="discount-tab-btn <?= ($order['discount_type'] ?? 'none') === 'nominal' ? 'active' : '' ?>" id="invTabNominal" onclick="setInvDiscountMode('nominal')" style="display: inline-flex; align-items: center; gap: 4px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="2" y="4" width="20" height="16" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                            <span>Nominal (Rp)</span>
                        </button>
                    </div>

                    <div id="invPercentBox" style="display: <?= ($order['discount_type'] ?? 'none') === 'percent' ? 'flex' : 'none' ?>; gap: 8px; align-items: center; margin-bottom: 10px;">
                        <input type="number" id="invDiscountPercentInput" class="form-input" min="0" max="100" step="1" placeholder="0" value="<?= (float)($order['discount_percent'] ?? 10) ?>" style="width: 90px; text-align: right; font-weight: 700;" oninput="onInvDiscountInput()">
                        <span style="font-weight: 700; color: var(--text-muted);">%</span>
                        <div style="display: flex; gap: 4px;">
                            <button type="button" class="chip-btn" onclick="quickSetInvPercent(5)">5%</button>
                            <button type="button" class="chip-btn" onclick="quickSetInvPercent(10)">10%</button>
                            <button type="button" class="chip-btn" onclick="quickSetInvPercent(15)">15%</button>
                            <button type="button" class="chip-btn" onclick="quickSetInvPercent(20)">20%</button>
                        </div>
                    </div>

                    <div id="invNominalBox" style="display: <?= ($order['discount_type'] ?? 'none') === 'nominal' ? 'flex' : 'none' ?>; gap: 8px; align-items: center; margin-bottom: 10px;">
                        <span style="font-weight: 700; color: var(--text-muted); font-size: 0.85rem;">Rp</span>
                        <input type="number" id="invDiscountNominalInput" class="form-input" min="0" step="1000" placeholder="0" value="<?= (int)($order['discount_amount'] ?? 10000) ?>" style="width: 130px; text-align: right; font-weight: 700;" oninput="onInvDiscountInput()">
                        <div style="display: flex; gap: 4px;">
                            <button type="button" class="chip-btn" onclick="quickSetInvNominal(10000)">10rb</button>
                            <button type="button" class="chip-btn" onclick="quickSetInvNominal(20000)">20rb</button>
                            <button type="button" class="chip-btn" onclick="quickSetInvNominal(50000)">50rb</button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary btn-sm" onclick="saveInvDiscountAjax()" style="width: 100%; justify-content: center; padding: 8px 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Simpan Diskon ke Pesanan</span>
                    </button>
                </div>
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Pesan WhatsApp</span>
                    <span style="font-size: 0.72rem; color: var(--text-muted); font-weight: normal;">Otomatis terisi rincian & struk</span>
                </label>
                <textarea id="waMessageText" class="form-input" rows="8" style="font-family: monospace; font-size: 0.84rem; line-height: 1.5;"></textarea>
            </div>
            
            <?php
                $receiptToken = \App\Controllers\Admin::getReceiptToken($order['order_number'], $order['created_at']);
                $receiptUrl   = base_url('ereceipt/' . $order['order_number'] . '?token=' . $receiptToken);
            ?>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
                <a href="<?= $receiptUrl ?>" target="_blank" class="btn btn-outline-secondary" style="text-align: center; font-size: 0.85rem; padding: 8px 10px;">
                    Lihat Struk
                </a>
                <button type="button" class="btn btn-outline-secondary" onclick="copyToClipboard()" style="display: flex; align-items: center; justify-content: center; gap: 6px; font-size: 0.85rem; padding: 8px 10px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    Salin Teks
                </button>
                <button type="button" class="btn" style="background-color: #25D366; color: white; border: none; display: flex; align-items: center; justify-content: center; gap: 6px; font-weight: 600; font-size: 0.85rem; padding: 8px 10px;" onclick="sendToWA()">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    Kirim WA
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// State for Invoice WA Message Modal
let invOrderNumber = '<?= esc($order['order_number']) ?>';
let invCustomerName = '<?= esc($order['customer_name']) ?>';
let invReceiptUrl = '<?= $receiptUrl ?>';
let invSubtotal = <?= (float)($order['subtotal_amount'] ?? $order['total_amount'] ?? 0) ?>;
let invDiscountType = '<?= esc($order['discount_type'] ?? 'none') ?>';
let invDiscountPercent = <?= (float)($order['discount_percent'] ?? 0) ?>;
let invDiscountAmount = <?= (float)($order['discount_amount'] ?? 0) ?>;
let invTotalAmount = <?= (float)($order['total_amount'] ?? 0) ?>;

function buildWhatsAppMessage() {
    let msg = "Halo kak *" + invCustomerName + "*,\n\n";
    msg += "Terima kasih telah berkunjung ke La Hair Studio.\n";
    msg += "Berikut adalah struk pembayaran untuk pesanan: *" + invOrderNumber + "*\n\n";

    if (invDiscountAmount > 0) {
        msg += "Subtotal: " + formatRupiah(invSubtotal) + "\n";
        msg += "Diskon" + (invDiscountType === 'percent' ? " (" + invDiscountPercent + "%)" : "") + ": -" + formatRupiah(invDiscountAmount) + "\n";
        msg += "Total Akhir: *" + formatRupiah(invTotalAmount) + "*\n\n";
    } else {
        msg += "Total: *" + formatRupiah(invTotalAmount) + "*\n\n";
    }

    msg += "Link Struk Digital:\n";
    msg += invReceiptUrl + "\n\n";
    msg += "Ditunggu kedatangannya kembali!";
    return msg;
}

function updateWaTextarea() {
    const ta = document.getElementById('waMessageText');
    if (ta) ta.value = buildWhatsAppMessage();

    // Update displays inside invoice modal
    const subEl = document.getElementById('invModalSubtotal');
    if (subEl) subEl.textContent = formatRupiah(invSubtotal);

    const discRow = document.getElementById('invModalDiscountRow');
    const discLabel = document.getElementById('invModalDiscountLabel');
    const discVal = document.getElementById('invModalDiscountVal');
    const totalEl = document.getElementById('invModalTotal');

    if (invDiscountAmount > 0) {
        if (discRow) discRow.style.display = 'flex';
        if (discLabel) discLabel.textContent = 'Diskon ' + (invDiscountType === 'percent' ? '(' + invDiscountPercent + '%)' : '');
        if (discVal) discVal.textContent = '-' + formatRupiah(invDiscountAmount);
    } else {
        if (discRow) discRow.style.display = 'none';
    }

    if (totalEl) totalEl.textContent = formatRupiah(invTotalAmount);
}

function toggleInvDiscountPanel() {
    const panel = document.getElementById('invDiscountPanel');
    const chevron = document.getElementById('invDiscountChevron');
    if (panel) {
        const isHidden = panel.style.display === 'none';
        panel.style.display = isHidden ? 'block' : 'none';
        if (chevron) chevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
    }
}

function setInvDiscountMode(mode) {
    invDiscountType = mode;
    ['None', 'Percent', 'Nominal'].forEach(t => {
        const b = document.getElementById('invTab' + t);
        if (b) b.classList.remove('active');
    });
    const ab = document.getElementById('invTab' + (mode === 'percent' ? 'Percent' : (mode === 'nominal' ? 'Nominal' : 'None')));
    if (ab) ab.classList.add('active');

    const pBox = document.getElementById('invPercentBox');
    const nBox = document.getElementById('invNominalBox');

    if (mode === 'percent') {
        if (pBox) pBox.style.display = 'flex';
        if (nBox) nBox.style.display = 'none';
        onInvDiscountInput();
    } else if (mode === 'nominal') {
        if (pBox) pBox.style.display = 'none';
        if (nBox) nBox.style.display = 'flex';
        onInvDiscountInput();
    } else {
        if (pBox) pBox.style.display = 'none';
        if (nBox) nBox.style.display = 'none';
        invDiscountPercent = 0;
        invDiscountAmount = 0;
        invTotalAmount = invSubtotal;
        updateWaTextarea();
    }
}

function quickSetInvPercent(p) {
    const inp = document.getElementById('invDiscountPercentInput');
    if (inp) { inp.value = p; onInvDiscountInput(); }
}

function quickSetInvNominal(n) {
    const inp = document.getElementById('invDiscountNominalInput');
    if (inp) { inp.value = n; onInvDiscountInput(); }
}

function onInvDiscountInput() {
    if (invDiscountType === 'percent') {
        const pInput = document.getElementById('invDiscountPercentInput');
        let p = parseFloat(pInput ? pInput.value : 0) || 0;
        if (p < 0) p = 0;
        if (p > 100) p = 100;
        invDiscountPercent = p;
        invDiscountAmount = Math.round((p / 100) * invSubtotal);
    } else if (invDiscountType === 'nominal') {
        const nInput = document.getElementById('invDiscountNominalInput');
        let n = parseFloat(nInput ? nInput.value : 0) || 0;
        if (n < 0) n = 0;
        if (n > invSubtotal) n = invSubtotal;
        invDiscountAmount = n;
        invDiscountPercent = invSubtotal > 0 ? ((n / invSubtotal) * 100).toFixed(1) : 0;
    } else {
        invDiscountAmount = 0;
        invDiscountPercent = 0;
    }

    invTotalAmount = Math.max(0, invSubtotal - invDiscountAmount);
    updateWaTextarea();
}

async function saveInvDiscountAjax() {
    try {
        const formData = new FormData();
        formData.append('discount_type', invDiscountType);
        formData.append('discount_percent', invDiscountPercent);
        formData.append('discount_amount', invDiscountAmount);
        
        // Add CSRF token
        const csrfInput = document.querySelector('input[name="<?= csrf_token() ?>"]');
        if (csrfInput) {
            formData.append('<?= csrf_token() ?>', csrfInput.value);
        }

        const res = await fetch('/admin/orders/update-discount/' + invOrderNumber, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await res.json();
        if (data.status === 'success') {
            showToastMsg('Diskon berhasil disimpan & struk diupdate!', 'success');
            // Refresh WA message and displays
            updateWaTextarea();
        } else {
            showToastMsg(data.message || 'Gagal menyimpan diskon', 'error');
        }
    } catch (e) {
        showToastMsg('Terjadi kesalahan saat menyimpan diskon', 'error');
    }
}

function openInvoiceModal() {
    updateWaTextarea();
    document.getElementById('invoiceModal').style.display = 'block';
}

function closeInvoiceModal() {
    document.getElementById('invoiceModal').style.display = 'none';
}

function sendToWA() {
    var message = document.getElementById('waMessageText').value;
    var phone = "<?= $order['customer_phone'] ?? '' ?>";
    
    if (phone.startsWith('0')) {
        phone = '62' + phone.substring(1);
    }
    
    var url = "https://wa.me/" + phone + "?text=" + encodeURIComponent(message);
    window.open(url, '_blank');
}

function showToastMsg(msg, type) {
    if (typeof Toastify !== 'undefined') {
        Toastify({
            text: msg,
            duration: 2500,
            gravity: "top",
            position: "right",
            style: {
                background: type === 'success' ? '#10b981' : '#ef4444',
                borderRadius: '8px',
                fontWeight: '500'
            }
        }).showToast();
    } else {
        alert(msg);
    }
}

function copyToClipboard() {
    var copyText = document.getElementById("waMessageText");
    copyText.select();
    copyText.setSelectionRange(0, 99999); 
    navigator.clipboard.writeText(copyText.value);
    showToastMsg("Teks WhatsApp berhasil disalin!", "success");
}


// ===== Stock Usage Functions =====

function updateStockUnit(select) {
    if (!select) select = document.getElementById('stockInventoryId');
    if (!select) return;

    var errEl = document.getElementById('stockErrorMsg');
    if (errEl) errEl.style.display = 'none';

    var val = select.value;
    if (!val || select.selectedIndex === -1) {
        document.getElementById('stockUnitLabel').textContent = '...';
        return;
    }

    var opt = select.options[select.selectedIndex];
    var unit = opt ? opt.getAttribute('data-unit') : '';

    document.getElementById('stockUnitLabel').textContent = unit ? '(' + unit + ')' : '...';
    document.getElementById('stockQtyInput').focus();
}

function saveStockUsage() {
    var inventoryId = document.getElementById('stockInventoryId').value;
    var qty = document.getElementById('stockQtyInput').value;
    var errEl = document.getElementById('stockErrorMsg');

    if (!inventoryId) {
        errEl.textContent = 'Pilih bahan terlebih dahulu'; errEl.style.display = 'block'; return;
    }
    if (qty === '' || isNaN(qty) || parseFloat(qty) < 0) {
        errEl.textContent = 'Isi jumlah yang valid (minimal 0)'; errEl.style.display = 'block'; return;
    }
    errEl.style.display = 'none';

    var btn = document.querySelector('#stockUsageInput button, button[onclick="saveStockUsage()"]');
    if (btn) btn.disabled = true;

    var fd = new FormData();
    fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    fd.append('inventory_id', inventoryId);
    fd.append('quantity', qty);

    fetch('/admin/orders/stock-usage/<?= $order['order_number'] ?>', {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r){ return r.json(); })
    .then(function(data) {
        if (btn) btn.disabled = false;
        if (!data.success) {
            errEl.textContent = data.message; errEl.style.display = 'block'; return;
        }

        // Append to list
        var list = document.getElementById('stockUsageList');
        var ul   = document.getElementById('stockUsageItems');
        var li   = document.createElement('li');
        li.dataset.id = data.usage_id;
        li.style.cssText = 'display:flex;align-items:center;justify-content:space-between;background:var(--bg-input);border-radius:8px;padding:8px 10px;font-size:0.85rem;';
        var qtyDisplay = parseFloat(data.quantity) % 1 === 0 ? parseInt(data.quantity) : parseFloat(data.quantity);
        li.innerHTML = '<div><span style="font-weight:600;">' + data.inventory_name + '</span><span style="color:var(--text-muted);margin-left:6px;">× ' + qtyDisplay + ' ' + data.unit + '</span></div>'
            + '<button type="button" onclick="deleteStockUsage(' + data.usage_id + ', this)" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:0.8rem;padding:0 4px;font-weight:500;">Hapus</button>';
        ul.appendChild(li);
        list.style.display = '';

        // Reset form
        if (typeof $ !== 'undefined' && $('#stockInventoryId').length) {
            $('#stockInventoryId').val(null).trigger('change');
        } else {
            document.getElementById('stockInventoryId').value = '';
        }
        document.getElementById('stockQtyInput').value = '';
        document.getElementById('stockUnitLabel').textContent = '...';



        // Update badge
        var badge = document.querySelector('[data-stock-badge]');
        if (badge) {
            var currentCount = ul.querySelectorAll('li').length;
            badge.style.background = 'rgba(34,197,94,0.12)';
            badge.style.color = '#16a34a';
            badge.textContent = currentCount + ' item';
        }

        // Toast
        if (typeof Toastify !== 'undefined') {
            Toastify({ text: data.message, duration: 2000, gravity: 'top', position: 'right',
                style: { background: '#fff', color: '#333', borderLeft: '5px solid #22c55e', borderRadius: '8px', boxShadow: '0 4px 12px rgba(0,0,0,0.1)', padding: '12px 16px', fontWeight: '500' }
            }).showToast();
        }
    })
    .catch(function() { if (btn) btn.disabled = false; errEl.textContent = 'Gagal menyimpan'; errEl.style.display = 'block'; });
}

function deleteStockUsage(id, btnEl) {
    if (!confirm('Hapus catatan pemakaian stok ini?')) return;

    var fd = new FormData();
    fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('/admin/orders/stock-usage/delete/' + id, {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r){ return r.json(); })
    .then(function(data) {
        if (data.success) {
            var li = btnEl.closest('li');
            if (li) li.remove();
            var ul = document.getElementById('stockUsageItems');
            var remainingCount = ul ? ul.querySelectorAll('li').length : 0;
            if (remainingCount === 0) {
                document.getElementById('stockUsageList').style.display = 'none';
            }

            var badge = document.querySelector('[data-stock-badge]');
            if (badge) {
                if (remainingCount === 0) {
                    badge.style.background = 'rgba(245,158,11,0.12)';
                    badge.style.color = '#d97706';
                    badge.textContent = 'Belum Dicatat';
                } else {
                    badge.textContent = remainingCount + ' item';
                }
            }

            if (typeof Toastify !== 'undefined') {
                Toastify({ text: data.message || 'Catatan pemakaian stok dihapus', duration: 2000, gravity: 'top', position: 'right',
                    style: { background: '#fff', color: '#333', borderLeft: '5px solid #f97316', borderRadius: '8px', boxShadow: '0 4px 12px rgba(0,0,0,0.1)', padding: '12px 16px', fontWeight: '500' }
                }).showToast();
            }
        }
    });
}

</script>

<style>
    /* Minimal Select2 Styling */
    .select2-container .select2-selection--single {
        height: 42px !important;
        border: 1px solid var(--border) !important;
        border-radius: 8px !important;
        display: flex !important;
        align-items: center !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: normal !important;
        color: var(--text) !important;
        padding-left: 12px;
        padding-right: 30px; /* Space for clear button */
    }
    
    .select2-container--default .select2-selection--single .select2-selection__clear {
        position: absolute !important;
        right: 30px !important; /* Position before the arrow */
        height: 100%;
        top: 0;
        display: flex;
        align-items: center;
        color: #ef4444; /* Red color for remove action */
        font-weight: bold;
        margin-right: 0 !important;
        cursor: pointer;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        top: 1px !important;
    }

    /* Dropdown */
    .select2-dropdown {
        border-color: var(--border) !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        z-index: 9999;
    }
    
    .select2-search__field {
        border-radius: 6px !important;
        border: 1px solid var(--border) !important;
    }
    
    .select2-results__option {
        padding: 8px 12px;
        font-size: 0.9rem;
    }
    
    /* Discount Tabs & Quick Chips */
    .discount-tab-btn {
        background: transparent;
        border: none;
        padding: 6px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-muted);
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .discount-tab-btn.active {
        background: white;
        color: var(--primary);
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        font-weight: 700;
    }
    .chip-btn {
        background: #f3f4f6;
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 4px 10px;
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--text);
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .chip-btn:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
</style>

<?= $this->endSection() ?>


