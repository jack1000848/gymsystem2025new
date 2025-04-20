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
        // use sql raw query to get the data from the database example
        // SELECT *, p.PlanName FROM `customer` left join plan p on customer.CurrentPlanID = p.PlanID;
         $data['clients1'] = $fetchClients1->query("SELECT *, p.PlanName FROM `customer` left join plan p on customer.CurrentPlanID = p.PlanID")->getResultArray();
      
    
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


    public function getSchedules($id){

        $scheduleModel = new \App\Models\ViewScheduleForAllUserModel();

        //find schedule where id is eq to CoachID and CustomerID is null
        $schedules = $scheduleModel->where('CoachID', $id)->where('CustomerID', null)->findAll();
        // Return the schedules data as JSON
        return $this->response->setJSON($schedules);


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
        'Address'          => $this->request->getPost('clients1Adress'),     // Adjusted field name
        'Gender'           => $this->request->getPost('gender'),                  // Maps directly
      // 'PhoneNumber'      => $this->request->getPost('phone_number'),            // Add phone field
        'Email'            => $this->request->getPost('clients1Emailaddress'),    // Adjusted field name
        'password_hash'         =>  password_hash($this->request->getPost('password'), PASSWORD_BCRYPT), // Hash the password
        'RegisteredDate'   => $this->request->getPost('dateofregistration'), 
        'types_of_workout'   => $this->request->getPost('tworkout'), 
       // 'GymTimeSlot' => $this->request->getPost('timeslot'),                  // Maps directly
        'Membership_plan'   => $this->request->getPost('plans'),    
        'ExpirationDate' => $this->request->getPost('dateofregistration'),
        'WorkoutTypeID'    => null,                // Adjusted field name
        'CurrentPlanID'    => null,                   // Adjusted field name
        'CoachID'    =>  $this->request->getPost('coach'), // Add if necessary
       
        'WorkoutPlanID'    =>  null, // Add if necessary

       //  'verification_token' => $token,
        //    'is_verified' => 0

     ]; 
     
     
     
     
     

        $insertClients->insert($data);
       
    $customerId = $insertClients->getInsertID();

    // Retrieve schedule IDs and coach ID
    $scheduleIds = $this->request->getPost('coachsched'); // This should be an array
    $coachId = $this->request->getPost('coach');

    // Update the CoachSched table for each selected schedule
    if (!empty($scheduleIds) && !empty($coachId)) {
        $db = \Config\Database::connect();
        $builder = $db->table('CoachSched');

        foreach ($scheduleIds as $schedId) {
            $builder->where('CoachID', $coachId)
                    ->where('ID', $schedId)
                    ->update(['CustomerID' => $customerId]);
        }
    }
        {
            // Send verification email
           // $this->sendVerificationEmail($data['Email'], $token);
    
            session()->setFlashdata('success', 'Account created successfully.');
            return redirect()->to('/clients1');
        
    }
}
   /// private function sendVerificationEmail($email, $token)
   /// {
    ///    $emailService = service('email');
    
    ///    $emailService->setTo($email);
     ///   $emailService->setFrom('taysonmiguelito125@gmail.com', 'IshowFitnessGYM');
      //  $emailService->setSubject('Email Verification');
///$emailService->setMessage("Hello,

  ///  Thank you for signing up! To complete your registration and verify your email address, please click the link below: <a href='" . base_url("verify-email/$token") . "'>Verify Email</a>");
    
     //   if (!$emailService->send()) {
       //     log_message('error', $emailService->printDebugger(['headers']));
      //  }
   // }

    ///public function verifyEmail($token)
  ///  {
     ///   $memberModel = new CreateMemberModel();
       /// $user = $memberModel->where('verification_token', $token)->first();
    
     ///   if ($user) {
//$memberModel->update($user['CustomerID'], ['is_verified' => 1, 'verification_token' => null]);
      ///      session()->setFlashdata('success', 'Email verified successfully! You can now log in.');
     //       return redirect()->to('/member-login');
     //   } else {
     //       session()->setFlashdata('error', 'Invalid verification link.');
      //      return redirect()->to('/member-login');
     //   }
 //   }
  //  public function verify($token)
   // {
   //     $userModel = new CreateMemberModel(); // Ensure using correct model
///$user = $userModel->where('verification_token', $token)->first();

      //  if (!$user) {
     //       return redirect()->to('/loginclient')->with('error', 'Invalid or expired verification token.');
     //   }

        // Mark user as verified
     //   $userModel->update($user['CustomerID'], ['is_verified' => 1, 'verification_token' => null]);

       // return redirect()->to('/loginclient')->with('success', 'Your account has been verified. You can now log in.');
  //  }
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
        $customerModel = new CustomerModel();
    
        // Get the input data from the request
        $data = [
            'Firstname' => $this->request->getPost('clients1Fname'),
            'Lastname' => $this->request->getPost('clients1Lname'),
            'Address' => $this->request->getPost('clients1Fulladdress'),
            'Gender' => $this->request->getPost('gender'),
            'Email' => $this->request->getPost('clients1Emailaddress'),
           // 'RegisteredDate' => $this->request->getPost('dateofregistration'),
           // 'types_of_workout' => $this->request->getPost('tworkout'),
        ];
    
        // Validate required fields
        if (
            empty($data['Firstname']) ||
            empty($data['Lastname']) ||
            empty($data['Address']) ||
            empty($data['Gender']) ||
            empty($data['Email']) 
          //  empty($data['RegisteredDate']) ||
           // empty($data['types_of_workout'])
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
public function updaterenew($id)
    {
        $customerModel = new CustomerModel();
        $planModel = new PlanModel();
        $data = [
            'ExpirationDate' => $this->request->getPost('dateofregistration'),
            'types_of_workout' => $this->request->getPost('tworkout'),
            'Membership_plan' => $this->request->getPost('plans'),
            'CurrentPlanID' => $this->request->getPost('plans'),
            "PaidAmount" => $this->request->getPost('paidamount'),
            'CoachID' => $this->request->getPost('coach'),
            'amount' => $this->request->getPost('amount'),
            'duration' => $this->request->getPost('duration'),
        ];
        
        $plan = $planModel->find($data['Membership_plan']);
        if (!$plan) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid membership plan selected.',
            ]);
        }
        $startDate = new \DateTime($data['ExpirationDate']);
        $duration = $data['duration'] ?? 30;
        $endDate = (clone $startDate)->modify("+{$duration} days");
        $data['EndDate'] = $endDate->format('Y-m-d');
        $scheduleIds = $this->request->getPost('coachsched');
        $coachId = $data['CoachID'];
        if (!empty($scheduleIds) && !empty($coachId)) {
            $db = \Config\Database::connect();
            
            $builder = $db->table('CoachSched');
            $builder->where('CustomerID', $id)->update(['CustomerID' => null]);
            foreach ($scheduleIds as $schedId) {
                $builder->where('CoachID', $coachId)
                        ->where('ID', $schedId)
                        ->update(['CustomerID' => $id]);
            }
        }
        $updated = $customerModel->update($id, $data);
        $db = \Config\Database::connect();
        $sql = "INSERT INTO `paymenthistory` (`CustomerID`, `PlanID`, `PaidAmount`, `PaidDate`)
        VALUES (?, ?, ?, NOW())";
        $db->query($sql, [$id, $data["CurrentPlanID"], $data["PaidAmount"]]);

        if ($updated) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Client renewed successfully!'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to renew client. Please try again.'
            ]);
        }
    }

public function try($id)
{
    $clients1Model = new CustomerModel();
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