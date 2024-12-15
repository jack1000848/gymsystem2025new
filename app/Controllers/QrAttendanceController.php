<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\QrAttendanceModel;

class QrAttendanceController extends Controller
{
    public function scanQrCode()
    {
        $scannedData = $this->request->getPost('scanned_data');

        // Process the scanned data, e.g., save it to the database
        $QrAttendanceModel = new QrAttendanceModel();
        $QrAttendanceModel->insertAttendance(['data' => $scannedData]);

        // Redirect or display a success message
        return redirect()->to('/dashboard')->with('success', 'Attendance marked successfully!');
    }
}
