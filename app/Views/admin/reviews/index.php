<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1 class="page-title">Moderasi Ulasan</h1>
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
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Nama</th>
                    <th>Produk</th>
                    <th>Rating</th>
                    <th>Ulasan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reviews)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted);">Belum ada ulasan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <tr>
                            <td><?= date('d M Y H:i', strtotime($review['created_at'])) ?></td>
                            <td><?= esc($review['customer_name']) ?></td>
                            <td><?= esc($review['product_name']) ?></td>
                            <td>
                                <div style="color: #FFD700;">
                                    <?= str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) ?>
                                </div>
                            </td>
                            <td><?= esc($review['review_text']) ?></td>
                            <td>
                                <?php if ($review['is_approved']): ?>
                                    <span class="badge badge-success">Disetujui</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Menunggu</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?php if (!$review['is_approved']): ?>
                                        <form action="/admin/reviews/approve/<?= $review['id'] ?>" method="post" style="display: inline;">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-success" title="Setujui">✔</button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <form action="/admin/reviews/delete/<?= $review['id'] ?>" method="post" style="display: inline;" onsubmit="return confirm('Hapus ulasan ini?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">✕</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
