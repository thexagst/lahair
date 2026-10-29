<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa PIN - La Hair Studio</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8B4513;
            --primary-light: #fbf5f1;
            --primary-dark: #654321;
            --primary-hover: #9c4e16;
            --text-main: #1f2937;
            --text-heading: #1c1917;
            --text-muted: #6b7280;
            --text-dim: #9ca3af;
            --border-color: #e7e2dc;
            --radius-card: 22px;
            --radius-input: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
            color: var(--text-main);
            background: linear-gradient(135deg, rgba(253, 251, 249, 0.84) 0%, rgba(246, 241, 236, 0.90) 100%), url('/bg_lahair.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            position: relative;
        }

        .login-wrapper {
            width: 100%;
            max-width: 410px;
            position: relative;
            z-index: 1;
            animation: cardEntrance 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes cardEntrance {
            from { opacity: 0; transform: translateY(14px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.95);
            border-radius: var(--radius-card);
            padding: 40px 34px 28px;
            box-shadow: 
                0 24px 50px -12px rgba(78, 48, 28, 0.12),
                0 6px 16px -4px rgba(0, 0, 0, 0.04),
                0 0 0 1px rgba(255, 255, 255, 0.75);
            position: relative;
        }

        .card-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-logo {
            height: 48px;
            width: auto;
            object-fit: contain;
            margin-bottom: 16px;
        }

        .card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.55rem;
            font-weight: 700;
            color: var(--text-heading);
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .card-subtitle {
            font-size: 0.86rem;
            color: var(--text-muted);
            font-weight: 400;
            line-height: 1.45;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-label {
            display: block;
            font-size: 0.74rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #4b5563;
            margin-bottom: 7px;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: var(--primary);
            opacity: 0.85;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            height: 46px;
            padding: 0 16px 0 44px;
            background: #ffffff;
            border: 1.5px solid var(--border-color);
            border-radius: var(--radius-input);
            color: var(--text-main);
            font-family: inherit;
            font-size: 0.92rem;
            font-weight: 500;
            transition: all 0.2s ease;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .form-input::placeholder {
            color: var(--text-dim);
            font-size: 0.87rem;
            font-weight: 400;
        }

        .form-input:focus {
            outline: none;
            background: #ffffff;
            border-color: var(--primary);
            box-shadow: 
                0 0 0 4px rgba(139, 69, 19, 0.12),
                0 2px 8px rgba(139, 69, 19, 0.06);
        }

        .btn-submit {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #8B4513 0%, #6f3408 100%);
            color: #ffffff;
            border: none;
            border-radius: var(--radius-input);
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.01em;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 10px 22px -4px rgba(139, 69, 19, 0.38);
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #9b4e16 0%, #7d3c0b 100%);
            transform: translateY(-1.5px);
            box-shadow: 0 14px 28px -4px rgba(139, 69, 19, 0.46);
        }

        .btn-submit:active {
            transform: translateY(1px);
            box-shadow: 0 4px 12px rgba(139, 69, 19, 0.25);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 18px;
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .back-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .auth-alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 12px;
            font-size: 0.84rem;
            line-height: 1.4;
            margin-bottom: 20px;
            text-align: left;
        }

        .auth-alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }

        .auth-alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
        }

        .card-footer-attribution {
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid rgba(139, 69, 19, 0.08);
            display: flex;
            justify-content: center;
        }

        .creator-tag {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 0.72rem;
            color: #6b7280;
            padding: 4px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(139, 69, 19, 0.12);
        }

        .creator-tag strong {
            color: var(--text-heading);
            font-weight: 600;
        }

        .creator-tag-badge {
            display: inline-flex;
            align-items: center;
            padding: 1px 6px;
            border-radius: 4px;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 0.64rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            border: 1px solid rgba(139, 69, 19, 0.15);
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="card-header">
                <img src="/logo/lahair_logo_black.png" alt="La Hair Studio" class="brand-logo">
                <h1 class="card-title">Lupa PIN Akses</h1>
                <p class="card-subtitle">Masukkan email terdaftar untuk menerima kode OTP verifikasi</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="auth-alert auth-alert-error">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="auth-alert auth-alert-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>

            <form action="/auth/forgot-password/send" method="post">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                            </svg>
                        </span>
                        <input type="email" id="email" name="email" class="form-input" placeholder="contoh@email.com" required autofocus>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <span>Kirim Kode OTP</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            </form>

            <div style="text-align: center;">
                <a href="/login" class="back-link">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali ke Halaman Login</span>
                </a>
            </div>

            <div class="card-footer-attribution">
                <div class="creator-tag">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.7;">
                        <polyline points="16 18 22 12 16 6"></polyline>
                        <polyline points="8 6 2 12 8 18"></polyline>
                    </svg>
                    <span>Crafted by <strong>Duma Augusto</strong></span>
                    <span class="creator-tag-badge">Full Stack Developer</span>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
