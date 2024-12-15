<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientsModel;

//qr
use App\Models\QrCodeModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;

class ClientsController extends BaseController

{
    public function index()
    {
        $fetchClient = new ClientsModel();
        $data['clients'] = $fetchClient->findAll();

        return view("client/manage", $data);
                    //("client/add") nakakagawa ako ng accout if naka manage hindi!//
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
        $insertClients = new ClientsModel();

        if($img = $this->request->getFile('clientProfile'))  {
             if($img->isValid() && ! $img->hasMoved())  {
              $imageName = $img->getRandomName();
             $img->move('uploads/', $imageName); 

             }
        }
    


        $data = array (
            'first_name'=> $this->request->getPost('clientFirst'),
            'last_name'=>$this->request->getPost('clientLast'),
            'password' =>$this->request->getPost('password'),
            'age'=>$this->request->getPost('clientAge'),
            'address'=>$this->request->getPost('clientAdress'),
            'email'=>$this->request->getPost('clientEmail'),
            'client_profile' => $imageName,

        );

        $insertClients->insert($data);

        return redirect()->to('/coach')->with ('success', 'Coach Added Successfully!');
    }

    public function editClient($id)

    { 
        $fetchClient = new ClientsModel();
        //$data ['client'] = $fetchClient->where('id,'$id)->first();

        return view ('client/', $data);
 
    }

    public function updateClient($id)
    {
        //update
    }

    public function deleteClient($id)
    {
        $deleteClient = new ClientsModel();
        $deleteClient->delete($id);

        return redirect()->to('/coach')->with('success', 'Coach Deleted Successfully!');
    }
}
