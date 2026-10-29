<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1 class="page-title">Kelola Menu</h1>
    <a href="/admin/products/create" class="btn btn-primary">
        <span>+</span> Tambah Menu
    </a>
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

<!-- Search Box -->
<form method="get" class="search-box">
    <input type="text" name="search" class="search-input" placeholder="Cari menu atau kategori..." value="<?= esc($search ?? '') ?>">
    <button type="submit" class="btn btn-primary">Cari</button>
    <?php if ($search): ?>
        <a href="/admin/products" class="btn btn-secondary">Reset</a>
    <?php endif; ?>
</form>

<div class="card">
    <?php if (empty($products)): ?>
        <div class="empty-state">
            <h3><?= $search ? 'Tidak ada hasil' : 'Belum ada menu' ?></h3>
            <p><?= $search ? 'Coba kata kunci lain' : 'Mulai tambahkan menu untuk ditampilkan di website' ?></p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Kategori</th>
                        <th>Jumlah Isi</th>
                        <th>Terjual</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <div class="product-name">
                                    <?php if ($product['image']): ?>
                                        <img src="/<?= esc($product['image']) ?>" alt="" style="width: 44px; height: 44px; border-radius: 8px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="product-icon" style="background: var(--primary, #8b4513)">
                                            <?= strtoupper(substr($product['name'], 0, 2)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <strong><?= esc($product['name']) ?></strong>
                                        <?php if ($product['description']): ?>
                                            <br><small style="color: var(--text-muted)"><?= esc($product['description']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-muted"><?= esc(ucfirst($product['category'])) ?></span>
                            </td>
                            <td>
                                <a href="/admin/products/items/<?= $product['id'] ?>" style="color: var(--primary); font-weight: 500;">
                                    <?= $product['items_count'] ?> item
                                </a>
                            </td>
                            <td>
                                <span class="badge badge-muted"><?= number_format($product['sold'], 0, ',', '.') ?></span>
                            </td>
                            <td>
                                <?php if ($product['is_active']): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-muted">Nonaktif</span>
                                <?php endif; ?>
                            
                            <td>
                                <div class="actions">
                                    <a href="/admin/products/items/<?= $product['id'] ?>" class="btn btn-secondary btn-sm">Isi</a>
                                    <form action="/admin/products/items/<?= $product['id'] ?>/delete-all" method="post" onsubmit="return confirm('Hapus SEMUA isi menu <?= esc($product['name']) ?>?')" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus Semua Isi">Kosongkan</button>
                                    </form>
                                    <a href="/admin/products/edit/<?= $product['id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="/admin/products/delete/<?= $product['id'] ?>" method="post" onsubmit="return confirm('Yakin hapus produk ini? Semua harga akan ikut terhapus.')">
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

        <!-- Pagination -->
        <?php if ($pager && $pager->getPageCount() > 1): ?>
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Menampilkan <?= ($pager->getCurrentPage() - 1) * $pager->getPerPage() + 1 ?> - 
                    <?= min($pager->getCurrentPage() * $pager->getPerPage(), $pager->getTotal()) ?> 
                    dari <?= $pager->getTotal() ?> menu
                </div>
                <?= $pager->links('products', 'default') ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
