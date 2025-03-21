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
    public function index()
    {
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

        if($img = $this->request->getFile('clientProfile'))  {
             if($img->isValid() && ! $img->hasMoved())  {
              $imageName = $img->getRandomName();
             $img->move('uploads/', $imageName); 

             }
        }
    
        $data = array(
            'Firstname'  => $this->request->getPost('clientFirst'),
            'Lastname'   => $this->request->getPost('clientLast'),
            'Password'   => $this->request->getPost('password'),
            'address'    => $this->request->getPost('clientAdress'), // Add 'address' to $allowedFields if not present
            'Email'      => $this->request->getPost('clientEmail'),
            'Avatar'     => $imageName,
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
    // Load the EquipmentModel
    $CoachModel = new \App\Models\CoachModel();

    // Get the input data from the request (this is assuming you're sending POST data)
    $data = [
        'CoachID' => $this->request->getPost('CoachID'),
        'Firstname' => $this->request->getPost('Firstname'),
        'Lastname' => $this->request->getPost('Lastname'),
        'Email' => $this->request->getPost('Email'),
        'Password' => $this->request->getPost('Password'),
        'Address' => $this->request->getPost('Address'),
        
    ];

    // Optionally, you can validate the data before updating
    if (!$data['CoachID'] || !$data['Firstname'] || !$data['Lastname'] ||!$data['Email'] || !$data['Password'] || !$data['Address']) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'All fields are required!'
        ]);
    }

    // Attempt to update the record in the database
    $updated = $CoachModel->update($id, $data);

    // Check if the update was successful
    if ($updated) {
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Equipment updated successfully!'
        ]);
    } else {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update equipment. Please try again.'
        ]);
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
