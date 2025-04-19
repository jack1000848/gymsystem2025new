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
            'plans' => $planModel->select('PlanID, PlanName, Price')->where('IsActive', 1)->findAll(), // Include Price, filter active plans
        ];

        return view('clients1crud/payment', $data);
    }

    // Rest of the controller remains the same (add, edit, delete, myPayments methods)
    public function add()
{
    $model = new paymentModel();

    // Log the incoming request data
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
            if (!$this->validate([
                'CustomerID' => 'required|integer',
                'PaidAmount' => 'required|decimal',
                'PaidDate' => 'required|valid_date',
                'PlanID' => 'required|integer',
            ])) {
                return redirect()->back()->with('error', implode(', ', $this->validator->getErrors()))->withInput();
            }

            $data = [
                'CustomerID' => $this->request->getPost('CustomerID'),
                'PaidAmount' => $this->request->getPost('PaidAmount'),
                'PaidDate' => $this->request->getPost('PaidDate'),
                'PlanID' => $this->request->getPost('PlanID'),
            ];

            if ($paymentModel->update($id, $data)) {
                return redirect()->to('/payment')->with('success', 'Payment updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to update payment.')->withInput();
            }
        }

        $data = [
            'payment' => $paymentModel->find($id),
            'customers' => $customerModel->select('CustomerID, CONCAT(Firstname, " ", Lastname) as CustomerName')->findAll(),
            'plans' => $planModel->select('PlanID, PlanName, Price')->where('IsActive', 1)->findAll(),
        ];

        if (!$data['payment']) {
            throw new PageNotFoundException('Payment not found.');
        }

        return view('clients1crud/editpayment', $data);
    }


    public function delete($id)
    {
        $model = new paymentModel();

        if ($model->delete($id)) {
            return redirect()->to('/payment')->with('success', 'Payment deleted successfully.');
        } else {
            return redirect()->to('/payment')->with('error', 'Failed to delete payment.');
        }
    }

    public function myPayments()
    {
        // Get the logged-in client's CustomerID from session
        $clientId = session()->get('CustomerID');
        
        // Check if client is logged in
        if (!$clientId) {
            log_message('error', 'No CustomerID in session for myPayments');
            return redirect()->to('/login')->with('error', 'Please log in to view your payment history.');
        }

        $model = new paymentModel();

        // Fetch payments for the logged-in client, joined with plan details
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