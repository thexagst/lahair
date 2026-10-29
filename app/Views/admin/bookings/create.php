<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1 class="page-title">Buat Booking Offline</h1>
    <a href="/admin/orders" class="btn btn-secondary">← Kembali</a>
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

<form action="/admin/bookings/store" method="post" id="offlineBookingForm" onsubmit="return validateForm()">
    <?= csrf_field() ?>
    
    <!-- 1. Customer Information (Full-width card at top) -->
    <div class="card booking-customer-card">
        <div class="card-header-line">
            <div style="display: flex; align-items: center; gap: 8px;">
                <div class="card-icon-pill">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <h3 class="card-title">Data Customer</h3>
            </div>
            <span class="card-hint-badge">Ketik Nama / No. WA untuk autocomplete data tersimpan</span>
        </div>
        
        <div class="booking-customer-grid">
            <!-- Nama Customer -->
            <div class="form-group" style="position: relative; margin: 0; z-index: 60;">
                <label class="form-label">Nama Customer *</label>
                <input
                    type="text"
                    name="customer_name"
                    id="customerNameInput"
                    class="form-input"
                    required
                    placeholder="Nama customer"
                    autocomplete="off"
                >
                <div id="nameDropdown" class="customer-dropdown" style="display:none;"></div>
            </div>
            
            <!-- No. WhatsApp -->
            <div class="form-group" style="position: relative; margin: 0; z-index: 59;">
                <label class="form-label">No. WhatsApp *</label>
                <input
                    type="tel"
                    name="customer_phone"
                    id="customerPhoneInput"
                    class="form-input"
                    required
                    placeholder="08xxxxxxxxxx"
                    autocomplete="off"
                >
                <div id="phoneDropdown" class="customer-dropdown" style="display:none;"></div>
            </div>

            <!-- Tanggal Lahir -->
            <div class="form-group" style="position: relative; margin: 0;">
                <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Tanggal Lahir</span>
                    <span style="font-size: 0.72rem; color: var(--text-muted); font-weight: normal;">(Promo Ultah)</span>
                </label>
                <input
                    type="date"
                    name="customer_birthdate"
                    id="customerBirthdateInput"
                    class="form-input"
                    onchange="checkBirthdayAlert(this.value)"
                >
            </div>

            <!-- Cabang Salon -->
            <div class="form-group" style="margin: 0;">
                <label class="form-label">Cabang Salon *</label>
                <select name="branch_id" class="form-input" required>
                    <?php if (!empty($branches)): foreach ($branches as $b): ?>
                        <option value="<?= $b['id'] ?>" <?= ($b['id'] == ($activeBranchId ?? 1)) ? 'selected' : '' ?>>Cabang <?= esc($b['name']) ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
        </div>

        <!-- Birthday Alert Banner -->
        <div id="customerBirthdayAlert" style="display: none; margin-top: 14px; padding: 12px 16px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.08) 0%, rgba(249, 115, 22, 0.12) 100%); border: 1px solid rgba(234, 88, 12, 0.35); border-radius: 10px; color: #c2410c; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 34px; height: 34px; border-radius: 50%; background: #ea580c; color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12v10H4V12"/><path d="M2 7h20v5H2z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                </div>
                <div>
                    <div id="bdayAlertTitle" style="font-weight: 700; font-size: 0.9rem; color: #9a3412;">Customer Sedang Ulang Tahun</div>
                    <div id="bdayAlertSub" style="font-size: 0.78rem; color: #7c2d12; margin-top: 2px;">Spesial hari ulang tahunnya, berikan diskon atau penawaran terbaik.</div>
                </div>
            </div>
            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <span class="badge" style="background: white; color: #c2410c; border: 1px solid rgba(234, 88, 12, 0.3); font-weight: 700; font-size: 0.75rem; padding: 5px 10px; border-radius: 20px;" id="bdayBadgeDays">Hari Ini</span>
                <button type="button" class="btn btn-sm" id="btnSendBirthdayWa" onclick="sendBirthdayWa()" style="display: inline-flex; align-items: center; gap: 6px; background: #25D366; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 0.78rem; cursor: pointer; box-shadow: 0 1px 3px rgba(37,211,102,0.3);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.983z"/></svg>
                    Kirim Promo Ultah
                </button>
            </div>
        </div>
    </div>
    
    <!-- 2. Services & Summary Grid -->
    <div class="booking-grid">
        <!-- Left: Daftar Layanan -->
        <div class="card booking-services-card">
            <div class="card-header-line" style="margin-bottom: 14px; padding-bottom: 12px; border-bottom: 1px solid var(--border);">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div class="card-icon-pill">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><line x1="20" y1="4" x2="8.12" y2="15.88"></line><line x1="14.47" y1="14.48" x2="20" y2="20"></line><line x1="8.12" y1="8.12" x2="12" y2="12"></line></svg>
                    </div>
                    <div>
                        <h3 class="card-title">Daftar Layanan</h3>
                        <div style="font-size: 0.76rem; color: var(--text-muted); margin-top: 1px;">Pilih layanan salon yang dipesan customer</div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm" onclick="addService()" style="display: inline-flex; align-items: center; gap: 6px; font-weight: 600; padding: 7px 14px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Layanan
                </button>
            </div>
            
            <div id="servicesContainer">
                <!-- Services will be added here -->
            </div>
        </div>
        
        <!-- Right: Ringkasan & Pembayaran -->
        <div class="sticky-col">
            <div class="card booking-summary-card">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px; padding-bottom: 12px; border-bottom: 1px solid var(--border);">
                    <div class="card-icon-pill">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    </div>
                    <h3 class="card-title" style="margin: 0;">Ringkasan Pesanan</h3>
                </div>
                
                <div class="summary-box">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-size: 0.83rem;">
                        <span style="color: var(--text-muted);">Total Layanan</span>
                        <span id="totalServices" class="total-badge">0 layanan</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: baseline; padding-top: 10px; border-top: 1px dashed var(--border);">
                        <div>
                            <span style="font-weight: 700; font-size: 0.85rem; display: block;">Total Bayar</span>
                            <span style="font-size: 0.72rem; color: var(--text-muted);">Termasuk produk/add-on</span>
                        </div>
                        <span id="totalAmount" style="font-size: 1.2rem; font-weight: 800; color: var(--primary);">Rp 0</span>
                    </div>
                </div>
                
                <div class="form-group" style="margin-top: 14px; margin-bottom: 14px;">
                    <label class="form-label" style="font-weight: 600;">Status Pembayaran *</label>
                    <select name="payment_status" class="form-input" required style="font-weight: 600; cursor: pointer;">
                        <option value="pending">Pending (Belum Bayar)</option>
                        <option value="paid">Lunas (Sudah Bayar)</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary btn-save" style="width: 100%; padding: 12px; font-weight: 700; font-size: 0.94rem; display: flex; align-items: center; justify-content: center; gap: 8px; border-radius: 8px; box-shadow: 0 3px 8px rgba(139,69,19,0.25);">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Simpan Booking
                </button>
            </div>
        </div>
    </div>
</form>

<style>
    /* ── Card Styling & Typography ── */
    .booking-customer-card {
        padding: 16px 20px;
        margin-bottom: 18px;
        overflow: visible !important;
        position: relative;
        z-index: 60;
    }
    .booking-services-card {
        padding: 18px 20px;
        position: relative;
        z-index: 10;
    }
    .booking-summary-card {
        padding: 18px 20px;
        position: sticky;
        top: 16px;
        z-index: 10;
    }
    .card-title {
        font-size: 0.95rem;
        font-weight: 700;
        margin: 0;
        color: var(--text);
    }
    .card-header-line {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }
    .card-icon-pill {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: rgba(139, 69, 19, 0.1);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .card-hint-badge {
        font-size: 0.74rem;
        color: var(--text-muted);
        background: var(--bg-input);
        padding: 4px 10px;
        border-radius: 6px;
        border: 1px solid var(--border);
    }

    /* ── Customer Form Grid ── */
    .booking-customer-grid {
        display: grid;
        grid-template-columns: 1.2fr 1.1fr 1fr 1fr;
        gap: 14px;
        overflow: visible !important;
    }

    /* ── Form Controls ── */
    .form-label {
        font-size: 0.82rem;
        font-weight: 600;
        margin-bottom: 6px;
        color: var(--text);
        display: block;
    }
    .form-input, .form-select, select.form-input {
        padding: 9px 12px !important;
        font-size: 0.9rem !important;
        height: 40px !important;
        border-radius: 8px !important;
        border: 1px solid var(--border) !important;
        background: #ffffff !important;
        width: 100%;
        box-sizing: border-box;
    }
    .form-input:focus, select.form-input:focus {
        border-color: var(--primary) !important;
        outline: none;
        box-shadow: 0 0 0 3px rgba(139,69,19,0.12) !important;
    }

    /* ── Service Card ── */
    .service-card {
        background: #fafafa;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 14px;
        position: relative;
        border: 1px solid var(--border);
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .service-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px dashed rgba(0,0,0,0.08);
    }
    .service-number {
        font-weight: 700;
        color: var(--primary);
        font-size: 0.82rem;
        display: inline-flex;
        align-items: center;
        background: rgba(139, 69, 19, 0.08);
        padding: 3px 10px;
        border-radius: 12px;
    }
    .remove-service {
        background: rgba(239, 68, 68, 0.08);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: var(--danger);
        cursor: pointer;
        padding: 4px 10px;
        font-size: 0.74rem;
        font-weight: 600;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
    }
    .remove-service:hover {
        background: var(--danger);
        color: white;
        border-color: var(--danger);
    }
    .service-date-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 8px;
    }

    /* ── Addons ── */
    .addon-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 7px 10px;
        background: white;
        border-radius: 7px;
        margin-bottom: 5px;
        font-size: 0.82rem;
        border: 1px solid var(--border);
        cursor: pointer;
        transition: background 0.15s;
    }
    .addon-item:hover {
        background: #fdfaf8;
    }
    .addon-item input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: var(--primary);
        cursor: pointer;
    }

    /* ── Summary Card Box ── */
    .summary-box {
        background: var(--bg-input);
        padding: 14px 16px;
        border-radius: 10px;
        border: 1px solid var(--border);
    }
    .total-badge {
        font-weight: 700;
        background: white;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 0.78rem;
        border: 1px solid var(--border);
        color: var(--text);
    }

    /* ── Select2 Compact Styling ── */
    .select2-container--default .select2-selection--single {
        height: 40px !important;
        border: 1px solid var(--border) !important;
        border-radius: 8px !important;
        padding: 7px 12px !important;
        background: #ffffff !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 24px !important;
        padding-left: 0 !important;
        font-size: 0.88rem !important;
        color: var(--text) !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px !important;
    }
    .select2-dropdown {
        border-radius: 8px !important;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
        border: 1px solid var(--border) !important;
    }
    .select2-results__option {
        font-size: 0.86rem !important;
        padding: 8px 12px !important;
    }

    /* ── Customer Autocomplete Dropdown ── */
    .customer-dropdown {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        min-width: 280px;
        background: #ffffff;
        border: 1px solid var(--border, #e5e7eb);
        border-radius: 10px;
        box-shadow: 0 14px 36px rgba(0,0,0,0.22);
        z-index: 999999 !important;
        max-height: 250px;
        overflow-y: auto;
        padding: 5px;
    }
    .customer-option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 10px;
        border-radius: 7px;
        cursor: pointer;
        transition: background 0.12s;
        user-select: none;
    }
    .customer-option:hover {
        background: var(--primary-light, #fdf5f2);
    }
    .customer-option-avatar {
        width: 32px;
        height: 32px;
        background: var(--primary, #8B4513);
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
        font-weight: 700;
        flex-shrink: 0;
    }
    .customer-option-info { flex: 1; min-width: 0; }
    .customer-option-name {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--text, #1a1a1a);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .customer-option-phone {
        font-size: 0.75rem;
        color: var(--text-muted, #6b7280);
        margin-top: 1px;
    }
    .customer-option-badge {
        font-size: 0.68rem;
        background: rgba(139,69,19,0.1);
        color: var(--primary, #8B4513);
        padding: 2px 7px;
        border-radius: 20px;
        font-weight: 600;
        flex-shrink: 0;
    }
    .customer-dropdown-empty {
        padding: 14px;
        text-align: center;
        color: var(--text-muted, #6b7280);
        font-size: 0.82rem;
    }

    /* ── Responsive Tablet / Mobile Overrides ── */
    @media (max-width: 1080px) {
        .booking-customer-grid {
            grid-template-columns: 1fr 1fr !important;
            gap: 12px !important;
        }
    }
    @media (max-width: 640px) {
        .booking-customer-grid {
            grid-template-columns: 1fr !important;
            gap: 10px !important;
        }
        .booking-customer-card,
        .booking-services-card,
        .booking-summary-card {
            padding: 14px 16px !important;
        }
        .booking-summary-card {
            position: static !important;
        }
    }
</style>


<script>
    let serviceCount = 0;
    const items = <?= json_encode($items) ?>;
    const savedCustomers = <?= json_encode($savedCustomers ?? []) ?>;

    // ═══════════════════════════════════════════════════════
    //  CUSTOMER AUTOCOMPLETE — langsung di field Nama & HP
    // ═══════════════════════════════════════════════════════
    (function initCustomerAutocomplete() {
        let customerList = Array.isArray(savedCustomers) ? [...savedCustomers] : [];

        const nameInput  = document.getElementById('customerNameInput');
        const phoneInput = document.getElementById('customerPhoneInput');
        const nameDd     = document.getElementById('nameDropdown');
        const phoneDd    = document.getElementById('phoneDropdown');

        if (!nameInput || !phoneInput || !nameDd || !phoneDd) return;

        function escapeHTML(str) {
            return String(str || '').replace(/[&<>'"]/g, 
                tag => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[tag] || tag)
            );
        }

        function makeOptionHTML(c) {
            const rawName = c.name || '';
            const rawPhone = c.phone || '';
            const rawBirthdate = c.birthdate || '';
            const initials = rawName.trim().substring(0, 2).toUpperCase() || 'CU';
            const safeName = rawName.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
            const safePhone = rawPhone.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
            const safeBirthdate = rawBirthdate.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
            const count = c.order_count || 1;

            let bdayPill = '';
            if (rawBirthdate) {
                bdayPill = `<span style="font-size:0.7rem; color:#c2410c; background:rgba(234,88,12,0.1); padding:1px 6px; border-radius:10px; margin-left:6px; font-weight:600;">Ultah: ${escapeHTML(rawBirthdate)}</span>`;
            }

            return `<div class="customer-option"
                        onpointerdown="event.preventDefault(); pickCustomer('${safeName}','${safePhone}','${safeBirthdate}')"
                        onclick="pickCustomer('${safeName}','${safePhone}','${safeBirthdate}')">
                        <div class="customer-option-avatar">${initials}</div>
                        <div class="customer-option-info">
                            <div class="customer-option-name">${escapeHTML(rawName)} ${bdayPill}</div>
                            <div class="customer-option-phone">${escapeHTML(rawPhone)}</div>
                        </div>
                        <span class="customer-option-badge">${count}&times; order</span>
                    </div>`;
        }

        function filterLocal(q) {
            if (!q || !q.trim()) return customerList.slice(0, 20);
            const lq = q.trim().toLowerCase();
            return customerList.filter(c => {
                const name = (c.name || '').toLowerCase();
                const phone = (c.phone || '');
                return name.includes(lq) || phone.includes(q.trim());
            }).slice(0, 15);
        }

        function render(dropdown, list) {
            if (!list.length) {
                dropdown.innerHTML = '<div class="customer-dropdown-empty">Tidak ada customer tersimpan yang cocok</div>';
            } else {
                dropdown.innerHTML = list.map(makeOptionHTML).join('');
            }
            dropdown.style.display = 'block';
        }

        function closeAll() {
            nameDd.style.display  = 'none';
            phoneDd.style.display = 'none';
        }

        let searchDebounce = null;
        async function fetchServer(q, dropdown) {
            if (!q || q.trim().length < 1) return;
            try {
                const res = await fetch('/admin/customers/search?q=' + encodeURIComponent(q.trim()), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (res.ok) {
                    const data = await res.json();
                    if (Array.isArray(data)) {
                        data.forEach(item => {
                            if (!customerList.some(x => x.phone === item.phone)) {
                                customerList.push(item);
                            }
                        });
                        render(dropdown, filterLocal(q));
                    }
                }
            } catch(e) {}
        }

        function handleInput(input, dropdown) {
            const val = input.value;
            const matches = filterLocal(val);
            render(dropdown, matches);

            if (val && val.trim().length >= 1) {
                clearTimeout(searchDebounce);
                searchDebounce = setTimeout(() => {
                    fetchServer(val, dropdown);
                }, 200);
            }
        }

        function attachAutocomplete(input, dropdown) {
            input.addEventListener('focus', function() {
                handleInput(this, dropdown);
            });

            input.addEventListener('input', function() {
                handleInput(this, dropdown);
            });

            input.addEventListener('blur', function() {
                setTimeout(closeAll, 250);
            });
        }

        attachAutocomplete(nameInput,  nameDd);
        attachAutocomplete(phoneInput, phoneDd);

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#customerNameInput') &&
                !e.target.closest('#nameDropdown') &&
                !e.target.closest('#customerPhoneInput') &&
                !e.target.closest('#phoneDropdown')) {
                closeAll();
            }
        });

        window.pickCustomer = function(name, phone, birthdate) {
            nameInput.value  = name;
            phoneInput.value = phone;
            const bdayInput = document.getElementById('customerBirthdateInput');
            if (bdayInput) {
                bdayInput.value = birthdate || '';
                checkBirthdayAlert(bdayInput.value, name, phone);
            }
            closeAll();

            [nameInput, phoneInput, bdayInput].filter(Boolean).forEach(el => {
                el.style.transition = 'background-color 0.4s';
                el.style.backgroundColor = 'rgba(139,69,19,0.12)';
                setTimeout(() => { el.style.backgroundColor = ''; }, 600);
            });
        };
    })();

    // ═══════════════════════════════════════════════════════
    //  BIRTHDAY ALERT & WA PROMO HELPER
    // ═══════════════════════════════════════════════════════
    window.checkBirthdayAlert = function(bdateStr, custName, custPhone) {
        const alertBox = document.getElementById('customerBirthdayAlert');
        if (!alertBox) return;

        if (!bdateStr) {
            alertBox.style.display = 'none';
            return;
        }

        const parts = bdateStr.split('-');
        if (parts.length < 3) {
            alertBox.style.display = 'none';
            return;
        }

        const bMonth = parseInt(parts[1], 10);
        const bDay = parseInt(parts[2], 10);
        const bYear = parseInt(parts[0], 10);

        const today = new Date();
        const currentYear = today.getFullYear();
        const todayDateOnly = new Date(currentYear, today.getMonth(), today.getDate());

        let nextBday = new Date(currentYear, bMonth - 1, bDay);
        if (nextBday < todayDateOnly) {
            nextBday = new Date(currentYear + 1, bMonth - 1, bDay);
        }

        const diffTime = nextBday.getTime() - todayDateOnly.getTime();
        const diffDays = Math.round(diffTime / (1000 * 60 * 60 * 24));
        const ageTurning = nextBday.getFullYear() - bYear;

        const name = custName || document.getElementById('customerNameInput')?.value || 'Customer';

        // Check if birthday is today or within 7 days
        if (diffDays >= 0 && diffDays <= 7) {
            alertBox.style.display = 'flex';
            const titleEl = document.getElementById('bdayAlertTitle');
            const subEl = document.getElementById('bdayAlertSub');
            const badgeEl = document.getElementById('bdayBadgeDays');

            if (diffDays === 0) {
                titleEl.textContent = `Selamat Ulang Tahun untuk ${name}!`;
                subEl.textContent = `Hari ini ${name} berulang tahun${ageTurning > 0 ? ' ke-' + ageTurning : ''}. Berikan ucapan dan diskon atau penawaran terbaik.`;
                badgeEl.textContent = 'HARI INI';
                badgeEl.style.background = '#ea580c';
                badgeEl.style.color = '#ffffff';
            } else {
                titleEl.textContent = `Ulang Tahun ${name} Sebentar Lagi`;
                subEl.textContent = `Ulang tahun${ageTurning > 0 ? ' ke-' + ageTurning : ''} dalam ${diffDays} hari lagi (${bDay}/${bMonth}). Tawarkan promo dan diskon spesial ulang tahun.`;
                badgeEl.textContent = `H-${diffDays} (${bDay}/${bMonth})`;
                badgeEl.style.background = '#fff';
                badgeEl.style.color = '#c2410c';
            }
        } else {
            alertBox.style.display = 'none';
        }
    };

    window.sendBirthdayWa = function() {
        const name = (document.getElementById('customerNameInput')?.value || 'Kakak').trim();
        let phone = document.getElementById('customerPhoneInput')?.value || '';
        if (!phone) {
            alert('Nomor WhatsApp customer belum diisi!');
            return;
        }
        phone = phone.replace(/\D/g, '');
        if (phone.startsWith('0')) {
            phone = '62' + phone.substring(1);
        }
        const text = `Halo Kak ${name},\n\nKami segenap tim La Hair Studio mengucapkan selamat ulang tahun. Semoga senantiasa diberikan kesehatan, kebahagiaan, dan kelancaran dalam segala aktivitas.\n\nSebagai bentuk apresiasi atas kepercayaan Kakak kepada La Hair Studio, kami memberikan penawaran istimewa berupa Diskon Spesial Ulang Tahun sebesar 20% untuk seluruh layanan perawatan rambut di salon kami.\n\nUntuk menikmati penawaran ini, Kakak cukup menunjukkan pesan ini kepada staf kami saat berkunjung.\n\nTerima kasih atas kepercayaannya pada La Hair Studio. Kami nantikan kedatangan Kakak kembali.`;
        window.open(`https://wa.me/${phone}?text=${encodeURIComponent(text)}`, '_blank');
    };
    // ═══════════════════════════════════════════════════════

    // Add first service on load
    document.addEventListener('DOMContentLoaded', function() {
        addService();
    });
    
    function addService() {
        serviceCount++;
        const currentId = serviceCount;
        const container = document.getElementById('servicesContainer');
        
        const serviceCard = document.createElement('div');
        serviceCard.className = 'service-card';
        serviceCard.id = `service-${currentId}`;
        
        let optionsHtml = '<option value="">Pilih Layanan</option>';
        if (items && items.length > 0) {
            items.forEach(item => {
                optionsHtml += `<option value="${item.id}" data-price="${item.price}">
                    ${item.product_name} - ${item.item_name} (Rp ${formatNumber(item.price)})
                </option>`;
            });
        }
        
        serviceCard.innerHTML = `
            <div class="service-header">
                <span class="service-number">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right: 4px;"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line></svg>
                    Layanan #${currentId}
                </span>
                <button type="button" class="remove-service" onclick="removeService(${currentId})" title="Hapus layanan ini">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    Hapus
                </button>
            </div>
            
            <div class="form-group" style="margin-bottom: 10px;">
                <label class="form-label">Layanan *</label>
                <select name="services[${currentId}][item_id]" class="form-input item-select" id="items-${currentId}" required style="width: 100%;">
                    ${optionsHtml}
                </select>
                <input type="hidden" name="services[${currentId}][price]" id="price-${currentId}" value="0">
            </div>
            
            <div class="service-date-grid">
                <div class="form-group" style="margin: 0;">
                    <label class="form-label">Tanggal *</label>
                    <input type="date" name="services[${currentId}][booking_date]" class="form-input" id="date-${currentId}" required value="${getTodayDate()}">
                </div>
                
                <div class="form-group" style="margin: 0;">
                    <label class="form-label">Waktu *</label>
                    <input type="time" name="services[${currentId}][booking_time]" class="form-input" id="time-${currentId}" required value="09:00" min="09:00" max="20:00">
                </div>
            </div>
            
            <div id="addons-${currentId}"></div>
        `;
        
        container.appendChild(serviceCard);
        
        $(`#items-${currentId}`).select2({
            placeholder: 'Cari layanan...',
            allowClear: true,
            width: '100%'
        }).on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const price = selectedOption.data('price') || 0;
            $(`#price-${currentId}`).val(price);
            
            if (this.value) {
                loadAddons(currentId, this.value);
            } else {
                document.getElementById(`addons-${currentId}`).innerHTML = '';
            }
            calculateTotal();
        }).on('select2:open', function() {
            setTimeout(function() {
                const sf = document.querySelector('.select2-search__field');
                if (sf) sf.focus();
            }, 100);
        });
        
        updateServiceCount();
    }
    
    function removeService(id) {
        if (document.querySelectorAll('.service-card').length <= 1) {
            alert('Minimal harus ada 1 layanan dalam pesanan booking.');
            return;
        }
        const service = document.getElementById(`service-${id}`);
        if (service) {
            service.remove();
            updateServiceCount();
            calculateTotal();
        }
    }
    
    async function loadAddons(serviceId, itemId) {
        const addonsContainer = document.getElementById(`addons-${serviceId}`);
        
        try {
            const response = await fetch(`/api/items/${itemId}/addons`);
            const addons = await response.json();
            
            if (addons.length > 0) {
                const isMandatory = addons.some(a => a.is_required == 1);
                
                addonsContainer.innerHTML = `
                    <label class="form-label" style="margin-top: 12px; margin-bottom: 8px;">
                        Pilihan Produk ${isMandatory ? '*' : ''} 
                        <span style="font-size: 0.85rem; color: var(--text-muted);">
                            ${isMandatory ? '(Wajib pilih minimal 1)' : '(Opsional)'}
                        </span>
                    </label>
                    <div id="addons-list-${serviceId}" data-mandatory="${isMandatory}">
                        ${addons.map(addon => `
                            <label class="addon-item">
                                <input type="checkbox" name="services[${serviceId}][addons][]" value="${addon.id}" data-price="${addon.price}" onchange="calculateTotal()" class="addon-checkbox-${serviceId}">
                                <div style="flex: 1;">
                                    <span style="display: block;">${addon.name}</span>
                                    ${addon.is_required == 1 ? '<span style="font-size: 0.7rem; color: var(--danger); font-weight: 600;">Wajib</span>' : ''}
                                </div>
                                <span style="font-weight: 600; color: var(--primary); font-size: 0.85rem;">+Rp ${formatNumber(addon.price)}</span>
                            </label>
                        `).join('')}
                    </div>
                    <div id="addon-error-${serviceId}" style="color: var(--danger); font-size: 0.85rem; margin-top: 4px; display: none;">Pilih minimal 1 produk</div>
                `;
            } else {
                addonsContainer.innerHTML = '';
            }
        } catch (error) {
            addonsContainer.innerHTML = '';
        }
        
        calculateTotal();
    }
    
    function calculateTotal() {
        let total = 0;
        let count = 0;
        
        document.querySelectorAll('.service-card').forEach(card => {
            count++;
            const serviceId = card.id.split('-')[1];
            const priceInput = document.getElementById(`price-${serviceId}`);
            
            if (priceInput) {
                total += parseFloat(priceInput.value) || 0;
                card.querySelectorAll('input[name*="[addons]"]:checked').forEach(cb => {
                    total += parseFloat(cb.dataset.price) || 0;
                });
            }
        });
        
        document.getElementById('totalServices').textContent = count;
        document.getElementById('totalAmount').textContent = 'Rp ' + formatNumber(total);
    }
    
    function updateServiceCount() {
        document.getElementById('totalServices').textContent =
            document.querySelectorAll('.service-card').length;
    }
    
    function getTodayDate() {
        return new Date().toISOString().split('T')[0];
    }
    
    function validateForm() {
        let isValid = true;
        
        document.querySelectorAll('.service-card').forEach(card => {
            const serviceId = card.id.split('-')[1];
            const addonsList = document.getElementById(`addons-list-${serviceId}`);
            const errorDiv = document.getElementById(`addon-error-${serviceId}`);
            
            if (addonsList && addonsList.dataset.mandatory === "true") {
                const checkedAddons = card.querySelectorAll(`.addon-checkbox-${serviceId}:checked`);
                
                if (checkedAddons.length === 0) {
                    if (errorDiv) errorDiv.style.display = 'block';
                    isValid = false;
                } else {
                    if (errorDiv) errorDiv.style.display = 'none';
                }
            }
        });
        
        if (!isValid) {
            alert('Mohon pilih minimal 1 produk untuk setiap layanan yang mewajibkan pilihan produk!');
        }
        
        return isValid;
    }
    
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }
</script>

<?= $this->endSection() ?>
