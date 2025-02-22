<?php

namespace App\Models;

use CodeIgniter\Model;

class CoachPlanView extends Model
{
    protected $table            = 'CoachPlanView';  // The name of the view
    protected $primaryKey       = 'CoachID';  // Optional, if the view has a unique identifier like CoachID
    protected $useAutoIncrement = false;  // Views don't typically auto-increment
    protected $returnType       = 'array';  // You want to return data as an array
    protected $useSoftDeletes   = false;  // Views generally don't have soft deletes
    protected $protectFields    = true;
    
    // Define the fields you will be fetching from the view
    protected $allowedFields    = [
        'CoachID', 'PlanID', 'FullName'  // Include fields that exist in the view
    ];
    
    // Since you're only fetching data, you don't need insert/save logic
    // So the saveReservation method is not required unless you're updating data in a table
    // protected function saveReservation($data)
    // {
    //     return $this->insert($data);
    // }

    // Disable timestamps, as views generally don't support them
    protected $useTimestamps = false;

    // Validation and other settings
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks are not usually needed for views
    protected $allowCallbacks = false;
}
