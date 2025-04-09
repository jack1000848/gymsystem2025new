<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CoachModel;

//qr
use App\Models\QrCodeModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;

class CoachController extends BaseController

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


        $fetchClient = new CoachModel();
        $data['coaches'] = $fetchClient->findAll();
        $maxId = $fetchClient->selectMax('coachid')->first(); 
        $nextId = isset($maxId['coachid']) ? $maxId['coachid'] + 1 : 1;
        $data['next_id'] = $nextId;

        return view("client/manage", $data);
        
    }
   
    
    public function createClient() : string
    {
        
        $data['clientPassword'] = '20_'. uniqid();
        
        $gymcode = '2_' . uniqid();
    $data['gymcode'] = $gymcode;
    $this->load->view('client', $data);
        //return view('client/', $data);
    }

    public function storeClient()
    
    {
        $insertClients = new CoachModel();
         // Retrieve the email from the form input
    $email = $this->request->getPost('clientEmail');

    // Check if email is retrieved properly
    if (empty($email)) {
        return redirect()->back()->with('error', 'Email field is required.');
    }

    // Check if the email is a Gmail address
    if (!preg_match("/^[a-zA-Z0-9._%+-]+@gmail\.com$/", $email)) {
        return redirect()->to('/coach')->with('error', 'Only Gmail addresses are allowed.');
      
    }

        
        
    
        $data = array(
            'Firstname'  => $this->request->getPost('clientFirst'),
            'Lastname'   => $this->request->getPost('clientLast'),
            'Password'   => $this->request->getPost('password'),
            'address'    => $this->request->getPost('clientAdress'), // Add 'address' to $allowedFields if not present
            'Email'      => $this->request->getPost('clientEmail'),
           // 'Avatar'     => $imageName,
            'RegisteredDate' => date('Y-m-d H:i:s'), // Automatically set registration date
        );
        

        $insertClients->insert($data);

        return redirect()->to('/coach')->with ('success', 'Coach Added Successfully!');
    }

    public function edit($id)
    {
        $coachModel = new CoachModel();
    
        // Fetch the equipment data by ID
        $coach = $coachModel->find($id);
    
        if (!$coach) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Coach not found'
            ]);
        }
    
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $coach
        ]);
    }

    public function update($id)
{
    $coachModel = new CoachModel();

    // Get input data
    $data = $this->request->getPost();

    

    // Prepare update data
    $updateData = [
        'Firstname' => $data['Firstname'],
        'Lastname'  => $data['Lastname'],
        'Email'     => $data['Email'],
        'password_hash'  => password_hash($data['password_hash'], PASSWORD_DEFAULT), // Hash password
        'Address'   => $data['Address'],
    ];

    // Perform update
    if ($coachModel->update($id, $updateData)) {
        return $this->response->setJSON(['status' => 'success', 'message' => 'Coach updated successfully!']);
    } else {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update Coach.']);
    }
}




public function deleteCoach($id)
{
    $deleteCoach = new CoachModel();

    $isDeleted = $deleteCoach->delete($id);

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


}
