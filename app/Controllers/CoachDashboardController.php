<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CoachScheduleModel;
use App\Models\eCoachAttendanceModel;

use App\Models\CoachModel;

//use App\Models\Clients1Model;
use CodeIgniter\HTTP\ResponseInterface;

class CoachDashboardController extends BaseController 
{
    protected $coachModel; // Declare the model
    protected $attendanceModel;
    protected $session;

    public function __construct()
    {
        $this->attendanceModel = new eCoachAttendanceModel(); // Load the model
        $this->session = session(); // Load session
    
        $this->coachScheduleModel = new CoachScheduleModel();
       //  $this->timeModel = new TimeScheduleModel();
         $this->scheduleModel = model(CoachScheduleModel::class);
         $this->coachModel = model(CoachModel::class);
    }
    public function dashboardindex()
    {
        if (!session()->has('CoachID')) {
            return redirect()->to('/coach-login'); // Redirect if not logged in
        }
        
        return view('coachdashboard/index');
    }

    ///here's the coach manage my schedules
     public function coachManage()
    {
        if (!session()->has('CoachID')) {
            return redirect()->to('/coach-login'); // Redirect if not logged in
        }
        $coachID = session()->get('CoachID'); // Get logged-in coach's ID

        if (!$coachID) {
            return redirect()->to('/coach-login')->with('error', 'Please login first.');
        }

        // Filter schedules by the logged-in coach only
        $data['sched'] = $this->coachScheduleModel->where('CoachID', $coachID)->findAll();

        return view('/coachdashboard/ManagemyScheds', $data);
    }

    // Store Schedule
    public function storemanage()
    {
        if (!session()->has('CoachID')) {
            return redirect()->to('/coach-login'); // Redirect if not logged in
        }
        $validation = \Config\Services::validation();
        $rules = [
            'startdate' => 'required',
            'starttime' => 'required',
            'enddate'   => 'required',
            'endtime'   => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $startDateTime = $this->request->getPost('startdate') . ' ' . $this->request->getPost('starttime');
        $endDateTime   = $this->request->getPost('enddate') . ' ' . $this->request->getPost('endtime');
        $coachID = session()->get('CoachID');

        if (!$coachID) {
            return redirect()->back()->with('error', 'CoachID not found in session.');
        }

        $data = [
            'CoachID'      => $coachID,
            'ScheduleDate' => $this->request->getPost('startdate'),
            'Start'        => $startDateTime,
            'End'          => $endDateTime,
        ];

        $this->coachScheduleModel->insert($data);

        return redirect()->to('/coach-manage')->with('success', 'Schedule added successfully.');
    }



    public function edit($id)
    {
        $schedule = $this->scheduleModel->find($id);
        if ($schedule) {
            return $this->response->setJSON($schedule);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Schedule not found']);
        }
    }

    public function update()
{
    $id = $this->request->getPost('id');  // lowercase
    $data = [
        'ScheduleDate' => $this->request->getPost('startdate'),
        'Start'        => $this->request->getPost('starttime'),
        'End'          => $this->request->getPost('endtime'),
    ];

    if ($id) {
        $this->scheduleModel->update($id, $data);
        return $this->response->setJSON(['status' => 'success']);
    } else {
        return $this->response->setStatusCode(400)->setJSON(['status' => 'failed', 'message' => 'Invalid ID']);
    }
}

    public function delete($id)
{
    $this->scheduleModel = new \App\Models\CoachScheduleModel(); // Load the model
    $this->scheduleModel->delete($id);
    return $this->response->setJSON(['status' => 'success']);
}

///// this is coach attendance log\\\\\\\
public function mylogs()
{
    // Ensure coach is logged in
   // if (!$this->session->has('logged_in') || $this->session->get('role') !== 'coach') {
   //    return redirect()->to('/coach-login')->with('error', 'Please log in first.');
   // }

    // Get the logged-in coach ID
    $coachID = $this->session->get('CoachID');

    // Fetch attendance records for the logged-in coach
    $data['attendance'] = $this->attendanceModel
        ->where('CoachID', $coachID)
        ->orderBy('CheckInTime', 'DESC')
        ->findAll();

    return view('coachdashboard/viewmyattendance', $data);
}
   

    ///////////// this is the coach client list!
    public function coachclientlist(){
        return view ('/coachdashboard/viewmyclient');
    }


    //////////////// COACH QRCODE\\\\\\\\\\\\\\

    public function coachqr()
    {
        if (!session()->has('CoachID')) {
            return redirect()->to('/coach-login'); // Redirect if not logged in
        }

        $coachID = session()->get('CoachID'); // Get logged-in Coach ID
        $data['coach'] = $this->coachModel->find($coachID); // Fetch coach details

        if (!$data['coach']) {
            return redirect()->to('/dashboard')->with('error', 'Coach not found.');
        }

        return view('coachdashboard/myqrcode', $data);
    }

////// accout settings
public function accountSettings()
    {
        $session = session();
        $userID = $session->get('CoachID'); // Get logged-in user ID

        $userModel = new CoachModel();
        $user = $userModel->find($userID);

        if (!$user) {
            return redirect()->to('/dashboard')->with('error', 'User not found.');
        }

        return view('clientdashboard/accountsettings', ['user' => $user]);
    }

    public function updateAccount()
    {
        $session = session();
        $userID = $session->get('CoachID');

        $userModel = new CoachModel();
        $user = $userModel->find($userID);

        if (!$user) {
            return redirect()->to('/account-setting')->with('error', 'Unauthorized access.');
        }

        $fname = $this->request->getPost('Firstname');
    
        $password = $this->request->getPost('password');

        $updateData = ['Firstname' => $fname];
        
        // Only update password if provided

        if (!empty($password)) {
            $updateData['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        if ($userModel->update($userID, $updateData)) {
            return redirect()->to('/account-setting')->with('success', 'Account updated successfully.');
        } else {
            return redirect()->to('/account-setting')->with('error', 'Failed to update account.');
        }
    }


/////////////LOGOUT\\\\\\\\\\\\\\\\\\\\\
public function logout()
    {
        // Destroy the entire session
        session()->destroy();

        // Optional: Redirect to login or home page
        return redirect()->to('/coach-login')->with('success', 'You have been logged out.');
    }
    
}

?>