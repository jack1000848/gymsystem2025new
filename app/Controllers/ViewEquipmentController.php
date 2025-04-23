<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ViewEquipmentModel;


class ViewEquipmentController extends BaseController
{

    public function indexviewequipment()
    {
        if (!session()->has('isLoggedIn')) { // Dito dapat ang check, hindi sa login function
            return redirect()->to('/member-login')->with('error', 'Please login first.');
        }
      
        $fetchview =new ViewEquipmentModel();
        $data['viewequipment'] = $fetchview ->findAll();

        return view('clientdashboard/ViewGymEquipment', $data);
    }

    public function indexviewequipment1()
    {
        if (!session()->has('CoachID')) {
            return redirect()->to('/member-login'); // Redirect if not logged in
        }
        
        $fetchview =new ViewEquipmentModel();
        $data['viewequipment1'] = $fetchview ->findAll();

        return view('coachdashboard/viewequipment', $data);
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