<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\QrAttendanceModel;
use App\Models\CustomerPlanModel;

class QrAttendanceController extends Controller
{
    public function save()
    {
        $qrCodeData = $this->request->getPost('CustomerID');
        $attendanceModel = new QrAttendanceModel();
        $customerModel = new CustomerPlanModel();
    
        if (!$qrCodeData) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No QR data received'
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
    
        date_default_timezone_set('Asia/Manila');
        $currentDate = date('Y-m-d');
        $currentTime = date('Y-m-d H:i:s');
    
        // Check existing attendance
        $existingAttendance = $attendanceModel
            ->where('CustomerID', $customer['CustomerID'])
            ->where('DATE(InDate)', $currentDate)
            ->first();
    
        if (!$existingAttendance) {
            // Check-in
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
            // Already checked in, check out logic
            if ($existingAttendance['CheckOut']) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Already checked out today',
                    'customer' => $customer
                ]);
            }
    
            $checkInTime = strtotime($existingAttendance['InDate']);
            $diffInMinutes = (strtotime($currentTime) - $checkInTime) / 60;
    
            if ($diffInMinutes < 30) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Please wait 30 minutes before checking out',
                    'customer' => $customer
                ]);
            }
    
            // Checkout
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
        $data['scan-qr'] = $scanModel->findAll();
        return view('/qrAttendance/qrAttendance', $data);
    }
    
}
