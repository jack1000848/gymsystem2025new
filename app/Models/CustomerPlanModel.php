<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerPlanModel extends Model
{
    protected $table = 'CustomerPlan'; 
    protected $primaryKey = 'CustomerID';
    protected $allowedFields = ['CustomerID', 'CustomerName', 'PlanName', 'ExpirationDate'];

    /**
     * Fetch all customer plan data from the view.
     * @return array
     */
    public function getAllCustomersWithPlans()
    {
        return $this->findAll();
    }
}
