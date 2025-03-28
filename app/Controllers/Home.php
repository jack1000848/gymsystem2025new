<?php

namespace App\Controllers;
use App\Models\CustomerModel;

class Home extends BaseController
{
/*************  ✨ Codeium Command ⭐  *************/
    /**
     * Homepage for the user
     * 
     * This function renders the frontend view and checks if the password_hash is null
     * for each client. If it is, it will update the password_hash with the password
     * using the password_hash() function.
     * 
     * @return string The rendered view
     */
/******  00d1838e-870a-46cd-862a-fcb86bdea220  *******/
    public function index(): string
    {
        $fetchClients1 =new CustomerModel();
        $fetchClients = $fetchClients1->findAll();
        //loop through the data
        foreach($fetchClients as $client){
            //check if password_hash is null
            if($client['password_hash'] == null){
                //update the password_hash with the password
                $fetchClients1->update($client['CustomerID'], ['password_hash' => password_hash($client['Password'], PASSWORD_BCRYPT)]);
            }
        }
        return view('frontend');
    }
    public function joinus() 
    {
        return view('joinus');
    }



    public function admindashboard() 
    {
        return view('admindashboard/admindashboard');
    }

    
}
