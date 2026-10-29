<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <h1 class="page-title">Manajemen Afiliator</h1>
    <a href="/admin/affiliators/create" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Afiliator
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

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Afiliator</th>
                    <th>Slug / Link</th>
                    <th>Dibuat Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($affiliators)): ?>
                    <tr>
                        <td colspan="4" class="empty-state">
                            <h3>Belum ada afiliator</h3>
                            <p>Klik tombol di atas untuk menambah afiliator baru.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($affiliators as $af): ?>
                        <tr>
                            <td>
                                <div class="product-name">
                                    <div class="product-icon" style="background: var(--primary);">
                                        <?= substr($af['name'], 0, 1) ?>
                                    </div>
                                    <strong><?= esc($af['name']) ?></strong>
                                </div>
                            </td>
                            <td>
                                <code>/a/<?= esc($af['slug']) ?></code>
                                <button onclick="copyLink('<?= base_url('a/' . $af['slug']) ?>')" class="btn btn-sm btn-secondary" style="margin-left: 8px; padding: 2px 8px;">
                                    Salin
                                </button>
                            </td>
                            <td><?= date('d M Y', strtotime($af['created_at'])) ?></td>
                            <td>
                                <div class="actions">
                                    <a href="/admin/affiliators/edit/<?= $af['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                                    <form action="/admin/affiliators/delete/<?= $af['id'] ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
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

<script>
function copyLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        alert('Link berhasil disalin: ' + url);
    });
}
</script>
<?= $this->endSection() ?>
