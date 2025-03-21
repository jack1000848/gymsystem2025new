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

    public function myqrcode(){
        return view('clientdashboard/myqrcode');
    }
}

?>