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
    if (!session()->has('logged_in')) {
        return redirect()->to('/member-login')->with('error', 'Please login first.');
    }

    $session = session();
    $customerModel = new CustomerModel();

    // Get email and password from the POST request
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    // Fetch the client record by email
    $client = $customerModel->where('Email', $email)->first();

    if (!$client) {
        return redirect()->back()->with('error', 'No account found.');
    }

    // Check if account is frozen/disabled
    if ($client['is_frozen']) {
        return redirect()->back()->with('error', 'Your account is disabled. Please contact admin.');
    }

    // Check if the account is verified
    if ($client['is_verified'] == 0) {  // Assuming 0 means not verified
        return redirect()->back()->with('error', 'Your account is not verified. Please check your email for verification.');
    }

    // Check the password (Use password_verify if password is hashed)
    if ($client['Password'] === $password) {  // Change to password_verify() if needed
        // Set session data
        $session->set([
            'isLoggedIn' => true,
            'CustomerID' => $client['CustomerID'],
            'Email' => $client['Email'],
            'role' => 'Client',
            'logged_in' => true, // Optional but if you use it somewhere, keep it
        ]);

        // Optional: If you want to set CustomerID again as you requested
        session()->set('CustomerID', $client['CustomerID']); 

        return redirect()->to('/clientdashboard');
    } else {
        return redirect()->back()->with('error', 'Invalid password.');
    }
}

    

    




}



?>