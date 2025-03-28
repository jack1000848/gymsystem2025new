<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ViewScheduleForAllUserModel;
use App\Models\CoachScheduleModel;

class ViewScheduleForAllUserController extends BaseController
{
    public function __construct()
    {
        $this->coachScheduleModel = new CoachScheduleModel();
       //  $this->timeModel = new TimeScheduleModel();
    }
        
    
    public function adminview()
    {  
        $fetchClients1 =new ViewScheduleForAllUserModel();
        $data['coach1'] = $fetchClients1 ->findAll();

        return view('client/adminviewsched' ,$data) ;
    }

    public function clientview()
    {
       return view('clients1crud/viewmyscheds');
    }

    public function coachview()
{
    $session = session();
    $coachID = $session->get('CoachID');

    if (!$coachID) {
        return redirect()->to('/coach-login')->with('error', 'You must be logged in');
    }

    $fetchview = new ViewScheduleForAllUserModel();
    $data['coach'] = $fetchview->where('CoachID', $coachID)->findAll();

    

    return view('coachdashboard/TimeSheds', $data);
}
}
?>
