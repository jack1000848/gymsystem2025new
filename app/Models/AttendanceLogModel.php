<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceLogModel extends Model
{
    protected $table = 'viewcustomerattendance'; // Replace with your actual table/view name
    protected $primaryKey = 'CustomerID';
    protected $allowedFields = ['CustomerID', 'FullName', 'CheckIn' , 'CheckOut']; 

    public function getCustomers()
    {
        return $this->findAll();
    }
}
