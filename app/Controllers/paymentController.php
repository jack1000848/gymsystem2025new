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
        $paymentModel = new paymentModel();
        $customerModel = new CustomerModel();
        $planModel = new PlanModel();

        $data = [
            'payments' => $paymentModel->getPaymentsWithDetails(),
            'customers' => $customerModel->select('CustomerID, CONCAT(Firstname, " ", Lastname) as CustomerName')->findAll(),
            'plans' => $planModel->select('PlanID, PlanName, Price')->where('IsActive', 1)->findAll(),
        ];

        return view('clients1crud/payment', $data);
    }

    public function add()
    {
        $model = new paymentModel();

        log_message('debug', 'Add payment request: ' . json_encode($this->request->getPost()));

        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request method.'
            ]);
        }

        if (!$this->validate([
            'CustomerID' => 'required|integer',
            'PaidAmount' => 'required|decimal',
            'PaidDate' => 'required|valid_date',
            'PlanID' => 'required|integer',
        ])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => implode(', ', $this->validator->getErrors())
            ]);
        }

        $data = [
            'CustomerID' => $this->request->getPost('CustomerID'),
            'PaidAmount' => $this->request->getPost('PaidAmount'),
            'PaidDate' => $this->request->getPost('PaidDate'),
            'PlanID' => $this->request->getPost('PlanID'),
        ];

        if ($model->insert($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Payment added successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to add payment.'
            ]);
        }
    }

    public function edit($id)
    {
        $paymentModel = new paymentModel();
        $customerModel = new CustomerModel();
        $planModel = new PlanModel();
    
        if ($this->request->getMethod() === 'POST') {
            // ... (POST handling remains unchanged)
        }
    
        $payment = $paymentModel->find($id);
        if (!$payment) {
            log_message('error', 'Payment not found for ID: ' . $id);
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Payment not found.'
            ]);
        }
    
        $plan = $planModel->select('Price')->where('PlanID', $payment['PlanID'])->first();
        log_message('debug', 'Payment data: ' . json_encode($payment));
        log_message('debug', 'Plan data: ' . json_encode($plan));
    
        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'PaymentHistoryID' => $payment['PaymentHistoryID'],
                'CustomerID' => $payment['CustomerID'],
                'PaidAmount' => $payment['PaidAmount'],
                'PaidDate' => $payment['PaidDate'],
                'PlanID' => $payment['PlanID'],
                'PlanPrice' => $plan['Price'] ?? 0
            ]
        ]);
    }

    public function delete($id)
    {
        $model = new paymentModel();
    
        if (!$model->find($id)) {
            log_message('error', 'Payment not found for deletion, ID: ' . $id);
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Payment not found.'
            ]);
        }
    
        if ($model->delete($id)) {
            log_message('info', 'Payment deleted successfully, ID: ' . $id);
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Payment deleted successfully.'
            ]);
        } else {
            log_message('error', 'Failed to delete payment, ID: ' . $id);
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to delete payment.'
            ]);
        }
    }
    // New method to handle POST request for updating payments
    public function update($id)
    {
        $paymentModel = new paymentModel();

        if (!$this->validate([
            'CustomerID' => 'required|integer',
            'PaidAmount' => 'required|decimal',
            'PaidDate' => 'required|valid_date',
            'PlanID' => 'required|integer',
        ])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => implode(', ', $this->validator->getErrors())
            ]);
        }

        $data = [
            'CustomerID' => $this->request->getPost('CustomerID'),
            'PaidAmount' => $this->request->getPost('PaidAmount'),
            'PaidDate' => $this->request->getPost('PaidDate'),
            'PlanID' => $this->request->getPost('PlanID'),
        ];

        if ($paymentModel->update($id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Payment updated successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to update payment.'
            ]);
        }
    }
    public function myPayments()
    {
        $clientId = session()->get('CustomerID');
        
        if (!$clientId) {
            log_message('error', 'No CustomerID in session for myPayments');
            return redirect()->to('/login')->with('error', 'Please log in to view your payment history.');
        }

        $model = new paymentModel();

        $payments = $model->select('paymenthistory.*, plan.PlanName, plan.Price')
            ->join('plan', 'plan.PlanID = paymenthistory.PlanID', 'left')
            ->where('paymenthistory.CustomerID', $clientId)
            ->orderBy('paymenthistory.PaidDate', 'DESC')
            ->findAll();

        log_message('debug', 'Payments fetched for CustomerID ' . $clientId . ': ' . json_encode($payments));

        return view('clientdashboard/mypayment', [
            'payments' => $payments,
            'clientId' => $clientId
        ]);
    }
}