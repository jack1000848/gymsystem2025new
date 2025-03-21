<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\models\ClientloginModel;

    class LoginClientController extends BaseController
{

    public function LoginClient()
    {
        return view('/clientdashboard/loginclient');     ///loginview 
    }
 

    public function authenticate()
    {
        $session = session();
        $model = new ClientloginModel();

        // Use email instead of username
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        // Use the updated method to get user by email
        $user = $model->getUserByEmail($email);

        if ($user) {
            // Check if the user is verified
            if ($user['is_verified'] == 0) { // Assuming 0 means not verified
                return redirect()->back()->with('error', 'Your account is not verified. Please check your email for verification.');
            }
        
            // Compare the password (Replace this with hashed password checking later)
            if ($password == $user['Password']) { 
                // Store user data in session
                $session->set([
                    'CustomerID' => $user['CustomerID'],
                    'Email' => $user['Email'],
                    'logged_in' => true,
                ]);
                return redirect()->to('/clientdashboard'); // Redirect to the client dashboard
            } else {
                // Password mismatch
                return redirect()->back()->with('error', 'Invalid password.');
            }
        } else {
            // User not found
            return redirect()->back()->with('error', 'Email not found.');
        }
        

    }

    
    

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/loginclient');
    }





}



?>