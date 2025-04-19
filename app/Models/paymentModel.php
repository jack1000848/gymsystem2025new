<?php

namespace App\Models;

use CodeIgniter\Model;

class paymentModel extends Model
{
    protected $table = 'paymenthistory';
    protected $primaryKey = 'PaymentHistoryID';
    protected $allowedFields = ['CustomerID', 'PaidAmount', 'PaidDate', 'PlanID'];

    protected $validationRules = [
        'CustomerID' => 'required|integer',
        'PaidAmount' => 'required|decimal',
        'PaidDate' => 'required|valid_date',
        'PlanID' => 'required|integer',
    ];

    public function getPaymentsWithDetails()
    {
        return $this->select('paymenthistory.*, CONCAT(customer.Firstname, " ", customer.Lastname) as CustomerName, plan.PlanName')
            ->join('customer', 'customer.CustomerID = paymenthistory.CustomerID', 'left')
            ->join('plan', 'plan.PlanID = paymenthistory.PlanID', 'left')
            ->findAll();
    }
}
