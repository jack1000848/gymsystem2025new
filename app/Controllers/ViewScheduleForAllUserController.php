<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ViewScheduleForAllUserModel;
use App\Models\CoachScheduleModel;

class ViewScheduleForAllUserController extends BaseController
{
    public function __construct()
    {
        helper('url');
        $this->session = session();
        $this->coachScheduleModel = new CoachScheduleModel();
       //  $this->timeModel = new TimeScheduleModel();
    }
        
    
    public function adminview()
    {  
        
    if (!$this->session->has('logged_in')) {
        return redirect()->to('/joinus')->with('error', 'Please log in first.');
    }

    if ($this->session->get('Role') != 'Admin') {
        $roleVal = $this->session->get('Role');
        if ($roleVal == 'Customer') {
            return redirect()->to('/clientdashboard')->with('error', 'You are not authorized to access this page.');
        } else if ($roleVal == 'Coach') {
            return redirect()->to('/coachdashboard')->with('error', 'You are not authorized to access this page.');
        }
    }
    
        $fetchClients1 =new ViewScheduleForAllUserModel();
        $data['coach1'] = $fetchClients1 ->findAll();

        return view('client/adminviewsched' ,$data) ;
    }

    public function clientview()
    {
        $session = session();
    $customerID = $session->get('CustomerID');

    if (!$customerID) {
        return redirect()->to('/member-login')->with('error', 'You must be logged in');
    }

    $fetchview = new ViewScheduleForAllUserModel();
    $data['coach2'] = $fetchview->where('CustomerID', $customerID)->findAll();
       return view('clients1crud/viewmyscheds', $data);
    }

    public function coachview()
{
    $session = session();
    $coachID = $session->get('CoachID');

    if (!$coachID) {
        return redirect()->to('/member-login')->with('error', 'You must be logged in');
    }

    $fetchview = new ViewScheduleForAllUserModel();
    $data['coach'] = $fetchview->where('CoachID', $coachID)->findAll();

    

    return view('coachdashboard/TimeSheds', $data);
}
}
?>
