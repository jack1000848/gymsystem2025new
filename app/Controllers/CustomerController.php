<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\PlanModel;
use App\Models\CoachPlanView;
use App\Models\CreateMemberModel;
// eto sa qr

use App\Models\QrCodeModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Builder\Builder;


class CustomerController extends BaseController


{
    public function __construct()
    {
        $this->session = session(); // Initialize session
    }
 
    public function index()
    {
        if (!$this->session->has('logged_in')) {
            return redirect()->to('/joinus')->with('error', 'Please log in first.');
        }
        $fetchClients1 =new CustomerModel();
        $data['clients1'] = $fetchClients1 ->findAll();
      
    
      $maxId = $fetchClients1->selectMax('customerid')->first(); 
      $nextId = isset($maxId['customerid']) ? $maxId['customerid'] + 1 : 1;
      $data['next_id'] = $nextId;

      return view('clients1crud/manage1', $data);

       

        ////return view('clients1crud/list', $data);
    }

    public function linkcoach()
    {
        $coachModel = new CoachModel();
        $coaches = $coachModel->getCoaches();
        
        $data['coaches'] = $coaches;

        // Render the view with the data
        return view('clients1crud/add', $data);
    }

   

    public function getCoaches()
    {
        // Get the planId from the request query parameters
        $planId = $this->request->getVar('planId');
    
        // Check if the planId is provided, otherwise return an error
        if (!$planId) {
            return $this->response->setJSON(['error' => 'Plan ID is required']);
        }
    
        // Fetch coaches based on PlanID from the CoachPlanView model
        $coachPlanModel = new \App\Models\CoachPlanView();
        $coaches = $coachPlanModel->where('PlanID', $planId)->findAll();
    
        // Return the coaches data as a JSON response
        return $this->response->setJSON($coaches);
    }
    

    public function getPlans()
    {
        $plansModel = new PlanModel();
    
        // Get all plans
        $plans = $plansModel->findAll();
    
        // Return the plans data as JSON
        return $this->response->setJSON($plans);
    }
    

    


    public function getCount($tableName)
    {

        

            return "Hello world";
            
            
       // global $conn;
        
       // $table = validate ($tableName);
       // $query = "SELECT = FROM $table";
       // $result = mysqli_query($conn, $query);
      //  $totalCount = mysqli_num_row($result);
      //  return $totalCount;
    }


    public function createClients1()
    {
        $data['clients1Password'] = '20_'. uniqid();
        $fetchClient = new CustomerModel();
      $data['customer'] = $fetchClient->findAll();
      $maxId = $fetchClient->selectMax('customerid')->first(); 
      $nextId = isset($maxId['customerid']) ? $maxId['customerid'] + 1 : 1;
      $data['next_id'] = $nextId;

      return view('clients1crud/manage1', $data);
    }
    public function storeClients1()
     {
        
        $insertClients = new CustomerModel ();

        // Retrieve the email from the form input
    $email = $this->request->getPost('clients1Emailaddress');

    // Check if email is retrieved properly
    if (empty($email)) {
        return redirect()->back()->with('error', 'Email field is required.');
    }

    // Check if the email is a Gmail address
    if (!preg_match("/^[a-zA-Z0-9._%+-]+@gmail\.com$/", $email)) {
        return redirect()->to('/clients1')->with('error', 'Only Gmail addresses are allowed.');
       
    }
    
       // Generate unique token
    $token = bin2hex(random_bytes(50));

       $data = [
                      // Maps directly
        'Firstname'        => $this->request->getPost('clients1Fname'),           // Maps directly
        ///'Middlename'       => $this->request->getPost('clients1Mname') ?? null,   // Add if required
        'Lastname'         => $this->request->getPost('clients1Lname'),           // Adjusted field name
        'Address'          => $this->request->getPost('clients1Username'),     // Adjusted field name
        'Gender'           => $this->request->getPost('gender'),                  // Maps directly
      // 'PhoneNumber'      => $this->request->getPost('phone_number'),            // Add phone field
        'Email'            => $this->request->getPost('clients1Emailaddress'),    // Adjusted field name
        'password_hash'         =>  password_hash($this->request->getPost('password'), PASSWORD_BCRYPT), // Hash the password
        'RegisteredDate'   => $this->request->getPost('dateofregistration'), 
        'types_of_workout'   => $this->request->getPost('tworkout'), 
       // 'GymTimeSlot' => $this->request->getPost('timeslot'),                  // Maps directly
        'Membesrship_plan'   => $this->request->getPost('plans'),      // Adjusted field name
        'WorkoutTypeID'    => null,                // Adjusted field name
        'CurrentPlanID'    => null,                   // Adjusted field name
              
        'WorkoutPlanID'    =>  null, // Add if necessary

         'verification_token' => $token,
            'is_verified' => 0
     ];
     

        $insertClients->insert($data);
        {
            // Send verification email
            $this->sendVerificationEmail($data['Email'], $token);
    
            session()->setFlashdata('success', 'Account created successfully! Please verify your email.');
            return redirect()->to('/clients1');
        
    }}
    private function sendVerificationEmail($email, $token)
    {
        $emailService = service('email');
    
        $emailService->setTo($email);
        $emailService->setFrom('taysonmiguelito125@gmail.com', 'IshowFitnessGYM');
        $emailService->setSubject('Email Verification');
        $emailService->setMessage("Hello,

Thank you for signing up! To complete your registration and verify your email address, please click the link below: <a href='" . base_url("verify-email/$token") . "'>Verify Email</a>");
    
        if (!$emailService->send()) {
            log_message('error', $emailService->printDebugger(['headers']));
        }
    }

    public function verifyEmail($token)
    {
        $memberModel = new CreateMemberModel();
        $user = $memberModel->where('verification_token', $token)->first();
    
        if ($user) {
            $memberModel->update($user['CustomerID'], ['is_verified' => 1, 'verification_token' => null]);
            session()->setFlashdata('success', 'Email verified successfully! You can now log in.');
            return redirect()->to('/member-login');
        } else {
            session()->setFlashdata('error', 'Invalid verification link.');
            return redirect()->to('/member-login');
        }
    }
    public function verify($token)
    {
        $userModel = new CreateMemberModel(); // Ensure using correct model
        $user = $userModel->where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->to('/loginclient')->with('error', 'Invalid or expired verification token.');
        }

        // Mark user as verified
        $userModel->update($user['CustomerID'], ['is_verified' => 1, 'verification_token' => null]);

        return redirect()->to('/loginclient')->with('success', 'Your account has been verified. You can now log in.');
    }
    public function editClients1($id)
    {
        $clients1Model = new CustomerModel();

        // Fetch the Client data by ID
        $editclient = $clients1Model->find($id);
    
        if (!$editclient) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Client not found'
            ]);
        }
    
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $editclient
        ]);
    }
    public function updateClients1($id)
{
    // Load the CustomerModel
    $customerModel = new \App\Models\CustomerModel();

    // Get the input data from the request
    $data = [
        'gym_code' => $this->request->getPost('gymcode'), 
        'first_name' => $this->request->getPost('clients1Fname'),
        'last_name' => $this->request->getPost('clients1Lname'),
        'user_name' => $this->request->getPost('clients1Username'),
        'password' => $this->request->getPost('password'),
        'full_address' => $this->request->getPost('clients1Fulladdress'),
        'email_address' => $this->request->getPost('clients1Emailaddress'),
        'phone_number' => $this->request->getPost('clients1Phonenumber'),
        'gender' => $this->request->getPost('gender'),
        'date_of_registration' => $this->request->getPost('dateofregistration'),
        'GymTimeSlot' => $this->request->getPost('timeslot'),
        'workout_type' => $this->request->getPost('tworkout'),
        'plans' => $this->request->getPost('plans'),
        'amount' => $this->request->getPost('amount'),
    ];

    // Validate required fields
    if (
        !$data['gym_code'] || !$data['first_name'] || !$data['last_name'] ||
        !$data['user_name'] || !$data['password'] || !$data['full_address'] ||
        !$data['email_address'] || !$data['phone_number'] || !$data['gender'] ||
        !$data['date_of_registration'] || !$data['GymTimeSlot'] || !$data['workout_type'] ||
        !$data['plans'] || !$data['amount']
    ) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'All fields are required!'
        ]);
    }

    // Attempt to update the client in the database
    $updated = $customerModel->update($id, $data);

    // Check if the update was successful
    if ($updated) {
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Client updated successfully!'
        ]);
    } else {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update client. Please try again.'
        ]);
    }
}



public function deleteClients1($id)
{
    $deleteClients1 = new CustomerModel();

    $isDeleted = $deleteClients1->delete($id);

    if ($isDeleted) {
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Client deleted successfully.'
        ]);
    } else {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to delete client.'
        ]);
    }
}

public function toggleFreeze($id)
{
    $customerModel = new CustomerModel();
    $client = $customerModel->find($id);

    if ($client) {
        $newStatus = $client['is_frozen'] ? 0 : 1; // Toggle status

        $customerModel->update($id, ['is_frozen' => $newStatus]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => $newStatus ? 'Client frozen successfully.' : 'Client unfrozen successfully.'
        ]);
    } else {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Client not found.'
        ]);
    }
}
    public function renew($id = null) // Accept ID from URL
{
    $renewModel = new CustomerModel();

    if ($id === null) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'No client ID provided'
        ]);
    }

    $renewClient = $renewModel->find($id);

    if (!$renewClient) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Client not found'
        ]);
    }

    return $this->response->setJSON([
        'status' => 'success',
        'data' => $renewClient
    ]);
}

public function try($id)
{
    $clients1Model = new CustomerModel();

    // Fetch the Client data by ID
    $editclient = $clients1Model->find($id);

    if (!$editclient) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Client not found'
        ]);
    }

    return $this->response->setJSON([
        'status' => 'success',
        'data' => $editclient
    ]);
}
public function viewClient($id)
{
    $customerModel = new CustomerModel();
    $client = $customerModel->find($id);

    

    return view('clients1crud/client_view', ['client' => $client]);

}

}