<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title"><?= esc($title) ?></h1>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="/admin/products" class="btn btn-secondary">← Kembali</a>
        <form action="/admin/products/items/<?= $product['id'] ?>/delete-all" method="post" onsubmit="return confirm('PERINGATAN: Yakin ingin menghapus SEMUA isi menu ini? Tindakan ini tidak bisa dibatalkan.')" style="display: inline;">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-danger">
                <span>×</span> Hapus Semua
            </button>
        </form>
        <a href="/admin/products/items/<?= $product['id'] ?>/create" class="btn btn-primary">
            <span>+</span> Tambah Isi
        </a>
    </div>
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

<!-- Product Info -->
<div class="card" style="padding: 16px; margin-bottom: 20px;">
    <div style="display: flex; align-items: center; gap: 14px;">
        <?php if ($product['image']): ?>
            <img src="/<?= esc($product['image']) ?>" alt="<?= esc($product['name']) ?>" style="width: 56px; height: 56px; border-radius: 10px; object-fit: cover;">
        <?php else: ?>
            <div class="product-icon" style="background: var(--primary, #8b4513); width: 56px; height: 56px; font-size: 1.1rem;">
                <?= strtoupper(substr($product['name'], 0, 2)) ?>
            </div>
        <?php endif; ?>
        <div>
            <strong style="font-size: 1.1rem;"><?= esc($product['name']) ?></strong>
            <?php if ($product['description']): ?>
                <br><small style="color: var(--text-muted)"><?= esc($product['description']) ?></small>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Import CSV Section -->
<div class="card" style="padding: 16px; margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <h3 style="font-size: 1rem;">Export / Import Excel (CSV & XLSX)</h3>
        <a href="/admin/products/items/<?= $product['id'] ?>/template" style="background: #25D366; color: white; padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/><path d="M14 14h-4v4h4v-4z"/></svg>
            Export Data (CSV)
        </a>
    </div>
    <form action="/admin/products/items/<?= $product['id'] ?>/import" method="post" enctype="multipart/form-data" style="display: flex; gap: 10px;">
        <?= csrf_field() ?>
        <div style="flex: 1;">
            <input type="file" name="csv_file" accept=".csv,.xlsx" required class="form-input" style="padding: 8px;">
        </div>
        <button type="submit" class="btn btn-secondary">Upload Update</button>
    </form>
    <div style="margin-top: 10px; padding: 10px; background: var(--bg-input); border-radius: 6px; font-size: 0.75rem; color: var(--text-muted); line-height: 1.5;">
        <strong>Cara Update Massal:</strong>
        <ol style="margin-left: 20px; margin-top: 4px;">
            <li>Klik tombol <strong>Export Data (CSV)</strong> di atas untuk download data saat ini.</li>
            <li>Buka dan edit file di Excel (kolom ID boleh dibiarkan atau dikosongkan untuk item baru).</li>
            <li>Bisa langsung upload file <strong>.CSV</strong> maupun file asli Excel <strong>.XLSX</strong>.</li>

        </ol>
    </div>
</div>

<div class="card">
    <?php if (empty($items)): ?>
        <div class="empty-state">
            <h3>Belum ada isi menu</h3>
            <p>Tambahkan isi untuk menu ini</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Durasi</th>
                        <th>Terjual</th>
                        <th>Status</th>
                        <th>Urutan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td style="width: 60px;">
                                <?php if ($item['image']): ?>
                                    <img src="/<?= esc($item['image']) ?>" alt="" style="width: 40px; height: 40px; border-radius: 6px; object-fit: cover;">
                                <?php else: ?>
                                    <div style="width: 40px; height: 40px; border-radius: 6px; background: var(--bg-input); display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.7rem;">-</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= esc($item['name']) ?></strong>
                            </td>
                            <td>
                                <span style="color: var(--primary); font-weight: 600;">
                                    Rp <?= number_format($item['price'], 0, ',', '.') ?>
                                </span>
                            </td>
                            <td>
                                <span style="background: rgba(255, 107, 44, 0.1); color: var(--primary); padding: 4px 10px; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">
                                    <?= $item['duration_minutes'] ?? 60 ?> menit
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-muted"><?= number_format($item['sold'], 0, ',', '.') ?></span>
                            </td>
                            <td>
                                <?php if ($item['is_active']): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-muted">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $item['sort_order'] ?></td>
                            <td>
                                <div class="actions">
                                    <a href="/admin/products/items/<?= $item['id'] ?>/addons" class="btn btn-secondary btn-sm" title="Kelola Add-ons">
                                        <span style="font-size: 1.1rem;">+</span> Add-ons
                                    </a>
                                    <a href="/admin/products/items/<?= $product['id'] ?>/edit/<?= $item['id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="/admin/products/items/<?= $product['id'] ?>/delete/<?= $item['id'] ?>" method="post" onsubmit="return confirm('Yakin hapus item ini?')">
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
