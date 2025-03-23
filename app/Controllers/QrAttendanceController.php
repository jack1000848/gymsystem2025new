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
public function scan()
{
    $customerID = $this->request->getPost('CustomerID');

    if (!$customerID) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'No QR code data received!'
        ]);
    }

    $model = new AttendanceLogModel();

    // Check if the customer exists in the database
    $customer = $model->where('CustomerID', $customerID)->first();

    if (!$customer) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Invalid QR Code or Customer not found!'
        ]);
    }

    // If customer found, decide if check-in or check-out (simple example)
    // Sample logic: if no CheckIn, then CheckIn. If CheckIn exists, then CheckOut.
    if (empty($customer['CheckIn'])) {
        // Perform check-in
        $model->update($customerID, ['CheckIn' => date('Y-m-d H:i:s')]);

        return $this->response->setJSON([
            'status' => 'success',
            'action' => 'checkin',
            'CustomerID' => $customerID,
            'FullName' => $customer['FullName'],
            'ExpirationDate' => $customer['ExpirationDate'] ?? 'N/A'
        ]);
    } else if (empty($customer['CheckOut'])) {
        // Perform check-out
        $model->update($customerID, ['CheckOut' => date('Y-m-d H:i:s')]);

        return $this->response->setJSON([
            'status' => 'success',
            'action' => 'checkout',
            'CustomerID' => $customerID,
            'FullName' => $customer['FullName'],
            'ExpirationDate' => $customer['ExpirationDate'] ?? 'N/A'
        ]);
    } else {
        // Already checked out, cannot scan again
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Customer already checked in and out today!'
        ]);
    }
}
    
}
