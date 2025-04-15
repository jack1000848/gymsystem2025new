<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerBodyHistoryModel extends Model
{
    protected $table = 'customer_body_history';
    protected $primaryKey = 'HistoryID';
    protected $allowedFields = ['CustomerID', 'Weight', 'Height', 'RecordDate', 'Notes'];

    public function getHistory($customerId)
    {
        return $this->where('CustomerID', $customerId)
                    ->orderBy('RecordDate', 'ASC')
                    ->findAll();
    }

    public function addHistory($data)
    {
        return $this->insert($data);
    }
    public function getCustomersByCoach($coachID)
    {
        return $this->where('CoachID', $coachID)->findAll();
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
