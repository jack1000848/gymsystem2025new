<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table = 'tasks';
    protected $primaryKey = 'TaskID';
    protected $allowedFields = [
        'TaskID',
        'CustomerID',
        'CoachID',
        'TaskTitle',
        'TaskDescription',
        'DueDate',
        'Status',
        'Progress',
        'PdfPath',
    ];
    protected $useTimestamps = false;

    // Get tasks for a specific client
    public function getTasksByCustomer($customerID)
    {
        return $this->select('tasks.*, coach.Firstname') // Fetch coach name for display
                    ->join('coach', 'coach.CoachID = tasks.CoachID')
                    ->where('tasks.CustomerID', $customerID)
                    ->findAll();
    }
    // Get tasks assigned by a specific coach
    
    public function getTasksByCoach($coachID)
{
    return $this->select('tasks.*, customer.Firstname') // Adjust CustomerName as needed
                ->join('customer', 'customer.CustomerID = tasks.CustomerID')
                ->where('tasks.CoachID', $coachID) // Explicitly specify the table
                ->findAll();
}
// Fetch subtasks for a task
public function getSubtasks($taskID)
{
    return $this->db->table('subtasks')
                    ->where('TaskID', $taskID)
                    ->get()
                    ->getResultArray();
}

// Calculate progress based on completed subtasks
public function calculateProgress($taskID)
{
    $subtasks = $this->getSubtasks($taskID);
    if (empty($subtasks)) {
        return 0;
    }

    $totalSubtasks = count($subtasks);
    $completedSubtasks = count(array_filter($subtasks, fn($subtask) => $subtask['IsCompleted'] == 1));
    return round(($completedSubtasks / $totalSubtasks) * 100);
}

// New method to mark a task as completed and set the PDF path
public function markTaskAsCompleted($taskId, $pdfPath = null)
{
    return $this->update($taskId, [
        'Status' => 'completed',
        'Progress' => 100,
        'CompletedAt' => date('Y-m-d H:i:s'),
        'PdfPath' => $pdfPath
    ]);
}
public function updateStatusDirect($taskID)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $coachID = session()->get('CoachID');
        if (!$coachID) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please log in as a coach']);
        }

        $task = $this->taskModel->where('TaskID', $taskID)
                                ->where('CoachID', $coachID)
                                ->first();

        if (!$task) {
            return $this->response->setJSON(['success' => false, 'message' => 'Task not found or not assigned by you']);
        }

        $newStatus = $this->request->getPost('status');
        if (!in_array($newStatus, ['pending', 'incomplete', 'completed'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status selected', 'oldStatus' => $task['Status']]);
        }

        $data = [
            'Status' => $newStatus,
        ];

        if ($newStatus === 'completed') {
            $data['Progress'] = 100;
            $data['CompletedAt'] = date('Y-m-d H:i:s');
        } elseif ($newStatus === 'incomplete' && $task['CompletedAt']) {
            $data['CompletedAt'] = null;
        } elseif ($newStatus === 'pending') {
            $data['CompletedAt'] = null;
            $data['PdfPath'] = null;
        }

        if (in_array($newStatus, ['incomplete', 'completed'])) {
            $task = $this->taskModel->where('tasks.TaskID', $taskID)->first();
            $client = $this->customerModel->find($task['CustomerID']);
            $coach = $this->coachModel->find($task['CoachID']);

            if (!$client || !$coach) {
                return $this->response->setJSON(['success' => false, 'message' => 'Client or coach not found']);
            }

            $pdfService = new PdfService();
            $pdfService->generateTaskCompletionPdf($task, $client, $coach);
            $filename = 'task_' . $taskID . '_status.pdf';
            $pdfPath = $pdfService->savePdf($filename);
            $data['PdfPath'] = $pdfPath;
        }

        $this->taskModel->update($taskID, $data);

        return $this->response->setJSON(['success' => true, 'message' => 'Task status updated successfully']);
    }
    public function coachTasks()
    {
        $coachID = session()->get('CoachID');
        if (!$coachID) {
            return redirect()->to('/coach-login')->with('error', 'Please log in as a coach');
        }

        $tasks = $this->taskModel->select('tasks.*, customer.Firstname, customer.Lastname')
                                 ->join('customer', 'customer.CustomerID = tasks.CustomerID')
                                 ->where('tasks.CoachID', $coachID)
                                 ->findAll();

        return view('coachdashboard/addtask', ['tasks' => $tasks]);
    }
}