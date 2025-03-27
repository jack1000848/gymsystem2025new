<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ClientloginModel;
use App\Models\CustomerModel;

    class LoginClientController extends BaseController
{

    public function LoginClient()
    {
        return view('/clientdashboard/loginclient');     ///loginview 
    }
 

    public function authenticate()
    {
        $session = session();
        $customerModel = new CustomerModel();
        $coachModel = new LoginCoachModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('role');

        if ($role == 'Client') {
            $user = $customerModel->where('Email', $email)->first();
            $dashboard = '/clientdashboard';
        } else {
            $user = $coachModel->where('Email', $email)->first();
            $dashboard = '/coachdashboard';
        }

        if (!$user) {
            return redirect()->back()->with('error', 'No account found.');
        }

        if (isset($user['is_frozen']) && $user['is_frozen']) {
            return redirect()->back()->with('error', 'Your account is disabled. Please contact admin.');
        }

        if (isset($user['is_verified']) && $user['is_verified'] == 0) {
            return redirect()->back()->with('error', 'Your account is not verified. Please check your email.');
        }

        if ($user['Password'] === $password) { 
            $session->set([
                'isLoggedIn' => true,
                'UserID' => $user['CustomerID'] ?? $user['CoachID'], 
                'Email' => $user['Email'],
                'role' => $role
            ]);
            return redirect()->to($dashboard);
        } else {
            return redirect()->back()->with('error', 'Invalid password.');
        }
    }

    

    




}



?>