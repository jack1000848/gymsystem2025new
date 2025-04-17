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
    public function update()
    {
        $model = new \App\Models\paymentModel();
        $id = $this->request->getPost('PaymentHistoryID');
    
        $data = [
            'CustomerID' => $this->request->getPost('CustomerID'),
            'PaidAmount' => $this->request->getPost('PaidAmount'),
            'PaidDate' => $this->request->getPost('PaidDate'),
            'PlanID' => $this->request->getPost('PlanID'),
        ];
    
        $model->update($id, $data);
        return redirect()->to('/payment-history')->with('success', 'Payment updated successfully');
    }
    
    public function delete($id)
    {
        $model = new \App\Models\PaymentHistoryModel();
        $model->delete($id);
        return redirect()->to('/payment-history')->with('success', 'Payment deleted successfully');
    }
    


}
