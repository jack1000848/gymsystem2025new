<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
//use App\Models\Clients1Model;
use App\Models\CustomerModel;
use App\Models\QrAttendanceModel;


class ClientsDashboardController extends BaseController 
{
    protected $clientsModel; // Declare the model

    public function __construct()
    {
    
         $this->clientsModel = model(CustomerModel::class);
    }
    
    public function clientkrazy()
{
    if (!session()->has('isLoggedIn')) {
        return redirect()->to('/member-login')->with('error', 'Please login first.');
    }

    $db = \Config\Database::connect();
    $clientId = session()->get('CustomerID'); // Make sure this is the correct session key

    // Get the logged-in client's info
    $client = $db->table('customer')->where('CustomerID', $clientId)->get()->getRow();
    $coachId = $client->CoachID ?? null;

    if ($coachId === null) {
        return redirect()->to('/clientdashboard')->with('error', 'You have not been assigned a coach.');
    }

    $today = date('Y-m-d');

    // Get absence info if the coach is absent today
    $absence = $db->table('coach_absences')
        ->where('CoachID', $coachId)
        ->where('date', $today)
        ->get()
        ->getRow();

    // Pass the absence info to the view
    $data['coachAbsence'] = $absence;
    $data['client'] = $client;

    return view('clientdashboard/clientnotif', $data);
}

    public function index()
    {
        if (!session()->has('isLoggedIn')) { // Dito dapat ang check, hindi sa login function
            return redirect()->to('/member-login')->with('error', 'Please login first.');
        }
        
        
        return view('clientdashboard/index');
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
    ///try to view coach absent

    public function logout()
    {
        ///// Destroy the entire session//
        session()->destroy();

        // Optional: Redirect to login or home page
        return redirect()->to('/member-login')->with('success', 'You have been logged out.');
    }
}

?>