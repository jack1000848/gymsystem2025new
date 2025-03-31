<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\QrAttendanceModel;
use App\Models\eCoachAttendanceModel; // Correct model name
use App\Models\CustomerPlanModel;
use App\Models\QrAttendanceLogModel;
use App\Models\CoachModel;


class QrAttendanceController extends Controller
{
    protected $session;
    protected $coachModel;
    protected $attendanceModel;

    public function __construct()
    {
        $this->coachModel = new CoachModel();
     ///   $this->attendanceModel = new CoachAttendanceModel();

     helper(['url', 'form']); // Load helpers

     // Initialize models
     $this->coachModel = new CoachModel();
     $this->attendanceModel = new eCoachAttendanceModel(); // Correct model instance

     // Debugging check
     if (!$this->attendanceModel) {
         die("Error: eCoachAttendanceModel failed to load.");
     }
    }
    public function save($id)
{
    $customerPlanModel = new CustomerPlanModel();
    $qrAttendanceModel = new QrAttendanceModel();

    // Validate ID
    if (empty($id) || !is_numeric($id)) {
        return $this->response->setJSON(['error' => 'Invalid Customer ID'])->setStatusCode(400);
    }

    ///// Check if customer exists
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

    public function viewqrcodecoach()
    {
        $scanModel = new QrAttendanceModel();
        $data['coachattendanceqr'] = $scanModel->findAll();
        return view('/clients1crud/qrattendancecoach', $data);

    }
    public function save1($coachID)
{
    // Get current timestamp and today's date
    $timestamp = date('Y-m-d H:i:s');
    $today = date('Y-m-d');

    // Find the coach details
    $coach = $this->coachModel->where('CoachID', $coachID)->first();

    if (!$coach) {
        return $this->response->setJSON(['error' => 'Coach not found'])->setStatusCode(404);
    }

    // Check if attendanceModel is properly initialized
    if (!$this->attendanceModel) {
        return $this->response->setJSON(['error' => 'Attendance model not loaded'])->setStatusCode(500);
    }

    // Check if the coach has already checked in today
    $existingAttendance = $this->attendanceModel
        ->where('CoachID', $coachID)
        ->where('DATE(CheckInTime)', $today) // Ensure it's for the same day
        ->first();

    if ($existingAttendance) {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'You have already checked in today.',
            'coach'   => [
                'CoachID'   => $coach['CoachID'],
                'FullName'  => $coach['Firstname'] . ' ' . $coach['Lastname']
            ]
        ]);
    }

    // If no check-in for today, proceed with Check-In
    $this->attendanceModel->insert([
        'CoachID'     => $coachID,
        'CheckInTime' => $timestamp,
        'CheckOutTime' => null
    ]);

    return $this->response->setJSON([
        'status'  => 'check-in',
        'message' => 'Check-in successful',
        'coach'   => [
            'CoachID'   => $coach['CoachID'],
            'FullName'  => $coach['Firstname'] . ' ' . $coach['Lastname']
        ]
    ]);
}


}
