<?php
namespace App\Controllers;
use App\Models\paymentModel;
use App\Models\PlanModel; // Assuming you have a PlanModel
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class paymentController extends BaseController
{
    protected $stripe;

    public function __construct()
    {
        // Initialize Stripe (if using Stripe)
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY')); // Load from .env
    }

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
            return redirect()->to('/login')->with('error', 'Please log in to make a payment.');
        }

        $planModel = new PlanModel(); // Fetch available plans
        $data['plans'] = $planModel->findAll();

        return view('clientdashboard/make_payment', $data);
    }

    public function createPaymentIntent()
    {
        $clientId = session()->get('CustomerID');
        if (!$clientId) {
            return $this->response->setJSON(['error' => 'Unauthorized access.']);
        }

        $data = $this->request->getJSON(true);
        $amount = $data['amount'] ?? 0;
        $planId = $data['plan_id'] ?? null;

        if ($amount <= 0 || !$planId) {
            return $this->response->setJSON(['error' => 'Invalid amount or plan.']);
        }

        try {
            // Create a PaymentIntent with Stripe
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $amount, // Amount in cents
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'metadata' => ['customer_id' => $clientId, 'plan_id' => $planId]
            ]);

            // Save to paymenthistory after successful payment (optional, can be done via webhook)
            return $this->response->setJSON(['clientSecret' => $paymentIntent->client_secret]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }
}