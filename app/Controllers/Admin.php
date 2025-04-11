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
    $data = [];
    $checkinData = [];

    // Example data for illustration
    $totalCheckins = 0; // Initialize total check-ins count

    // Loop through the last 5 months
    for ($i = 4; $i >= 0; $i--) {
        // Get the current date
        $monthStart = Time::now()->subMonths($i);
        
        // Set the start of the month using modify()
        $monthStart->modify('first day of this month');
        
        // Set the end of the month using modify()
        $monthEnd = clone $monthStart;
        $monthEnd->modify('last day of this month');

        // Example: Add logic to fetch check-ins for the month
        $checkinsThisMonth = rand(80, 180); // Replace with your actual logic

        // Push data into the chart data array
        $checkinData[] = [
            'Month' => $monthStart->format('F Y'),
            'Check-ins' => $checkinsThisMonth
        ];

        // Accumulate total check-ins
        $totalCheckins += $checkinsThisMonth;
    }
    $data = [['Month', 'Check-ins']]; // Add header row

    foreach ($checkinData as $row) {
        $data[] = [$row['Month'], $row['Check-ins']];
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

