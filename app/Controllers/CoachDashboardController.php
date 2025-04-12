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

        return view('coachdashboard/accountsettings1', ['user' => $user]);
    }

    public function updateAccount()
{
    $session = session();
    $coachID = $session->get('CoachID');

    $firstname = $this->request->getPost('firstname');
    $lastname = $this->request->getPost('lastname');
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    $data = [
        'Firstname' => $firstname,
        'Lastname'  => $lastname,
        'Email'     => $email,
    ];

    if (!empty($password)) {
        $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
    }

    // Check if $data has at least one value to update
    if (empty($data)) {
        return redirect()->back()->with('error', 'There is no data to update.');
    }

    $model = new CoachModel();
    $model->update($coachID, $data);

    return redirect()->back()->with('success', 'Account updated successfully.');
}

///try the coach absent 
public function markAbsence()
{
    $coachId = session()->get('CoachID');
    $today = date('Y-m-d');
    $message = $this->request->getPost('message');

    $db = \Config\Database::connect();

    // Check if already marked today
    $existing = $db->table('coach_absences')
        ->where('CoachID', $coachId)
        ->where('date', $today)
        ->get()
        ->getRow();

    if ($existing) {
        return redirect()->back()->with('error', 'You already marked yourself absent today.');
    }

    // Insert absence record
    $db->table('coach_absences')->insert([
        'CoachID' => $coachId,
        'date' => $today,
        'message' => $message,
    ]);

    // ✅ Notify all clients assigned to this coach
    $clients = $db->table('clients') // Replace 'clients' with your actual client table
        ->where('CoachID', $coachId)
        ->get()
        ->getResult();

    foreach ($clients as $client) {
        $db->table('notifications')->insert([
            'ClientID' => $client->ClientID,
            'message' => "Your coach is absent today. " . ($message ? "Note: $message" : ""),
        ]);
    }

    return redirect()->back()->with('success', 'You are marked absent today. Your clients have been notified.');
}
public function markAbsenceForm()
{
    $session = session();
    $userID = $session->get('CoachID'); // Get logged-in user ID

    return view('coachdashboard/markabsent');  // Replace 'coach/mark_absence' with your actual view path
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