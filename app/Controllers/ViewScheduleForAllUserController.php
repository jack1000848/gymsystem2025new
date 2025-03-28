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
        return view('clients1crud/adminviewsched');
    }

    public function clientview()
    {
       // return view('clients1crud/adminviewsched');
    }

    public function coachview()
    {
        $session = session(); // Start session
        $coachID = $session->get('CoachID'); // Retrieve logged-in coach's ID
    
        if (!$coachID) {
            return redirect()->to('/coach-login')->with('error', 'You must be logged in');
        }

        $fetchview = new ViewScheduleForAllUserModel();
        $data['coach'] = $fetchview->findAll();
        // Filter schedules by the logged-in coach only
        $data['coach'] = $this->coachScheduleModel->where('CoachID', $coachID)->findAll();
        return view('coachdashboard/TimeSheds', $data);
    }
}
?>
