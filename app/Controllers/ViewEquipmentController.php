<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ViewEquipmentModel;


class ViewEquipmentController extends BaseController
{

    public function indexviewequipment()
    {
      
        $fetchview =new ViewEquipmentModel();
        $data['viewequipment'] = $fetchview ->findAll();

        return view('clientdashboard/ViewGymEquipment', $data);
    }

    
   // public function indexviewequipment()
   // {
   //     $fetchview =new EquipmentModel();
   //     $data['viewequipment'] = $fetchview ->findAll();
///
  //      return view('clientdashboard/ViewGymEquipment', $data);
  //  }
}


?>