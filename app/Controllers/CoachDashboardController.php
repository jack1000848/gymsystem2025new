<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
//use App\Models\Clients1Model;


class CoachDashboardController extends BaseController 
{
    public function index()
    {
        return view('coachdashboard/index');
    }
    
    

}

?>