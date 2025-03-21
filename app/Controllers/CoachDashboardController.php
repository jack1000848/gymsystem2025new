<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\WorkoutScheduleModel;
use App\Models\TimeScheduleModel;
//use App\Models\Clients1Model;


class CoachDashboardController extends BaseController 
{
    public function __construct()
    {
        $this->workoutModel = new WorkoutScheduleModel();
        $this->timeModel = new TimeScheduleModel();
    }
    public function index()
    {
        return view('coachdashboard/index');
    }

    ///here's the coach manage my schedules
    public function coachManage(){

        $daysched = new WorkoutScheduleModel(); // Change to your actual model name
        $timesched = new TimeScheduleModel();
        $data['sched'] = $daysched->findAll(); // Fetch all schedules from the database
        $data['time'] = $timesched->findAll(); // Fetch all schedules from the database
        
        return view('/coachdashboard/ManagemyScheds', $data);
    }
    
    public function storemanage(){

        // Load the validation service
    $validation = \Config\Services::validation();
    $validation->setRules([
        'wschedule'   => 'required',
        'wplan' => 'required'
    ]);

    // Validate the input
    if (!$this->validate($validation->getRules())) {
        return $this->response->setStatusCode(400)->setJSON([
            'error' => 'All fields are required.'
        ]);
    }

    // Prepare data for insertion
    $scheduleData = [
        'Day'           => $this->request->getPost('wschedule'),
        'WorkoutPlanID' => $this->request->getPost('wplan')
    ];

    // Insert data using the model
    $scheduleModel = new WorkoutScheduleModel();
    $scheduleID = $scheduleModel->insert($scheduleData);

    if ($scheduleID) {
        return $this->response->setStatusCode(200)->setJSON([
            'success' => 'Workout Schedule added successfully.'
        ]);
    } else {
        return $this->response->setStatusCode(500)->setJSON([
            'error' => 'Failed to add workout schedule.'
        ]);
    }
    }


    public function edit($id)
    {
        $scheduleModel = new WorkoutScheduleModel();
    
        // Find the schedule by ScheduleID
        $schedule = $scheduleModel->find($id);
    
        if (!$schedule) {
            // If no record found, return 404 JSON response
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Schedule not found.'
            ]);
        }
    
        // Return the schedule data as JSON
        return $this->response->setStatusCode(200)->setJSON($schedule);
    }
    

    public function update($id)
    {
        $scheduleModel = new WorkoutScheduleModel();
    
        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'wschedule'   => 'required',
            'wplan' => 'required'
        ]);
    
        if (!$this->validate($validation->getRules())) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'All fields are required.'
            ]);
        }
    
        // Prepare data for update
        $data = [
            'Day'   => $this->request->getPost('wschedule'),
            'WorkoutPlanID' => $this->request->getPost('wplan')
        ];
    
        // Update the schedule
        $updated = $scheduleModel->update($id, $data);
    
        if ($updated) {
            return $this->response->setStatusCode(200)->setJSON([
                'success' => 'Workout schedule updated successfully.'
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Failed to update workout schedule.'
            ]);
        }
    }

    public function delete($id)
    {
        $model = new WorkoutScheduleModel();
    
        // Check if the record exists
        $schedule = $model->find($id);
        if (!$schedule) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Schedule not found'])->setStatusCode(404);
        }
    
        // Delete the schedule
        $model->delete($id);
    
        return $this->response->setJSON(['status' => 'success', 'message' => 'Schedule deleted successfully']);
    }
    

        ////here's the time schedule//


        public function coachtimeManage(){

            $model = new TimeScheduleModel(); // Change to your actual model name
            $data['time'] = $model->findAll(); // Fetch all schedules from the database
            
            return view('/coachdashboard/TimeSheds', $data);
        }
        public function timestore()
        {
            // Load model if not loaded in constructor
            $this->timeModel = new \App\Models\TimeScheduleModel();
        
            $startTime = $this->request->getPost('start');
            $endTime = $this->request->getPost('end');
        
            // Validate required fields
            if (empty($startTime) || empty($endTime)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Start Time and End Time are required.'
                ]);
            }
        
            $data = [
                'StartTime' => $startTime,
                'EndTime'   => $endTime
            ];
        
            // Insert to database
            if ($this->timeModel->insert($data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Time Schedule added successfully.'
                ]);
            } else {
                // Get error from the model
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to add time schedule.',
                    'error' => $this->timeModel->errors()
                ]);
            }
        }
        
        public function editTime($id)
    {
        $time = $this->timeModel->find($id);
        return $this->response->setJSON($time);
    }

    // Update Time Schedule
    public function updateTime($id)
    {
        $data = [
            'ID'        => $id,
            'StartTime' => $this->request->getPost('start'),
            'EndTime'   => $this->request->getPost('end'),
        ];
        $this->timeModel->save($data);
        return $this->response->setJSON(['status' => 'updated']);
    }

    // Delete Time Schedule
    public function deleteTime($id)
    {
        $this->timeModel->delete($id);
        return $this->response->setJSON(['status' => 'deleted']);
    }
        





    ///////////// this is the coach client list!
    public function coachclientlist(){
        return view ('/coachdashboard/viewmyclient');
    }


    
}

?>