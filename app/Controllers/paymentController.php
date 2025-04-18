<?php

namespace App\Controllers;
use App\Models\paymentModel;
use App\Models\planModel;
use App\Models\CustomerModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class paymentController extends BaseController
{
    public function payment()
    {
        $model = new paymentModel();
        $customerModel = new \App\Models\CustomerModel(); // Assuming you have a customer model
        $planModel = new \App\Models\planModel(); // Assuming you have a plan model
    
        $data['payments'] = $model->findAll();
        $data['customers'] = $customerModel->findAll();
        $data['plans'] = $planModel->findAll();
    
        return view('clients1crud/payment', $data);
    }

    public function add()
    {
        $model = new paymentModel();

        if ($this->request->getMethod() === 'post' && $this->validate([
            'CustomerID' => 'required|integer',
            'PaidAmount' => 'required|decimal',
            'PaidDate' => 'required|valid_date',
            'PlanID' => 'required|integer',
        ])) {
            $data = [
                'CustomerID' => $this->request->getPost('CustomerID'),
                'PaidAmount' => $this->request->getPost('PaidAmount'),
                'PaidDate' => $this->request->getPost('PaidDate'),
                'PlanID' => $this->request->getPost('PlanID'),
            ];

            if ($model->insert($data)) {
                return redirect()->to('/payment')->with('success', 'Payment added successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to add payment.')->withInput();
            }
        } else {
            return redirect()->back()->with('error', 'Invalid input.')->withInput();
        }
    }

    public function myPayments()
    {
        $clientId = session()->get('CustomerID'); // Adjust as needed
        $model = new paymentModel();

        $payments = $model->select('paymenthistory.*, PlanName as PlanName')
            ->join('plan', 'plan.PlanID = paymenthistory.PlanID', 'left')
            ->where('CustomerID', $clientId)
            ->orderBy('PaidDate', 'DESC')
            ->findAll();
        return view('clientdashboard/mypayment', ['payments' => $payments]);
    }
    
}