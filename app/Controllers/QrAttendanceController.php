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

        date_default_timezone_set('Asia/Manila'); // Optional: Set your timezone
        $today = date('Y-m-d');

        // Validate ID
        if (empty($id) || !is_numeric($id)) {
            return $this->response->setJSON(['error' => 'Invalid Customer ID'])->setStatusCode(400);
        }

        // Check if customer exists
        $customer = $customerPlanModel->find($id);
        if (!$customer) {
            return $this->response->setJSON(['error' => 'Customer not found'])->setStatusCode(404);
        }

        // Check if already checked-in today
        $attendance = $qrAttendanceModel
            ->where('CustomerID', $id)
            ->where('DATE(CheckIn)', $today)
            ->first();

        if (!$attendance) {
            // ✅ Perform Check-In
            $qrAttendanceModel->insert([
                'CustomerID'   => $id,
                'CheckIn' => date('Y-m-d H:i:s')
            ]);
            return $this->response->setJSON([
                'success' => 'Checked In Successfully',
                'status' => 'check-in',
                'customer' => $customer
            ]);
        } else {
            // ✅ Check if already checked-out
            if ($attendance['CheckOut']) {
                return $this->response->setJSON(['error' => 'Already completed check-out today.'])->setStatusCode(400);
            }

            // ✅ Check if 20 minutes have passed since check-in
            $checkInTime = strtotime($attendance['CheckIn']);
            $currentTime = time();

            if (($currentTime - $checkInTime) < (20 * 60)) {
                $remainingMinutes = 20 - floor(($currentTime - $checkInTime) / 60);
                return $this->response->setJSON([
                    'error' => "You can check-out after {$remainingMinutes} minute(s)."
                ])->setStatusCode(400);
            }

            // ✅ Perform Check-Out
            $qrAttendanceModel->update($attendance['ID'], [
                'CheckOut' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON([
                'success' => 'Checked Out Successfully',
                'status' => 'check-out',
                'customer' => $customer
            ]);
        }
    }

    public function viewqrcode()
    {
        $scanModel = new QrAttendanceModel();
        $data['scan-qr'] = $scanModel->findAll();
        return view('/qrAttendance/qrAttendance', $data);
        ////c
    }

    
}
