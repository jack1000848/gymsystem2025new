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
 // Unified QR Scan Handler - Check-In or Check-Out based on status
 public function scan()
 {
     $customerID = $this->request->getPost('CustomerID');
     if (!$customerID) {
         return $this->response->setJSON(['status' => 'error', 'message' => 'Customer ID missing']);
     }

     $model = new AttendanceLogModel();

     // Check if there's an existing check-in without a check-out
     $existing = $model->where('CustomerID', $customerID)
                       ->where('CheckOut IS NULL')
                       ->first();

     if ($existing) {
         // Perform Check-Out
         $model->update($customerID, ['CheckOut' => date('Y-m-d H:i:s')]);
         $action = 'checkout';
     } else {
         // Perform Check-In
         $model->update($customerID, ['CheckIn' => date('Y-m-d H:i:s'), 'CheckOut' => null]);
         $action = 'checkin';
     }

     return $this->response->setJSON([
         'status' => 'success',
         'action' => $action,
         'CustomerID' => $customerID
     ]);
 }
    
}
