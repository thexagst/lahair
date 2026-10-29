<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin') ?> - La Hair Studio Admin</title>
    <link rel="icon" type="image/png" href="/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon/favicon.svg" />
    <link rel="shortcut icon" href="/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="La Hair" />
    <link rel="manifest" href="/favicon/site.webmanifest" />
    <meta name="author" content="Duma Augusto">
    <meta name="designer" content="Duma Augusto - Full Stack Developer">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/admin.css?v=<?= time() ?>">
    <!-- SortableJS for Drag & Drop -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <!-- Select2 (for search in dropdowns) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-logo">
            <a href="/admin">
                <img src="/logo/lahair_logo_black.png" alt="La Hair Studio" style="height: 40px; width: auto; object-fit: contain;">
            </a>
        </div>

        <div style="padding: 0 16px 14px 16px;">
            <a href="/admin/select-branch" style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background: rgba(255, 255, 255, 0.9); border: 1px solid rgba(139, 69, 19, 0.2); border-radius: 8px; text-decoration: none; color: #2c2c2c; font-size: 0.78rem; font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.04); transition: all 0.2s;" title="Ganti cabang kerja">
                <span style="display: flex; align-items: center; gap: 7px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.2" style="flex-shrink: 0;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= esc(session()->get('active_branch_name') ?? 'Pilih Cabang') ?></span>
                </span>
                <span style="color: var(--primary); font-size: 0.72rem; flex-shrink: 0; display: flex; align-items: center; gap: 3px; font-weight: 700;">
                    Ganti
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                </span>
            </a>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Utama</div>
                <a href="/admin/orders" class="nav-item <?= strpos(current_url(), '/orders') !== false ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Pesanan
                </a>
                
                <a href="/admin/bookings/create" class="nav-item <?= strpos(current_url(), '/bookings') !== false ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Booking Offline
                </a>

                <a href="/admin/products" class="nav-item <?= strpos(current_url(), '/products') !== false ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    Menu
                </a>
            </div>


            <?php if (session()->get('role') !== 'worker') : ?>
            <div class="nav-section">
                <div class="nav-section-title">Laporan</div>
                <a href="/admin/reports/financial" class="nav-item <?= strpos(current_url(), '/reports') !== false ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Laporan Keuangan
                </a>
                <a href="/admin/inventory" class="nav-item <?= strpos(current_url(), '/inventory') !== false ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Stok Bahan
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Lainnya</div>
                <a href="/admin/admins" class="nav-item <?= strpos(current_url(), '/admins') !== false ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Kelola Admin
                </a>
                <a href="/admin/settings" class="nav-item <?= strpos(current_url(), '/settings') !== false ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Pengaturan
                </a>
            </div>
            <?php endif; ?>
        </nav>

        <!-- Creator & Developer Attribution -->
        <div class="sidebar-creator-badge" title="Engineered by Duma Augusto — Full Stack Developer">
            <div class="scb-profile">
                <div class="scb-avatar">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="16 18 22 12 16 6"></polyline>
                        <polyline points="8 6 2 12 8 18"></polyline>
                    </svg>
                </div>
                <div class="scb-info">
                    <span class="scb-name">Duma Augusto</span>
                    <span class="scb-role">Full Stack Developer</span>
                </div>
                <span class="scb-status-dot" title="Active"></span>
            </div>
        </div>

        <div class="sidebar-footer">
            <a href="/" target="_blank" class="footer-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Lihat Website
            </a>
            <a href="/logout" class="footer-item logout" onclick="return confirm('Yakin ingin keluar?')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar Admin
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="admin-main">
        <header class="admin-header">
            <div style="display: flex; align-items: center; gap: 16px;">
                <button class="mobile-toggle" onclick="toggleSidebar()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="header-title"><?= esc($title ?? 'Dashboard') ?></span>
            </div>
            <div class="header-actions" style="display: flex; align-items: center; gap: 12px;">
                <!-- Branch Selector Dropdown Switcher -->
                <?php
                    $activeBName = session()->get('active_branch_name') ?? 'Semua Cabang';
                    $activeBId   = session()->get('active_branch_id');
                ?>
                <div class="branch-switcher-container" style="position: relative;">
                    <button type="button" id="branchDropdownBtn" onclick="toggleBranchMenu(event)" style="display: inline-flex; align-items: center; gap: 7px; padding: 6px 12px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; cursor: pointer; border: 1px solid var(--border); background: var(--bg-card); color: var(--text); box-shadow: var(--shadow); transition: all 0.2s;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>Cabang: <strong style="color: var(--primary);"><?= esc($activeBName) ?></strong></span>
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="color: var(--text-muted);"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    
                    <div id="branchDropdownMenu" style="display: none; position: absolute; right: 0; top: calc(100% + 6px); min-width: 220px; background: #ffffff; border-radius: 10px; box-shadow: 0 12px 28px -4px rgba(0,0,0,0.12), 0 4px 10px -2px rgba(0,0,0,0.06); border: 1px solid var(--border); padding: 6px; z-index: 9999;">
                        <div style="padding: 6px 10px 6px; font-size: 0.7rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Pilih Cabang Kerja</div>
                        <a href="/admin/switch-branch/1" style="display: flex; align-items: center; justify-content: space-between; padding: 7px 10px; border-radius: 6px; text-decoration: none; color: var(--text); font-size: 0.82rem; font-weight: <?= ($activeBId == 1) ? '700' : '500' ?>; background: <?= ($activeBId == 1) ? 'var(--primary-light)' : 'transparent' ?>; transition: background 0.15s;">
                            <span style="display: flex; align-items: center; gap: 7px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="<?= ($activeBId == 1) ? 'var(--primary)' : 'var(--text-muted)' ?>" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span>Cabang Semarang</span>
                            </span>
                            <?php if ($activeBId == 1): ?>
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            <?php endif; ?>
                        </a>
                        <a href="/admin/switch-branch/2" style="display: flex; align-items: center; justify-content: space-between; padding: 7px 10px; border-radius: 6px; text-decoration: none; color: var(--text); font-size: 0.82rem; font-weight: <?= ($activeBId == 2) ? '700' : '500' ?>; background: <?= ($activeBId == 2) ? 'var(--primary-light)' : 'transparent' ?>; transition: background 0.15s;">
                            <span style="display: flex; align-items: center; gap: 7px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="<?= ($activeBId == 2) ? 'var(--primary)' : 'var(--text-muted)' ?>" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span>Cabang Indramayu</span>
                            </span>
                            <?php if ($activeBId == 2): ?>
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            <?php endif; ?>
                        </a>
                        <div style="height: 1px; background: var(--border); margin: 4px 0;"></div>
                        <a href="/admin/switch-branch/all" style="display: flex; align-items: center; justify-content: space-between; padding: 7px 10px; border-radius: 6px; text-decoration: none; color: var(--text); font-size: 0.82rem; font-weight: <?= ($activeBId === null || $activeBId === 'all') ? '700' : '500' ?>; background: <?= ($activeBId === null || $activeBId === 'all') ? 'var(--primary-light)' : 'transparent' ?>; transition: background 0.15s;">
                            <span style="display: flex; align-items: center; gap: 7px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="<?= ($activeBId === null || $activeBId === 'all') ? 'var(--primary)' : 'var(--text-muted)' ?>" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                <span>Semua Cabang</span>
                            </span>
                            <?php if ($activeBId === null || $activeBId === 'all'): ?>
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            <?php endif; ?>
                        </a>
                        <div style="height: 1px; background: var(--border); margin: 4px 0;"></div>
                        <a href="/admin/select-branch" style="display: flex; align-items: center; gap: 7px; padding: 7px 10px; border-radius: 6px; text-decoration: none; color: var(--text-muted); font-size: 0.78rem;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            <span>Layar Pilihan Cabang</span>
                        </a>
                    </div>
                </div>

                <span style="font-size: 0.85rem; color: var(--text-muted);">
                    <?= date('d M Y') ?>
                </span>
            </div>
        </header>
        
        <main class="admin-container" id="main-content">
            <?= $this->renderSection('content') ?>
        </main>

        <!-- Admin Main Footer -->
        <footer class="admin-main-footer">
            <div class="admin-footer-left">
                &copy; <?= date('Y') ?> <strong>La Hair Studio</strong> Management System
            </div>
            <div class="admin-footer-right">
                <div class="admin-footer-credit">
                    <span class="admin-footer-badge">DEVELOPER</span>
                    <span>Crafted by <strong>Duma Augusto</strong> &bull; Full Stack Developer</span>
                </div>
            </div>
        </footer>
    </div>

    <!-- External Assets -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    
    <!-- Flash Data Bridge for SPA -->
    <div id="flash-data-container">
        <script>
            window.flashData = {
                success: "<?= session()->getFlashdata('success') ?>",
                error: "<?= session()->getFlashdata('error') ?>"
            };
        </script>
    </div>

    <!-- Global Libraries (QRCode, Scanner) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <!-- Admin Logic (SPA, Toast, Sidebar) -->
    <script src="/js/admin.js?v=<?= time() ?>"></script>
    <script>
        function toggleBranchMenu(e) {
            if (e) e.stopPropagation();
            const menu = document.getElementById('branchDropdownMenu');
            if (menu) {
                menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'block' : 'none';
            }
        }
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('branchDropdownMenu');
            const btn = document.getElementById('branchDropdownBtn');
            if (menu && menu.style.display === 'block') {
                if (!menu.contains(e.target) && !btn.contains(e.target)) {
                    menu.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>
