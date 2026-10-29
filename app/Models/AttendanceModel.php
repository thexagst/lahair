<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table            = 'attendances';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['user_id', 'date', 'check_in', 'check_out', 'status', 'notes', 'created_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get attendance for a user on a specific date
     */
    public function getAttendance($userId, $date)
    {
        return $this->where('user_id', $userId)
                    ->where('date', $date)
                    ->first();
    }

    /**
     * Get monthly report
     */
    public function getMonthlyReport($month, $year)
    {
        return $this->select('attendances.*, admins.username as worker_name')
                    ->join('admins', 'admins.id = attendances.user_id')
                    ->where('MONTH(date)', $month)
                    ->where('YEAR(date)', $year)
                    ->orderBy('date', 'ASC')
                    ->findAll();
    }
}
