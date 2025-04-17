<?php

namespace App\Controllers;
use App\Models\PaymentHistoryModel;

class PaymentHistory extends BaseController
{
    public function index()
    {
        $model = new PaymentHistoryModel();
        $data['payments'] = $model->findAll();

        return view('clients1crud/payment', $data);
    }
}
