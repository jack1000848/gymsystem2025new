<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
//use App\Models\Clients1Model;
use App\Models\CustomerModel;


class ClientsDashboardController extends BaseController 
{
    protected $clientsModel; // Declare the model

    public function __construct()
    {
    
         $this->clientsModel = model(CustomerModel::class);
    }
    
    public function index()
    {
        return view('clientdashboard/index');
    }
    
    /////heres the viewqrcode in dashboard

    public function myqrcode()
    {   
        if (!session()->has('CustomerID')) {
            return redirect()->to('/member-login'); // Redirect if not logged in
        }

        $customerID = session()->get('CustomerID'); // Get logged-in Customer ID

        // Debugging: Ensure session is working
        // dd($customerID); // ✅ If this prints a valid number, session is OK

        // ✅ Fetch client details from the correct table
        $data['client'] = $this->clientsModel->find($customerID); 

        if (!$data['client']) {
            return redirect()->to('/clientdashboard')->with('error', 'Client not found.');
        }

        return view('clientdashboard/myqrcode', $data); // ✅ Pass client data to the view
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