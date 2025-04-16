<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PlanModel;
use App\Models\CoachModel;
use App\Models\CoachPlanModel;
use app\Models\CoachScheduleModel;

class PlanController extends BaseController
{
    protected $session; // Declare session variable
    protected $planModel;
    protected $coachPlanModel;
    public function __construct()
    {
        $this->session = session(); // Initialize session
        $this->planModel = new PlanModel();
        $this->coachPlanModel = new CoachPlanModel();
    }
/////trt to add coach schedule
    public function getCoachSchedule($coachID)
{
    $schedules = $this->coachScheduleModel->where('CoachID', $coachID)->findAll();
    return $this->response->setJSON($schedules);
}
    public function indexgymplan()
    {
        if (!$this->session->has('logged_in')) {
            return redirect()->to('/joinus')->with('error', 'Please log in first.');
        }
            
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
          ///  'GymTimeSlot' => $this->request->getPost('timeslot'),
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
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request method.'
            ])->setStatusCode(405);
        }
    
        // Normalize checkbox value and input fields
        $planData = [
            'PlanName'    => $this->request->getPost('Pname'),
            'Description' => $this->request->getPost('description'),
            'Duration'    => $this->request->getPost('durationim'),
            'Price'       => $this->request->getPost('price'),
            'IsActive'    => $this->request->getPost('active') == '1' ? 1 : 0,
        ];
    
        $planId = $this->request->getPost('id');
        $coachIDs = $this->request->getPost('coaches');
    
        // Ensure $coachIDs is an array
        if (!is_array($coachIDs)) {
            $coachIDs = explode(',', $coachIDs); // fallback if it's a comma-separated string
        }
    
        try {
            if ($planId) {
                // Update existing plan
                $this->planModel->update($planId, $planData);
    
                // Clear and re-add coach relationships
                $this->coachPlanModel->where('PlanID', $planId)->delete();
    
                foreach ($coachIDs as $coachID) {
                    if (!empty($coachID)) {
                        $this->coachPlanModel->insert([
                            'CoachID' => $coachID,
                            'PlanID'  => $planId
                        ]);
                    }
                }
    
                $message = 'Plan updated successfully!';
            } else {
                // Create new plan
                $planId = $this->planModel->insert($planData);
    
                foreach ($coachIDs as $coachID) {
                    if (!empty($coachID)) {
                        $this->coachPlanModel->insert([
                            'CoachID' => $coachID,
                            'PlanID'  => $planId
                        ]);
                    }
                }
    
                $message = 'Plan created successfully!';
            }
    
            return $this->response->setJSON([
                'status' => 'success',
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to save plan: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
    
}
?>