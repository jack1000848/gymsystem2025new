<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
//use App\Models\Clients1Model;
use App\Models\CustomerModel;
use App\Models\QrAttendanceModel;
use App\Models\CustomerBodyHistoryModel; // Add the history model

class ClientsDashboardController extends BaseController 
{
    protected $clientsModel; // Declare the model
    protected $historyModel; // For customer_body_history table

    public function __construct()
    {
        $this->clientsModel = model(CustomerModel::class);
        $this->historyModel = model(CustomerBodyHistoryModel::class); // Initialize history model
         $this->clientsModel = model(CustomerModel::class);
    }
    public function coachAbsenceNotification()
{
    $session = session();
    $customerId = $session->get('CustomerID');

    if (!$customerId) {
        return redirect()->to('/member-login'); // Or wherever you handle login
    }

    $db = \Config\Database::connect();

    // Step 1: Get the client's assigned coach
    $client = $db->table('customer')
        ->where('CustomerID', $customerId)
        ->get()
        ->getRow();

    if (!$client || !$client->CoachID) {
        return view('clientdashboard/clientnotif', ['message' => 'No coach assigned.']);
    }

    $coachId = $client->CoachID;

    // Step 2: Check if coach is absent today
    $today = date('Y-m-d');
    $absence = $db->table('coach_absences')
        ->where('CoachID', $coachId)
        ->where('date', $today)
        ->get()
        ->getRow();

    if ($absence) {
        $message = "Your coach is absent today.";
        if (!empty($absence->message)) {
            $message .= " Note: " . $absence->message;
        }
    } else {
        $message = "Your coach is available today.";
    }

    return view('clientdashboard/clientnotif', ['message' => $message]);
}


   

    public function index()
    {
        if (!session()->has('isLoggedIn')) { // Dito dapat ang check, hindi sa login function
            return redirect()->to('/member-login')->with('error', 'Please login first.');
        }
        $customerId = session()->get('CustomerID');
        $customer = $this->clientsModel->find($customerId);
    
        if (!$customer) {
            return redirect()->to('/member-login')->with('error', 'Customer not found.');
        }
    
        // Fetch body history for the chart
        $history = $this->historyModel->getHistory($customerId);

        // Fetch monthly check-in data for the attendance chart (if still needed)
    $monthlyCheckinData = $this->getMonthlyCheckinData($customerId);
    // Fetch attendance records using QrAttendanceModel
    $qrAttendanceModel = new QrAttendanceModel();
    $attendanceRecords = $qrAttendanceModel
        ->where('CustomerID', $customerId)
        ->orderBy('InDate', 'DESC')
        ->findAll();
    
        // Debug: Log the data to ensure it's being fetched
    log_message('debug', 'Customer Data: ' . json_encode($customer));
    log_message('debug', 'Body History Data: ' . json_encode($history));
    log_message('debug', 'Monthly Check-in Data: ' . json_encode($monthlyCheckinData));
    log_message('debug', 'Attendance Records: ' . json_encode($attendanceRecords));
    
       // Pass customer, history, monthly check-in data, and attendance records to the view
       return view('clientdashboard/index', [
        'client' => $customer,
        'history' => $history,
        'monthlyCheckinData' => $monthlyCheckinData,
        'attendance' => $attendanceRecords,
        'tasks' => $tasks // Add tasks to the view data
    ]);
    }
    private function getMonthlyCheckinData($customerId)
    {
        // Replace with actual database query if needed
        $checkins = [
            ['month' => 'Jan', 'checkins' => 5],
            ['month' => 'Feb', 'checkins' => 3],
            ['month' => 'Mar', 'checkins' => 7],
            ['month' => 'Apr', 'checkins' => 4]
        ];
    
        $data = [['Month', 'Check-ins']];
        $total = 0;
        foreach ($checkins as $row) {
            $data[] = [$row['month'], (int)$row['checkins']];
            $total += $row['checkins'];
        }
    
        return [
            'data' => $data,
            'total' => $total
        ];
    }

    //// here the client view their attendance log
    public function viewAttendance()
    {
        if (!session()->has('isLoggedIn')) { // Dito dapat ang check, hindi sa login function
            return redirect()->to('/member-login')->with('error', 'Please login first.');
     }
        // Get customer ID from session
        $customerID = session()->get('CustomerID');
    
        // Validate ID
        if (empty($customerID) || !is_numeric($customerID)) {
            return $this->response->setJSON(['error' => 'Invalid Customer ID'])->setStatusCode(400);
        }
    
        $qrAttendanceModel = new QrAttendanceModel();
    
        // Fetch attendance records
        $attendanceRecords = $qrAttendanceModel
            ->where('CustomerID', $customerID)
            ->orderBy('InDate', 'DESC')
            ->findAll();
    
            return view ('clientdashboard/myattendance', ['attendance' => $attendanceRecords]);
        // Return records as JSON
       // return $this->response->setJSON(['attendance' => $attendanceRecords]);
    }
    



    /////heres the viewqrcode in dashboard

    public function myqrcode()
    {   
        if (!session()->has('CustomerID')) {
            return redirect()->to('/member-login')->with('error', 'Please login first.'); // Redirect if not logged in
        }

        $customerID = session()->get('CustomerID'); // Get logged-in Customer ID

        // Debugging: Ensure session is working
        // dd($customerID); // ✅ If this prints a valid number, session is OK

        // ✅ Fetch client details from the correct table
        $data['client'] = $this->clientsModel->find($customerID); 

        if (!$data['client']) {
            return redirect()->to('/clientdashboard')->with('error', 'Client not found.');
        }

        return view('clientdashboard/myqrcode', $data); // ✅ Pass client data to the view
    }

    ///// heres my account settings

    public function accountSettings()
    {
        $session = session();
        $userID = $session->get('CustomerID'); // Get logged-in user ID

        $userModel = new CustomerModel();
        $user = $userModel->find($userID);

        if (!$user) {
            return redirect()->to('/dashboard')->with('error', 'User not found.');
        }

        return view('clientdashboard/accountsettings', ['user' => $user]);
    }

    public function updateAccount()
    {
        $session = session();
        $userID = $session->get('CustomerID');
    
        $userModel = new CustomerModel();
        $user = $userModel->find($userID);
    
        if (!$user) {
            return redirect()->to('/account-setting')->with('error', 'Unauthorized access.');
        }
    
        // Get all form inputs
        $fname = $this->request->getPost('Firstname');
        $lname = $this->request->getPost('Lastname');
        $email = $this->request->getPost('Email');
        $password = $this->request->getPost('password');
    
        // Build update array
        $updateData = [
            'Firstname' => $fname,
            'Lastname' => $lname,
            'Email'     => $email,
        ];
    
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
    // New method: Show form to add/update body info
    public function bodyInfo()
    {
        if (!session()->has('isLoggedIn')) {
            return redirect()->to('/member-login')->with('error', 'Please login first.');
        }

        $customerId = session()->get('CustomerID');
        $customer = $this->clientsModel->find($customerId);

        if (!$customer) {
            return redirect()->to('/clientdashboard')->with('error', 'Customer not found.');
        }

        return view('clientdashboard/bodyinformation', ['client' => $customer]); // Adjusted view path
    }

    // New method: Save or update body info
    public function saveBodyInfo()
    {
        if (!session()->has('isLoggedIn')) {
            return redirect()->to('/member-login')->with('error', 'Please login first.');
        }

        $customerId = session()->get('CustomerID');
        $validation = \Config\Services::validation();

        // Validation rules
        $validation->setRules([
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'weight_goal' => 'required|numeric',
            'height_goal' => 'permit_empty|numeric',
            'notes' => 'permit_empty'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Prepare data for the customer table
        $data = [
            'Height' => $this->request->getPost('height'),
            'Weight' => $this->request->getPost('weight'),
            'Weight_Goal' => $this->request->getPost('weight_goal'),
            'Height_Goal' => $this->request->getPost('height_goal') ?: null,
            'Goal_Set_Date' => date('Y-m-d H:i:s')
        ];

        // Update customer table
        $this->clientsModel->update($customerId, $data);

        // Log the update in the history table
        $historyData = [
            'CustomerID' => $customerId,
            'Height' => $this->request->getPost('height'),
            'Weight' => $this->request->getPost('weight'),
            'RecordDate' => date('Y-m-d H:i:s'),
            'Notes' => $this->request->getPost('notes')
        ];
        $this->historyModel->insert($historyData);

        return redirect()->to('/customer/body/history')->with('success', 'Body information updated successfully.');
    }

    // New method: View body history and chart
    public function bodyHistory()
    {
        if (!session()->has('isLoggedIn')) {
            return redirect()->to('/member-login')->with('error', 'Please login first.');
        }

        $customerId = session()->get('CustomerID');
        $history = $this->historyModel->getHistory($customerId);

        return view('clientdashboard/updateinformation', ['history' => $history]); // Adjusted view path
    }


    public function logout()
    {
        ///// Destroy the entire session//
        session()->destroy();

        // Optional: Redirect to login or home page
        return redirect()->to('/member-login')->with('success', 'You have been logged out.');
    }
}

?>