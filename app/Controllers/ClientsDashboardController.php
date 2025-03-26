<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
//use App\Models\Clients1Model;


class ClientsDashboardController extends BaseController 
{
    public function index()
    {
        return view('clientdashboard/index');
    }
    
    /////heres the viewqrcode in dashboard

    public function myqrcode()
    {
        return view('clientdashboard/myqrcode');
    }

    public function logout()
    {
        // Destroy the entire session
        session()->destroy();

        // Optional: Redirect to login or home page
        return redirect()->to('/member-login')->with('success', 'You have been logged out.');
    }
}

?>