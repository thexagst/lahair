<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h1 class="page-title"><?= esc($title) ?></h1>
</div>

<!-- Date Filter -->
<div class="card" style="padding: 20px; margin-bottom: 24px;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; align-items: end;">
        <div class="form-group" style="margin: 0;">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" id="startDate" class="form-input" value="<?= date('Y-m-01') ?>">
        </div>
        
        <div class="form-group" style="margin: 0;">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" id="endDate" class="form-input" value="<?= date('Y-m-d') ?>">
        </div>

        <div class="form-group" style="margin: 0;">
            <label class="form-label">Cabang</label>
            <select id="branchFilter" class="form-select" onchange="loadData()">
                <option value="all" <?= ($activeBranchId === null) ? 'selected' : '' ?>>Semua Cabang</option>
                <?php if (!empty($branches)): foreach ($branches as $b): ?>
                    <option value="<?= $b['id'] ?>" <?= ($activeBranchId == $b['id']) ? 'selected' : '' ?>>
                        Cabang <?= esc($b['name']) ?>
                    </option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        
        <div style="display: flex; gap: 8px;">
            <button onclick="loadData()" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Tampilkan
            </button>
            <button onclick="exportExcel()" class="btn btn-success">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                </svg>
                Export Excel
            </button>
        </div>
    </div>
    
    <!-- Quick Filters -->
    <div style="display: flex; gap: 8px; margin-top: 16px; flex-wrap: wrap;">
        <button onclick="setDateRange('today')" class="btn btn-secondary" style="font-size: 0.85rem; padding: 6px 12px;">Hari Ini</button>
        <button onclick="setDateRange('week')" class="btn btn-secondary" style="font-size: 0.85rem; padding: 6px 12px;">7 Hari</button>
        <button onclick="setDateRange('month')" class="btn btn-secondary" style="font-size: 0.85rem; padding: 6px 12px;">30 Hari</button>
        <button onclick="setDateRange('thisMonth')" class="btn btn-secondary" style="font-size: 0.85rem; padding: 6px 12px;">Bulan Ini</button>
        <button onclick="setDateRange('lastMonth')" class="btn btn-secondary" style="font-size: 0.85rem; padding: 6px 12px;">Bulan Lalu</button>
    </div>
</div>

<!-- Summary Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 24px;">
    <div class="card" style="padding: 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div style="font-size: 0.9rem; opacity: 0.9; margin-bottom: 8px;">Total Pendapatan</div>
        <div style="font-size: 2rem; font-weight: 700;" id="totalRevenue">Rp 0</div>
        <div style="font-size: 0.85rem; opacity: 0.8; margin-top: 8px;" id="orderCount">0 order</div>
    </div>
    
    <div class="card" style="padding: 24px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
        <div style="font-size: 0.9rem; opacity: 0.9; margin-bottom: 8px;">Online Booking</div>
        <div style="font-size: 2rem; font-weight: 700;" id="onlineRevenue">Rp 0</div>
        <div style="font-size: 0.85rem; opacity: 0.8; margin-top: 8px;" id="onlinePercent">0%</div>
    </div>
    
    <div class="card" style="padding: 24px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
        <div style="font-size: 0.9rem; opacity: 0.9; margin-bottom: 8px;">Offline Booking</div>
        <div style="font-size: 2rem; font-weight: 700;" id="offlineRevenue">Rp 0</div>
        <div style="font-size: 0.85rem; opacity: 0.8; margin-top: 8px;" id="offlinePercent">0%</div>
    </div>
</div>

<!-- Chart -->
<div class="card" style="padding: 24px; margin-bottom: 24px;">
    <h3 style="margin-bottom: 20px; font-size: 1.1rem; font-weight: 600;">Trend Pendapatan Harian</h3>
    <canvas id="revenueChart" style="max-height: 300px;"></canvas>
</div>

<!-- Daily Breakdown Table -->
<div class="card" style="padding: 24px; margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 700; color: #111827;">Rincian Pendapatan Harian</h3>
            <p style="margin: 4px 0 0; font-size: 0.85rem; color: #6b7280;">Klik pada baris tanggal untuk melihat rincian order pada hari tersebut</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="button" onclick="toggleAllDays(true)" class="btn btn-secondary" style="font-size: 0.8rem; padding: 5px 12px; cursor: pointer;">Buka Semua</button>
            <button type="button" onclick="toggleAllDays(false)" class="btn btn-secondary" style="font-size: 0.8rem; padding: 5px 12px; cursor: pointer;">Tutup Semua</button>
        </div>
    </div>

    <div style="overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 8px;">
        <table id="dailyTable" style="width: 100%; border-collapse: collapse; font-size: 0.88rem; text-align: left;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569; font-weight: 600;">
                    <th style="padding: 12px 14px; width: 35px; text-align: center;"></th>
                    <th style="padding: 12px 14px;">Tanggal</th>
                    <th style="padding: 12px 14px; text-align: center;">Jumlah Order</th>
                    <th style="padding: 12px 14px; text-align: right;">Online Booking</th>
                    <th style="padding: 12px 14px; text-align: right;">Offline Booking</th>
                    <th style="padding: 12px 14px; text-align: right;">Total Pendapatan</th>
                    <th style="padding: 12px 14px; width: 90px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="dailyTableBody">
                <!-- Populated via JS -->
            </tbody>
            <tfoot id="dailyTableFoot" style="background: #f1f5f9; border-top: 2px solid #cbd5e1; font-weight: 700;">
                <!-- Populated via JS -->
            </tfoot>
        </table>
    </div>

    <div id="dailyTableEmpty" style="display: none; text-align: center; padding: 40px 20px; color: #94a3b8; font-size: 0.9rem;">
        Tidak ada transaksi pada rentang tanggal yang dipilih.
    </div>
</div>

<!-- Orders by Status -->
<div class="card" style="padding: 24px;">
    <h3 style="margin-bottom: 20px; font-size: 1.1rem; font-weight: 600;">Status Order</h3>
    <div id="statusBreakdown" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px;">
        <!-- Will be populated by JS -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let revenueChart = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        loadData();
    });
    
    function setDateRange(range) {
        const today = new Date();
        const endDate = document.getElementById('endDate');
        const startDate = document.getElementById('startDate');
        
        endDate.value = formatDate(today);
        
        switch(range) {
            case 'today':
                startDate.value = formatDate(today);
                break;
            case 'week':
                const weekAgo = new Date(today);
                weekAgo.setDate(weekAgo.getDate() - 7);
                startDate.value = formatDate(weekAgo);
                break;
            case 'month':
                const monthAgo = new Date(today);
                monthAgo.setDate(monthAgo.getDate() - 30);
                startDate.value = formatDate(monthAgo);
                break;
            case 'thisMonth':
                startDate.value = formatDate(new Date(today.getFullYear(), today.getMonth(), 1));
                break;
            case 'lastMonth':
                const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                const lastMonthEnd = new Date(today.getFullYear(), today.getMonth(), 0);
                startDate.value = formatDate(lastMonth);
                endDate.value = formatDate(lastMonthEnd);
                break;
        }
        
        loadData();
    }
    
    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }
    
    async function loadData() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const branch = document.getElementById('branchFilter') ? document.getElementById('branchFilter').value : '';
        
        try {
            const response = await fetch(`/admin/reports/revenue-data?start_date=${startDate}&end_date=${endDate}&branch_id=${branch}`);
            const data = await response.json();
            
            updateSummaryCards(data);
            updateChart(data.daily_breakdown);
            renderDailyTable(data.daily_breakdown);
            updateStatusBreakdown(data.orders_by_status);
        } catch (error) {
            console.error('Error loading data:', error);
            alert('Gagal memuat data. Silakan coba lagi.');
        }
    }
    
    function updateSummaryCards(data) {
        // Total Revenue
        document.getElementById('totalRevenue').textContent = 'Rp ' + formatNumber(data.total_revenue || 0);
        
        const totalOrders = data.daily_breakdown.reduce((sum, day) => sum + parseInt(day.order_count), 0);
        document.getElementById('orderCount').textContent = totalOrders + ' order';
        
        // Online vs Offline
        const onlineData = data.revenue_by_type.find(t => t.booking_type === 'online') || {total: 0};
        const offlineData = data.revenue_by_type.find(t => t.booking_type === 'offline') || {total: 0};
        
        const onlineRevenue = parseFloat(onlineData.total || 0);
        const offlineRevenue = parseFloat(offlineData.total || 0);
        const total = onlineRevenue + offlineRevenue;
        
        document.getElementById('onlineRevenue').textContent = 'Rp ' + formatNumber(onlineRevenue);
        document.getElementById('offlineRevenue').textContent = 'Rp ' + formatNumber(offlineRevenue);
        
        document.getElementById('onlinePercent').textContent = total > 0 ? Math.round(onlineRevenue / total * 100) + '% dari total' : '0%';
        document.getElementById('offlinePercent').textContent = total > 0 ? Math.round(offlineRevenue / total * 100) + '% dari total' : '0%';
    }
    
    function updateChart(dailyData) {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        if (revenueChart) {
            revenueChart.destroy();
        }
        
        const chartData = dailyData.slice().reverse();

        const labels = chartData.map(d => {
            const date = new Date(d.date);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        });
        
        const values = chartData.map(d => parseFloat(d.total));
        
        revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: values,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + formatNumber(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value >= 1000000 ? (value/1000000).toFixed(1) + 'jt' : (value >= 1000 ? (value/1000).toFixed(0) + 'rb' : value));
                            }
                        }
                    }
                }
            }
        });
    }
    
    function updateStatusBreakdown(statusData) {
        const container = document.getElementById('statusBreakdown');
        const statusLabels = {
            'pending': 'Pending',
            'confirmed': 'Confirmed',
            'processing': 'Processing',
            'completed': 'Completed',
            'cancelled': 'Cancelled'
        };
        
        const statusColors = {
            'pending': '#fbbf24',
            'confirmed': '#3b82f6',
            'processing': '#8b5cf6',
            'completed': '#10b981',
            'cancelled': '#ef4444'
        };
        
        container.innerHTML = statusData.map(s => `
            <div style="padding: 16px; background: ${statusColors[s.status]}20; border-radius: 8px; border-left: 4px solid ${statusColors[s.status]};">
                <div style="font-size: 0.85rem; color: #666; margin-bottom: 4px;">${statusLabels[s.status] || s.status}</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: ${statusColors[s.status]};">${s.count}</div>
            </div>
        `).join('');
    }
    
    function renderDailyTable(dailyData) {
        const tbody = document.getElementById('dailyTableBody');
        const tfoot = document.getElementById('dailyTableFoot');
        const empty = document.getElementById('dailyTableEmpty');
        const table = document.getElementById('dailyTable');

        if (!dailyData || dailyData.length === 0) {
            tbody.innerHTML = '';
            tfoot.innerHTML = '';
            table.style.display = 'none';
            empty.style.display = 'block';
            return;
        }

        table.style.display = 'table';
        empty.style.display = 'none';

        let grandOrders = 0;
        let grandOnline = 0;
        let grandOffline = 0;
        let grandTotal = 0;

        let rowsHtml = '';
        dailyData.forEach((day, idx) => {
            grandOrders += parseInt(day.order_count || 0);
            grandOnline += parseFloat(day.online_total || 0);
            grandOffline += parseFloat(day.offline_total || 0);
            grandTotal += parseFloat(day.total || 0);

            const dayId = 'day-row-' + idx;
            const subId = 'sub-row-' + idx;
            const iconId = 'icon-' + idx;

            // Render sub-row orders
            let subOrdersHtml = '';
            if (day.orders && day.orders.length > 0) {
                day.orders.forEach(order => {
                    const statusColors = {
                        'completed':  { bg: 'rgba(16, 185, 129, 0.12)', text: '#059669', label: 'Completed' },
                        'processing': { bg: 'rgba(139, 92, 246, 0.12)', text: '#7c3aed', label: 'Processing' },
                        'confirmed':  { bg: 'rgba(59, 130, 246, 0.12)', text: '#2563eb', label: 'Confirmed' },
                        'pending':    { bg: 'rgba(245, 158, 11, 0.12)', text: '#d97706', label: 'Pending' },
                        'cancelled':  { bg: 'rgba(239, 68, 68, 0.12)', text: '#dc2626', label: 'Cancelled' }
                    };
                    const sc = statusColors[order.status] || { bg: '#f1f5f9', text: '#475569', label: order.status };
                    const bookingBadge = order.booking_type === 'online' 
                        ? '<span style="background:rgba(99,102,241,0.12);color:#4f46e5;padding:2px 8px;border-radius:12px;font-size:0.75rem;font-weight:600;">Online</span>'
                        : '<span style="background:rgba(245,158,11,0.12);color:#d97706;padding:2px 8px;border-radius:12px;font-size:0.75rem;font-weight:600;">Offline</span>';
                    
                    const phone = order.customer_phone ? `<br><small style="color:#64748b;font-size:0.75rem;">${order.customer_phone}</small>` : '';

                    subOrdersHtml += `
                        <tr style="border-bottom: 1px solid #f1f5f9; background: #ffffff;">
                            <td style="padding: 9px 12px; color: #64748b; white-space: nowrap; font-weight: 500;">${order.time}</td>
                            <td style="padding: 9px 12px; white-space: nowrap;">
                                <a href="/admin/orders/view/${encodeURIComponent(order.order_number)}" target="_blank" style="color: #2563eb; font-weight: 600; text-decoration: none;">
                                    #${order.order_number} ↗
                                </a>
                            </td>
                            <td style="padding: 9px 12px; color: #1e293b;">
                                <strong>${order.customer_name}</strong>${phone}
                            </td>
                            <td style="padding: 9px 12px; color: #475569; max-width: 250px;">
                                ${order.services}
                            </td>
                            <td style="padding: 9px 12px; text-align: center;">${bookingBadge}</td>
                            <td style="padding: 9px 12px; text-align: center;">
                                <span style="background:${sc.bg};color:${sc.text};padding:2px 8px;border-radius:12px;font-size:0.75rem;font-weight:600;">
                                    ${sc.label}
                                </span>
                            </td>
                            <td style="padding: 9px 12px; text-align: right; font-weight: 700; color: #059669; white-space: nowrap;">
                                Rp ${formatNumber(order.total_amount)}
                            </td>
                        </tr>
                    `;
                });
            } else {
                subOrdersHtml = `<tr><td colspan="7" style="padding: 16px; text-align: center; color: #94a3b8;">Tidak ada rincian pesanan.</td></tr>`;
            }

            // Main row
            rowsHtml += `
                <tr id="${dayId}" onclick="toggleDayRow(${idx})" style="cursor: pointer; border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                    <td style="padding: 12px 14px; text-align: center; color: #64748b;">
                        <span id="${iconId}" style="display: inline-block; transition: transform 0.2s; font-size: 0.75rem;">▶</span>
                    </td>
                    <td style="padding: 12px 14px; font-weight: 600; color: #1e293b;">
                        ${day.formatted_date}
                    </td>
                    <td style="padding: 12px 14px; text-align: center;">
                        <span style="background: rgba(59, 130, 246, 0.1); color: #2563eb; padding: 3px 10px; border-radius: 12px; font-weight: 600; font-size: 0.8rem;">
                            ${day.order_count} order
                        </span>
                    </td>
                    <td style="padding: 12px 14px; text-align: right; color: #475569;">
                        Rp ${formatNumber(day.online_total)}
                    </td>
                    <td style="padding: 12px 14px; text-align: right; color: #475569;">
                        Rp ${formatNumber(day.offline_total)}
                    </td>
                    <td style="padding: 12px 14px; text-align: right; font-weight: 700; color: #0f172a;">
                        Rp ${formatNumber(day.total)}
                    </td>
                    <td style="padding: 12px 14px; text-align: center;">
                        <button type="button" class="btn btn-secondary" style="font-size: 0.75rem; padding: 4px 10px; pointer-events: none;">
                            Lihat
                        </button>
                    </td>
                </tr>
                <tr id="${subId}" class="daily-sub-row" style="display: none; background: #f8fafc;">
                    <td colspan="7" style="padding: 12px 16px 18px 24px;">
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                            <div style="padding: 10px 14px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; font-weight: 600; font-size: 0.82rem; color: #475569; display: flex; justify-content: space-between; align-items: center;">
                                <span>Daftar Transaksi: <strong>${day.formatted_date}</strong> (${day.order_count} order)</span>
                                <span style="color: #059669; font-weight: 700;">Total: Rp ${formatNumber(day.total)}</span>
                            </div>
                            <div style="overflow-x: auto;">
                                <table style="width: 100%; border-collapse: collapse; font-size: 0.82rem; text-align: left;">
                                    <thead>
                                        <tr style="background: #f1f5f9; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 0.78rem; text-transform: uppercase;">
                                            <th style="padding: 8px 12px; width: 65px;">Jam</th>
                                            <th style="padding: 8px 12px; width: 150px;">No. Order</th>
                                            <th style="padding: 8px 12px; width: 180px;">Customer</th>
                                            <th style="padding: 8px 12px;">Layanan</th>
                                            <th style="padding: 8px 12px; width: 90px; text-align: center;">Tipe</th>
                                            <th style="padding: 8px 12px; width: 100px; text-align: center;">Status</th>
                                            <th style="padding: 8px 12px; width: 130px; text-align: right;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${subOrdersHtml}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = rowsHtml;

        // Render tfoot summary
        tfoot.innerHTML = `
            <tr style="color: #0f172a; font-size: 0.9rem;">
                <td colspan="2" style="padding: 14px; font-weight: 700;">TOTAL KESELURUHAN</td>
                <td style="padding: 14px; text-align: center;">
                    <span style="background: #2563eb; color: #ffffff; padding: 4px 10px; border-radius: 12px; font-weight: 700; font-size: 0.82rem;">
                        ${grandOrders} order
                    </span>
                </td>
                <td style="padding: 14px; text-align: right; color: #475569;">
                    Rp ${formatNumber(grandOnline)}
                </td>
                <td style="padding: 14px; text-align: right; color: #475569;">
                    Rp ${formatNumber(grandOffline)}
                </td>
                <td style="padding: 14px; text-align: right; font-weight: 800; color: #059669; font-size: 1.05rem;">
                    Rp ${formatNumber(grandTotal)}
                </td>
                <td></td>
            </tr>
        `;
    }

    function toggleDayRow(idx) {
        const subRow = document.getElementById('sub-row-' + idx);
        const icon = document.getElementById('icon-' + idx);
        if (!subRow) return;

        if (subRow.style.display === 'none') {
            subRow.style.display = 'table-row';
            if (icon) icon.style.transform = 'rotate(90deg)';
        } else {
            subRow.style.display = 'none';
            if (icon) icon.style.transform = 'rotate(0deg)';
        }
    }

    function toggleAllDays(expand) {
        const subRows = document.querySelectorAll('.daily-sub-row');
        subRows.forEach((row, idx) => {
            row.style.display = expand ? 'table-row' : 'none';
            const icon = document.getElementById('icon-' + idx);
            if (icon) icon.style.transform = expand ? 'rotate(90deg)' : 'rotate(0deg)';
        });
    }

    function exportExcel() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const branch = document.getElementById('branchFilter') ? document.getElementById('branchFilter').value : '';
        window.location.href = `/admin/reports/export-excel?start_date=${startDate}&end_date=${endDate}&branch_id=${branch}`;
    }
    
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }
</script>

<?= $this->endSection() ?>
