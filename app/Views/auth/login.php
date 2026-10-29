<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Login Admin') ?> - La Hair Studio</title>
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
            /* Warm luxury studio ambiance using the sidebar botanical background */
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
            from {
                opacity: 0;
                transform: translateY(14px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Clean Modern Frosted Glass Card */
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

        /* Clean Centered Header */
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
            transition: transform 0.25s ease;
        }

        .brand-logo:hover {
            transform: scale(1.03);
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

        /* Form Controls */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 7px;
        }

        .form-label {
            font-size: 0.74rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #4b5563;
        }

        .forgot-link {
            font-size: 0.76rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.15s ease;
        }

        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
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
            transition: opacity 0.2s ease, transform 0.2s ease;
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

        .input-group:focus-within .input-icon {
            opacity: 1;
            transform: scale(1.05);
        }

        /* Toggle Password Button */
        .toggle-password {
            position: absolute;
            right: 10px;
            background: transparent;
            border: none;
            color: var(--primary);
            opacity: 0.75;
            cursor: pointer;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: opacity 0.15s ease, background 0.15s ease;
        }

        .toggle-password:hover {
            opacity: 1;
            background: rgba(139, 69, 19, 0.08);
        }

        /* Remember Me */
        .remember-wrap {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 22px;
            cursor: pointer;
            user-select: none;
        }

        .remember-wrap input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
            border-radius: 4px;
        }

        .remember-label {
            font-size: 0.84rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Submit Button */
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

        .btn-submit svg {
            transition: transform 0.2s ease;
        }

        .btn-submit:hover svg {
            transform: translateX(3px);
        }

        /* Alerts */
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

        .auth-alert svg {
            flex-shrink: 0;
        }

        /* Footer Watermark */
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
            transition: all 0.2s ease;
        }

        .creator-tag:hover {
            background: #ffffff;
            border-color: var(--primary);
            color: var(--text-main);
            box-shadow: 0 4px 12px rgba(139, 69, 19, 0.08);
            transform: translateY(-1px);
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

        /* Responsive Mobile Tuning */
        @media (max-width: 480px) {
            body {
                padding: 18px 12px;
            }

            .login-card {
                padding: 30px 20px 22px;
                border-radius: 18px;
            }

            .card-title {
                font-size: 1.35rem;
            }

            .brand-logo {
                height: 42px;
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-card">
            <!-- Centered Header -->
            <div class="card-header">
                <img src="/logo/lahair_logo_black.png" alt="La Hair Studio" class="brand-logo">
                <h1 class="card-title">Admin Portal</h1>
                <p class="card-subtitle">Silakan masuk untuk mengelola sistem studio</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="auth-alert auth-alert-error">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="auth-alert auth-alert-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>

            <form action="/login" method="post" autocomplete="on">
                <?= csrf_field() ?>

                <div class="form-group">
                    <div class="form-label-row">
                        <label class="form-label" for="username">Username</label>
                    </div>
                    <div class="input-group">
                        <span class="input-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>
                        <input type="text" id="username" name="username" class="form-input" placeholder="Masukkan username admin" required autofocus autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-label-row">
                        <label class="form-label" for="pin_field">PIN Akses (6 Digit)</label>
                        <a href="/auth/forgot-password" class="forgot-link">Lupa PIN?</a>
                    </div>
                    <div class="input-group">
                        <span class="input-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </span>
                        <input 
                            type="password" 
                            name="password" 
                            id="pin_field" 
                            class="form-input" 
                            placeholder="••••••" 
                            maxlength="6" 
                            pattern="[0-9]{6}" 
                            inputmode="numeric" 
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" class="toggle-password" id="togglePinBtn" aria-label="Lihat PIN" title="Tampilkan PIN">
                            <svg id="eyeIcon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <label class="remember-wrap">
                    <input type="checkbox" name="remember" value="1">
                    <span class="remember-label">Ingat perangkat ini</span>
                </label>

                <button type="submit" class="btn-submit">
                    <span>Masuk ke Dashboard</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            </form>

            <!-- Professional Watermark -->
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

    <script>
        const pinField = document.getElementById('pin_field');
        const toggleBtn = document.getElementById('togglePinBtn');
        const eyeIcon = document.getElementById('eyeIcon');

        // Numeric sanitization for PIN
        if (pinField) {
            pinField.addEventListener('keydown', function(e) {
                const allowed = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Home', 'End', 'Enter'];
                if (!allowed.includes(e.key) && !/^[0-9]$/.test(e.key)) {
                    e.preventDefault();
                }
            });

            pinField.addEventListener('paste', function(e) {
                e.preventDefault();
                const digits = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '').substring(0, 6);
                this.value = digits;
            });
        }

        // Toggle PIN visibility
        if (toggleBtn && pinField) {
            toggleBtn.addEventListener('click', function() {
                const isPassword = pinField.type === 'password';
                pinField.type = isPassword ? 'text' : 'password';
                if (isPassword) {
                    eyeIcon.innerHTML = `
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    `;
                    toggleBtn.title = 'Sembunyikan PIN';
                } else {
                    eyeIcon.innerHTML = `
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    `;
                    toggleBtn.title = 'Tampilkan PIN';
                }
            });
        }
    </script>
</body>
</html>
