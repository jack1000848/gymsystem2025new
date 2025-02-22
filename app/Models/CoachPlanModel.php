<?php

namespace App\Models;

use CodeIgniter\Model;

class CoachPlanModel extends Model
{
    protected $table            = 'coachplan';
    protected $primaryKey       = 'CoachPlanID'; // If you have an auto-increment ID for this table
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'CoachID', 'PlanID'
    ];

    // Optionally add methods for inserting or deleting records from the junction table
}
