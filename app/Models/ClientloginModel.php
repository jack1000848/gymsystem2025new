<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientloginModel extends Model
{
    protected $table            = 'customer';
    protected $primaryKey       = 'CustomerID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'CustomerID','Firstname', 'Middlename', 'Lastname', 'Address', 'Gender', 'PhoneNumber', 'Email', 'phone_number', 'Password', 'RegisteredDate','CurrentPlanID', 'ExpirationDate','WorkoutTypeID','WorkoutPlanID'
        
   
    ];
    
    public function getUserByEmail($email)
    {
        return $this->where('Email', $email)->first();
    }
    public function saveReservation($data)
    {
        return $this->insert($data);
    }
    
    
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
