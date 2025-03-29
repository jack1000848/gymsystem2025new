<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\QrAttendanceModel;
use App\Models\CustomerPlanModel;
use App\Models\QrAttendanceLogModel;

class QrAttendanceController extends Controller
{
    protected $session; // Declare session variable

    public function __construct()
    {
        $this->session = session(); // Initialize session
    }

    public function save($id)
    {
        $customerPlanModel = new CustomerPlanModel();
        $qrAttendanceModel = new QrAttendanceModel();
    
        date_default_timezone_set('Asia/Manila');
        $today = date('Y-m-d');
    
        if (empty($id) || !is_numeric($id)) {
            return $this->response->setJSON(['error' => 'Invalid Customer ID'])->setStatusCode(400);
        }
    
        // ✅ Check if customer exists
        $customer = $customerPlanModel->find($id);
        if (!$customer) {
            return $this->response->setJSON(['error' => 'Customer not found'])->setStatusCode(404);
        }
    
        // ✅ Find existing attendance record for today
        $attendance = $qrAttendanceModel
            ->where('CustomerID', $id)
            ->where('DATE(InDate)', $today)
            ->first();
    
        if (!$attendance) {
            // ✅ First tap → Check-in
            $qrAttendanceModel->insert([
                'CustomerID' => $id,
                'InDate'     => date('Y-m-d H:i:s')
            ]);
    
            log_message('info', "Customer ID {$id} checked in successfully.");
    
            return $this->response->setJSON([
                'success'  => 'Checked In Successfully',
                'status'   => 'check-in',
                'customer' => $customer
            ]);
        } else {
            // ✅ Already checked-in, now allow immediate check-out
            if ($attendance['CheckOut']) {
                return $this->response->setJSON([
                    'error' => 'Already checked out today.'
                ])->setStatusCode(400);
            }
    
            // ✅ Perform Check-Out
            $updateStatus = $qrAttendanceModel->update($attendance['AttendanceID'], [
                'CheckOut' => date('Y-m-d H:i:s')
            ]);
    
            if (!$updateStatus) {
                log_message('error', "Failed to update check-out for Customer ID {$id}.");
                return $this->response->setJSON(['error' => 'Failed to check-out. Please try again.'])->setStatusCode(500);
            }
    
            log_message('info', "Customer ID {$id} checked out successfully.");
    
            return $this->response->setJSON([
                'success'  => 'Checked Out Successfully',
                'status'   => 'check-out',
                'customer' => $customer
            ]);
        }
    }
    



    public function viewqrcode()
    {

        if (!$this->session->has('logged_in')) {
            return redirect()->to('/joinus')->with('error', 'Please log in first.');
        }
    
        $scanModel = new QrAttendanceModel();
        $data['scan-qr'] = $scanModel->findAll();
        return view('/qrAttendance/qrAttendance', $data);
        ////c
    }

    
}
