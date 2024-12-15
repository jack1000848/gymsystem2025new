<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\models\ClientloginModel;

    class LoginClientController extends BaseController
{

    public function LoginClient()
    {
        return view('clientdashboard/loginclient');     ///loginview 
    }
 

    public function authenticate()
    {
        $session = session();
        $model = new ClientloginModel();

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $model->getUserByUsername($username);

        if ($user) {
            if ($password == $user['password']) { // Replace with hashing later
                $session->set([
                    'user_id' => $user['id'],
                    'user_name' => $user['user_name'],
                    'logged_in' => true,
                ]);
                return redirect()->to('/clientdashboard'); // Replace with your dashboard URL
            } else {
                return redirect()->back()->with('error', 'Invalid password.');
            }
        } else {
            return redirect()->back()->with('error', 'Username1 not found.');
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