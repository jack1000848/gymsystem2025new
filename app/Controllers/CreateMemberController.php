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
        

        

        if ($insertClients->save($data)) {
            session()->setFlashdata('success', 'Account created successfully!');
            return redirect()->to('/member-login');
    } }
    
    
    
}