<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1 class="page-title">Kelola Admin & Worker</h1>
    <a href="/admin/admins/create" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Admin
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
    <input type="text" name="search" class="search-input" placeholder="Cari username atau email..." value="<?= esc($search ?? '') ?>">
    <button type="submit" class="btn btn-primary">Cari</button>
    <?php if ($search): ?>
        <a href="/admin/admins" class="btn btn-secondary">Reset</a>
    <?php endif; ?>
</form>

<div class="card">
    <?php if (empty($admins)): ?>
        <div class="empty-state">
            <h3><?= $search ? 'Tidak ada hasil' : 'Belum ada admin' ?></h3>
            <p><?= $search ? 'Coba kata kunci lain' : 'Klik tombol "Tambah Admin" untuk menambahkan admin atau worker baru.' ?></p>
        </div>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><strong><?= esc($admin['username']) ?></strong></td>
                        <td><?= esc($admin['email']) ?></td>
                        <td>
                            <?php if ($admin['role'] === 'admin'): ?>
                                <span class="badge badge-success">Super Admin</span>
                            <?php else: ?>
                                <span class="badge badge-muted">Worker</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d M Y', strtotime($admin['created_at'])) ?></td>
                        <td>
                            <div class="actions">
                                <a href="/admin/admins/edit/<?= $admin['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                                <?php if ($admin['id'] != session()->get('admin_id')): ?>
                                    <form action="/admin/admins/delete/<?= $admin['id'] ?>" method="post" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus admin ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($pager && $pager->getPageCount() > 1): ?>
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Menampilkan <?= ($pager->getCurrentPage() - 1) * $pager->getPerPage() + 1 ?> - 
                    <?= min($pager->getCurrentPage() * $pager->getPerPage(), $pager->getTotal()) ?> 
                    dari <?= $pager->getTotal() ?> admin
                </div>
                <?= $pager->links('admins', 'default') ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
