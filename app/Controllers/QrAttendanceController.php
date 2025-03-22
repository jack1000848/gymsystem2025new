<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\QrAttendanceModel;
use App\Models\CustomerPlanModel;

class QrAttendanceController extends Controller
{
    public function save($id)
    {
        $customerPlanModel = new CustomerPlanModel();
        $qrAttendanceModel = new QrAttendanceModel();
    
        if (empty($id) || !is_numeric($id)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid Customer ID']);
        }
    
        $customer = $customerPlanModel->find($id);
        if (!$customer) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Customer not found']);
        }
    
        $data = ['CustomerID' => $id];
        if ($qrAttendanceModel->insert($data)) {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => 'Attendance recorded successfully',
                'customer' => $customer
            ]);
        }
    
        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to record attendance']);
    }
    
    public function list()
    {
        $scanModel = new QrAttendanceModel();
        $data['scanned_qr_codes'] = $scanModel->findAll();
        return view('/qrAttendance/qrAttendance', $data);
    }

    public function delete($id)
{
    $qrAttendanceModel = new QrAttendanceModel();

    if ($qrAttendanceModel->delete($id)) {
        return redirect()->to('/attendance')->with('success', 'Attendance record deleted successfully.');
    } else {
        return redirect()->to('/attendance')->with('error', 'Failed to delete attendance record.');
    }
}
    
}
