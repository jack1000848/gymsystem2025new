<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PlanModel;
use App\Models\CoachModel;
use App\Models\CoachPlanModel;


class PlanController extends BaseController
{
    public function indexgymplan()
    {
        $fetchPlan =new PlanModel();
        $fetchCoaches = new CoachModel();
        $data['coaches'] = $fetchCoaches->findAll();
        $data['gymplans'] = $fetchPlan ->findAll();

        return view('gymplan/manageplan', $data);
    }

    
    public function creategymplan()
    {
        return view('gymplan/manageplan', $data);
    }
    public function storegymplan()
     {
        $planData = [
            'PlanName' => $this->request->getPost('Pname'),
            'Description' => $this->request->getPost('description'),
            'Duration' => $this->request->getPost('durationim'),
            'Price' => $this->request->getPost('price'),
            'TrainerIncluded' => $this->request->getPost('trainer'),
            'IsActive' => $this->request->getPost('active')
        ];
    
        $planModel = new PlanModel();
        $planID = $planModel->insert($planData);
        $coachIDs = $this->request->getPost('coaches'); 
        $coachPlanModel = new CoachPlanModel();

        foreach ($coachIDs as $coachID) {
            $coachPlanModel->insert([
                'CoachID' => $coachID,
                'PlanID' => $planID
            ]);
        }

        return redirect()->to('/gymplans')->with('success', 'Equipment Added Successfully!');

    }
    
}


?>