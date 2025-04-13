<?php

namespace App\Controllers;

use App\Models\Task;
use App\Models\Customer; // Assume you have a Customer model
use App\Models\Coach;    // Assume you have a Coach model
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class TaskController extends BaseController
{
    protected $taskModel;
    protected $customerModel;
    protected $coachModel;

    public function __construct()
    {
        $this->taskModel = new Task();
        $this->customerModel = new Customer();
        $this->coachModel = new Coach();
    }

    // Show form to create a task (for coaches)
    public function create()
    {
        // Check if user is a coach
        if (session()->get('role') !== 'coach') {
            return redirect()->to('/coachdashboard')->with('error', 'Unauthorized access');
        }

        $data['customers'] = $this->customerModel->findAll(); // List all clients
        return view('coachdashboard/markabsent', $data);
    }

    // Save a new task
    public function store()
    {
        if (session()->get('role') !== 'coach') {
            return redirect()->to('/coachdashboard')->with('error', 'Unauthorized access');
        }

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
        return redirect()->to('/coachdashboard/addtask')->with('success', 'Task assigned successfully');
    }

    // List tasks for a coach
    public function coachTasks()
    {
        if (session()->get('role') !== 'coach') {
            return redirect()->to('/coachdashboard')->with('error', 'Unauthorized access');
        }

        $data['tasks'] = $this->taskModel->getTasksByCoach(session()->get('CoachID'));
        return view('/coachdashboard/addtask', $data);
    }

    // List tasks for a client
    public function clientTasks()
    {
        if (session()->get('role') !== 'client') {
            return redirect()->to('/clientdashboard')->with('error', 'Unauthorized access');
        }

        $data['tasks'] = $this->taskModel->getTasksByCustomer(session()->get('CustomerID'));
        return view('clientdashboard/mytasks', $data);
    }

    // Mark task as completed (for clients)
    public function complete($taskID)
    {
        if (session()->get('role') !== 'client') {
            return redirect()->to('/clientdashboard')->with('error', 'Unauthorized access');
        }

        $task = $this->taskModel->find($taskID);
        if ($task && $task['CustomerID'] == session()->get('CustomerID')) {
            $this->taskModel->update($taskID, ['Status' => 'completed']);
            return redirect()->to('clientdashboard/mytasks')->with('success', 'Task marked as completed');
        }

        return redirect()->to('clientdashboard/mytasks')->with('error', 'Invalid task');
    }
}