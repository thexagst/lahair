<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="page-header" style="flex-direction: row; justify-content: space-between; align-items: flex-end;">
    <div>
        <h1 class="page-title">Daftar Pesanan</h1>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 4px;">
            <?= ($startDate && $endDate) 
                ? 'Menampilkan pesanan dari ' . date('d M Y', strtotime($startDate)) . ' sampai ' . date('d M Y', strtotime($endDate)) 
                : 'Menampilkan semua riwayat pesanan.' ?>
        </p>
    </div>
    
    <!-- Date Filter -->
    <form action="" method="get" style="display: flex; gap: 10px; align-items: flex-end; background: var(--bg-card); padding: 10px; border-radius: 12px; border: 1px solid var(--border); box-shadow: var(--shadow);">
        <div>
            <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 4px;">Dari Tanggal</label>
            <input type="date" name="start_date" value="<?= esc($startDate) ?>" class="form-input" style="padding: 6px 10px; font-size: 0.85rem;">
        </div>
        <div>
            <label style="display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 4px;">Sampai Tanggal</label>
            <input type="date" name="end_date" value="<?= esc($endDate) ?>" class="form-input" style="padding: 6px 10px; font-size: 0.85rem;">
        </div>
        <button type="submit" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.85rem; height: 38px;">
            Filter
        </button>
        <?php if ($startDate || $endDate): ?>
            <a href="/admin/orders" class="btn btn-secondary" style="padding: 8px 16px; font-size: 0.85rem; height: 38px; display: flex; align-items: center;">Reset</a>
        <?php endif; ?>
    </form>
</div>

<!-- Stats Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 24px;">
    <!-- Card 1: Omzet -->
    <div class="card" style="padding: 14px 16px; display: flex; align-items: center; gap: 12px;">
        <div style="width: 40px; height: 40px; flex-shrink: 0; border-radius: 10px; background: rgba(34, 197, 94, 0.1); color: #22c55e; display: flex; align-items: center; justify-content: center;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div>
            <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 2px;">Total Pendapatan</p>
            <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--text); line-height: 1.2;">Rp <?= number_format($stats['revenue'], 0, ',', '.') ?></h3>
        </div>
    </div>

    <!-- Card 2: Total Order -->
    <div class="card" style="padding: 14px 16px; display: flex; align-items: center; gap: 12px;">
        <div style="width: 40px; height: 40px; flex-shrink: 0; border-radius: 10px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; display: flex; align-items: center; justify-content: center;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
        </div>
        <div>
            <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 2px;">Total Pesanan</p>
            <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--text); line-height: 1.2;"><?= number_format($stats['total']) ?></h3>
        </div>
    </div>

    <!-- Card 3: Sukses -->
    <div class="card" style="padding: 14px 16px; display: flex; align-items: center; gap: 12px;">
        <div style="width: 40px; height: 40px; flex-shrink: 0; border-radius: 10px; background: rgba(249, 115, 22, 0.1); color: #f97316; display: flex; align-items: center; justify-content: center;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div>
            <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 2px;">Pesanan Sukses</p>
            <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--text); line-height: 1.2;"><?= number_format($stats['success']) ?></h3>
        </div>
    </div>

    <!-- Card 4: Customer Ultah -->
    <div class="card" style="padding: 14px 16px; display: flex; align-items: center; gap: 12px; <?= (!empty($todayBirthdays) && $todayBirthdays > 0) ? 'border-color: rgba(234, 88, 12, 0.4); background: linear-gradient(135deg, #ffffff 0%, rgba(254, 243, 199, 0.35) 100%);' : '' ?>">
        <div style="width: 40px; height: 40px; flex-shrink: 0; border-radius: 10px; background: rgba(234, 88, 12, 0.1); color: #ea580c; display: flex; align-items: center; justify-content: center;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12v10H4V12"/><path d="M2 7h20v5H2z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
        </div>
        <div style="flex: 1;">
            <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 2px;">Customer Ultah</p>
            <div style="display: flex; align-items: baseline; gap: 6px;">
                <h3 style="font-size: 1.2rem; font-weight: 700; color: #ea580c; line-height: 1.2;">
                    <?= (int)($todayBirthdays ?? 0) ?> <span style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted);">hari ini</span>
                </h3>
                <span style="font-size: 0.72rem; color: var(--text-muted);">/ <?= count($upcomingBirthdays ?? []) ?> 14 hari</span>
            </div>
        </div>
    </div>
</div>

<!-- Customer Birthday Promo Widget -->
<?php if (!empty($upcomingBirthdays)): ?>
<div class="card" style="margin-bottom: 24px; padding: 18px 20px; border-left: 4px solid #ea580c; background: #ffffff;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(234, 88, 12, 0.1); color: #ea580c; display: flex; align-items: center; justify-content: center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12v10H4V12"/><path d="M2 7h20v5H2z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
            </div>
            <div>
                <h3 style="font-size: 0.98rem; font-weight: 700; margin: 0; color: var(--text);">Customer Berulang Tahun (14 Hari Mendatang)</h3>
                <p style="font-size: 0.78rem; color: var(--text-muted); margin: 2px 0 0 0;">Kirimkan ucapan selamat dan penawaran diskon spesial langsung ke WhatsApp customer.</p>
            </div>
        </div>
        <span class="badge" style="background: rgba(234, 88, 12, 0.1); color: #c2410c; border: 1px solid rgba(234, 88, 12, 0.2); font-weight: 700; font-size: 0.76rem; padding: 5px 12px; border-radius: 20px;">
            <?= count($upcomingBirthdays) ?> Customer
        </span>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 12px;">
        <?php foreach ($upcomingBirthdays as $bCust): ?>
            <?php
                $isToday = ($bCust['days_until_birthday'] === 0);
                $cleanPhone = preg_replace('/\D/', '', $bCust['phone'] ?? '');
                if (str_starts_with($cleanPhone, '0')) {
                    $cleanPhone = '62' . substr($cleanPhone, 1);
                }
                $custName = trim($bCust['name'] ?? 'Kakak');
                $ageTurning = $bCust['age_turning'] ?? 0;
                $msgText = "Halo Kak {$custName},\n\nKami segenap tim La Hair Studio mengucapkan selamat ulang tahun. Semoga senantiasa diberikan kesehatan, kebahagiaan, dan kelancaran dalam segala aktivitas.\n\nSebagai bentuk apresiasi atas kepercayaan Kakak kepada La Hair Studio, kami memberikan penawaran istimewa berupa Diskon Spesial Ulang Tahun sebesar 20% untuk seluruh layanan perawatan rambut di salon kami.\n\nUntuk menikmati penawaran ini, Kakak cukup menunjukkan pesan ini kepada staf kami saat berkunjung.\n\nTerima kasih atas kepercayaannya pada La Hair Studio. Kami nantikan kedatangan Kakak kembali.";
                $waUrl = "https://wa.me/{$cleanPhone}?text=" . rawurlencode($msgText);
            ?>
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 14px; border-radius: 10px; border: 1px solid <?= $isToday ? 'rgba(234, 88, 12, 0.4)' : 'var(--border)' ?>; background: <?= $isToday ? 'linear-gradient(135deg, rgba(254, 243, 199, 0.35) 0%, rgba(255, 237, 213, 0.4) 100%)' : '#fafafa' ?>; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 10px; min-width: 0;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: <?= $isToday ? '#ea580c' : 'rgba(139, 69, 19, 0.1)' ?>; color: <?= $isToday ? '#ffffff' : 'var(--primary)' ?>; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; flex-shrink: 0;">
                        <?= strtoupper(substr($bCust['name'], 0, 2)) ?>
                    </div>
                    <div style="min-width: 0;">
                        <div style="font-weight: 700; font-size: 0.88rem; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?= esc($bCust['name']) ?>
                        </div>
                        <div style="display: flex; align-items: center; gap: 6px; font-size: 0.74rem; color: var(--text-muted); margin-top: 2px;">
                            <span>Ultah: <?= esc($bCust['birthday_formatted']) ?><?= $ageTurning > 0 ? " ({$ageTurning} th)" : "" ?></span>
                            <span>•</span>
                            <span style="font-weight: 600; color: <?= $isToday ? '#ea580c' : '#4b5563' ?>;">
                                <?= $isToday ? 'Hari Ini' : 'H-' . $bCust['days_until_birthday'] ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <a href="<?= $waUrl ?>" target="_blank" class="btn btn-sm" style="display: inline-flex; align-items: center; gap: 6px; background: #25D366; color: white; padding: 6px 12px; font-size: 0.75rem; font-weight: 600; border-radius: 6px; text-decoration: none; flex-shrink: 0; box-shadow: 0 1px 3px rgba(37,211,102,0.3);">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.983z"/></svg>
                    Kirim Promo
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="card">
    <?php if (empty($orders)): ?>
        <div class="empty-state">
            <h3>Belum ada pesanan</h3>
            <p>Tidak ada pesanan pada rentang tanggal ini.</p>
        </div>
    <?php else: ?>

        <div class="table-responsive">
            <table class="table" style="font-size: 0.88rem;">
                <thead>
                    <tr>
                        <th style="padding: 10px 20px;">No. Pesanan</th>
                        <th style="padding: 10px 14px;">Pelanggan</th>
                        <th style="padding: 10px 14px;">Total</th>
                        <th style="padding: 10px 14px;">Status</th>
                        <th style="padding: 10px 14px;">Pekerja</th>
                        <th style="padding: 10px 14px;">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr onclick="window.location='/admin/orders/view/<?= $order['order_number'] ?>'" style="cursor: pointer; transition: background 0.1s;">
                            <td style="padding: 10px 20px;">
                                <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                    <span style="font-weight: 700; color: var(--primary); font-family: monospace; font-size: 0.85rem;">
                                        #<?= esc($order['order_number']) ?>
                                    </span>
                                    <?php if (!empty($order['branch_name'])): ?>
                                        <span style="font-size: 0.68rem; font-weight: 600; padding: 1px 6px; border-radius: 4px; background: var(--primary-light, #fdf5f2); color: var(--primary, #8B4513); border: 1px solid rgba(139, 69, 19, 0.15);">
                                            <?= esc($order['branch_name']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td style="padding: 10px 14px;">
                                <div style="font-weight: 600; color: var(--text); font-size: 0.88rem;"><?= esc($order['customer_name']) ?></div>
                                <div style="font-size: 0.78rem; color: var(--text-muted);"><?= esc($order['customer_phone']) ?></div>
                            </td>
                            <td style="padding: 10px 14px;">
                                <div style="font-weight: 700; font-size: 0.88rem;">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></div>
                            </td>
                            <td style="padding: 10px 14px;">
                                <span class="badge badge-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span>
                            </td>
                            <td style="padding: 10px 14px;">
                                <?php 
                                    $orderWorkers = $order['workers'] ?? [];
                                    $workerNames = !empty($order['worker_names']) ? $order['worker_names'] : ($order['worker_name'] ?? '');
                                ?>
                                <?php if (!empty($orderWorkers)): ?>
                                    <div style="display: flex; align-items: center; gap: 6px;" title="<?= esc($workerNames) ?>">
                                        <div style="display: flex; align-items: center; flex-shrink: 0;">
                                            <?php foreach ($orderWorkers as $wIdx => $wItem): ?>
                                                <div style="width: 22px; height: 22px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 700; color: #475569; border: 1.5px solid #fff; margin-left: <?= $wIdx > 0 ? '-6px' : '0' ?>; z-index: <?= 10 - $wIdx ?>;" title="<?= esc($wItem['username']) ?>">
                                                    <?= strtoupper(substr($wItem['username'], 0, 1)) ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <span style="font-weight: 500; font-size: 0.85rem; max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            <?= esc($workerNames) ?>
                                        </span>
                                    </div>
                                <?php elseif (!empty($order['worker_name'])): ?>
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <div style="width: 22px; height: 22px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 700; color: #64748b; flex-shrink: 0;">
                                            <?= strtoupper(substr($order['worker_name'], 0, 1)) ?>
                                        </div>
                                        <span style="font-weight: 500; font-size: 0.85rem;"><?= esc($order['worker_name']) ?></span>
                                    </div>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-size: 0.82rem;">-</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 10px 14px;">
                                <div style="color: var(--text-muted); font-size: 0.83rem;"><?= date('d M Y', strtotime($order['created_at'])) ?></div>
                                <div style="font-size: 0.75rem; color: var(--text-muted); opacity: 0.7;"><?= date('H:i', strtotime($order['created_at'])) ?></div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<style>
    .badge-pending { background: rgba(255, 193, 7, 0.15); color: #f59e0b; }
    .badge-processing { background: rgba(33, 150, 243, 0.15); color: #3b82f6; }
    .badge-completed { background: rgba(76, 175, 80, 0.15); color: #22c55e; }
    .badge-cancelled { background: rgba(244, 67, 54, 0.15); color: #ef4444; }
</style>

<?= $this->endSection() ?>
