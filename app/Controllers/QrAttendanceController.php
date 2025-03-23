<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\QrAttendanceModel;
use App\Models\CustomerPlanModel;

class QrAttendanceController extends Controller
{
    public function save($qrCodeData)
    {
        $attendanceModel = new QrAttendanceModel();
        $customerModel = new CustomerPlanModel();


        // Insert attendance record
        $data = ['CustomerID' => $id];
        if ($qrAttendanceModel->insert($data)) {
            return $this->response->setJSON([
                'success' => 'Attendance recorded successfully',
                'customer' => $customer
            ]);
        }
        // Find customer by QR Code Data (assuming QR contains CustomerID)
        $customer = $customerModel->where('CustomerID', $qrCodeData)->first();

        if (!$customer) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Customer not found'
            ]);
        }

        date_default_timezone_set('Asia/Manila'); // Adjust timezone if needed
        $currentDate = date('Y-m-d');
        $currentTime = date('Y-m-d H:i:s');

        // Check if there's already a check-in for today
        $existingAttendance = $attendanceModel
            ->where('CustomerID', $customer['CustomerID'])
            ->where('DATE(InDate)', $currentDate)
            ->first();

        if (!$existingAttendance) {
            // No check-in today, perform check-in
            $attendanceModel->insert([
                'CustomerID' => $customer['CustomerID'],
                'InDate' => $currentTime,
                'CheckOut' => null
            ]);

            return $this->response->setJSON([
                'status' => 'success',
                'type' => 'checkin',
                'message' => 'Check-in successful',
                'customer' => $customer
            ]);
        } else {
            // Already checked in, check if can checkout
            if ($existingAttendance['CheckOut']) {
                // Already checked out today
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Already checked out today',
                    'customer' => $customer
                ]);
            }

            // Check time difference for 30 minutes rule
            $checkInTime = strtotime($existingAttendance['InDate']);
            $diffInMinutes = (strtotime($currentTime) - $checkInTime) / 60;

            if ($diffInMinutes < 30) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Please wait 30 minutes before checking out',
                    'customer' => $customer
                ]);
            }

            // Perform checkout
            $attendanceModel->update($existingAttendance['AttendanceID'], [
                'CheckOut' => $currentTime
            ]);

            return $this->response->setJSON([
                'status' => 'success',
                'type' => 'checkout',
                'message' => 'Check-out successful',
                'customer' => $customer
            ]);
        }
    }
    public function list()
    {
        $scanModel = new QrAttendanceModel();
        $data['scanned_qr_codes'] = $scanModel->findAll();
        return view('/qrAttendance/qrAttendance', $data);
    }
    
}
