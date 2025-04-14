<?php

namespace App\Models;

use CodeIgniter\Model;

class SubtaskModel extends Model
{
    protected $table = 'subtasks';
    protected $primaryKey = 'SubtaskID';
    protected $allowedFields = ['TaskID', 'SubtaskName', 'IsCompleted'];
}