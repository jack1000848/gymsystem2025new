<?php

namespace App\Models;

use CodeIgniter\Model;

class QrAttendanceModel extends Model
{
    protected $table = 'cattendance';
    protected $primaryKey = 'AttendanceID';
    protected $allowedFields = ['CustomerID', 'InDate'];

    public function insertAttendance($data)
    {
        return $this->insert($data);
    }

    public function deleteAttendance($id)
{
    return $this->delete($id);
}

}
