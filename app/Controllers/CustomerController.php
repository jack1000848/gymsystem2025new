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
use Endroid\QrCode\Builder\Builder;


class CustomerController extends BaseController


{
 
    public function index()
    {
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
        'types_of_workout'   => $this->request->getPost('tworkout'), 
        'GymTimeSlot' => $this->request->getPost('timeslot'),                  // Maps directly
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
public function viewClient($id)
{
    $customerModel = new CustomerModel();
    $client = $customerModel->find($id);

    if (!$client) {
        return redirect()->to('/clients1')->with('error', 'Client not found.');
    }

    return view('clients1crud/client_view', ['client' => $client]);

}

}