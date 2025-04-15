<?php
namespace App\Controllers;

use App\Models\TaskModel;
use App\Models\CustomerModel;
use App\Models\CoachModel;
use App\Models\SubtaskModel;
use App\Services\PdfService;
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
    $customers = $this->customerModel->findAll(); // List all clients
    log_message('debug', 'Customers fetched: ' . json_encode($customers)); // Debug log
    $data['customers'] = $customers;
    return view('coachdashboard/assigntask', $data);
}

    // Save a new task
    public function store()
    {
        $coachID = session()->get('CoachID');
        $customerID = $this->request->getPost('CustomerID');

        $validationRules = [
            'CustomerID' => 'required|integer',
            'TaskTitle' => 'required|min_length[3]',
            'DueDate' => 'required|valid_date',
            'subtasks' => 'required',
            'subtasks.*' => 'required|min_length[3]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Save the main task
        $taskData = [
            'CustomerID' => $customerID,
            'CoachID' => $coachID,
            'TaskTitle' => $this->request->getPost('TaskTitle'),
            'TaskDescription' => $this->request->getPost('TaskDescription'),
            'DueDate' => $this->request->getPost('DueDate'),
            'Status' => 'pending',
            'Progress' => 0,
        ];

        $this->taskModel->insert($taskData);
        $taskID = $this->taskModel->insertID();

        // Save subtasks
        $subtaskModel = new SubtaskModel();
        $subtasks = $this->request->getPost('subtasks');
        foreach ($subtasks as $subtaskName) {
            $subtaskModel->insert([
                'TaskID' => $taskID,
                'SubtaskName' => $subtaskName,
                'IsCompleted' => false,
            ]);
        }

        return redirect()->to('/tasks/coach')->with('success', 'Task assigned successfully');
    }

    // List tasks for a coach
    public function coachTasks()
    {
        $data['tasks'] = $this->taskModel->getTasksByCoach(session()->get('CoachID'));
        return view('/coachdashboard/addtask', $data);
    }

    // List tasks for a client
    public function clientTasks()
    {
        $data['tasks'] = $this->taskModel->getTasksByCustomer(session()->get('CustomerID'));
        return view('clientdashboard/mytask', $data);
    }

    // Mark task as completed (for clients)
    public function complete($taskID)
    {
        $task = $this->taskModel->find($taskID);
        if ($task && $task['CustomerID'] == session()->get('CustomerID')) {
            $this->taskModel->update($taskID, ['Status' => 'completed']);
            return redirect()->to('/tasks/client')->with('success', 'Task marked as completed');
        }

        return redirect()->to('/clientdashboard/mytasks')->with('error', 'Invalid task');
    }

    public function updateProgress($taskID)
    {
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

    public function updateSubtasks($taskID)
    {
        $customerID = session()->get('CustomerID');
        if (!$customerID) {
            return redirect()->to('/client-login')->with('error', 'Please log in as a client');
        }

        // Verify the task belongs to this client
        $task = $this->taskModel->where('TaskID', $taskID)
                                ->where('CustomerID', $customerID)
                                ->first();

        if (!$task) {
            return redirect()->to('/tasks/client')->with('error', 'Task not found or not assigned to you');
        }

        if ($task['Status'] !== 'pending') {
            return redirect()->to('/tasks/client')->with('error', 'Cannot update subtasks for a non-pending task');
        }

        // Update subtasks
        $subtaskModel = new SubtaskModel();
        $subtasks = $subtaskModel->where('TaskID', $taskID)->findAll();
        $submittedSubtasks = $this->request->getPost('subtasks') ?? [];

        foreach ($subtasks as $subtask) {
            $isCompleted = isset($submittedSubtasks[$subtask['SubtaskID']]) ? 1 : 0;
            $subtaskModel->update($subtask['SubtaskID'], ['IsCompleted' => $isCompleted]);
        }

        // Recalculate progress
        $progress = $this->taskModel->calculateProgress($taskID);
        $this->taskModel->update($taskID, ['Progress' => $progress]);

        return redirect()->to('/tasks/client')->with('success', 'Subtasks updated successfully');
    }

    // Display form to update task status (for coaches)
    public function updateStatus($taskID)
    {
        $coachID = session()->get('CoachID');
        if (!$coachID) {
            return redirect()->to('/coach-login')->with('error', 'Please log in as a coach');
        }

        $task = $this->taskModel->select('tasks.*, customer.Firstname, customer.Lastname')
                                ->join('customer', 'customer.CustomerID = tasks.CustomerID')
                                ->where('tasks.TaskID', $taskID)
                                ->where('tasks.CoachID', $coachID)
                                ->first();

        if (!$task) {
            return redirect()->to('update-status/')->with('error', 'Task not found or not assigned by you');
        }

        return view('coachdashboard/assigntask', [
            'task' => $task
        ]);
    }

    // Handle task status update (for coaches)
    public function saveTaskStatus($taskID)
    {
        $coachID = session()->get('CoachID');
        if (!$coachID) {
            return redirect()->to('/coach-login')->with('error', 'Please log in as a coach');
        }

        $task = $this->taskModel->where('TaskID', $taskID)
                                ->where('CoachID', $coachID)
                                ->first();

        if (!$task) {
            return redirect()->to('/tasks/coach')->with('error', 'Task not found or not assigned by you');
        }

        $status = $this->request->getPost('status');
        $progress = $this->request->getPost('progress') ?? $task['Progress'];

        if (!in_array($status, ['pending', 'incomplete', 'completed'])) {
            return redirect()->to('/tasks/coach')->with('error', 'Invalid status selected.');
        }

        $data = [
            'Status' => $status,
            'Progress' => $progress
        ];

        // If status is completed, set CompletedAt
        if ($status === 'completed') {
            $data['Progress'] = 100;
            $data['CompletedAt'] = date('Y-m-d H:i:s');
        } elseif ($status === 'incomplete' && $task['CompletedAt']) {
            // If changing from completed to incomplete, clear CompletedAt
            $data['CompletedAt'] = null;
        } elseif ($status === 'pending') {
            // If changing to pending, clear CompletedAt and PdfPath
            $data['CompletedAt'] = null;
            $data['PdfPath'] = null;
        }

        // If status is incomplete or completed, generate PDF
        if (in_array($status, ['incomplete', 'completed'])) {
            // Fetch additional data for PDF
            $task = $this->taskModel->select('tasks.*, equipment.Description AS EquipmentName')
                                    ->join('equipment', 'equipment.EquipmentID = tasks.EquipmentID', 'left')
                                    ->where('tasks.TaskID', $taskID)
                                    ->first();
            $client = $this->customerModel->find($task['CustomerID']);
            $coach = $this->coachModel->find($task['CoachID']);

            if (!$client || !$coach) {
                return redirect()->to('/tasks/coach')->with('error', 'Client or coach not found.');
            }

            // Generate PDF
            $pdfService = new PdfService();
            $pdfService->generateTaskCompletionPdf($task, $client, $coach);

            // Save the PDF file
            $filename = 'task_' . $taskID . '_status.pdf';
            $pdfPath = $pdfService->savePdf($filename);

            // Update task with PDF path
            $data['PdfPath'] = $pdfPath;
        }

        // Update the task
        $this->taskModel->update($taskID, $data);

        // If status is incomplete or completed, redirect to download PDF
        if (in_array($status, ['incomplete', 'completed'])) {
            return redirect()->to('/tasks/download-pdf/' . $taskID)
                             ->with('success', 'Task status updated to ' . $status . '. Download the PDF below.');
        }

        return redirect()->to('/tasks/coach')->with('success', 'Task status updated successfully.');
    }

    // Download the generated PDF
    public function downloadPdf($taskID)
    {
        $coachID = session()->get('CoachID');
        if (!$coachID) {
            return redirect()->to('/coach-login')->with('error', 'Please log in as a coach');
        }

        $task = $this->taskModel->where('TaskID', $taskID)
                                ->where('CoachID', $coachID)
                                ->first();

        if (!$task || !$task['PdfPath']) {
            return redirect()->to('/tasks/coach')->with('error', 'PDF not found for this task.');
        }

        $client = $this->customerModel->find($task['CustomerID']);
        $coach = $this->coachModel->find($task['CoachID']);
        $task = $this->taskModel->select('tasks.*, equipment.Description AS EquipmentName')
                                ->join('equipment', 'equipment.EquipmentID = tasks.EquipmentID', 'left')
                                ->where('tasks.TaskID', $taskID)
                                ->first();

        // Generate the PDF again for download
        $pdfService = new PdfService();
        $pdfService->generateTaskCompletionPdf($task, $client, $coach);

        // Output the PDF for download
        $filename = 'task_' . $taskID . '_status.pdf';
        $pdfService->outputPdfForDownload($filename);

        return redirect()->to('/tasks/coach')->with('success', 'PDF downloaded successfully.');
    }

    public function checkStatus($taskID)
{
    $coachID = session()->get('CoachID');
    if (!$coachID) {
        return redirect()->to('/coach-login')->with('error', 'Please log in as a coach');
    }

    $task = $this->taskModel->select('tasks.*, customer.Firstname, customer.Lastname')
                            ->join('customer', 'customer.CustomerID = tasks.CustomerID')
                            ->where('tasks.TaskID', $taskID)
                            ->where('tasks.CoachID', $coachID)
                            ->first();

    if (!$task) {
        return redirect()->to('/tasks/coach')->with('error', 'Task not found or not assigned by you');
    }

    return view('coachdashboard/check_task_status', [
        'task' => $task
    ]);
}
public function updateStatusModal($taskID)
{
    // Ensure this is an AJAX request
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
    }

    $coachID = session()->get('CoachID');
    if (!$coachID) {
        return $this->response->setJSON(['success' => false, 'message' => 'Please log in as a coach']);
    }

    // Fetch the task
    $task = $this->taskModel->where('TaskID', $taskID)
                            ->where('CoachID', $coachID)
                            ->first();

    if (!$task) {
        return $this->response->setJSON(['success' => false, 'message' => 'Task not found or not assigned by you']);
    }

    $newStatus = $this->request->getPost('status');
    if (!in_array($newStatus, ['pending', 'incomplete', 'completed'])) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid status selected']);
    }

    $data = [
        'Status' => $newStatus,
    ];

    // If status is completed, set Progress to 100 and set CompletedAt
    if ($newStatus === 'completed') {
        $data['Progress'] = 100;
        $data['CompletedAt'] = date('Y-m-d H:i:s');
    } elseif ($newStatus === 'incomplete' && $task['CompletedAt']) {
        // If changing from completed to incomplete, clear CompletedAt
        $data['CompletedAt'] = null;
    } elseif ($newStatus === 'pending') {
        // If changing to pending, clear CompletedAt and PdfPath
        $data['CompletedAt'] = null;
        $data['PdfPath'] = null;
    }

    // If status is incomplete or completed, generate PDF
    if (in_array($newStatus, ['incomplete', 'completed'])) {
        // Fetch additional data for PDF
        $task = $this->taskModel->select('tasks.*, equipment.Description AS EquipmentName')
                                ->join('equipment', 'equipment.EquipmentID = tasks.EquipmentID', 'left')
                                ->where('tasks.TaskID', $taskID)
                                ->first();
        $client = $this->customerModel->find($task['CustomerID']);
        $coach = $this->coachModel->find($task['CoachID']);

        if (!$client || !$coach) {
            return $this->response->setJSON(['success' => false, 'message' => 'Client or coach not found']);
        }

        // Generate PDF
        $pdfService = new PdfService();
        $pdfService->generateTaskCompletionPdf($task, $client, $coach);

        // Save the PDF file
        $filename = 'task_' . $taskID . '_status.pdf';
        $pdfPath = $pdfService->savePdf($filename);

        // Update task with PDF path
        $data['PdfPath'] = $pdfPath;
    }

    // Update the task
    $this->taskModel->update($taskID, $data);

    return $this->response->setJSON(['success' => true, 'message' => 'Task status updated successfully']);
}
}