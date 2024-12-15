<?php

namespace App\Models;

use CodeIgniter\Model;

class QrAttendanceModel extends Model
{
    protected $table = 'tbl_attendance';
    protected $primaryKey = 'id';
    protected $allowedFields = ['data', 'scanned_at'];

    public function insertAttendance($data)
    {
        return $this->insert($data);
    }
}
