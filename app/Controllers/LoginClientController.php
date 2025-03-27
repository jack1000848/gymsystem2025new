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
        $customerModel = new CustomerModel();  // Model for Clients
        $coachModel = new LoginCoachModel();   // Model for Coaches

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // 1st: Check in Client table
        $user = $customerModel->where('Email', $email)->first();
        $role = 'Client';
        $dashboard = '/clientdashboard';

        // 2nd: If not found, check in Coach table
        if (!$user) {
            $user = $coachModel->where('Email', $email)->first();
            $role = 'Coach';
            $dashboard = '/coachdashboard';
        }

        // If user not found
        if (!$user) {
            return redirect()->back()->with('error', 'No account found.');
        }

        // Check if the account is frozen (for clients only)
        if ($role == 'Client' && isset($user['is_frozen']) && $user['is_frozen']) {
            return redirect()->back()->with('error', 'Your account is disabled. Please contact admin.');
        }

        // Check if the account is verified (for clients only)
        if ($role == 'Client' && isset($user['is_verified']) && $user['is_verified'] == 0) {
            return redirect()->back()->with('error', 'Your account is not verified. Please check your email.');
        }

        // Check password
        if ($user['Password'] === $password) { 
            // Set session based on user type
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