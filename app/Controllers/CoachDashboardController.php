<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CoachScheduleModel;
use App\Models\TimeScheduleModel;
//use App\Models\Clients1Model;
use CodeIgniter\HTTP\ResponseInterface;

class CoachDashboardController extends BaseController 
{
    public function __construct()
    {
        $this->workoutModel = new CoachScheduleModel();
        $this->timeModel = new TimeScheduleModel();
    }
    public function index()
    {
        return view('coachdashboard/index');
    }

    ///here's the coach manage my schedules
    public function coachManage(){

        $daysched = new CoachScheduleModel(); // Change to your actual model name
        ///$timesched = new TimeScheduleModel();
        
        $data['sched'] = $daysched->findAll(); // Fetch all schedules from the database
       // $data['time'] = $timesched->findAll(); // Fetch all schedules from the database
        return view('/coachdashboard/ManagemyScheds', $data);
    }
    
    ppublic function storemanage()
    { 
        $model = new CoachScheduleModel();
    
        // Validate POST data
        $validation = \Config\Services::validation();
        $validation->setRules([
            'startdate' => 'required',
            'starttime' => 'required',
            'enddate'   => 'required',
            'endtime'   => 'required'
        ]);
    
        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }
    
        // Get the inputs
        $startDate = $this->request->getPost('startdate');
        $startTime = $this->request->getPost('starttime');
        $endDate   = $this->request->getPost('enddate');
        $endTime   = $this->request->getPost('endtime');  // ✅ FIXED: Fetch the end time properly
        $coachID   = session()->get('CoachID'); // Assuming the coach is logged in
    
        // Combine date and time into one datetime format
        $start = date('Y-m-d H:i:s', strtotime($startDate . ' ' . $startTime));
        $end   = date('Y-m-d H:i:s', strtotime($endDate . ' ' . $endTime));
    
        // Save to DB
        $model->insert([
            'CoachID'      => $coachID,
            'ScheduleDate' => $startDate,
            'Start'        => $start,
            'End'          => $end,
            'CustomerID'   => null // You can update this if you have CustomerID
        ]);
    
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Schedule saved successfully'
        ]);
    }
    



    public function edit($id)
    {
        $scheduleModel = new CoachScheduleModel();
    
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
        $scheduleModel = new CoachScheduleModel();
    
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
        $model = new CoachScheduleModel();
    
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

           
            $db = \Config\Database::connect();
            $sql = "SELECT * FROM ViewCoachSchedule";
            $query = $db->query($sql);
            $data['coach'] = $query->getResult();
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