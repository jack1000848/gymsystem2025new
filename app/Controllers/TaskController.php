<?php

namespace App\Controllers;

use App\Models\TaskModel;
use App\Models\CustomerModel; // Assume you have a Customer model
use App\Models\CoachModel;    // Assume you have a Coach model
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class TaskController extends BaseController
{
    protected $taskModel;
    protected $customerModel;
    protected $coachModel;

    public function __construct()
    {
        $this->taskModel = new TaskModel();
        $this->customerModel = new CustomerModel();
        $this->coachModel = new CoachModel();
    }
   

    // Show form to create a task (for coaches)
    public function create()
    {
        // Check if user is a coach
       // if (session()->get('role') !== 'coach') {
//return redirect()->to('/coachdashboard')->with('error', 'Unauthorized access');
      //  }

        $data['customers'] = $this->customerModel->findAll(); // List all clients
        return view('coachdashboard/assigntask', $data);
    }

    // Save a new task
    public function store()
    {
       /// if (session()->get('role') !== 'coach') {
       //     return redirect()->to('/coachdashboard')->with('error', 'Unauthorized access');
      //  }

        $validationRules = [
            'CustomerID' => 'required|integer',
            'TaskTitle' => 'required|min_length[3]',
            'DueDate' => 'required|valid_date',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'CustomerID' => $this->request->getPost('CustomerID'),
            'CoachID' => session()->get('CoachID'), // Assume CoachID in session
            'TaskTitle' => $this->request->getPost('TaskTitle'),
            'TaskDescription' => $this->request->getPost('TaskDescription'),
            'DueDate' => $this->request->getPost('DueDate'),
            'Status' => 'pending',
        ];

        $this->taskModel->save($data);
        return redirect()->to('/tasks/coach')->with('success', 'Task assigned successfully');
    }

    // List tasks for a coach
    public function coachTasks()
    {
     //   if (session()->get('role') !== 'coach') {
     //       return redirect()->to('/tasks/coach')->with('error', 'Unauthorized access');
     //  }

        $data['tasks'] = $this->taskModel->getTasksByCoach(session()->get('CoachID'));
        return view('/coachdashboard/addtask', $data);
    }

    // List tasks for a client
    public function clientTasks()
    {
       // if (session()->get('role') !== 'client') {
       //     return redirect()->to('/clientdashboard')->with('error', 'Unauthorized access');
       // }

        $data['tasks'] = $this->taskModel->getTasksByCustomer(session()->get('CustomerID'));
        return view('clientdashboard/mytask', $data);
    }

    // Mark task as completed (for clients)
    public function complete($taskID)
    {
       /// if (session()->get('role') !== 'client') {
       ///     return redirect()->to('/clientdashboard')->with('error', 'Unauthorized access');
      ///  }

        $task = $this->taskModel->find($taskID);
        if ($task && $task['CustomerID'] == session()->get('CustomerID')) {
            $this->taskModel->update($taskID, ['Status' => 'completed']);
            return redirect()->to('tasks/client')->with('success', 'Task marked as completed');
        }

        return redirect()->to('clientdashboard/mytasks')->with('error', 'Invalid task');
    }
    public function updateProgress($taskID)
{
   // if (session()->get('role') !== 'client') {
    //    return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
   // }

    $customerID = session()->get('CustomerID');
    if (!$customerID) {
        return redirect()->to('/login')->with('error', 'Please log in as a client');
    }

    // Verify the task belongs to this client
    $task = $this->taskModel->where('TaskID', $taskID)
                            ->where('CustomerID', $customerID)
                            ->first();

    if (!$task) {
        return redirect()->to('/tasks/client')->with('error', 'Task not found or not assigned to you');
    }

    if ($task['Status'] !== 'pending') {
        return redirect()->to('/tasks/client')->with('error', 'Cannot update progress for a non-pending task');
    }

    // Calculate progress based on steps completed
    $stepsCompleted = (int) $this->request->getPost('steps_completed');
    $progress = $stepsCompleted * 33; // 0 steps: 0%, 1 step: 33%, 2 steps: 66%, 3 steps: 100%
    if ($stepsCompleted == 3) {
        $progress = 100; // Ensure 3 steps = 100%
    }

    // Update the task's progress
    $this->taskModel->update($taskID, ['Progress' => $progress]);
    return redirect()->to('/tasks/client')->with('success', 'Progress updated successfully');
}
public function updateStatus($taskID)
{
   // if (session()->get('role') !== 'coach') {
   //     return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
  //  }

    $coachID = session()->get('CoachID');
    if (!$coachID) {
        return redirect()->to('/login')->with('error', 'Please log in as a coach');
    }

    // Verify the task belongs to this coach
    $task = $this->taskModel->where('TaskID', $taskID)
                            ->where('CoachID', $coachID)
                            ->first();

    if (!$task) {
        return redirect()->to('/tasks/coach')->with('error', 'Task not found or not assigned by you');
    }

    $status = $this->request->getPost('status');
    if (!in_array($status, ['pending', 'completed', 'incomplete'])) {
        return redirect()->to('/tasks/coach')->with('error', 'Invalid status');
    }

    // Update the task's status
    $this->taskModel->update($taskID, ['Status' => $status]);
    return redirect()->to('/tasks/coach')->with('success', 'Task status updated successfully');
}
}