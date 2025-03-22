<?php

namespace App\Controllers;

use App\Models\AttendanceLogModel;
use CodeIgniter\Controller;

class AttendanceLogController extends Controller
{
    public function checkin()
    {
        $model = new AttendanceLogModel();
        $data['customers'] = $model->getCustomers();

        return view('/qrAttendance/attendancelog', $data);
    }

    public function checkout($customerId)
{
    $model = new AttendanceLogModel();

    // Update CheckOut time
    $model->set('CheckOut', date('Y-m-d H:i:s'))
          ->where('CustomerID', $customerId)
          ->update();

    return redirect()->to('/attendance')->with('success', 'Customer checked out successfully');
}
}
