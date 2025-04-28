<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\LoginCoachModel;

class LoginClientController extends BaseController
{
    public function LoginClient()
    {
        $session = session();
        if ($session->get('isLoggedIn')) {
            // Redirect based on role if already logged in
            $role = $session->get('Role');
            if ($role === 'Customer') {
                return redirect()->to('/clientdashboard');
            } elseif ($role === 'Coach') {
                return redirect()->to('/coachdashboard');
            } elseif ($role === 'Admin') {
                return redirect()->to('/admin');
            }
        }
        return view('/clientdashboard/loginclient'); // Load the unified login view
    }

    public function authenticate()
    {
        $session = session();
        $customerModel = new CustomerModel();
        $coachModel = new LoginCoachModel();

        // Get email and password from the POST request
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Check for hardcoded admin credentials
        if ($email === 'admin1@example.com' && $password === 'admin1') {
            $session->set([
                'isLoggedIn' => true,
                'AdminID' => 1, // Hardcoded ID for admin
                'Email' => $email,
                'Role' => 'Admin',
                'logged_in' => true,
            ]);
            return redirect()->to('/admin');
        }

        // Check if the email exists in the Customer (Client) table
        $client = $customerModel->where('Email', $email)->first();
        if ($client) {
            // Check if account is frozen/disabled
            if ($client['is_frozen']) {
                return redirect()->back()->with('error', 'Your account is disabled. Please contact admin.');
            }

            // Verify password
            if (password_verify($password, $client['password_hash'])) {
                // Set session data for Client
                $session->set([
                    'isLoggedIn' => true,
                    'CustomerID' => $client['CustomerID'],
                    'Email' => $client['Email'],
                    'Role' => 'Customer',
                    'logged_in' => true,
                ]);
                return redirect()->to('/clientdashboard');
            } else {
                return redirect()->back()->with('error', 'Invalid password.');
            }
        }

        // Check if the email exists in the Coach table
        $coach = $coachModel->where('Email', $email)->first();
        if ($coach) {
            // Verify password
            if (password_verify($password, $coach['password_hash'])) {
                // Set session data for Coach
                $session->set([
                    'isLoggedIn' => true,
                    'CoachID' => $coach['CoachID'],
                    'Email' => $coach['Email'],
                    'Role' => 'Coach',
                    'logged_in' => true,
                ]);
                return redirect()->to('/coachdashboard');
            } else {
                return redirect()->back()->with('error', 'Invalid password.');
            }
        }

        // If neither Admin, Client, nor Coach is found
        return redirect()->back()->with('error', 'Email not found.');
    }

    // Optional: Unified Forgot Password
    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }
}
