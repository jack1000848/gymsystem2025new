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

}