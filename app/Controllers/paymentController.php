<?php

namespace App\Controllers;
use App\Models\paymentModel;

class paymentController extends BaseController
{
    public function payment()
    {
        $model = new paymentModel();
        $data['payments'] = $model->findAll();

        return view('clients1crud/payment', $data);
    }

    public function myPayments()
{
    $clientId = session()->get('CustomerID'); // adjust as needed
    $model = new \App\Models\paymentModel();

    $payments = $model->select('paymenthistory.*, plans.name as PlanName')
        ->join('plans', 'plans.PlanID = paymenthistory.PlanID', 'left')
        ->where('CustomerID', $clientId)
        ->orderBy('PaidDate', 'DESC')
        ->findAll();
        return view('clientdashboard/mypayment', $data);
    }

    
}
