<?php

namespace App\Controllers;

use App\Models\AttendanceLogModel;
use CodeIgniter\Controller;

class AttendanceLogController extends Controller
{
    public function index()
    {
        $model = new AttendanceLogModel();
        $data['customers'] = $model->getCustomers();

        return view('/qrAttendance/attendancelog', $data);
    }
}
