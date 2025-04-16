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
        $fetchPlan = new PlanModel();
        $plan = $fetchPlan->find($id);
        
        if ($plan) {
            // Fetch associated coaches if needed
            // Assuming you have a relationship or separate table for plan-coaches
            $plan['coaches'] = $this->getPlanCoaches($id); // Implement this method as needed
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $plan
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Plan not found'
        ], 404);
    }

    public function delete($id)
    {
        $fetchPlan = new PlanModel();
        if ($fetchPlan->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Plan deleted successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to delete plan'
        ], 400);
    }
    
    public function update($id)
    {
        $planModel = new PlanModel();
        $data = [
            'PlanName' => $this->request->getPost('Pname'),
            'Description' => $this->request->getPost('description'),
            'Duration' => $this->request->getPost('durationim'),
            'Price' => $this->request->getPost('price'),
            'IsActive' => $this->request->getPost('active') ? 1 : 0
        ];

        $coaches = $this->request->getPost('coaches');
        if ($coaches) {
            $this->updatePlanCoaches($id, $coaches);
        }

        if ($planModel->update($id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Plan updated successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update plan'
        ])->setStatusCode(400);
    }

    // Placeholder method for handling plan coaches
    private function getPlanCoaches($planId)
    {
        // Implement logic to fetch associated coaches
        // This might involve querying a junction table
        return []; // Return array of coach IDs
    }

    private function updatePlanCoaches($planId, $coaches)
    {
        // Implement logic to update plan-coach relationships
        // This might involve updating a junction table
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