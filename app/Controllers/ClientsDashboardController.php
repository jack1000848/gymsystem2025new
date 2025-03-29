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
        if (!session()->has('isLoggedIn')) { // Dito dapat ang check, hindi sa login function
            return redirect()->to('/member-login')->with('error', 'Please login first.');
        }
        return view('clientdashboard/index');
    }

    //// here the client view their attendance log
    public function viewAttendance($customerID)
    {
        ///$data['customerID'] = $customerID;$qrAttendanceModel = new QrAttendanceModel();

    // Validate ID
    if (empty($customerID) || !is_numeric($customerID)) {
        return $this->response->setJSON(['error' => 'Invalid Customer ID'])->setStatusCode(400);
    }

    // Fetch attendance records for the client
    $attendanceRecords = $qrAttendanceModel
        ->where('CustomerID', $customerID)
        ->orderBy('InDate', 'DESC')
        ->findAll();

    // Check if records exist
    if (!$attendanceRecords) {
        return $this->response->setJSON(['error' => 'No attendance records found.']);
    }

    return $this->response->setJSON(['myattendance' => $attendanceRecords]);
       /// return view('clientdashboard/myattendance', $data);
    }



    /////heres the viewqrcode in dashboard

    public function myqrcode()
    {   
        if (!session()->has('CustomerID')) {
            return redirect()->to('/member-login')->with('error', 'Please login first.'); // Redirect if not logged in
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