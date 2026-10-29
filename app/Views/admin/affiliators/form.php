<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <h1 class="page-title"><?= $title ?></h1>
    <a href="/admin/affiliators" class="btn btn-secondary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>
</div>

<div class="card" style="max-width: 600px;">
    <div class="admin-container" style="padding: 24px;">
        <form action="<?= isset($affiliator) ? '/admin/affiliators/update/' . $affiliator['id'] : '/admin/affiliators/store' ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label class="form-label">Nama Afiliator</label>
                <input type="text" name="name" class="form-input" value="<?= old('name', $affiliator['name'] ?? '') ?>" required placeholder="Contoh: Budi Store">
                <?php if (isset(session('errors')['name'])): ?>
                    <div class="form-help" style="color: var(--danger);"><?= session('errors')['name'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Slug / Custom Link</label>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="color: var(--text-muted); font-size: 0.9rem;">/a/</span>
                    <input type="text" name="slug" id="slugInput" class="form-input" value="<?= old('slug', $affiliator['slug'] ?? '') ?>" required placeholder="contoh-link">
                </div>
                <div class="form-help">
                    Link akan menjadi: <code><?= base_url('a/') ?><span id="slugPreview"><?= old('slug', $affiliator['slug'] ?? 'contoh-link') ?></span></code>
                </div>
                <?php if (isset(session('errors')['slug'])): ?>
                    <div class="form-help" style="color: var(--danger);"><?= session('errors')['slug'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Afiliator
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('slugInput').addEventListener('input', function(e) {
    let slug = e.target.value.toLowerCase().replace(/[^a-z0-9-]/g, '-').replace(/-+/g, '-');
    e.target.value = slug;
    document.getElementById('slugPreview').innerText = slug || 'contoh-link';
});
</script>
<?= $this->endSection() ?>
