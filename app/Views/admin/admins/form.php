<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1 class="page-title"><?= isset($admin) ? 'Edit Admin' : 'Tambah Admin' ?></h1>
    <a href="/admin/admins" class="btn btn-secondary">Kembali</a>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (session()->get('errors')): ?>
    <div class="alert alert-error">
        <ul style="margin: 0; padding-left: 20px;">
            <?php foreach (session()->get('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card" style="padding: 24px;">
    <form action="<?= isset($admin) ? '/admin/admins/update/' . $admin['id'] : '/admin/admins/store' ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label class="form-label">Username *</label>
            <input type="text" name="username" class="form-input" 
                   value="<?= old('username', $admin['username'] ?? '') ?>" 
                   placeholder="Contoh: staff_laura"
                   required>
        </div>

        <div class="form-group">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-input" 
                   value="<?= old('email', $admin['email'] ?? '') ?>" 
                   placeholder="Contoh: laura@lahair.com"
                   required>
        </div>

        <div class="form-group">
            <label class="form-label">PIN <?= isset($admin) ? '(Kosongkan jika tidak ingin mengubah)' : '*' ?></label>
            <input type="tel" name="password" id="pin_input" class="form-input"
                   placeholder="6 digit PIN angka"
                   maxlength="6"
                   pattern="[0-9]{6}"
                   inputmode="numeric"
                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                   <?= !isset($admin) ? 'required' : '' ?>>
            <small style="color: rgba(255,255,255,0.5); font-size: 0.8rem;">Masukkan tepat 6 angka sebagai PIN login</small>
        </div>

        <div class="form-group">
            <label class="form-label">Role *</label>
            <select name="role" class="form-select" required>
                <option value="admin" <?= old('role', $admin['role'] ?? '') === 'admin' ? 'selected' : '' ?>>
                    Super Admin (Akses Penuh)
                </option>
                <option value="worker" <?= old('role', $admin['role'] ?? '') === 'worker' ? 'selected' : '' ?>>
                    Worker (Hanya Pesanan)
                </option>
            </select>
            <div class="form-help">
                <strong>Super Admin:</strong> Akses penuh ke semua menu.<br>
                <strong>Worker:</strong> Hanya bisa mengakses menu Pesanan.
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= isset($admin) ? 'Update Admin' : 'Tambah Admin' ?>
            </button>
            <a href="/admin/admins" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<script>
    const pinInput = document.getElementById('pin_input');
    if (pinInput) {
        // Block non-numeric keys
        pinInput.addEventListener('keydown', function(e) {
            const allowed = ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Home','End'];
            if (!allowed.includes(e.key) && !/^[0-9]$/.test(e.key)) {
                e.preventDefault();
            }
        });
        // Clean on paste
        pinInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text');
            const digits = text.replace(/[^0-9]/g, '').substring(0, 6);
            this.value = digits;
        });
    }
</script>
