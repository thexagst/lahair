<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AttendanceModel;
use App\Models\HolidayModel;
use App\Models\AdminModel;

class Attendance extends BaseController
{
    protected $attendanceModel;
    protected $holidayModel;
    protected $adminModel;
    
    public function __construct()
    {
        $this->attendanceModel = new AttendanceModel();
        $this->holidayModel = new HolidayModel();
        $this->adminModel = new AdminModel();
    }

    /**
     * Admin Dashboard: QR Code & Today's Attendance
     */
    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        // Generate Daily Token
        $today = date('Y-m-d');
        $token = $this->generateDailyToken($today);
        
        // Get Today's Attendance
        $todaysAttendance = $this->attendanceModel->select('attendances.*, admins.username')
                                                 ->join('admins', 'admins.id = attendances.user_id')
                                                 ->where('date', $today)
                                                 ->findAll();

        $data = [
            'title' => 'Absensi Harian',
            'token' => $token,
            'today' => $today,
            'attendance_list' => $todaysAttendance
        ];

        return view('admin/attendance/index', $data);
    }

    /**
     * Worker Scan Page
     */
    public function scan()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        $data = [
            'title' => 'Scan Absensi',
        ];
        
        return view('admin/attendance/scan', $data);
    }

    /**
     * Process Scan (AJAX)
     */
    public function processScan()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Silakan login terlebih dahulu.']);
        }

        $scannedToken = $this->request->getPost('token');
        $today = date('Y-m-d');
        $validToken = $this->generateDailyToken($today);

        if ($scannedToken !== $validToken) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'QR Code tidak valid atau sudah kadaluarsa.']);
        }

        $userId = session()->get('admin_id');
        
        // Check if already checked in today
        $existing = $this->attendanceModel->where('user_id', $userId)
                                          ->where('date', $today)
                                          ->first();

        $timeNow = date('H:i:s');
        
        if ($existing) {
            // Already checked in, process as Check Out?
            // User requested check-in/out logic.
            // Let's simple toggle: if checked in but no check out -> check out.
            // If already checked out -> msg "Already done".
            
            if ($existing['check_out'] == null) {
                // Check Out
                $this->attendanceModel->update($existing['id'], ['check_out' => $timeNow]);
                return $this->response->setJSON(['status' => 'success', 'message' => 'Berhasil Check-Out pukul ' . $timeNow]);
            } else {
                return $this->response->setJSON(['status' => 'info', 'message' => 'Anda sudah menyelesaikan absensi hari ini via check-out pada ' . $existing['check_out']]);
            }
        } else {
            // Check In
            // Determine status (Late?)
            // Get Shop Settings for start time
            $configPath = WRITEPATH . 'shop_settings.json';
            $startTime = '09:00';
            if (file_exists($configPath)) {
                $settings = json_decode(file_get_contents($configPath), true);
                if(isset($settings['start_time'])) $startTime = $settings['start_time'];
            }
            
            $status = 'hadir';
            if ($timeNow > $startTime) {
                $status = 'telat';
            }
            
            $this->attendanceModel->insert([
                'user_id' => $userId,
                'date' => $today,
                'check_in' => $timeNow,
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            $msg = 'Berhasil Check-In pukul ' . $timeNow;
            if ($status == 'telat') $msg .= ' (Telat)';
            
            return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
        }
    }

    /**
     * Reports
     */
    public function report()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/admin/login');
        }

        $month = $this->request->getGet('month') ?? date('m');
        $year = $this->request->getGet('year') ?? date('Y');

        $reports = $this->attendanceModel->getMonthlyReport($month, $year);
        
        // Group by User
        $groupedReports = [];
        foreach($reports as $row) {
            $groupedReports[$row['user_id']]['name'] = $row['worker_name'];
            $groupedReports[$row['user_id']]['days'][$row['date']] = $row;
        }

        // Get Holidays
        $holidays = $this->holidayModel->where('MONTH(date)', $month)
                                       ->where('YEAR(date)', $year)
                                       ->findAll();
                                       
        // Map Holidays
        $holidayMap = [];
        foreach($holidays as $h) {
            $holidayMap[$h['date']] = $h['description'];
        }

        $data = [
            'title' => 'Laporan Absensi',
            'reports' => $groupedReports,
            'holidays' => $holidayMap,
            'month' => $month,
            'year' => $year
        ];

        return view('admin/attendance/report', $data);
    }
    
    /**
     * Holidays Management (View & Add/Delete)
     */
    public function holidays()
    {
         if (session()->get('role') !== 'admin') {
            return redirect()->to('/admin/login');
        }
        
        $holidays = $this->holidayModel->orderBy('date', 'DESC')->findAll();
        
        $data = [
            'title' => 'Manajemen Hari Libur',
            'holidays' => $holidays
        ];
        
        return view('admin/attendance/holidays', $data);
    }
    
    public function storeHoliday()
    {
        if (session()->get('role') !== 'admin') return redirect()->to('/admin/login');
        
        $this->holidayModel->save([
            'date' => $this->request->getPost('date'),
            'description' => $this->request->getPost('description')
        ]);
        
        return redirect()->back()->with('success', 'Hari libur berhasil ditambahkan.');
    }
    
    public function deleteHoliday($id)
    {
        if (session()->get('role') !== 'admin') return redirect()->to('/admin/login');
        
        $this->holidayModel->delete($id);
        return redirect()->back()->with('success', 'Hari libur berhasil dihapus.');
    }

    /**
     * Hash Generator for Daily Token
     */
    private function generateDailyToken($date)
    {
        // Simple hash logic: MD5(Date + Salt)
        // Ensure this salt is consistent or stored in env. Using hardcoded for simplicity now.
        $salt = 'LaHairStudioApps2026';
        return md5($date . $salt);
    }
}
