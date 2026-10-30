<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title"><?= esc($title) ?></h1>
        <p style="font-size: 0.85rem; color: #6b7280; margin: 4px 0 0 0;">
            Kelola dan pantau ketersediaan stok bahan baku serta produk.
        </p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <button type="button" onclick="document.getElementById('importModal').style.display='flex'" class="btn btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px; vertical-align: text-bottom;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
            Import Excel/CSV
        </button>
        <button type="button" onclick="openModal('create')" class="btn btn-primary">
            <span>+</span> Tambah Stok
        </button>
    </div>
</div>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .modal-content {
        background: var(--bg-card);
        padding: 24px;
        border-radius: 12px;
        width: 90%;
        max-width: 520px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }
    /* Spreadsheet-style table */
    #inventoryTable {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.82rem;
        font-family: 'Segoe UI', sans-serif;
    }
    #inventoryTable th,
    #inventoryTable td {
        border: 1px solid #d1d5db;
        padding: 6px 10px;
        white-space: nowrap;
        vertical-align: middle;
        line-height: 1.4;
    }
    #inventoryTable thead th {
        background: #e8edf3;
        font-weight: 700;
        color: #374151;
        font-size: 0.8rem;
        text-transform: none;
        letter-spacing: 0;
        position: sticky;
        top: 0;
        z-index: 2;
    }
    #inventoryTable tbody tr:nth-child(even) {
        background: #f9fafb;
    }
    #inventoryTable tbody tr:hover {
        background: #eff6ff;
    }
    #inventoryTable tbody tr:hover .editable-cell {
        text-decoration: underline dotted #9ca3af;
    }
    .editable-cell {
        cursor: pointer;
        display: block;
        width: 100%;
    }
    .editable-cell:hover {
        color: var(--primary, #8B4513);
    }
    .inline-input {
        border: 1.5px solid var(--primary, #8B4513);
        border-radius: 4px;
        padding: 2px 6px;
        font-size: 0.82rem;
        width: 100%;
        box-sizing: border-box;
        background: #fff;
        outline: none;
    }
    .uom-select {
        border: 1.5px solid var(--primary, #8B4513);
        border-radius: 4px;
        padding: 2px 4px;
        font-size: 0.8rem;
        background: #fff;
    }
    .sheet-container {
        max-height: 70vh;
        overflow-y: auto;
        border: 1px solid #d1d5db;
        border-radius: 6px;
    }
    #inventoryTable .col-no   { width: 40px;  text-align: center; color: #9ca3af; }
    #inventoryTable .col-name { min-width: 200px; }
    #inventoryTable .col-uom  { width: 90px; }
    #inventoryTable .col-qty  { width: 110px; text-align: right; }
    #inventoryTable .col-sat  { width: 80px; }
    #inventoryTable .col-val  { width: 130px; color: #374151; }
    #inventoryTable .col-date { width: 130px; color: #6b7280; font-size: 0.75rem; }
    #inventoryTable .col-act  { width: 85px;  text-align: center; }
    #inventoryTable td.col-qty span { font-weight: 700; }
    .btn-act-history {
        background: none;
        border: none;
        color: #2563eb;
        cursor: pointer;
        font-size: 1rem;
        padding: 2px 4px;
        line-height: 1;
        border-radius: 4px;
        transition: background 0.15s;
    }
    .btn-act-history:hover {
        background: #dbeafe;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>

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

<div class="card" style="padding: 0; overflow: hidden;">
    <?php if (empty($inventories)): ?>
        <div class="empty-state" style="padding: 40px;">
            <h3>Belum ada data stok</h3>
            <p>Tambahkan data bahan baku atau produk fisik untuk gudang.</p>
        </div>
    <?php else: ?>
        <div style="padding: 8px 16px 6px; font-size: 0.75rem; color: #6b7280; border-bottom: 1px solid #e5e7eb; display:flex; justify-content:space-between; align-items:center;">
            <span>Klik sel angka stok untuk edit langsung · <?= count($inventories) ?> item bahan</span>
        </div>
        <div class="sheet-container">
            <table id="inventoryTable">
                <thead>
                    <tr>
                        <th class="col-no">#</th>
                        <th class="col-name">Nama Stok</th>
                        <th class="col-uom">Kemasan</th>
                        <th class="col-qty" style="background:#f1f5f9; color:#1e293b; font-weight:700;">Stok</th>
                        <th class="col-sat">Satuan Isi</th>
                        <th class="col-val" title="Isi per kemasan, misal 1 botol = 300 ml">Isi / Kemasan</th>
                        <th class="col-date">Terakhir Update</th>
                        <th class="col-act">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1; 
                    foreach ($inventories as $item): 
                        $curStock = (float)($item['current_branch_stock'] ?? $item['stock'] ?? 0);
                    ?>
                        <tr data-id="<?= $item['id'] ?>" 
                            data-name="<?= esc($item['name']) ?>" 
                            data-unit="<?= esc($item['unit']) ?>" 
                            data-sub_unit="<?= esc($item['sub_unit'] ?? '') ?>" 
                            data-unit_value="<?= $item['unit_value'] ?? '' ?>"
                            data-stock="<?= $curStock ?>">
                            
                            <td class="col-no"><?= $no++ ?></td>
                            <td class="col-name">
                                <span class="editable-cell" onclick="editCell(this, 'name')" title="Klik edit"><?= esc($item['name']) ?></span>
                            </td>
                            <td class="col-uom">
                                <span class="editable-cell" onclick="editCell(this, 'unit')" title="Klik edit"><?= esc($item['unit']) ?></span>
                            </td>
                            <td class="col-qty">
                                <span class="editable-cell" 
                                      onclick="editStockCell(this)" 
                                      title="Klik edit stok" 
                                      style="color:#0284c7; font-weight:700;">
                                    <?= number_format($curStock, ($curStock == floor($curStock) ? 0 : 2), ',', '.') ?>
                                </span>
                            </td>
                            <td class="col-sat">
                                <span class="editable-cell" onclick="editCell(this, 'sub_unit')" title="Klik edit"><?= esc($item['sub_unit'] ?? '') ?></span>
                            </td>
                            <td class="col-val">
                                <?php if (!empty($item['unit_value'])): ?>
                                    <span class="editable-cell" onclick="editCell(this, 'unit_value')" title="Klik edit" style="color:#3b82f6; font-weight:600;">
                                        <?= number_format((float)$item['unit_value'], 0, ',', '.') ?> <?= esc($item['sub_unit'] ?? '') ?>
                                    </span>
                                <?php else: ?>
                                    <span class="editable-cell" onclick="editCell(this, 'unit_value')" title="Klik untuk isi" style="color:#9ca3af; font-style:italic;">— isi —</span>
                                <?php endif; ?>
                            </td>
                            <td class="col-date date-cell"><?= date('d M Y H:i', strtotime($item['updated_at'] ?? $item['created_at'])) ?></td>
                            <td class="col-act">
                                <div style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                                    <button type="button" class="btn-act-history" onclick="showHistory(<?= $item['id'] ?>, '<?= esc($item['name'], 'js') ?>')" title="Riwayat Pemakaian">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                    </button>
                                    <?php if (session()->get('role') !== 'worker'): ?>
                                    <form action="/admin/inventory/delete/<?= $item['id'] ?>" method="post" onsubmit="return confirm('Hapus stok ini?')" style="display:inline; margin:0;">
                                        <?= csrf_field() ?>
                                        <button type="submit" style="background:none;border:none;color:#94a3b8;cursor:pointer;padding:3px;line-height:1;border-radius:4px;transition:color 0.15s;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#94a3b8'" title="Hapus">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Form Tambah Stok -->
<div id="inventoryModal" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 520px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 class="modal-title" id="modalTitle">Tambah Bahan & Stok Baru</h3>
            <button type="button" onclick="closeModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        
        <form id="inventoryForm" action="/admin/inventory/store" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="branch_id" value="<?= esc($activeBranchId ?? 1) ?>">
            
            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label">Nama Bahan / Produk *</label>
                <input type="text" name="name" id="name" class="form-input" required placeholder="Contoh: Shampoo Color Brilliance, Bleaching Powder">
            </div>
            
            <div class="form-grid" style="grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div class="form-group">
                    <label class="form-label">Kemasan / UOM *</label>
                    <input type="text" name="unit" id="unit" class="form-input" required placeholder="botol, pack, pcs" list="unitlist">
                </div>

                <div class="form-group">
                    <label class="form-label">Satuan Pemakaian *</label>
                    <input type="text" name="sub_unit" id="sub_unit" class="form-input" required placeholder="ml, gr, pcs" list="unitlist">
                    <datalist id="unitlist">
                        <option value="pcs">
                        <option value="ml">
                        <option value="gr">
                        <option value="botol">
                        <option value="pack">
                        <option value="sachet">
                    </datalist>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label">Isi per Kemasan (Opsional)</label>
                <input type="number" step="any" name="unit_value" id="unit_value" class="form-input" placeholder="Contoh: 1 botol = 1000 ml, isi: 1000">
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label class="form-label">Jumlah Stok Awal *</label>
                <input type="number" step="any" min="0" name="stock" id="stock" class="form-input" value="0" required placeholder="0">
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Simpan Data</button>
                <button type="button" onclick="closeModal()" class="btn btn-secondary">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 500px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 class="modal-title">Import Stok dari Excel/CSV</h3>
            <button type="button" onclick="document.getElementById('importModal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        
        <form action="/admin/inventory/import" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div style="margin-bottom: 16px; background: var(--bg-body); padding: 16px; border-radius: 8px; border: 1px dashed var(--border);">
                <p style="margin-top: 0; font-size: 0.9rem; margin-bottom: 8px;">1. Download template CSV ini untuk melihat format.</p>
                <a href="/admin/inventory/template" class="btn btn-secondary btn-sm" style="display: inline-block;">Download Template</a>
            </div>
            
            <div style="margin-bottom: 24px;">
                <p style="margin-top: 0; font-size: 0.9rem; margin-bottom: 8px;">2. Upload file CSV yang sudah diisi.</p>
                <input type="file" name="csv_file" accept=".csv" class="form-input" required>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Mulai Import</button>
                <button type="button" onclick="document.getElementById('importModal').style.display='none'" class="btn btn-secondary">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Riwayat Pemakaian -->
<div id="historyModal" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 680px; width: 95%;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; border-bottom: 1px solid #e5e7eb; padding-bottom: 12px;">
            <div>
                <h3 class="modal-title" style="margin: 0; font-size: 1.15rem; color: #111827;">Riwayat Pemakaian Bahan</h3>
                <div id="historyItemSubtitle" style="font-size: 0.85rem; color: #6b7280; margin-top: 4px; font-weight: 500;">...</div>
            </div>
            <button type="button" onclick="closeHistoryModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #9ca3af; line-height: 1;">&times;</button>
        </div>
        
        <div id="historyLoading" style="text-align: center; padding: 35px 20px; color: #6b7280; font-size: 0.9rem;">
            <div style="display: inline-block; width: 22px; height: 22px; border: 2.5px solid #e5e7eb; border-top-color: #3b82f6; border-radius: 50%; animation: spin 0.8s linear infinite; margin-bottom: 8px;"></div>
            <div>Memuat riwayat pemakaian...</div>
        </div>

        <div id="historyTableContainer" style="display: none; max-height: 420px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 6px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
                <thead style="background: #f9fafb; position: sticky; top: 0; z-index: 1;">
                    <tr style="border-bottom: 1px solid #e5e7eb; color: #4b5563; font-weight: 600;">
                        <th style="padding: 10px 12px;">Tanggal</th>
                        <th style="padding: 10px 12px;">No. Order</th>
                        <th style="padding: 10px 12px;">Customer</th>
                        <th style="padding: 10px 12px; text-align: right;">Jumlah Dipakai</th>
                    </tr>
                </thead>
                <tbody id="historyTableBody">
                </tbody>
            </table>
        </div>

        <div id="historyEmpty" style="display: none; text-align: center; padding: 35px 20px; color: #9ca3af; font-size: 0.9rem;">
            Belum ada riwayat pemakaian untuk bahan ini.
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 18px; border-top: 1px solid #e5e7eb; padding-top: 12px;">
            <button type="button" onclick="closeHistoryModal()" class="btn btn-secondary" style="padding: 7px 18px; font-size: 0.85rem;">Tutup</button>
        </div>
    </div>
</div>

<script>
    const CSRF_NAME        = '<?= csrf_token() ?>';
    const CSRF_HASH        = '<?= csrf_hash() ?>';
    const ACTIVE_BRANCH_ID = <?= (int)($activeBranchId ?? 1) ?>;
    const UOM_OPTIONS      = ['pcs', 'ml', 'gr', 'kg', 'liter', 'botol', 'pack', 'sachet', 'tube', 'kaleng'];

    // ─── Inline Edit for Stock ────────────────────────────────────────────────────
    function editStockCell(span) {
        if (span.querySelector('input')) return;

        const row = span.closest('tr');
        const id  = row.dataset.id;
        const rawVal = row.getAttribute('data-stock') || '0';

        const input = document.createElement('input');
        input.className = 'inline-input';
        input.type = 'number';
        input.step = 'any';
        input.min = '0';
        input.value = rawVal;
        input.style.width = '90px';

        span.textContent = '';
        span.appendChild(input);
        input.focus();
        input.select();

        const save = () => saveStock(span, row, input.value.trim());
        input.addEventListener('blur', save);
        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); input.blur(); }
            if (e.key === 'Escape') { 
                input.removeEventListener('blur', save); 
                span.textContent = Number(rawVal).toLocaleString('id-ID');
            }
        });
    }

    async function saveStock(span, row, newValue) {
        const id = row.dataset.id;
        const currentVal = row.getAttribute('data-stock') || '0';

        if (newValue === '' || isNaN(newValue)) {
            span.textContent = Number(currentVal).toLocaleString('id-ID');
            return;
        }

        if (parseFloat(newValue) === parseFloat(currentVal)) {
            span.textContent = Number(currentVal).toLocaleString('id-ID');
            return;
        }

        span.textContent = '⏳';

        const form = new FormData();
        form.append(CSRF_NAME, CSRF_HASH);
        form.append('branch_id', ACTIVE_BRANCH_ID);
        form.append('branch_stock', newValue);

        try {
            const res = await fetch(`/admin/inventory/update/${id}`, { method: 'POST', body: form });
            const result = await res.json();

            if (result.success) {
                row.setAttribute('data-stock', newValue);
                span.textContent = Number(newValue).toLocaleString('id-ID');

                // Update timestamp
                const dateCell = row.querySelector('.date-cell');
                if (dateCell) {
                    const now = new Date();
                    dateCell.textContent = now.toLocaleDateString('id-ID', {day:'2-digit',month:'short',year:'numeric'}) + ' ' + now.toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'});
                }
            } else {
                span.textContent = Number(currentVal).toLocaleString('id-ID');
                alert(result.message || 'Gagal menyimpan stok');
            }
        } catch (err) {
            span.textContent = Number(currentVal).toLocaleString('id-ID');
            console.error(err);
        }
    }

    // ─── Inline Edit for Name, Unit, Sub-unit, Unit Value ───────────────────────
    function editCell(span, field) {
        if (span.querySelector('input, select')) return;

        const row = span.closest('tr');
        const id  = row.dataset.id;
        const rawVal = row.dataset[field];

        let control;

        if (field === 'unit' || field === 'sub_unit') {
            control = document.createElement('select');
            control.className = 'uom-select';
            UOM_OPTIONS.forEach(u => {
                const opt = document.createElement('option');
                opt.value = u;
                opt.textContent = u;
                if (u === rawVal) opt.selected = true;
                control.appendChild(opt);
            });
            if (!UOM_OPTIONS.includes(rawVal) && rawVal) {
                const opt = document.createElement('option');
                opt.value = rawVal;
                opt.textContent = rawVal;
                opt.selected = true;
                control.insertBefore(opt, control.firstChild);
            }
        } else {
            control = document.createElement('input');
            control.className = 'inline-input';
            control.type = (field === 'unit_value') ? 'number' : 'text';
            control.value = rawVal;
            if (field === 'unit_value') { control.min = 0; control.step = 'any'; control.style.width = '90px'; control.placeholder = 'mis. 300'; }
        }

        span.textContent = '';
        span.appendChild(control);
        control.focus();
        if (control.select) control.select();

        const save = () => saveCell(span, row, field, control.value.trim());
        control.addEventListener('blur', save);
        control.addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); control.blur(); }
            if (e.key === 'Escape') { control.removeEventListener('blur', save); restoreCell(span, row, field); }
        });
    }

    function restoreCell(span, row, field) {
        const val = row.dataset[field];
        if (field === 'unit_value') {
            const unit = row.dataset.sub_unit || row.dataset.unit;
            span.textContent = val ? Number(val).toLocaleString('id-ID') + ' ' + unit : '— isi —';
            span.style.color = val ? '#3b82f6' : '#9ca3af';
            span.style.fontStyle = val ? 'normal' : 'italic';
            span.style.fontWeight = val ? '600' : 'normal';
        } else {
            span.textContent = val;
        }
    }

    async function saveCell(span, row, field, newValue) {
        const id   = row.dataset.id;
        const data = {
            name:       row.dataset.name,
            unit:       row.dataset.unit,
            sub_unit:   row.dataset.sub_unit || '',
            unit_value: row.dataset.unit_value || '',
        };

        if (newValue === data[field]) { restoreCell(span, row, field); return; }
        if (newValue === '' && field !== 'unit_value') { restoreCell(span, row, field); return; }

        data[field] = newValue;
        span.textContent = '⏳';

        const form = new FormData();
        form.append(CSRF_NAME, CSRF_HASH);
        form.append('name',       data.name);
        form.append('unit',       data.unit);
        form.append('sub_unit',   data.sub_unit);
        form.append('unit_value', data.unit_value);

        try {
            const res = await fetch(`/admin/inventory/update/${id}`, { method: 'POST', body: form });
            if (res.ok || res.redirected) {
                row.dataset[field] = newValue;
                if (field === 'unit_value') {
                    const unit = row.dataset.sub_unit || row.dataset.unit;
                    if (newValue) {
                        span.textContent = Number(newValue).toLocaleString('id-ID') + ' ' + unit;
                        span.style.color = '#3b82f6'; span.style.fontWeight = '600'; span.style.fontStyle = 'normal';
                    } else {
                        span.textContent = '— isi —';
                        span.style.color = '#9ca3af'; span.style.fontWeight = 'normal'; span.style.fontStyle = 'italic';
                    }
                } else {
                    span.textContent = newValue;
                }
                span.onclick = () => editCell(span, field);
            } else {
                restoreCell(span, row, field);
                alert('Gagal menyimpan. Coba lagi.');
            }
        } catch (err) {
            restoreCell(span, row, field);
            console.error(err);
        }
    }

    // ─── Modal Form Controls ───────────────────────────────────────────────────
    function openModal(mode) {
        const modal = document.getElementById('inventoryModal');
        const form  = document.getElementById('inventoryForm');
        form.reset();
        modal.style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('inventoryModal').style.display = 'none';
    }

    // ─── Riwayat Pemakaian ─────────────────────────────────────────────────────
    async function showHistory(id, name) {
        const modal = document.getElementById('historyModal');
        const subtitle = document.getElementById('historyItemSubtitle');
        const loading = document.getElementById('historyLoading');
        const container = document.getElementById('historyTableContainer');
        const empty = document.getElementById('historyEmpty');
        const tbody = document.getElementById('historyTableBody');

        subtitle.textContent = name;
        loading.style.display = 'block';
        container.style.display = 'none';
        empty.style.display = 'none';
        tbody.innerHTML = '';
        modal.style.display = 'flex';

        try {
            const res = await fetch(`/admin/inventory/history/${id}`);
            const json = await res.json();

            loading.style.display = 'none';

            if (!json.success || !json.data || json.data.length === 0) {
                empty.style.display = 'block';
                return;
            }

            container.style.display = 'block';
            tbody.innerHTML = json.data.map(u => `
                <tr style="border-bottom: 1px solid #f3f4f6;">
                    <td style="padding: 10px 12px; color: #6b7280;">${u.date}</td>
                    <td style="padding: 10px 12px; font-weight: 600; color: #1f2937;">
                        <a href="/admin/orders/view/${u.order_number.split(' ')[0]}" target="_blank" style="color: #2563eb; text-decoration: none;">
                            #${u.order_number}
                        </a>
                    </td>
                    <td style="padding: 10px 12px;">${u.customer_name}</td>
                    <td style="padding: 10px 12px; text-align: right; font-weight: 700; color: #dc2626;">
                        -${u.quantity} ${u.unit}
                    </td>
                </tr>
            `).join('');

        } catch (err) {
            loading.style.display = 'none';
            empty.style.display = 'block';
            empty.textContent = 'Gagal memuat riwayat.';
        }
    }

    function closeHistoryModal() {
        document.getElementById('historyModal').style.display = 'none';
    }

    window.onclick = function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.style.display = 'none';
        }
    }
</script>

<?= $this->endSection() ?>
