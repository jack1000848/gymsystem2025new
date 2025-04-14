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


}