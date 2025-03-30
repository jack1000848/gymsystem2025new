<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\QrAttendanceModel;
use app\Models\eCoachAttendanceModel;
use App\Models\CustomerPlanModel;
use App\Models\QrAttendanceLogModel;
use App\Models\CoachModel;


class QrAttendanceController extends Controller
{
    protected $session; // Declare session variable
    protected $coachModel;
    protected $attendanceModel;

    public function __construct()
    {
        $this->coachModel = new CoachModel();
     ///   $this->attendanceModel = new eCoachAttendanceModel();
     $this->attendanceModel = model(eCoachAttendanceModel::class);
     $this->scheduleModel = model(CoachScheduleModel::class);
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
        $qrCode = $coachID; // Assuming the QR code is the CoachID

        // Validate the QR code (CoachID)
        if (empty($qrCode)) {
            return $this->response->setJSON(['error' => 'Invalid QR Code'])->setStatusCode(400);
        }

        // Load models
        $this->attendanceModel = new eCoachAttendanceModel();
        $this->customerPlanModel = new CustomerPlanModel();
        $this->coachModel = new CoachModel();
        $this->scheduleModel = new CoachScheduleModel();
    {

        $customerPlanModel = new CustomerPlanModel();
       // $AttendanceModel = new eCoachAttendanceModel();
        // Find the coach based on the scanned QR code
        $coach = $this->coachModel->where('CoachID', $qrCode)->first();

        if (!$coach) {
            return $this->response->setJSON(['error' => 'Coach not found'])->setStatusCode(404);
        }

        $coachID = $coach['CoachID'];
        $fullName = $coach['Firstname'] . ' ' . $coach['Lastname'];

        // Check for last attendance record (to determine check-in or check-out)
        $lastAttendance = $this->attendanceModel
            ->where('CoachID', $coachID)
            ->orderBy('Timestamp', 'DESC')
            ->first();

        // Determine if it's a check-in or check-out
        $action = 'check-in';

        if ($lastAttendance && $lastAttendance['CheckOutTime'] === null) {
            // If last record exists and there is no check-out time, mark it as check-out
            $this->attendanceModel->update($lastAttendance['id'], ['CheckOutTime' => date('Y-m-d H:i:s')]);
            $action = 'check-out';
        } else {
            // Otherwise, create a new check-in record
            $this->attendanceModel->insert([
                'CoachID' => $coachID,
                'CheckInTime' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->response->setJSON([
            'status' => $action,
            'coach' => [
                'CoachID' => $coachID,
                'FullName' => $fullName,
            ]
        ]);
    }
    }
}
