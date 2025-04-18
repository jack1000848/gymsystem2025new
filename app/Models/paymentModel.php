<?php

namespace App\Models;

use CodeIgniter\Model;

class paymentModel extends Model
{
    protected $table = 'paymenthistory';
    protected $primaryKey = 'PaymentHistoryID';
    protected $allowedFields = ['CustomerID', 'PaidAmount', 'PaidDate', 'PlanID'];

    // Optional: Add validation rules
    protected $validationRules = [
        'CustomerID' => 'required|integer',
        'PaidAmount' => 'required|decimal',
        'PaidDate' => 'required|valid_date',
        'PlanID' => 'required|integer',
    ];
}
