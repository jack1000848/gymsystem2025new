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
        ///return view('gymplan/manageplan');
    }

    public function edit($id)
    {
        $fetchPlan =new PlanModel();
        $plan = $fetchPlan->find($id);
        return $this->response->setJSON($plan);
    }

    public function delete($id)
    {
        $fetchPlan =new PlanModel();
        $fetchPlan->delete($id);
        return $this->response->setJSON(['success' => true]);
    }
    
    public function update($id)
    {
        $fetchPlan =new PlanModel();
        $data = [
            'PlanName' => $this->request->getPost('Pname'),
            'Description' => $this->request->getPost('description'),
            'Duration' => $this->request->getPost('durationim'),
            'GymTimeSlot' => $this->request->getPost('timeslot'),
            'Price' => $this->request->getPost('price'),
            'TrainerIncluded' => $this->request->getPost('trainer'),
            'IsActive' => $this->request->getPost('active')
        ];
        $fetchPlan->update($id, $data);
        return $this->response->setJSON(['success' => true]);
    }

    
    public function creategymplan()
    {
        return view('gymplan/manageplan', $data);
    }
    public function storegymplan()
     {
        if (!$this->request->getPost('Pname')) {
            return redirect()->back()->with('error', 'Plan Name is required.');
        }
        
        $planData = [
            'PlanName' => $this->request->getPost('Pname'),
            'Description' => $this->request->getPost('description'),
            'Duration' => $this->request->getPost('durationim'),
            'GymTimeSlot' => $this->request->getPost('timeslot'),
            'Price' => $this->request->getPost('price'),
            'TrainerIncluded' => $this->request->getPost('trainer'),
           'isActive' => $this->request->getPost('active') ? 1 : 0,

        ];
    
        $planModel = new PlanModel();
        $planID = $planModel->insert($planData);
        $coachIDs = $this->request->getPost('coaches'); 
        $coachPlanModel = new CoachPlanModel();
        if($coachIDs != null){
            foreach ($coachIDs as $coachID) {
                $coachPlanModel->insert([
                    'CoachID' => $coachID,
                    'PlanID' => $planID
                ]);
            }
        }
        

        return redirect()->to('/gymplans')->with('success', 'Gym Plan Added Successfully!');

    }
    
}


?>