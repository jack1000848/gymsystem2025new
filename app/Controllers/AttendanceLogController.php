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
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'No QR code data received!'
        ]);
    }

    $model = new AttendanceLogModel();

    // Check if the customer exists in the database
    $customer = $model->where('CustomerID', $customerID)->first();

    if (!$customer) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Invalid QR Code or Customer not found!'
        ]);
    }

    // If customer found, decide if check-in or check-out (simple example)
    // Sample logic: if no CheckIn, then CheckIn. If CheckIn exists, then CheckOut.
    if (empty($customer['CheckIn'])) {
        // Perform check-in
        $model->update($customerID, ['CheckIn' => date('Y-m-d H:i:s')]);

        return $this->response->setJSON([
            'status' => 'success',
            'action' => 'checkin',
            'CustomerID' => $customerID,
            'FullName' => $customer['FullName'],
            'ExpirationDate' => $customer['ExpirationDate'] ?? 'N/A'
        ]);
    } else if (empty($customer['CheckOut'])) {
        // Perform check-out
        $model->update($customerID, ['CheckOut' => date('Y-m-d H:i:s')]);

        return $this->response->setJSON([
            'status' => 'success',
            'action' => 'checkout',
            'CustomerID' => $customerID,
            'FullName' => $customer['FullName'],
            'ExpirationDate' => $customer['ExpirationDate'] ?? 'N/A'
        ]);
    } else {
        // Already checked out, cannot scan again
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Customer already checked in and out today!'
        ]);
    }
}

    
}
