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

    // Siguraduhin na tama ang column names
    $customer = $customerPlanModel->where('CustomerID', $id)->first();

    // Validate ID
    if (empty($id) || !is_numeric($id)) {
        return $this->response->setJSON(['error' => 'Invalid Customer ID'])->setStatusCode(400);
    }

    // Check if customer exists
    $customer = $customerPlanModel->find($id);
    if (!$customer) {
        return $this->response->setJSON(['error' => 'Customer not found'])->setStatusCode(404);
    }

    // Get the current date (YYYY-MM-DD) for daily check-in restriction
    $currentDate = date('Y-m-d');
    
    // Check if the user has already checked in today
    $todayRecord = $qrAttendanceModel->where('CustomerID', $id)
        ->where('DATE(InDate)', $currentDate)
        ->first();
            ////12hrbebe
        $currentTime = date('Y-m-d h:i A');
        

    if ($todayRecord) {
        if ($todayRecord['CheckOut'] === null) {
            // If already checked in today but not checked out, update CheckOut
            $qrAttendanceModel->update($todayRecord['AttendanceID'], ['CheckOut' => $currentTime]);
            return $this->response->setJSON([
                'status' => 'check-out',
                'customer' => $customer,
                'message' => 'Checked out successfully.'
            ]);
        } else {
            // If already checked in and checked out today, deny new check-in
            return $this->response->setJSON([
                'error' => 'You have already checked in today.'
            ])->setStatusCode(400);
        }
    } else {
        // If no record for today, allow check-in
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
