<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\QrAttendanceModel;
use App\Models\CustomerPlanModel;
use App\Models\QrAttendanceLogModel;

class QrAttendanceController extends Controller
{
    protected $session; // Declare session variable

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
    
        // Get the last attendance record
        $lastRecord = $qrAttendanceModel->where('CustomerID', $id)->orderBy('InDate', 'DESC')->first();
    
        $currentTime = date('Y-m-d H:i:s');
    
        if ($lastRecord && $lastRecord['CheckOut'] == null) {
            // If the last record exists and CheckOut is NULL, update with CheckOut time
            $qrAttendanceModel->update($lastRecord['AttendanceID'], ['CheckOut' => $currentTime]);
            return $this->response->setJSON([
                'status' => 'check-out',
                'customer' => $customer,
                'message' => 'Checked out successfully.'
            ]);
        } else {
            // Otherwise, create a new Check-In record
            $qrAttendanceModel->insert([
                'CustomerID' => $id,
                'InDate' => $currentTime,
                'CheckOut' => null
            ]);
            return $this->response->setJSON([
                'status' => 'check-in',
                'customer' => $customer,
                'message' => 'Checked in successfully.'
            ]);
        }
    }


    public function viewqrcode()
    {

       // if (!$this->session->has('logged_in')) {
       //     return redirect()->to('/joinus')->with('error', 'Please log in first.');
       // }
    
        $scanModel = new QrAttendanceModel();
        $data['scan-qr'] = $scanModel->findAll();
        return view('/qrAttendance/qrAttendance', $data);
        ////c
    }

    
}
