<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1 class="page-title">Pengajuan Penarikan</h1>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="card">
    <?php if (empty($withdrawals)): ?>
        <div class="empty-state">
            <h3>Belum ada pengajuan</h3>
            <p>Pengajuan penarikan dana dari affiliate akan muncul di sini.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Affiliate</th>
                        <th>Informasi Rekening</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($withdrawals as $w): ?>
                        <tr>
                            <td>
                                <strong><?= esc($w['affiliate_name']) ?></strong>
                                <br><small style="color: var(--text-muted)"><?= esc($w['affiliate_email']) ?></small>
                            </td>
                            <td>
                                <?php if ($w['bank_name']): ?>
                                    <strong><?= esc($w['bank_name']) ?></strong><br>
                                    <span><?= esc($w['account_number']) ?></span><br>
                                    <small>a.n. <?= esc($w['account_name']) ?></small>
                                <?php else: ?>
                                    <span style="color: #ef4444; font-size: 0.8rem;">Belum mengisi data rekening</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong style="color: var(--primary)">Rp <?= number_format($w['amount'], 0, ',', '.') ?></strong>
                            </td>
                            <td>
                                <span class="badge badge-<?= $w['status'] ?>"><?= ucfirst($w['status']) ?></span>
                            </td>
                            <td>
                                <?= date('d M Y, H:i', strtotime($w['created_at'])) ?>
                            </td>
                            <td>
                                <?php if ($w['status'] === 'pending'): ?>
                                    <div style="display: flex; gap: 8px;">
                                        <button class="btn btn-primary btn-sm" onclick="approveWithdrawal(<?= $w['id'] ?>)">Setujui</button>
                                        <button class="btn btn-secondary btn-sm" onclick="rejectWithdrawal(<?= $w['id'] ?>)">Tolak</button>
                                    </div>
                                <?php else: ?>
                                    <small style="color: var(--text-muted)"><?= esc($w['notes'] ?: '-') ?></small>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Approve -->
<div id="approveModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 24px; border-radius: 8px; width: 100%; max-width: 400px; position: relative;">
        <h3 style="margin-bottom: 20px;">Setujui Penarikan</h3>
        
        <div style="background: rgba(255, 107, 44, 0.1); color: #ff6b2c; padding: 12px 16px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: flex-start; gap: 12px; border: 1px solid rgba(255, 107, 44, 0.2);">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20" style="margin-top: 2px; flex-shrink: 0;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span style="font-size: 0.85rem; line-height: 1.5; font-weight: 500;">
                <strong>Penting:</strong> Pastikan dana sudah berhasil ditransfer ke rekening mitra sebelum Bapak memberikan persetujuan akhir.
            </span>
        </div>
        <form id="approveForm" action="" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="status" value="approved">
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 4px; font-weight: 600;">Catatan Transfer:</label>
                <textarea name="notes" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; min-height: 80px;" placeholder="Contoh: Sudah ditransfer via BCA / No. Ref: 12345">Sudah ditransfer</textarea>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="btn btn-secondary" onclick="closeApproveModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Konfirmasi & Setujui</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Reject -->
<div id="rejectModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 24px; border-radius: 8px; width: 100%; max-width: 400px; position: relative;">
        <h3 style="margin-bottom: 20px;">Tolak Penarikan</h3>
        
        <div style="background: rgba(30, 41, 59, 0.05); color: #475569; padding: 12px 16px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: flex-start; gap: 12px; border: 1px solid rgba(0, 0, 0, 0.05);">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20" style="margin-top: 2px; flex-shrink: 0;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span style="font-size: 0.85rem; line-height: 1.5; font-weight: 500;">
                <strong>Info:</strong> Saldo mitra akan dikembalikan secara otomatis ke akun mereka setelah penolakan ini dikonfirmasi.
            </span>
        </div>
        <form id="rejectForm" action="" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="status" value="rejected">
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 4px; font-weight: 600;">Alasan Penolakan:</label>
                <textarea name="notes" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; min-height: 80px;" placeholder="Contoh: Nomor rekening tidak valid"></textarea>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="btn btn-secondary" onclick="closeRejectModal()">Batal</button>
                <button type="submit" class="btn btn-danger" style="background: #ef4444; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Tolak & Kembalikan Saldo</button>
            </div>
        </form>
    </div>
</div>

<script>
    function approveWithdrawal(id) {
        document.getElementById('approveForm').action = '/admin/withdrawals/update/' + id;
        document.getElementById('approveModal').style.display = 'flex';
    }

    function closeApproveModal() {
        document.getElementById('approveModal').style.display = 'none';
    }

    function rejectWithdrawal(id) {
        document.getElementById('rejectForm').action = '/admin/withdrawals/update/' + id;
        document.getElementById('rejectModal').style.display = 'flex';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('rejectModal')) {
            closeRejectModal();
        }
        if (event.target == document.getElementById('approveModal')) {
            closeApproveModal();
        }
    }
</script>

<style>
    .badge-approved { background: rgba(34, 197, 94, 0.15); color: #22c55e; }
    .badge-rejected { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
    .badge-pending { background: rgba(255, 193, 7, 0.15); color: #f59e0b; }
</style>

<?= $this->endSection() ?>
