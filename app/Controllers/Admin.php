<?php

namespace App\Controllers;
use Config\Database;
use App\Models\CustomerModel;
use App\Models\CoachModel;
use App\Models\GenderChartModel;
use CodeIgniter\I18n\Time; // <-- Make sure this is imported


class Admin extends BaseController
{
    

    public function __construct()
{
    helper('url');
    $this->session = session();
    

    // Prevent back button after logout
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
}
    private function getCount($tableName)
    {
        $db = \Config\Database::connect();
        $query = $db->query('SELECT * FROM ' . $tableName);
        $totalRows = $query->getNumRows();
    
       
        return $totalRows;
    }
    

    public function index()
    {
        if (!$this->session->has('logged_in')) {
            return redirect()->to('/joinus')->with('error', 'Please log in first.');
        }

        if(!$this->session->get('Role') == 'Admin'){
            $roleVal = $this->session->get('Role');
            if($roleVal == 'Customer'){
                return redirect()->to('/clientdashboard')->with('error', 'You are not authorized to access this page.');
            }else if($roleVal == 'Coach'){
                return redirect()->to('/coachdashboard')->with('error', 'You are not authorized to access this page.');
            }
        }

       /// if(!$this->session->get('Role') == null || $this->session->get('Role') != 'Admin'){
     ///       return redirect()->to('/clientdashboard')->with('error', 'You are not authorized to access this page.');
    //    }
    
        /// gender chart
        $userModel = new GenderChartModel();

        $maleCount = $userModel->where('gender', 'Male')->countAllResults();
        $femaleCount = $userModel->where('gender', 'Female')->countAllResults();

        $data = [
            'male' => $maleCount,
            'female' => $femaleCount,
        ];
       
        
        // bar chart for attendnce
        $data['monthlyCheckinData'] = $this->getMonthlyCheckins(); // Get the monthly check-in data
        $data['monthlyCoachAttendance'] = $this->getMonthlyCoachAttendance();
        // For chart
        $coachModel = new CoachModel();
        $clientModel = new CustomerModel();

        $coachCount = $coachModel->countAll();
        $clientCount = $clientModel->countAll();
         $data['coachCount'] = $coachCount;
         $data['clientCount'] = $clientCount;

        // Call the private function using $this
        $totalClient = $this->getCount('coach');
        $totalClients = $this->getCount('customer');
        $totalEquipment = $this->getCount('equipment');

        // Pass the result to the view
        $data['totalClients'] = $totalClients; ///client
        $data['totalClient'] = $totalClient; ////coach /trainer
        $data['totalEquipment'] = $totalEquipment;

        $fetchClients1 =new CoachModel();
        $fetchClients = $fetchClients1->findAll();
        //loop through the data
        foreach($fetchClients as $client){
            //check if password_hash is null
            if($client['password_hash'] == null){
                //update the password_hash with the password
                $fetchClients1->update($client['CoachID'], ['password_hash' => password_hash($client['Password'], PASSWORD_BCRYPT)]);
            }
        }

        return view('admin/index', $data);
        

    
    
}
   private function getMonthlyCheckins()
{
    $db = \Config\Database::connect();
    $builder = $db->table('viewcustomerattendance'); // Use your actual view/table
    $checkinData = [];
    $totalCheckins = 0;

    // Header for Google Charts
    $data = [['Month', 'Check-ins']];

    for ($i = 4; $i >= 0; $i--) {
        $monthStart = Time::now()->subMonths($i)->modify('first day of this month')->setTime(0, 0, 0);
        $monthEnd = Time::now()->subMonths($i)->modify('last day of this month')->setTime(23, 59, 59);

        // Count check-ins in this month
        $builder->resetQuery(); // Clear any previous query state
        $builder->where('CheckIn >=', $monthStart->toDateTimeString());
        $builder->where('CheckIn <=', $monthEnd->toDateTimeString());

        $checkinCount = $builder->countAllResults();
        $monthLabel = $monthStart->format('F Y');

        $data[] = [$monthLabel, $checkinCount];
        $totalCheckins += $checkinCount;
    }

    return [
        'data' => $data,
        'total' => $totalCheckins
    ];
}


private function getMonthlyCoachAttendance()
{
    $db = \Config\Database::connect();
    $builder = $db->table('coachattendance');

    $data = [['Month', 'Coach Check-ins']];
    $totalCheckins = 0;

    for ($i = 4; $i >= 0; $i--) {
        $monthStart = Time::now()->subMonths($i)->modify('first day of this month')->setTime(0, 0, 0);
        $monthEnd = Time::now()->subMonths($i)->modify('last day of this month')->setTime(23, 59, 59);

        $builder->resetQuery();
        $builder->where('CheckInTime >=', $monthStart->toDateTimeString());
        $builder->where('CheckInTime <=', $monthEnd->toDateTimeString());

        $checkinCount = $builder->countAllResults();
        $monthLabel = $monthStart->format('F Y');

        $data[] = [$monthLabel, $checkinCount];
        $totalCheckins += $checkinCount;
    }

    return [
        'data' => $data,
        'total' => $totalCheckins
    ];
}

    

    public function index1()
    {
        $model = new AttendanceLogModel();
        $data['customers'] = $model->getCustomers();

        return view('/admin/index', $data);
    }

    public function login()
    {
        $session = session();
        $request = service('request');

        // Get form input
        $username = $request->getPost('username');
        $password = $request->getPost('password');

        // Hardcoded admin credentials (replace with database check)
        if ($username === 'admin' && $password === 'admin') {
            $session->set('logged_in', true);
            return redirect()->to('/admin');
        } else {
            return redirect()->to('/joinus')->with('error', 'Invalid username or password.');
        }
    }
    
    public function logout()
{
    $session = session();
    $session->destroy();
    return redirect()->to('/joinus')->withHeaders([
        'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        'Pragma' => 'no-cache',
    ]);
}


}

