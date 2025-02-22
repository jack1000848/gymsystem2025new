<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\models\LoginCoachModel;

    class LoginCoachController extends BaseController
{

    public function LoginCoach()
    {
        return view('coachdashboard/logincoach');     ///loginview 
    }
 

    public function authenticate1()
    {
        $session = session();
        $model = new LoginCoachModel();

        // Use email instead of username
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        // Use the updated method to get user by email
        $user = $model->getUserByEmail1($email);

        if ($user) {
            // Compare the password (Note: replace with hashed password checking later)
            if ($password == $user['Password']) { 
                // Store user data in session
                $session->set([
                    'CoachID' => $user['CoachID'],
                    'Email' => $user['Email'],
                    'logged_in' => true,
                ]);
                return redirect()->to('/coachdashboard'); // Redirect to the client dashboard
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