<?php

namespace App\Models;

use CodeIgniter\Model;

class HolidayModel extends Model
{
    protected $table            = 'holidays';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['date', 'description'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at'; // Allow updated_at handling if needed

    /**
     * Check if a date is a holiday
     */
    public function isHoliday($date)
    {
        return $this->where('date', $date)->first();
    }
}
