<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link rel="icon" type="image/png" href="/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon/favicon.svg" />
    <link rel="shortcut icon" href="/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="La Hair" />
    <link rel="manifest" href="/favicon/site.webmanifest" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --text: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .receipt-container {
            background: white;
            width: 100%;
            max-width: 380px;
            padding: 30px 25px;
            border-radius: 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .text-center { text-align: center; }
        .logo { 
            height: 60px; 
            width: auto; 
            margin-bottom: 10px; 
            filter: grayscale(100%); /* Mono style */
        }
        .studio-name { font-weight: 700; font-size: 1.1rem; letter-spacing: -0.02em; }
        .studio-address { font-size: 0.8rem; color: var(--text-muted); line-height: 1.4; margin-top: 4px; }
        
        .divider {
            border-top: 2px dashed var(--border);
            margin: 20px 0;
        }
        
        .meta-info {
            font-size: 0.85rem;
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        .meta-label { color: var(--text-muted); }
        .meta-value { font-weight: 600; font-family: 'Space Mono', monospace; }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            margin: 15px 0;
        }
        .items-table th {
            text-align: left;
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.75rem;
            text-transform: uppercase;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border);
        }
        .items-table td {
            padding: 10px 0;
            border-bottom: 1px solid rgba(0,0,0,0.03);
            vertical-align: top;
        }
        .item-name {
            font-weight: 600;
            display: block;
            margin-bottom: 2px;
        }
        .item-sub {
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        .item-price {
            text-align: right;
            font-family: 'Space Mono', monospace;
            white-space: nowrap;
        }
        
        .total-section {
            margin-top: 10px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            margin-bottom: 6px;
        }
        .total-row.final {
            font-size: 1.1rem;
            font-weight: 700;
            border-top: 2px solid var(--text);
            padding-top: 12px;
            margin-top: 12px;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-muted);
            line-height: 1.5;
        }
        
        .print-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: var(--text);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 30px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }
        
        @media print {
            body { background: white; padding: 0; }
            .receipt-container { box-shadow: none; max-width: 100%; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="text-center">
            <img src="/logo/lahair_logo_black.png" alt="La Hair Studio" class="logo">
            <div class="studio-address">
                Plaza Taman Niaga, BSB F 3A, Lt. 2, Semarang<br>
                Telp: +62 899-3577-757
            </div>
        </div>
        
        <div class="divider"></div>
        
        <div class="meta-info">
            <span class="meta-label">No. Order</span>
            <span class="meta-value">#<?= $order['order_number'] ?></span>
        </div>
        <div class="meta-info">
            <span class="meta-label">Tanggal</span>
            <span class="meta-value"><?= date('d/m/Y H:i') ?></span>
        </div>
        <div class="meta-info">
            <span class="meta-label">Pelanggan</span>
            <span class="meta-value"><?= esc($order['customer_name']) ?></span>
        </div>
        <?php 
            $stylistDisplay = '';
            if (!empty($order['workers'])) {
                $stylistDisplay = implode(', ', array_column($order['workers'], 'username'));
            } elseif (!empty($order['worker_names'])) {
                $stylistDisplay = $order['worker_names'];
            } elseif (!empty($order['worker_name'])) {
                $stylistDisplay = $order['worker_name'];
            }
        ?>
        <?php if (!empty($stylistDisplay)): ?>
        <div class="meta-info">
            <span class="meta-label">Stylist</span>
            <span class="meta-value"><?= esc($stylistDisplay) ?></span>
        </div>
        <?php endif; ?>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th width="65%">Item</th>
                    <th width="35%" style="text-align: right;">Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                <tr>
                    <td>
                        <span class="item-name"><?= esc($item['product_name']) ?></span>
                        <span class="item-sub"><?= esc($item['item_name']) ?></span>
                        <?php if ($item['addon_items']): ?>
                            <?php foreach (json_decode($item['addon_items'], true) as $addon): ?>
                                <div class="item-sub" style="font-style: italic;">+ <?= esc($addon['name']) ?></div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </td>
                    <td class="item-price">
                        <?= number_format($item['price'], 0, ',', '.') ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="total-section">
            <?php if (!empty($order['discount_amount']) && (float)$order['discount_amount'] > 0): ?>
            <div class="total-row">
                <span class="meta-label">Subtotal</span>
                <span class="meta-value">Rp <?= number_format($order['subtotal_amount'] ?? $order['total_amount'], 0, ',', '.') ?></span>
            </div>
            <div class="total-row" style="color: #16a34a;">
                <span>Diskon <?= ($order['discount_type'] === 'percent' && (float)$order['discount_percent'] > 0) ? '(' . (float)$order['discount_percent'] . '%)' : '' ?></span>
                <span class="meta-value">-Rp <?= number_format($order['discount_amount'], 0, ',', '.') ?></span>
            </div>
            <?php endif; ?>
            <div class="total-row final">
                <span>TOTAL</span>
                <span>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
            </div>
        </div>
        
        <div class="divider"></div>
        
        <div class="footer">
            Terima kasih atas kunjungan Anda.<br>
            Harap simpan struk ini sebagai bukti pembayaran.<br>
            <em>Follow us @lahair.studio</em>
        </div>
        
        <button onclick="window.print()" class="print-btn">Cetak Struk</button>
    </div>
</body>
</html>
