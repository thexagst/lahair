<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Cabang - La Hair Studio Admin</title>
    <link rel="icon" type="image/png" href="/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8B4513;
            --primary-light: #fdf5f2;
            --primary-dark: #654321;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        body {
            background: linear-gradient(rgba(26, 18, 14, 0.72), rgba(26, 18, 14, 0.82)), url('/bg_lahair.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: var(--text-dark);
        }
        .select-card {
            background: #ffffff;
            border-radius: 20px;
            width: 100%;
            max-width: 560px;
            padding: 44px 36px 36px;
            box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.45);
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        .select-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary);
        }
        .logo-box {
            margin-bottom: 20px;
        }
        .logo-box img {
            height: 44px;
            width: auto;
            object-fit: contain;
        }
        .user-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--primary);
            background: var(--primary-light);
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 16px;
            border: 1px solid rgba(139, 69, 19, 0.15);
        }
        .title {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 8px;
            letter-spacing: -0.025em;
        }
        .subtitle {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin-bottom: 30px;
            line-height: 1.55;
            max-width: 440px;
            margin-left: auto;
            margin-right: auto;
        }
        .branch-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }
        @media (max-width: 480px) {
            .branch-grid {
                grid-template-columns: 1fr;
            }
            .select-card {
                padding: 32px 20px 24px;
            }
        }
        .branch-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 26px 18px;
            border: 1.5px solid var(--border);
            border-radius: 14px;
            text-decoration: none;
            background: #fafafa;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        .branch-item:hover {
            border-color: var(--primary);
            background: #ffffff;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px -5px rgba(139, 69, 19, 0.12);
        }
        .branch-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--primary);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);
            transition: all 0.2s;
        }
        .branch-item:hover .branch-icon {
            background: var(--primary-light);
            border-color: rgba(139, 69, 19, 0.2);
            color: var(--primary-dark);
            transform: scale(1.06);
        }
        .branch-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
            letter-spacing: -0.01em;
        }
        .branch-desc {
            font-size: 0.76rem;
            color: var(--text-muted);
            line-height: 1.4;
        }
        .branch-action {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.76rem;
            font-weight: 600;
            color: var(--primary);
            margin-top: 12px;
            opacity: 0;
            transform: translateY(2px);
            transition: all 0.2s;
        }
        .branch-item:hover .branch-action {
            opacity: 1;
            transform: translateY(0);
        }
        .btn-all-branches {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            padding: 9px 18px;
            border-radius: 50px;
            background: #f1f5f9;
            transition: all 0.2s;
            border: 1px solid transparent;
        }
        .btn-all-branches:hover {
            color: var(--text-dark);
            background: #e2e8f0;
            border-color: #cbd5e1;
        }
    </style>
</head>
<body>

    <div class="select-card">
        <div class="logo-box">
            <img src="/logo/lahair_logo_black.png" alt="La Hair Studio">
        </div>

        <?php if (session()->has('username')): ?>
            <div class="user-badge">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <span><?= esc(session()->get('username')) ?></span>
            </div>
        <?php endif; ?>

        <h1 class="title">Pilih Cabang Kerja</h1>
        <p class="subtitle">
            Pilih cabang salon untuk memfokuskan data pesanan, stok bahan baku, dan laporan kerja Anda.
        </p>

        <div class="branch-grid">
            <?php if (!empty($branches)): foreach ($branches as $b): ?>
                <a href="/admin/switch-branch/<?= $b['id'] ?>?redirect=/admin/orders" class="branch-item">
                    <div class="branch-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div class="branch-name">Cabang <?= esc($b['name']) ?></div>
                    <div class="branch-desc">
                        <?= esc($b['address'] ?? 'Cabang ' . $b['name']) ?>
                    </div>
                    <span class="branch-action">
                        Kelola
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </span>
                </a>
            <?php endforeach; else: ?>
                <a href="/admin/switch-branch/1?redirect=/admin/orders" class="branch-item">
                    <div class="branch-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div class="branch-name">Cabang Semarang</div>
                    <div class="branch-desc">Plaza Taman Niaga BSB</div>
                    <span class="branch-action">
                        Kelola
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </span>
                </a>
                <a href="/admin/switch-branch/2?redirect=/admin/orders" class="branch-item">
                    <div class="branch-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div class="branch-name">Cabang Indramayu</div>
                    <div class="branch-desc">Cabang Indramayu</div>
                    <span class="branch-action">
                        Kelola
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </span>
                </a>
            <?php endif; ?>
        </div>

        <a href="/admin/switch-branch/all?redirect=/admin/orders" class="btn-all-branches">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"/>
                <rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/>
                <rect x="3" y="14" width="7" height="7"/>
            </svg>
            <span>Semua Cabang (Konsolidasi)</span>
        </a>
    </div>

</body>
</html>
