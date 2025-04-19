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

    public function getMonthlyPayments()
    {
        $data = [['Month', 'Total Paid Amount']];
        $totalPayments = 0;

        for ($i = 4; $i >= 0; $i--) {
            $monthStart = Time::now()->subMonths($i)->modify('first day of this month')->setTime(0, 0, 0);
            $monthEnd = Time::now()->subMonths($i)->modify('last day of this month')->setTime(23, 59, 59);

            $this->resetQuery();
            $this->selectSum('PaidAmount', 'total_amount');
            $this->where('PaidDate >=', $monthStart->toDateString());
            $this->where('PaidDate <=', $monthEnd->toDateString());

            $result = $this->get()->getRow();
            $totalAmount = $result->total_amount ? (float)$result->total_amount : 0;
            $monthLabel = $monthStart->format('F Y');

            $data[] = [$monthLabel, $totalAmount];
            $totalPayments += $totalAmount;
        }

        return [
            'data' => $data,
            'total' => $totalPayments
        ];
    }
}
