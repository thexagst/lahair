<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1 class="page-title"><?= esc($title) ?></h1>
    <a href="/admin/products/items/<?= $item['id'] ?>/addons" class="btn btn-secondary">← Kembali</a>
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

<!-- Item Info -->
<div class="card" style="padding: 16px; margin-bottom: 20px;">
    <div style="display: flex; align-items: center; gap: 14px;">
        <?php if ($item['image']): ?>
            <img src="/<?= esc($item['image']) ?>" alt="<?= esc($item['name']) ?>" style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover;">
        <?php else: ?>
            <div style="width: 48px; height: 48px; border-radius: 8px; background: var(--bg-input); display: flex; align-items: center; justify-content: center; color: var(--text-muted);">+</div>
        <?php endif; ?>
        <div>
            <strong><?= esc($item['name']) ?></strong>
            <br><small style="color: var(--text-muted);">Rp <?= number_format($item['price'], 0, ',', '.') ?></small>
        </div>
    </div>
</div>

<div class="card" style="padding: 24px;">
    <form action="<?= isset($addon) ? '/admin/products/items/' . $item['id'] . '/addons/update/' . $addon['id'] : '/admin/products/items/' . $item['id'] . '/addons/store' ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label class="form-label">Nama Add-on *</label>
            <input 
                type="text"
                name="name"
                class="form-input"
                value="<?= old('name', $addon['name'] ?? '') ?>"
                placeholder="Contoh: Vitamin Rambut, Hair Mask, dll"
                required
            >
            <p class="form-help">Nama tambahan layanan yang bisa dipilih customer</p>
        </div>
        
        <div class="form-group">
            <label class="form-label">Harga Tambahan (Rp) *</label>
            <input 
                type="number" 
                name="price" 
                class="form-input" 
                value="<?= old('price', $addon['price'] ?? '') ?>"
                placeholder="50000"
                min="0"
                step="1000"
                required
            >
            <p class="form-help">Harga yang akan ditambahkan ke total jika customer memilih add-on ini</p>
        </div>

        <div class="form-group">
            <label class="form-check">
                <input 
                    type="checkbox" 
                    name="is_required" 
                    value="1"
                    <?= old('is_required', $addon['is_required'] ?? 0) ? 'checked' : '' ?>
                >
                <span>Wajib Dipilih (Trigger validasi 'Minimal Pilih 1')</span>
            </label>
            <p class="form-help" style="margin-left: 28px;">Jika dicentang, customer WAJIB memilih minimal 1 produk saat checkout</p>
        </div>
        
        <div class="form-group">
            <label class="form-check">
                <input 
                    type="checkbox" 
                    name="is_active" 
                    value="1"
                    <?= old('is_active', $addon['is_active'] ?? 1) ? 'checked' : '' ?>
                >
                <span>Add-on Aktif (tampilkan di checkout)</span>
            </label>
        </div>

        <!-- Material Recipe Section -->
        <div class="form-group" style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border-color, #e5e7eb);">
            <label class="form-label" style="font-size: 1rem; font-weight: 600;">Komposisi Bahan (Resep)</label>
            <p class="form-help" style="margin-bottom: 12px;">Tentukan bahan apa saja yang digunakan dari Gudang Stok untuk add-on ini. Stok akan berkurang otomatis saat order diproses.</p>
            
            <?php 
                $inventories = $inventories ?? [];
                $existingMaterials = $materials ?? [];
                $inventoryOptions = '';
                foreach ($inventories as $inv) {
                    $invUnit = (!empty($inv['unit_value']) && !empty($inv['sub_unit'])) ? $inv['sub_unit'] : $inv['unit'];
                    $inventoryOptions .= '<option value="' . $inv['id'] . '">' . esc($inv['name']) . ' (' . esc($invUnit) . ')</option>';
                }
            ?>
            
            <div id="materialsContainer">
                <?php if (!empty($existingMaterials)): ?>
                    <?php foreach ($existingMaterials as $mat): ?>
                        <div class="material-row" style="display: flex; gap: 10px; align-items: center; margin-bottom: 8px;">
                            <select name="materials[]" class="form-input" style="flex: 2;" required>
                                <option value="">Pilih Bahan</option>
                                <?php foreach ($inventories as $inv): 
                                    $invUnit = (!empty($inv['unit_value']) && !empty($inv['sub_unit'])) ? $inv['sub_unit'] : $inv['unit'];
                                ?>
                                    <option value="<?= $inv['id'] ?>" <?= $inv['id'] == $mat['inventory_id'] ? 'selected' : '' ?>>
                                        <?= esc($inv['name']) ?> (<?= esc($invUnit) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="number" name="quantities[]" class="form-input" style="flex: 1;" value="<?= $mat['quantity'] ?>" min="1" placeholder="Qty" required>
                            <button type="button" onclick="removeMaterial(this)" style="background: var(--danger, #ef4444); color: white; border: none; border-radius: 6px; padding: 8px 12px; cursor: pointer; font-size: 1rem;">×</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <button type="button" onclick="addMaterial()" class="btn btn-secondary" style="margin-top: 8px; font-size: 0.85rem;">
                + Tambah Bahan
            </button>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= isset($addon) ? 'Update Add-on' : 'Simpan Add-on' ?>
            </button>
            <a href="/admin/products/items/<?= $item['id'] ?>/addons" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
    const inventoryOptions = `<?= $inventoryOptions ?>`;
    
    function addMaterial() {
        const container = document.getElementById('materialsContainer');
        const div = document.createElement('div');
        div.className = 'material-row';
        div.style.cssText = 'display: flex; gap: 10px; align-items: center; margin-bottom: 8px;';
        div.innerHTML = `
            <select name="materials[]" class="form-input" style="flex: 2;" required>
                <option value="">Pilih Bahan</option>
                ${inventoryOptions}
            </select>
            <input type="number" name="quantities[]" class="form-input" style="flex: 1;" min="1" placeholder="Qty" required>
            <button type="button" onclick="removeMaterial(this)" style="background: var(--danger, #ef4444); color: white; border: none; border-radius: 6px; padding: 8px 12px; cursor: pointer; font-size: 1rem;">×</button>
        `;
        container.appendChild(div);
    }
    
    function removeMaterial(btn) {
        btn.parentElement.remove();
    }
</script>

<?= $this->endSection() ?>
