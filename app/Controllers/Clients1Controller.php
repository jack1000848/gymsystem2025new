<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Clients1Model;
// eto sa qr

use App\Models\QrCodeModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;


class Clients1Controller extends BaseController


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
        $fetchClients1 =new Clients1Model();
        $data['clients1'] = $fetchClients1 ->findAll();

        //testing
        //$data['coaches'] = [
        //    'coach1',
          //  'coach2',
        //    'coach3'
       // ];
       ///for dynamic

       

        return view('clients1crud/list', $data);
    }

    public function linkcoach()
    {
        $coachModel = new CoachModel();
        $coaches = $coachModel->getCoaches();
        
        $data['coaches'] = $coaches;

        // Render the view with the data
        return view('clients1crud/add', $data);
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
        $data['gymcode'] = '155_' . uniqid();

        //qr
    
        return view('clients1crud/add', $data);
    }
    public function storeClients1()
     {
        $insertClients = new Clients1Model ();

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

       ///$clients1Model = new Clients1Model();
       // $clients1Model->saveReservation($data);

        
        

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
            //qr
            
          ///  'duration' => $this->request->getPost('duration'),
           /// 'qr_code_path' => $this->request->getPost('qrcode'),


            //$data = $this->request->getPost('data');
           // if (!$data) {
            //    return redirect()->back()->with('error', 'Please enter data to generate QR code.');
           // }
    
            // Generate QR code
            
            
           

       
        );

        $insertClients->insert($data);

        return redirect()->to('/clients1')->with('success', 'Clients Added Successfully!');
    }
    public function editClients1($id)
    {
        $fetchClients1 =new Clients1Model();
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
          $insertClients = new Clients1Model ();
          $updateClients1 = new Clients1Model();
        
        
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
      $deleteClients1 = new Clients1Model();
      $deleteClients1->delete($id);

      return redirect()->to('/clients1')->with('success', 'Client Deleted Successfully!');
    }

    public function renew()
    {
        return view('/clients1');
    }

    
}