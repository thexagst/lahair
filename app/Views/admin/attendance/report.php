<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="header" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 class="page-title">Laporan Absensi</h1>
    <form method="get" class="d-flex gap-2">
        <select name="month" class="form-select">
            <?php 
            $months = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            foreach($months as $k => $v): ?>
                <option value="<?= $k ?>" <?= $k == $month ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
        <select name="year" class="form-select">
            <?php for($y = date('Y'); $y >= 2024; $y--): ?>
                <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
</div>

<div class="card" style="overflow-x: auto;">
    <?php
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    ?>
    <table class="table table-bordered table-striped" style="font-size: 0.8rem; white-space: nowrap;">
        <thead>
            <tr>
                <th style="position: sticky; left: 0; background: white; z-index: 10;">Nama Worker</th>
                <?php for($d=1; $d<=$daysInMonth; $d++): 
                    $date = sprintf('%04d-%02d-%02d', $year, $month, $d);
                    $dayName = date('D', strtotime($date));
                    $isWeekend = ($dayName == 'Sat' || $dayName == 'Sun');
                    $isHoliday = isset($holidays[$date]);
                    
                    $thStyle = '';
                    if ($isWeekend) $thStyle = 'background-color: #f8d7da; color: #721c24;';
                    if ($isHoliday) $thStyle = 'background-color: #f8d7da; color: #721c24;';
                ?>
                    <th class="text-center" style="min-width: 30px; <?= $thStyle ?>">
                        <?= $d ?><br>
                        <small><?= substr($dayName, 0, 1) ?></small>
                    </th>
                <?php endfor; ?>
                <th class="text-center">Hadir</th>
                <th class="text-center">Telat</th>
                <th class="text-center">Alpha</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reports as $userId => $userReport): 
                $stats = ['hadir' => 0, 'telat' => 0, 'alpha' => 0, 'izin' => 0, 'sakit' => 0, 'libur' => 0];
            ?>
            <tr>
                <td style="position: sticky; left: 0; background: white; z-index: 10; font-weight: 600;">
                    <?= esc($userReport['name']) ?>
                </td>
                
                <?php for($d=1; $d<=$daysInMonth; $d++): 
                    $date = sprintf('%04d-%02d-%02d', $year, $month, $d);
                    $record = $userReport['days'][$date] ?? null;
                    $status = $record ? $record['status'] : '-';
                    $isHoliday = isset($holidays[$date]);
                    
                    if ($record) {
                        if (isset($stats[$status])) $stats[$status]++;
                    } else {
                        // If no record, usually means Alpha unless it's future date or holiday
                        if ($date <= date('Y-m-d') && !$isHoliday) {
                             // Only count alpha if it's passed or today
                             // $stats['alpha']++; 
                             // Logic to determine alpha vs just not generated yet?
                             // Let's just show '-' if no record
                        }
                    }
                    
                    $cellClass = '';
                    $cellContent = '-';
                    
                    if ($isHoliday) {
                        $cellClass = 'bg-light';
                        $cellContent = '<span title="'.esc($holidays[$date]).'" style="color:red">L</span>';
                    }
                    
                    if ($record) {
                        if ($status == 'hadir') {
                            $cellContent = '<span class="text-success">✔</span>';
                        } elseif ($status == 'telat') {
                            $cellContent = '<span class="text-warning" title="'.substr($record['check_in'],0,5).'">T</span>';
                        } elseif ($status == 'alpha') {
                            $cellContent = '<span class="text-danger">A</span>';
                        } elseif ($status == 'izin') {
                            $cellContent = '<span class="text-info">I</span>';
                        } elseif ($status == 'sakit') {
                            $cellContent = '<span class="text-info">S</span>';
                        }
                    }
                ?>
                    <td class="text-center <?= $cellClass ?>" style="padding: 8px 4px;">
                        <?= $cellContent ?>
                    </td>
                <?php endfor; ?>
                
                <td class="text-center font-weight-bold"><?= $stats['hadir'] ?></td>
                <td class="text-center font-weight-bold"><?= $stats['telat'] ?></td>
                <td class="text-center font-weight-bold"><?= $stats['alpha'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div style="margin-top: 15px;">
        <strong>Keterangan:</strong>
        <ul style="list-style: none; padding: 0; display: flex; gap: 15px; font-size: 0.85rem;">
            <li><span class="text-success">✔</span> Hadir</li>
            <li><span class="text-warning">T</span> Telat</li>
            <li><span class="text-danger">A</span> Alpha</li>
            <li><span class="text-info">I</span> Izin</li>
            <li><span class="text-info">S</span> Sakit</li>
            <li><span style="color:red">L</span> Libur</li>
        </ul>
    </div>
</div>

<?= $this->endSection() ?>
