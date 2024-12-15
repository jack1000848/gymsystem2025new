<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PlansModel;


class PlanController extends BaseController
{
    public function indexgymplan()
    {
        $fetchPlan =new PlansModel();
        $data['gymplans'] = $fetchPlan ->findAll();

        return view('gymplan/manageplan', $data);
    }

    
    public function creategymplan()
    {
        return view('gymplan/manageplan', $data);
    }
    public function storegymplan()
     {
        $insertEquipments = new PlansModel ();

        
        $data = array(
            'plan_name' => $this->request->getPost('Pname'), 
            'description' => $this->request->getPost('description'),
            'duration_in_months' => $this->request->getPost('durationim'),
            'price' => $this->request->getPost('price'),
            'trainer_included' => $this->request->getPost('trainer'),
            'creation_date' => $this->request->getPost('creation'),
            'active' => $this->request->getPost('active'),

        );

        $insertEquipments->insert($data);

        return redirect()->to('/gymplans')->with('success', 'Equipment Added Successfully!');

    }
    
}


?>