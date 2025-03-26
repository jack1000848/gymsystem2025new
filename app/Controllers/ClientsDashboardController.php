<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
//use App\Models\Clients1Model;


class ClientsDashboardController extends BaseController 
{
    protected $coachModel; // Declare the model

    public function __construct()
    {
    
         $this->coachModel = model(CoachModel::class);
    }
    
    public function index()
    {
        return view('clientdashboard/index');
    }
    
    /////heres the viewqrcode in dashboard

    public function myqrcode()
    {   if (!session()->has('CustomerID')) {
        return redirect()->to('/member-login'); // Redirect if not logged in
    }

    dd(session()->get('CustomerID')); // <--- Paste this here and test
    $customerID = session()->get('CustomerID'); // Get logged-in Coach ID
    $data['client'] = $this->coachModel->find($customerID); // Fetch coach details

    if (!$data['client']) {
        return redirect()->to('/clientdashboard')->with('error', 'Coach not found.');
    }
        return view('clientdashboard/myqrcode');
    }

    public function logout()
    {
        ///// Destroy the entire session//
        session()->destroy();

        // Optional: Redirect to login or home page
        return redirect()->to('/member-login')->with('success', 'You have been logged out.');
    }
}

?>