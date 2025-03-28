<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ViewSheduleForAllUserModel;
use App\Models\CoachScheduleModel;


class ViewSheduleForAllUserController extends BaseController
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
    $fetchview =new ViewSheduleForAllUserModel();
    $data['coach'] = $fetchview ->findAll();
    return view('coachdashboard/TimeSheds');
}

}
?>