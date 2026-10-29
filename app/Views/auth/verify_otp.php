<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - La Hair Studio</title>
    <link rel="icon" type="image/png" href="/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon/favicon.svg" />
    <link rel="shortcut icon" href="/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="La Hair" />
    <link rel="manifest" href="/favicon/site.webmanifest" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Same core styles as forgot_password.php */
        :root {
            --neon-orange: #ff6b2c;
            --bg-primary: #0d0d12;
            --bg-secondary: #14141c;
            --text-primary: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.5);
            --border-subtle: rgba(255, 255, 255, 0.08);
            --radius-md: 12px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        body::before {
            content: ''; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at center, rgba(255, 107, 44, 0.05) 0%, transparent 70%); z-index: -1;
        }
        .login-card {
            background: var(--bg-secondary); width: 100%; max-width: 400px;
            padding: 40px; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5); text-align: center;
        }
        h1 { font-size: 1.5rem; margin-bottom: 8px; color: var(--text-primary); }
        p { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 32px; }
        .form-group { text-align: left; margin-bottom: 20px; }
        .form-label { display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 8px; }
        .form-input {
            width: 100%; padding: 12px 16px; background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-subtle); border-radius: 8px; color: white;
            font-family: inherit; transition: all 0.2s; letter-spacing: 4px; text-align: center; font-size: 1.2rem;
        }
        .form-input:focus {
            outline: none; border-color: var(--neon-orange); background: rgba(255, 255, 255, 0.05);
            box-shadow: 0 0 0 4px rgba(255, 107, 44, 0.1);
        }
        .btn-login {
            width: 100%; padding: 14px; background: var(--neon-orange); color: white;
            border: none; border-radius: 8px; font-size: 1rem; font-weight: 600;
            cursor: pointer; transition: transform 0.2s, opacity 0.2s; margin-top: 10px;
        }
        .btn-login:hover { opacity: 0.9; }
        .btn-login:active { transform: scale(0.98); }
        .alert { padding: 12px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 24px; }
        .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; }
        .alert-success { background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: #22c55e; }
        .back-link { display: block; margin-top: 20px; font-size: 0.9rem; color: var(--text-muted); text-decoration: none; }
        .back-link:hover { color: white; }
    </style>
</head>
<body>

    <div class="login-card">
        <h1>Kode OTP</h1>
        <p>Masukkan 6 digit kode yang dikirim ke email Anda.</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form action="/auth/forgot-password/verify" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="email" value="<?= esc($email ?? '') ?>">
            
            <div class="form-group">
                <input type="text" name="otp" class="form-input" placeholder="000000" maxlength="6" required autofocus>
            </div>

            <button type="submit" class="btn-login">Verifikasi</button>
        </form>
        
        <a href="/auth/forgot-password" class="back-link">Kirim Ulang Kode</a>
    </div>

</body>
</html>
