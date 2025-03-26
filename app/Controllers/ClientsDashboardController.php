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
    {   if (!session()->has('CoachID')) {
        return redirect()->to('/member-login'); // Redirect if not logged in
    }

    $coachID = session()->get('CoachID'); // Get logged-in Coach ID
    $data['client'] = $this->coachModel->find($coachID); // Fetch coach details

    if (!$data['client']) {
        return redirect()->to('/dashboard')->with('error', 'Coach not found.');
    }
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