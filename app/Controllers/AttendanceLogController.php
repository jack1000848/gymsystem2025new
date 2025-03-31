<?php

namespace App\Controllers;

use App\Models\AttendanceLogModel;
use App\Models\eCoachAttendanceModel; 
use CodeIgniter\Controller;

class AttendanceLogController extends Controller
{
    protected $session; // Declare session variable

    public function __construct()
    {
        $this->session = session(); // Initialize session
    }
    public function checkin()
    {
        if (!$this->session->has('logged_in')) {
            return redirect()->to('/joinus')->with('error', 'Please log in first.');
        }
        $model = new AttendanceLogModel();
        $data['customers'] = $model->getCustomers();

        return view('/qrAttendance/attendancelog', $data);
    }
 // Update the CheckOut time
 public function checkout()
 {
     $customerID = $this->request->getPost('CustomerID');

     if ($customerID) {
         $model = new AttendanceLogModel();

         // Update CheckOut time as the current timestamp
         $model->update($customerID, [
             'CheckOut' => date('Y-m-d H:i:s')
         ]);

         return redirect()->to('/qrAttendance/attendancelog')->with('success', 'Customer Checked Out Successfully');
     } else {
         return redirect()->to('/qrAttendance/attendancelog')->with('error', 'Invalid Customer ID');
     }
 }
 // Unified QR Scan Handler - Check-In or Check-Out based on status
 
/////////////// this is the coach attendance log
public function coachattendance()
{
    if (!$this->session->has('logged_in')) {
        return redirect()->to('/joinus')->with('error', 'Please log in first.');
    }
    $model = new AttendanceLogModel();
    $data['coachatt'] = $model->getCustomers();

    return view('/clients1crud/coachattendance', $data);
    
}
 public function coachcheckout()
 {
     $coachID = $this->request->getPost('CoachID');

     if ($coachID) {
         $model = new AttendanceLogModel();

         // Update CheckOut time as the current timestamp
         $model->update($coachID, [
             'CheckOut' => date('Y-m-d H:i:s')
         ]);

         return redirect()->to('/clients1crud/coachattendance')->with('success', 'Customer Checked Out Successfully');
     } else {
         return redirect()->to('/clients1crud/coachattendance')->with('error', 'Invalid Customer ID');
     }
 }
}