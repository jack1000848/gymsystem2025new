<?php
namespace App\Controllers;
use App\Models\paymentModel;
use App\Models\PlanModel;

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
        $clientId = session()->get('CustomerID');
        if (!$clientId) {
            return redirect()->to('/login')->with('error', 'Please log in to view your payments.');
        }

        $model = new paymentModel();
        $payments = $model->select('paymenthistory.*, plan.PlanName')
            ->join('plan', 'plan.PlanID = paymenthistory.PlanID', 'left')
            ->where('CustomerID', $clientId)
            ->orderBy('PaidDate', 'DESC')
            ->findAll();
        return view('clientdashboard/mypayment', ['payments' => $payments]);
    }

    public function makePayment()
    {
        $clientId = session()->get('CustomerID');
        if (!$clientId) {
            return redirect()->to('/login')->with('error', 'Please log in to add a payment.');
        }

        $planModel = new PlanModel();
        $data['plans'] = $planModel->findAll();
        return view('clientdashboard/make_payment', $data);
    }

    public function addPayment()
    {
        $clientId = session()->get('CustomerID');
        if (!$clientId) {
            return redirect()->to('/login')->with('error', 'Please log in to add a payment.');
        }

        $data = $this->request->getPost();
        $planId = $data['plan_id'] ?? null;
        $amount = $data['amount'] ?? 0;
        $paidDate = $data['paid_date'] ?? date('Y-m-d');

        // Validate input
        if (!$planId || $amount <= 0 || !$paidDate) {
            return redirect()->back()->with('error', 'Invalid plan, amount, or date.');
        }

        $model = new paymentModel();
        $result = $model->insert([
            'CustomerID' => $clientId,
            'PaidAmount' => $amount,
            'PaidDate' => $paidDate,
            'PlanID' => $planId
        ]);

        if ($result) {
            return redirect()->to('/clientdashboard/make_payment')->with('success', 'Payment added successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to add payment.');
        }
    }
}