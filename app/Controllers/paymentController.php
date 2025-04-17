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
}
