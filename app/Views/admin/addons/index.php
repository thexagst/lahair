<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1 class="page-title">Kelola Add-ons: <?= esc($item['name']) ?></h1>
    <a href="/admin/products/items/<?= $productItem['product_id'] ?>" class="btn btn-secondary">← Kembali</a>
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

<!-- Item Info Card -->
<div class="card" style="padding: 20px; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; gap: 16px;">
        <?php if ($item['image']): ?>
            <img src="/<?= esc($item['image']) ?>" alt="<?= esc($item['name']) ?>" style="width: 64px; height: 64px; border-radius: 12px; object-fit: cover;">
        <?php else: ?>
            <div style="width: 64px; height: 64px; border-radius: 12px; background: var(--bg-input); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--text-muted);">+</div>
        <?php endif; ?>
        <div style="flex: 1;">
            <h3 style="margin: 0; font-size: 1.2rem;"><?= esc($item['name']) ?></h3>
            <p style="margin: 6px 0 0; color: var(--text-muted); font-size: 0.9rem;">
                Harga: <strong style="color: var(--primary);">Rp <?= number_format($item['price'], 0, ',', '.') ?></strong> • 
                Durasi: <strong><?= $item['duration_minutes'] ?? 60 ?> menit</strong>
            </p>
        </div>
        <a href="/admin/products/items/<?= $item['id'] ?>/addons/create" class="btn btn-primary">
            <span style="font-size: 1.2rem;">+</span> Tambah Add-on
        </a>
    </div>
</div>

<!-- Add-ons List -->
<div class="card">
    <?php if (empty($addons)): ?>
        <div class="empty-state">
            <h3>Belum ada add-on</h3>
            <p>Tambahkan add-on untuk layanan ini (opsional)</p>
            <a href="/admin/products/items/<?= $item['id'] ?>/addons/create" class="btn btn-primary" style="margin-top: 12px;">
                <span style="font-size: 1.2rem;">+</span> Tambah Add-on Pertama
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Add-on</th>
                        <th>Harga Tambahan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($addons as $addon): ?>
                        <tr>
                            <td>
                                <strong><?= esc($addon['name']) ?></strong>
                                <?php if ($addon['is_required']): ?>
                                    <span class="badge badge-error" style="background-color: var(--danger); color: white; margin-left: 8px; font-size: 0.7rem;">Wajib</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="color: var(--primary); font-weight: 600;">
                                    + Rp <?= number_format($addon['price'], 0, ',', '.') ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($addon['is_active']): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-muted">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="/admin/products/items/<?= $item['id'] ?>/addons/edit/<?= $addon['id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="/admin/products/items/<?= $item['id'] ?>/addons/delete/<?= $addon['id'] ?>" method="post" onsubmit="return confirm('Yakin hapus add-on ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
