<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerPlanModel extends Model
{
    protected $table = 'customer'; // Change to the correct table
    protected $primaryKey = 'CustomerID';
    protected $allowedFields = [
        'CustomerID', 'Firstname', 'Middlename', 'Lastname', 'ExpirationDate'
    ];

    // Custom method to fetch customer data with Fullname
    public function getCustomerWithFullname($id)
    {
        return $this->select('CustomerID, CONCAT(Firstname, " ", IFNULL(Middlename, ""), " ", Lastname) AS Fullname, ExpirationDate')
                    ->where('CustomerID', $id)
                    ->first();
    }
}
