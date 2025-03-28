<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ViewEquipmentModel;


class ViewEquipmentController extends BaseController
{
public function adminview ()
{

    return view('clients1crud/adminviewsched');
}

public function clientview ()
{

    return view('clients1crud/adminviewsched');
}
public function coachview ()
{

    return view('clients1crud/adminviewsched');
}

}
?>