<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CreateMemberModel;
// eto sa qr

use App\Models\QrCodeModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;


class CreateMemberController extends BaseController


{
  public function testDatabase()
  {
      $db = \Config\Database::connect();
      if ($db->connect()) {
          echo "Database connected successfully.";
      } else {
          echo "Database connection failed.";
      }
  }
    public function index()
    {
        $fetchClients1 =new CreateMemberModel();
        $data['join-now'] = $fetchClients1 ->findAll();

      
        return view('create_member/createmember', $data);
    }

    public function linkcoach()
    {
        $coachModel = new CoachModel();
        $coaches = $coachModel->getCoaches();
        
        $data['coaches'] = $coaches;

        // Render the view with the data
        return view('clients1crud/add', $data);
    }



    public function createClients1()
    {
        $data['clients1Password'] = '20_'. uniqid();
        $data['gymcode'] = '155_' . uniqid();

        //qr
    
        return view('create_member/createmember', $data);
    }
    public function storeClient()
     {
        $insertClients = new CreateMemberModel ();

       

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
            

        );

        

        if ($insertClients->save($data)) {
            session()->setFlashdata('success', 'Account created successfully!');
            return redirect()->to('/member-login');
    } }
    
    
    
}