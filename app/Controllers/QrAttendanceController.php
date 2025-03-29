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

    $customer = $customerPlanModel->find($id);
    if (!$customer) {
        return $this->response->setJSON(['error' => 'Customer not found'])->setStatusCode(404);
    }

    $attendance = $qrAttendanceModel
        ->where('CustomerID', $id)
        ->where('DATE(InDate)', $today)
        ->first();

    if (!$attendance) {
        // ✅ First tap - Check-in
        $qrAttendanceModel->insert([
            'CustomerID' => $id,
            'InDate'     => date('Y-m-d H:i:s')
        ]);
        return $this->response->setJSON([
            'success'  => 'Checked In Successfully',
            'status'   => 'check-in',
            'customer' => $customer
        ]);
    } else {
        // ✅ Already checked-in, now validate check-out
        if ($attendance['CheckOut']) {
            return $this->response->setJSON(['error' => 'Already completed check-in today.'])->setStatusCode(400);
        }

        $InDateTime = strtotime($attendance['InDate']);
        $currentTime = time();

        if (($currentTime - $InDateTime) < (1 * 60)) {
            $remainingMinutes = 1 - floor(($currentTime - $InDateTime) / 60);
            return $this->response->setJSON([
                'error' => "You can check-out after {$remainingMinutes} minute(s)."
            ])->setStatusCode(400);
        }

        // ✅ Perform Check-Out (Fixed the primary key reference)
        $qrAttendanceModel->update($attendance['AttendanceID'], [
            'CheckOut' => date('Y-m-d H:i:s')
        ]);

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
