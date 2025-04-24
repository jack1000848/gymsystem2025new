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
    public function getPaymentWithDetails($id)
    {
        return $this->select('PaymentHistory.*, Customers.CustomerName, Plans.PlanName, Plans.Price as PlanPrice')
            ->join('Customers', 'Customers.CustomerID = PaymentHistory.CustomerID')
            ->join('Plans', 'Plans.PlanID = PaymentHistory.PlanID')
            ->where('PaymentHistory.PaymentHistoryID', $id)
            ->first();
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
    public function getPaymentsByMonth($yearMonth)
    {
        [$year, $month] = explode('-', $yearMonth);
        return $this->select('paymenthistory.*, CONCAT(customer.Firstname, " ", customer.Lastname) as CustomerName, plan.PlanName')
            ->join('customer', 'customer.CustomerID = paymenthistory.CustomerID')
            ->join('plan', 'plan.PlanID = paymenthistory.PlanID')
            ->where("YEAR(paymenthistory.PaidDate) = $year")
            ->where("MONTH(paymenthistory.PaidDate) = $month")
            ->findAll();
    }

    // Assuming getPaymentsWithDetails1 is used in the main payment view
    public function getPaymentsWithDetails1()
    {
        return $this->select('paymenthistory.*, CONCAT(customer.Firstname, " ", customer.Lastname) as CustomerName, plan.PlanName, plan.Price as PlanPrice')
            ->join('customer', 'customer.CustomerID = paymenthistory.CustomerID')
            ->join('plan', 'plan.PlanID = paymenthistory.PlanID')
            ->findAll();
    }

    // New method to fetch payments for a specific month
    public function getPaymentsWithDetailsForMonth($year, $month)
    {
        return $this->select('paymenthistory.*, CONCAT(customer.Firstname, " ", customer.Lastname) as CustomerName, plan.PlanName')
            ->join('customer', 'customer.CustomerID = paymenthistory.CustomerID')
            ->join('plan', 'plan.PlanID = paymenthistory.PlanID')
            ->where('YEAR(paymenthistory.PaidDate)', $year)
            ->where('MONTH(paymenthistory.PaidDate)', $month)
            ->findAll();
    }
    
}
