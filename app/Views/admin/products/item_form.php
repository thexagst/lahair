<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1 class="page-title"><?= esc($title) ?></h1>
    <a href="/admin/products/items/<?= $product['id'] ?>" class="btn btn-secondary">← Kembali</a>
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

<!-- Product Info -->
<div class="card" style="padding: 16px; margin-bottom: 20px;">
    <div style="display: flex; align-items: center; gap: 14px;">
        <?php if ($product['image']): ?>
            <img src="/<?= esc($product['image']) ?>" alt="<?= esc($product['name']) ?>" style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover;">
        <?php else: ?>
            <div class="product-icon" style="background: var(--primary, #8b4513); width: 48px; height: 48px; font-size: 0.85rem;">
                <?= strtoupper(substr($product['name'], 0, 2)) ?>
            </div>
        <?php endif; ?>
        <div>
            <strong><?= esc($product['name']) ?></strong>
        </div>
    </div>
</div>

<div class="card" style="padding: 24px;">
    <form action="<?= isset($item) ? '/admin/products/items/' . $product['id'] . '/update/' . $item['id'] : '/admin/products/items/' . $product['id'] . '/store' ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label class="form-label">Nama Isi *</label>
            <input 
                type="text" 
                name="name" 
                class="form-input" 
                value="<?= old('name', $item['name'] ?? '') ?>"
                placeholder="Contoh: Short Hair, Long Hair, dll"
                required
            >
        </div>
        
        <!-- Image Upload -->
        <div class="form-group">
            <label class="form-label">Gambar Isi</label>
            <div class="image-upload-container">
                <?php if (isset($item) && $item['image']): ?>
                    <div class="current-image">
                        <img src="/<?= esc($item['image']) ?>" alt="Current image" style="max-width: 100px; max-height: 100px; border-radius: 8px; margin-bottom: 10px;">
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
                    <img id="preview" src="" alt="Preview" style="max-width: 100px; max-height: 100px; border-radius: 8px;">
                </div>
                <p class="form-help">Format: JPG, PNG, WEBP. Maks 2MB. Opsional.</p>
            </div>
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Harga (Rp) *</label>
                <input 
                    type="number" 
                    name="price" 
                    class="form-input" 
                    value="<?= old('price', $item['price'] ?? '') ?>"
                    placeholder="150000"
                    min="0"
                    step="100"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label">Durasi Layanan (Menit) *</label>
                <input 
                    type="number" 
                    name="duration_minutes" 
                    class="form-input" 
                    value="<?= old('duration_minutes', $item['duration_minutes'] ?? 60) ?>"
                    placeholder="60"
                    min="15"
                    step="15"
                    required
                >
                <p class="form-help">Contoh: 60 untuk 1 jam, 90 untuk 1.5 jam</p>
            </div>

            <!-- Material Recipe -->
            <div class="form-group" style="grid-column: 1 / -1; background: var(--bg-body); padding: 15px; border-radius: 8px; margin-top: 10px;">
                <label class="form-label" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <span>Komposisi Bahan (Resep)</span>
                    <button type="button" onclick="addMaterial()" class="btn btn-secondary btn-sm" style="font-size: 0.8rem;">+ Tambah Bahan</button>
                </label>
                
                <div id="material-list">
                    <?php if (isset($materials) && !empty($materials)): ?>
                        <?php foreach ($materials as $mat): ?>
                            <div class="material-row" style="display: flex; gap: 10px; margin-bottom: 10px;">
                                <select name="materials[]" class="form-input" style="flex: 2;">
                                    <option value="">Pilih Bahan</option>
                                    <?php foreach ($inventories as $inv): 
                                        $invUnit = (!empty($inv['unit_value']) && !empty($inv['sub_unit'])) ? $inv['sub_unit'] : $inv['unit'];
                                    ?>
                                        <option value="<?= $inv['id'] ?>" <?= $inv['id'] == $mat['inventory_id'] ? 'selected' : '' ?>>
                                            <?= esc($inv['name']) ?> (<?= esc($invUnit) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="number" name="quantities[]" class="form-input" style="flex: 1;" placeholder="Qty" value="<?= $mat['quantity'] ?>" min="1">
                                <button type="button" onclick="removeMaterial(this)" class="btn btn-danger btn-sm" style="padding: 0 10px;">&times;</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <p class="form-help" style="margin-top: 8px;">Pilih bahan dari Gudang Stok yang digunakan untuk satu layanan ini.</p>
            </div>
            
            <div class="form-group">
                <label class="form-label">Urutan Tampil</label>
                <input 
                    type="number" 
                    name="sort_order" 
                    class="form-input" 
                    value="<?= old('sort_order', $item['sort_order'] ?? 0) ?>"
                    min="0"
                >
            </div>

            <div class="form-group">
                <label class="form-label">Jumlah Terjual</label>
                <input 
                    type="number" 
                    name="sold" 
                    class="form-input" 
                    value="<?= old('sold', $item['sold'] ?? 0) ?>"
                    min="0"
                >
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-check">
                <input 
                    type="checkbox" 
                    name="is_active" 
                    value="1"
                    <?= old('is_active', $item['is_active'] ?? 1) ? 'checked' : '' ?>
                >
                <span>Item Aktif (tampilkan di website)</span>
            </label>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= isset($item) ? 'Update Isi' : 'Simpan Isi' ?>
            </button>
            <a href="/admin/products/items/<?= $product['id'] ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
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

    const inventoryOptions = `
        <option value="">Pilih Bahan</option>
        <?php if (isset($inventories)) foreach ($inventories as $inv): 
            $invUnit = (!empty($inv['unit_value']) && !empty($inv['sub_unit'])) ? $inv['sub_unit'] : $inv['unit'];
        ?>
            <option value="<?= $inv['id'] ?>"><?= esc($inv['name']) ?> (<?= esc($invUnit) ?>)</option>
        <?php endforeach; ?>
    `;

    function addMaterial() {
        const list = document.getElementById('material-list');
        const div = document.createElement('div');
        div.className = 'material-row';
        div.style.cssText = 'display: flex; gap: 10px; margin-bottom: 10px;';
        
        div.innerHTML = `
            <select name="materials[]" class="form-input" style="flex: 2;">
                ${inventoryOptions}
            </select>
            <input type="number" name="quantities[]" class="form-input" style="flex: 1;" placeholder="Qty" min="1" value="1">
            <button type="button" onclick="removeMaterial(this)" class="btn btn-danger btn-sm" style="padding: 0 10px;">&times;</button>
        `;
        
        list.appendChild(div);
    }

    window.removeMaterial = function(btn) {
        btn.parentElement.remove();
    }
</script>

<?= $this->endSection() ?>
