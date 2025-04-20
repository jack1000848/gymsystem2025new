<?php

namespace App\Models;

use CodeIgniter\Model;

class SelectedCoachFromRegistrationModel extends Model
{
    protected $table = 'SelectedCoachFromRegistration'; // Replace with your actual table name
    protected $primaryKey = 'CustomerID';
    protected $allowedFields = ['CustomerID', 'ScheduleID', 'CoachID']; // Adjust based on your columns
}