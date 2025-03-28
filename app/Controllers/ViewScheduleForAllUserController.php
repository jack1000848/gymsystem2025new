<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ViewScheduleForAllUserModel;
use App\Models\CoachScheduleModel;

class ViewScheduleForAllUserController extends BaseController
{
    public function adminview()
    {
        return view('clients1crud/adminviewsched');
    }

    public function clientview()
    {
        return view('clients1crud/adminviewsched');
    }

    public function coachview()
    {
        $fetchview = new ViewScheduleForAllUserModel();
        $data['coach'] = $fetchview->findAll();
        return view('coachdashboard/TimeSheds', $data);
    }
}
?>
