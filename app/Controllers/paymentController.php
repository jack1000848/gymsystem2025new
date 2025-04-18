<?php

namespace App\Controllers;
use App\Models\paymentModel;
use App\Models\PlanModel;
use App\Models\CustomerModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class paymentController extends BaseController
{
    public function payment()
    {
        $model = new paymentModel();
        $customerModel = new \App\Models\CustomerModel(); // Assuming you have a customer model
        $planModel = new \App\Models\PlanModel(); // Assuming you have a plan model
    
        $data['payments'] = $model->findAll();
        $data['customers'] = $customerModel->findAll();
        $data['plans'] = $planModel->findAll();
    
        return view('clients1crud/payment', $data);
    }

    public function add()
    {
        $model = new paymentModel();
        log_message('debug', 'Payment add request received: ' . json_encode($this->request->getPost()));
    
        // Check if the request method is POST
        if ($this->request->getMethod() !== 'post') {
            log_message('error', 'Invalid request method: Expected POST, got ' . $this->request->getMethod());
            return redirect()->back()->with('error', 'Invalid request method.')->withInput();
        }
    
        // Perform validation
        $validationRules = [
            'CustomerID' => 'required|integer',
            'PaidAmount' => 'required|decimal',
            'PaidDate' => 'required|valid_date',
            'PlanID' => 'required|integer',
        ];
    
        if (!$this->validate($validationRules)) {
            $validationErrors = $this->validator->getErrors();
            log_message('error', 'Validation failed: ' . json_encode($validationErrors));
            return redirect()->back()->with('error', 'Invalid input: ' . json_encode($validationErrors))->withInput();
        }
    
        // If validation passes, proceed with insertion
        $data = [
            'CustomerID' => $this->request->getPost('CustomerID'),
            'PaidAmount' => $this->request->getPost('PaidAmount'),
            'PaidDate' => $this->request->getPost('PaidDate'),
            'PlanID' => $this->request->getPost('PlanID'),
        ];
    
        log_message('debug', 'Validated data: ' . json_encode($data));
    
        if ($model->insert($data)) {
            log_message('debug', 'Payment inserted successfully');
            return redirect()->to('/payment')->with('success', 'Payment added successfully.');
        } else {
            $errors = $model->errors();
            log_message('error', 'Failed to insert payment: ' . json_encode($errors));
            return redirect()->back()->with('error', 'Failed to add payment: ' . json_encode($errors))->withInput();
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