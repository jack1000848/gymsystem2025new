<?php

namespace App\Models;

use CodeIgniter\Model;

class CoachAttendanceModel extends Model
{
    protected $table            = 'coachattendance';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['id','CoachID', 'CheckInTime', 'CheckOutTime'];
}