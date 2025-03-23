<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\QrAttendanceModel;
use App\Models\CustomerPlanModel;
use App\Models\QrAttendanceLogModel;

class QrAttendanceController extends Controller
{
    public function save()
 {
     $customerID = $this->request->getPost('CustomerID');
     if (!$customerID) {
         return $this->response->setJSON(['status' => 'error', 'message' => 'Customer ID missing']);
     }

     $model = new AttendanceLogModel();

     // Check if there's an existing check-in without a check-out
     $existing = $model->where('CustomerID', $CustomerID)
                       ->where('CheckOut IS NULL')
                       ->first();

     if ($existing) {
         // Perform Check-Out
         $model->update($CustomerID, ['CheckOut' => date('Y-m-d H:i:s')]);
         $action = 'checkout';
     } else {
         // Perform Check-In
         $model->update($CustomerID, ['CheckIn' => date('Y-m-d H:i:s'), 'CheckOut' => null]);
         $action = 'checkin';
     }

     return $this->response->setJSON([
        'status' => 'error',
        'message' => 'Invalid QR Code or Customer not found!'
     ]);
 }

    public function list()
    {
        $scanModel = new QrAttendanceModel();
        $data['scan-qr'] = $scanModel->findAll();
        return view('/qrAttendance/qrAttendance', $data);
        ////c
    }

    
}
