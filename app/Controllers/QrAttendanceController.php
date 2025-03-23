<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\QrAttendanceModel;
use App\Models\CustomerPlanModel;
use App\Models\QrAttendanceLogModel;

class QrAttendanceController extends Controller
{
    public function save($id)
    {
        $customerPlanModel = new CustomerPlanModel();
        $qrAttendanceModel = new QrAttendanceModel();
    
        // Validate ID
        if (empty($id) || !is_numeric($id)) {
            return $this->response->setJSON(['error' => 'Invalid Customer ID'])->setStatusCode(400);
        }
    
        // Check if customer exists
        $customer = $customerPlanModel->find($id);
        if (!$customer) {
            return $this->response->setJSON(['error' => 'Customer not found'])->setStatusCode(404);
        }
    
        // Insert attendance record
        $data = ['CustomerID' => $id];
        if ($qrAttendanceModel->insert($data)) {
            return $this->response->setJSON([
                'success' => 'Attendance recorded successfully',
                'customer' => $customer
            ]);
        }
    
        // Handle insertion failure
        return $this->response->setJSON(['error' => 'Failed to record attendance'])->setStatusCode(500);
    }


    public function viewqrcode()
    {
        $scanModel = new QrAttendanceModel();
        $data['scan-qr'] = $scanModel->findAll();
        return view('/qrAttendance/qrAttendance', $data);
        ////c
    }

    
}
