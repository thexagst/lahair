<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="header">
    <h1 class="page-title">Manajemen Hari Libur</h1>
</div>

<div class="app-grid">
    <!-- LEFT: Add Form -->
    <div class="card">
        <h3 class="card-title">Tambah Hari Libur</h3>
        <form action="/admin/attendance/holidays/store" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label">Tanggal</label>
                <input type="date" name="date" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <input type="text" name="description" class="form-input" placeholder="Contoh: Hari Raya Idul Fitri" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Simpan</button>
        </form>
    </div>

    <!-- RIGHT: List -->
    <div class="card">
        <h3 class="card-title">Daftar Hari Libur</h3>
        
        <?php if (empty($holidays)): ?>
            <p class="text-muted">Belum ada data hari libur.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($holidays as $h): ?>
                    <tr>
                        <td><?= date('d M Y', strtotime($h['date'])) ?></td>
                        <td><?= esc($h['description']) ?></td>
                        <td>
                            <form action="/admin/attendance/holidays/delete/<?= $h['id'] ?>" method="post" onsubmit="return confirm('Hapus hari libur ini?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn-icon" style="color: #ef4444;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<style>
    .app-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 900px) {
        .app-grid { grid-template-columns: 1fr; }
    }
</style>

<?= $this->endSection() ?>
