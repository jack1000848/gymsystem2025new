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
        $data['clients'] = $fetchClient->findAll();
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

    public function editClient($id)

    { 
        $fetchClient = new CoachModel();
        //$data ['client'] = $fetchClient->where('id,'$id)->first();

        return view ('client/', $data);
 
    }

    public function updateClient($id)
    {
        //update
    }

    public function deleteClient($id)
    {
        $deleteClient = new CoachModel();
        $deleteClient->delete($id);

        return redirect()->to('/coach')->with('success', 'Coach Deleted Successfully!');
    }
}
