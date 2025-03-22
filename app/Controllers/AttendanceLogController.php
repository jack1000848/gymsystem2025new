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
 // Update the CheckOut time
 public function checkout()
 {
     $customerID = $this->request->getPost('CustomerID');

     if ($customerID) {
         $model = new AttendanceLogModel();

         // Update CheckOut time as the current timestamp
         $model->update($customerID, [
             'CheckOut' => date('Y-m-d H:i:s')
         ]);

         return redirect()->to('/qrAttendance/attendancelog')->with('success', 'Customer Checked Out Successfully');
     } else {
         return redirect()->to('/qrAttendance/attendancelog')->with('error', 'Invalid Customer ID');
     }
 }
    
}
