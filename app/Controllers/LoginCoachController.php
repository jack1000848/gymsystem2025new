<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\models\LoginCoachnModel;

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

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $user = $model->getUserByEmail($email);

        if ($email) {
            if ($password == $user['password']) { // Replace with hashing later
                $session->set([
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'logged_in' => true,
                ]);
                return redirect()->to('/coachdashboard'); // Replace with your dashboard URL
            } else {
                return redirect()->back()->with('error', 'Invalid password.');
            }
        } else {
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