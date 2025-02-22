<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\PlanModel;
use App\Models\CoachPlanView;

// eto sa qr

use App\Models\QrCodeModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;


class CustomerController extends BaseController


{
 
    public function index()
    {
        $fetchClients1 =new CustomerModel();
        $data['clients1'] = $fetchClients1 ->findAll();
      
    
      $maxId = $fetchClients1->selectMax('customerid')->first(); 
      $nextId = isset($maxId['customerid']) ? $maxId['customerid'] + 1 : 1;
      $data['next_id'] = $nextId;

      return view('clients1crud/list', $data);

       

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

      return view('clients1crud/add', $data);
    }
    public function storeClients1()
     {
        $insertClients = new CustomerModel ();

       /// $name = $this->request->getPost('first_name');
       /// $date = $this->request->getPost('date_of_registration');
       /// $duration = $this->request->getPost('duration');

        // Generate QR code data
       // $qrCodeData = "Name: $name\nDate: $date\nDuration: $duration days";

        // Generate QR code image (using a library like Endroid/QrCode)
        // ... (code to generate QR code image and save it to a file)

       // $data = [
           // 'first_name' => $name,
         //   'date' => $date,
         //   'date_of_registration' => $duration,
          ///  'qr_code_path' => $qrCodeImagePath // Replace with actual image path
       // ];

       ///$CustomerModel = new CustomerModel();
       // $CustomerModel->saveReservation($data);

        
        

       $data = [
        'CustomerID'       => $this->request->getPost('gymcode'),                 // Maps directly
        'Firstname'        => $this->request->getPost('clients1Fname'),           // Maps directly
        ///'Middlename'       => $this->request->getPost('clients1Mname') ?? null,   // Add if required
        'Lastname'         => $this->request->getPost('clients1Lname'),           // Adjusted field name
        'Address'          => $this->request->getPost('clients1Username'),     // Adjusted field name
        'Gender'           => $this->request->getPost('gender'),                  // Maps directly
      // 'PhoneNumber'      => $this->request->getPost('phone_number'),            // Add phone field
        'Email'            => $this->request->getPost('clients1Emailaddress'),    // Adjusted field name
        'Password'         => $this->request->getPost('password'),
        'RegisteredDate'   => $this->request->getPost('dateofregistration'), 
        'types_of_workout'   => $this->request->getPost('tworkout'),                   // Maps directly
        'Membesrship_plan'   => $this->request->getPost('plans'),      // Adjusted field name
        'WorkoutTypeID'    => null,                // Adjusted field name
        'CurrentPlanID'    => null,                   // Adjusted field name
              
        'WorkoutPlanID'    =>  null, // Add if necessary
    ];
    
           
           

       
      

        $insertClients->insert($data);

        return redirect()->to('/clients1')->with('success', 'Clients Added Successfully!');
    }
    public function editClients1($id)
    {
        $fetchClients1 =new CustomerModel();
        $data['clients1'] = $fetchClients1 ->where('id', $id)->first();

        
        
       return view('clients1crud/edit',$data);
    }
    public function updateClients1($id)
    {
       /// if($img = $this->request->getFile('studentProfile')) {
           /// if($img->isValid() && !$img->hasMoved()) {
          ///      $imageName = $img->getRandomName();
          ///      $img->move('uploads/', $imageName);
          //  }
          $insertClients = new CustomerModel ();
          $updateClients1 = new CustomerModel();
        
        
        $data = array(
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
            'workout_type' => $this->request->getPost('tworkout'),
            'plans' => $this->request->getPost('plans'),
            'amount' => $this->request->getPost('amount'),
           // 'plans' => $this->request->getPost('clients1Phonenumber'),
           // 'attendance_count' => $this->request->getPost('clients1Phonenumber'),

            
        );  
  
        $updateClients1->update($id, $data);
        
        

        return redirect()->to('/clients1')->with('success', 'Client Updated Successfully!');
    }

    public function deleteClients1($id)
    {
      $deleteClients1 = new CustomerModel();
      $deleteClients1->delete($id);

      return redirect()->to('/clients1')->with('success', 'Client Deleted Successfully!');
    }

    public function renew()
    {
        return view('/clients1');
    }

    
}