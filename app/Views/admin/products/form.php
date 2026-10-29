<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>


<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 45px;
        padding: 8px;
        border: 1px solid var(--border);
        border-radius: var(--radius);
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 43px;
    }
</style>

<div class="page-header">
    <h1 class="page-title"><?= esc($title) ?></h1>
    <a href="/admin/products" class="btn btn-secondary">← Kembali</a>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-error">
        <ul style="margin: 0; padding-left: 20px;">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card" style="padding: 24px;">
    <form action="<?= isset($product) ? '/admin/products/update/' . $product['id'] : '/admin/products/store' ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Nama Menu *</label>
                <input 
                    type="text" 
                    name="name" 
                    class="form-input" 
                    value="<?= old('name', $product['name'] ?? '') ?>"
                    placeholder="Contoh: Hair Flawless "
                    required
                >
            </div>
            
            <div class="form-group">
                <label class="form-label">Kategori *</label>
                <select name="category" class="form-select select2-tags" required style="width: 100%;">
                    <option value="">Pilih atau Buat Kategori</option>
                    <?php 
                    $currentCategory = old('category', $product['category'] ?? '');
                    $allCategories = $categories ?? [];
                    // Add standard categories if list is empty or just as defaults
                    if (empty($allCategories)) {
                        $allCategories = ['game', 'voucher', 'service', 'other'];
                    }
                    
                    // Add current category if distinct
                    if ($currentCategory && !in_array($currentCategory, $allCategories)) {
                        $allCategories[] = $currentCategory;
                    }
                    
                    $allCategories = array_unique($allCategories);
                    sort($allCategories);
                    
                    foreach ($allCategories as $cat): 
                    ?>
                        <option value="<?= esc($cat) ?>" <?= $currentCategory == $cat ? 'selected' : '' ?>><?= esc(ucfirst($cat)) ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="form-help">Ketik untuk membuat kategori baru atau pilih dari daftar.</p>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Deskripsi Singkat</label>
            <input 
                type="text" 
                name="description" 
                class="form-input" 
                value="<?= old('description', $product['description'] ?? '') ?>"
                placeholder="Contoh: Perawatan rambut rusak & kering"
                maxlength="100"
            >
            <p class="form-help">Maksimal 100 karakter</p>
        </div>
        
        <!-- Image Upload -->
        <div class="form-group">
            <label class="form-label">Gambar Menu</label>
            <div class="image-upload-container">
                <?php if (isset($product) && $product['image']): ?>
                    <div class="current-image">
                        <img src="/<?= esc($product['image']) ?>" alt="Current image" style="max-width: 150px; max-height: 150px; border-radius: 8px; margin-bottom: 10px;">
                        <p style="font-size: 0.8rem; color: var(--text-muted);">Gambar saat ini</p>
                    </div>
                <?php endif; ?>
                <input 
                    type="file" 
                    name="image" 
                    id="imageInput"
                    class="form-input" 
                    accept="image/jpeg,image/png,image/webp"
                    onchange="previewImage(this)"
                >
                <div id="imagePreview" style="margin-top: 10px; display: none;">
                    <img id="preview" src="" alt="Preview" style="max-width: 150px; max-height: 150px; border-radius: 8px;">
                </div>
                <p class="form-help">Format: JPG, PNG, WEBP. Maks 2MB</p>
            </div>
        </div>
        
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Total Terjual (Variasi)</label>
                <input 
                    type="number" 
                    name="sold" 
                    class="form-input" 
                    value="<?= old('sold', $product['sold'] ?? 0) ?>"
                    min="0"
                >
                <p class="form-help">Angka ini akan ditampilkan di halaman depan</p>
            </div>
        </div>
                    
        <div class="form-group">
            <label class="form-check">
                <input 
                    type="checkbox" 
                    name="is_active" 
                    value="1"
                    <?= old('is_active', $product['is_active'] ?? 1) ? 'checked' : '' ?>
                >
                <span>Menu Aktif (tampilkan di website)</span>
            </label>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= isset($product) ? 'Update Menu' : 'Simpan Menu' ?>
            </button>
            <a href="/admin/products" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<!-- Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2-tags').select2({
            tags: true,
            placeholder: "Pilih atau ketik kategori baru",
            allowClear: true,
            tokenSeparators: [','] // Allow comma validity
        });
    });

    function previewImage(input) {
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?= $this->endSection() ?>
