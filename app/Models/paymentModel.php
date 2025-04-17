<?php

namespace App\Models;

use CodeIgniter\Model;

class paymentModel extends Model
{
    protected $table = 'paymenthistory';
    protected $primaryKey = 'PaymentHistoryID';
    protected $allowedFields = ['CustomerID', 'PaidAmount', 'PaidDate', 'PlanID'];
}
