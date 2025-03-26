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
        $this->coachScheduleModel = new CoachScheduleModel();
         $this->timeModel = new TimeScheduleModel();
    }
    public function dashboardindex()
    {

        return view('coachdashboard/index');
    }

    ///here's the coach manage my schedules
    public function coachManage()
    {
        $coachID = session()->get('CoachID'); // Get logged-in coach's ID

        if (!$coachID) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        // Filter schedules by the logged-in coach only
        $data['sched'] = $this->coachScheduleModel->where('CoachID', $coachID)->findAll();

        return view('/coachdashboard/ManagemyScheds', $data);
    }

    // Store Schedule
    public function storemanage()
    {
        $validation = \Config\Services::validation();
        $rules = [
            'startdate' => 'required',
            'starttime' => 'required',
            'enddate'   => 'required',
            'endtime'   => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $startDateTime = $this->request->getPost('startdate') . ' ' . $this->request->getPost('starttime');
        $endDateTime   = $this->request->getPost('enddate') . ' ' . $this->request->getPost('endtime');
        $coachID = session()->get('CoachID');

        if (!$coachID) {
            return redirect()->back()->with('error', 'CoachID not found in session.');
        }

        $data = [
            'CoachID'      => $coachID,
            'ScheduleDate' => $this->request->getPost('startdate'),
            'Start'        => $startDateTime,
            'End'          => $endDateTime,
        ];

        $this->coachScheduleModel->insert($data);

        return redirect()->to('/coach-manage')->with('success', 'Schedule added successfully.');
    }



    public function edit($id)
    {
        $schedule = $this->CoachScheduleModel->find($id);
        return $this->response->setJSON($schedule);
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $this->CoachScheduleModel->update($id, [
            'ScheduleDate' => $this->request->getPost('startdate'),
            'Start' => $this->request->getPost('starttime'),
            'End' => $this->request->getPost('endtime')
        ]);
        return $this->response->setJSON(['status' => 'success']);
    }

    public function delete($id)
    {
        $this->CoachScheduleModel->delete($id);
        return $this->response->setJSON(['status' => 'deleted']);
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

/////////////LOGOUT\\\\\\\\\\\\\\\\\\\\\
public function logout()
    {
        // Destroy the entire session
        session()->destroy();

        // Optional: Redirect to login or home page
        return redirect()->to('/login')->with('success', 'You have been logged out.');
    }
    
}

?>