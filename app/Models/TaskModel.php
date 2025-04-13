<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table = 'tasks';
    protected $primaryKey = 'TaskID';
    protected $allowedFields = [
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
        return $this->where('CustomerID', $customerID)
                    ->findAll();
    }

    // Get tasks assigned by a specific coach
    public function getTasksByCoach($coachID)
    {
        return $this->where('CoachID', $coachID)
                    ->join('customer', 'customer.CustomerID = tasks.CustomerID')
                    ->findAll();
    }
}