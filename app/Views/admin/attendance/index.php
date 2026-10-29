<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="header" style="margin-bottom: 24px;">
    <div class="header-left">
        <h1 class="page-title">Absensi Harian</h1>
        <p class="page-subtitle"><?= date('l, d F Y', strtotime($today)) ?></p>
    </div>
    <div class="header-right">
        <a href="/admin/attendance/report" class="btn btn-outline-secondary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            Laporan
        </a>
        <a href="/admin/attendance/holidays" class="btn btn-outline-secondary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            Hari Libur
        </a>
    </div>
</div>

<div class="app-grid">
    <!-- LEFT: QR Code Display -->
    <div class="card" style="text-align: center; padding: 40px;">
        <h3 class="card-title">Scan untuk Absen</h3>
        <p class="text-muted" style="margin-bottom: 30px;">
            Worker dapat melakukan scan QR Code ini melalui menu <strong>Scan Absen</strong> di dashboard masing-masing.
        </p>
        
        <div id="qrcode" style="display: inline-block; padding: 20px; background: white; border: 1px solid var(--border); border-radius: 12px;"></div>
        
        <div style="margin-top: 20px; font-family: monospace; color: var(--text-muted); font-size: 0.9rem;">
            Token Hari Ini: <span id="tokenDisplay" style="filter: blur(4px); cursor: pointer;" onclick="this.style.filter='none'"><?= substr($token, 0, 8) ?>...</span>
        </div>
        
        <div style="margin-top: 40px;">
            <div class="alert alert-info">
                QR Code ini diperbarui secara otomatis setiap hari.
            </div>
        </div>
    </div>

    <!-- RIGHT: Today's Attendance List -->
    <div class="card">
        <h3 class="card-title">Kehadiran Hari Ini</h3>
        
        <?php if (empty($attendance_list)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
                <h3>Belum ada absensi</h3>
                <p>Belum ada worker yang melakukan check-in hari ini.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendance_list as $row): ?>
                        <tr>
                            <td class="fw-500">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 24px; height: 24px; background: var(--bg-input); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700;">
                                        <?= strtoupper(substr($row['username'], 0, 2)) ?>
                                    </div>
                                    <?= esc($row['username']) ?>
                                </div>
                            </td>
                            <td><?= date('H:i', strtotime($row['check_in'])) ?></td>
                            <td>
                                <?php if ($row['check_out']): ?>
                                    <?= date('H:i', strtotime($row['check_out'])) ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                    $badgeClass = 'bg-success';
                                    if ($row['status'] == 'telat') $badgeClass = 'bg-warning text-dark';
                                    if ($row['status'] == 'alpha') $badgeClass = 'bg-danger';
                                    if ($row['status'] == 'izin' || $row['status'] == 'sakit') $badgeClass = 'bg-info';
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= ucfirst($row['status']) ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- QRCode Library -->
<!-- QRCode Library moved to layout -->
<script>
    var token = "<?= $token ?>";
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        text: token,
        width: 256,
        height: 256,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });
    
    // Auto refresh logic check? Not really needed unless day changes while viewing.
    // Daily refresh is handled by server on reload.
</script>

<style>
    .app-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 900px) {
        .app-grid { grid-template-columns: 1fr; }
    }
</style>

<?= $this->endSection() ?>
