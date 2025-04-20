<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\QrAttendanceModel;
use App\Models\eCoachAttendanceModel; // Correct model name
use App\Models\CustomerPlanModel;
use App\Models\QrAttendanceLogModel;
use App\Models\CoachModel;
use App\Models\PlanModel; // Add this line to import PlanModel


class QrAttendanceController extends Controller
{
    protected $session;
    protected $coachModel;
    protected $attendanceModel;

    public function __construct()
    {
        helper('url');
        $this->session = session();
        $this->coachModel = new CoachModel();
        $this->session = session(); //
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
        $planModel = new PlanModel(); // This should now work

        // Validate ID
        if (empty($id) || !is_numeric($id)) {
            return $this->response->setJSON(['error' => 'Invalid Customer ID'])->setStatusCode(400);
        }

        // Check if customer exists
        $customer = $customerPlanModel->find($id);
        if (!$customer) {
            return $this->response->setJSON(['error' => 'Customer not found'])->setStatusCode(404);
        }

        // Fetch all plans and create a mapping of PlanID to PlanName
        $plans = $planModel->findAll();
        $planMap = [];
        foreach ($plans as $plan) {
            $planMap[$plan['PlanID']] = $plan['PlanName'];
        }

        // Add PlanName to the customer data
        $customer['PlanName'] = isset($planMap[$customer['Membership_plan']]) ? $planMap[$customer['Membership_plan']] : 'No Plan';

        // Get the current date (YYYY-MM-DD) for daily check-in restriction
        $currentDate = date('Y-m-d');
        
        // Check if the user has already checked in today
        $todayRecord = $qrAttendanceModel->where('CustomerID', $id)
            ->where('DATE(InDate)', $currentDate)
            ->first();
                
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
        if (!$this->session->has('logged_in')) {
            return redirect()->to('/joinus')->with('error', 'Please log in first.');
        }

        if ($this->session->get('Role') != 'Admin') {
            $roleVal = $this->session->get('Role');
            if ($roleVal == 'Customer') {
                return redirect()->to('/clientdashboard')->with('error', 'You are not authorized to access this page.');
            } else if ($roleVal == 'Coach') {
                return redirect()->to('/coachdashboard')->with('error', 'You are not authorized to access this page.');
            }
        }
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
        $timestamp = date('Y-m-d H:i:s');
        $todayDate = date('Y-m-d');
    
        // Find coach
        $coach = $this->coachModel->where('CoachID', $coachID)->first();
        if (!$coach) {
            return $this->response->setJSON(['error' => 'Coach not found'])->setStatusCode(404);
        }
    
        // Ensure attendance model is loaded
        if (!$this->attendanceModel) {
            return $this->response->setJSON(['error' => 'Attendance model not loaded'])->setStatusCode(500);
        }
    
        // Get today's attendance record
        $todayAttendance = $this->attendanceModel
            ->where('CoachID', $coachID)
            ->where('DATE(CheckInTime)', $todayDate)
            ->orderBy('CheckInTime', 'DESC')
            ->first();
    
        if (!$todayAttendance) {
            // No attendance today: Do check-in
            $this->attendanceModel->insert([
                'CoachID'     => $coachID,
                'CheckInTime' => $timestamp,
                'CheckOutTime' => null
            ]);
    
            return $this->response->setJSON([
                'status'   => 'check-in',
                'message'  => 'Check-in successful',
                'coach'    => [
                    'CoachID'   => $coach['CoachID'],
                    'FullName'  => $coach['Firstname'] . ' ' . $coach['Lastname']
                ]
            ]);
        }
    
        if ($todayAttendance && $todayAttendance['CheckOutTime'] === null) {
            // Already checked in, but not yet checked out: Do check-out
            $this->attendanceModel->update($todayAttendance['ID'], ['CheckOutTime' => $timestamp]);
    
            return $this->response->setJSON([
                'status'   => 'check-out',
                'message'  => 'Check-out successful',
                'coach'    => [
                    'CoachID'   => $coach['CoachID'],
                    'FullName'  => $coach['Firstname'] . ' ' . $coach['Lastname']
                ]
            ]);
        }
    
        // Already checked in and checked out today
        return $this->response->setJSON([
            'error' => 'You have already checked in and out today.'
        ])->setStatusCode(403);
    }
    

}
