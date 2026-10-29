<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1 class="page-title">Pengaturan</h1>
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



<!-- Shop Settings -->
<div class="card" style="padding: 24px; margin-bottom: 20px;">
    <h3 style="margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border);">
        Pengaturan Toko
    </h3>
    
    <form action="/admin/settings/shop" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label class="form-label">Jam Buka</label>
            <input
                type="time"
                name="start_time"
                class="form-input"
                value="<?= esc($shopSettings['start_time'] ?? '09:00') ?>"
                required
            >
        </div>

        <div class="form-group">
            <label class="form-label">Jam Tutup</label>
            <input
                type="time"
                name="end_time"
                class="form-input"
                value="<?= esc($shopSettings['end_time'] ?? '20:00') ?>"
                required
            >
        </div>

        <div class="form-group">
            <label class="form-label">Komisi Hairdresser (%)</label>
            <input
                type="number"
                name="commission_percent"
                class="form-input"
                placeholder="Contoh: 10"
                min="0"
                max="100"
                value="<?= esc($shopSettings['commission_percent'] ?? '0') ?>"
            >
            <p class="form-help">Secara otomatis menghitung fee hairdresser berdasarkan total pesanan.</p>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan Pengaturan Toko</button>
        </div>
    </form>
</div>




<!-- Profile Bio Settings -->
<div class="card" style="padding: 24px; margin-top: 20px;">
    <h3 style="margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border);">
        Profil Bio / Slogan
    </h3>
    
    <form action="/admin/settings/bio" method="post">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label class="form-label">Bio Teks</label>
            <input 
                type="text" 
                name="profile_bio" 
                class="form-input" 
                value="<?= esc($profileBio ?? '') ?>"
                placeholder="Premium Hair Salon - Best Treatment in Town"
            >
            <p class="form-help">Teks ini akan muncul di bawah nama toko di homepage.</p>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan Bio</button>
        </div>
    </form>
</div>

<!-- Social Links Settings -->
<div class="card" style="padding: 24px; margin-top: 20px;">
    <h3 style="margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border);">
        Link Media Sosial
    </h3>
    
    <form action="/admin/settings/social" method="post">
        <?= csrf_field() ?>
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Link TikTok</label>
                <input 
                    type="url" 
                    name="tiktok_url" 
                    class="form-input" 
                    value="<?= esc($socialLinks['tiktok'] ?? '') ?>"
                    placeholder="https://tiktok.com/@..."
                >
            </div>
            <div class="form-group">
                <label class="form-label">Link Instagram</label>
                <input 
                    type="url" 
                    name="instagram_url" 
                    class="form-input" 
                    value="<?= esc($socialLinks['instagram'] ?? '') ?>"
                    placeholder="https://www.instagram.com/..."
                >
            </div>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan Link Sosial</button>
        </div>
    </form>
</div>

<!-- Terms & Conditions Settings -->
<div class="card" style="padding: 24px; margin-top: 20px;">
    <h3 style="margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border);">
        Syarat & Ketentuan
    </h3>
    
    <form action="/admin/settings/terms" method="post">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label class="form-label">Teks Syarat & Ketentuan</label>
            <textarea 
                name="terms_conditions" 
                class="form-input" 
                style="min-height: 200px; resize: vertical;"
                placeholder="Tuliskan syarat dan ketentuan toko di sini..."><?= esc($terms ?? '') ?></textarea>
            <p class="form-help">Informasi ini akan ditampilkan pada halaman Syarat & Ketentuan.</p>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan Syarat & Ketentuan</button>
        </div>
    </form>
</div>



<script>

</script>

<?= $this->endSection() ?>
