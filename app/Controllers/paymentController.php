<?php
namespace App\Controllers;

use App\Models\paymentModel;
use App\Models\PlanModel;
use App\Models\CustomerModel;

use CodeIgniter\Exceptions\PageNotFoundException;

class paymentController extends BaseController
{
    public function __construct()
    {
        helper('url');
        $this->session = session();
       
    }
    public function payment()
{
    if (!$this->session->has('logged_in')) {
        return redirect()->to('/joinus')->with('error', 'Please log in first.');
    }

    if ($this->session->get('Role') != 'Admin') {
        $roleVal = $this->session->get('Role');
        if ($roleVal == 'Customer') {
            return redirect()->to('/clientdashboard')->with('error', 'You are not authorized to access this page.');
        } else if ($roleVal == 'Coach') {
            return redirect()->to('/coachdashboard')->with('error', 'You are not authorized to access this page.');
        }
    }
    
    $paymentModel = new PaymentModel();
    $customerModel = new CustomerModel();
    $planModel = new PlanModel();

    $data = [
        'payments' => $paymentModel->getPaymentsWithDetails1(),
        'customers' => $customerModel->select('CustomerID, CONCAT(Firstname, " ", Lastname) as CustomerName')->findAll(),
        'plans' => $planModel->select('PlanID, PlanName, Price')->where('IsActive', 1)->findAll(),
    ];

    return view('clients1crud/payment', $data);
}
public function generatePdf($yearMonth)
{
    if (!$this->session->has('logged_in')) {
        return redirect()->to('/joinus')->with('error', 'Please log in first.');
    }

    if ($this->session->get('Role') != 'Admin') {
        $roleVal = $this->session->get('Role');
        if ($roleVal == 'Customer') {
            return redirect()->to('/clientdashboard')->with('error', 'You are not authorized to access this page.');
        } else if ($roleVal == 'Coach') {
            return redirect()->to('/coachdashboard')->with('error', 'You are not authorized to access this page.');
        }
    }

    $paymentModel = new PaymentModel();
    $payments = $paymentModel->getPaymentsByMonth($yearMonth);

    if (empty($payments)) {
        return redirect()->to('/payment')->with('error', 'No payments found for the selected month.');
    }

    // Load dompdf
    $dompdf = new \Dompdf\Dompdf();
    [$year, $month] = explode('-', $yearMonth);
    $monthName = date('F', mktime(0, 0, 0, $month, 1));

    // HTML content for the PDF
    $html = '
    <h1>Payment Report for ' . $monthName . ' ' . $year . '</h1>
    <table style="width:100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #3498db; color: white;">
                <th style="border: 1px solid #ccc; padding: 8px;">Payment ID</th>
                <th style="border: 1px solid #ccc; padding: 8px;">Customer</th>
                <th style="border: 1px solid #ccc; padding: 8px;">Paid Amount</th>
                <th style="border: 1px solid #ccc; padding: 8px;">Paid Date</th>
                <th style="border: 1px solid #ccc; padding: 8px;">Plan</th>
            </tr>
        </thead>
        <tbody>';
    
    foreach ($payments as $payment) {
        $html .= '
            <tr>
                <td style="border: 1px solid #ccc; padding: 8px;">' . esc($payment['PaymentHistoryID']) . '</td>
                <td style="border: 1px solid #ccc; padding: 8px;">' . esc($payment['CustomerName']) . '</td>
                <td style="border: 1px solid #ccc; padding: 8px;">₱' . number_format($payment['PaidAmount'], 2) . '</td>
                <td style="border: 1px solid #ccc; padding: 8px;">' . esc($payment['PaidDate']) . '</td>
                <td style="border: 1px solid #ccc; padding: 8px;">' . esc($payment['PlanName']) . '</td>
            </tr>';
    }

    $html .= '
        </tbody>
    </table>';

    // Load HTML to dompdf
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Output the PDF
    $dompdf->stream("Payment_Report_{$monthName}_{$year}.pdf", ['Attachment' => true]);
}
// New method to fetch payments for a specific month
public function monthly($monthYear)
{
    if (!$this->session->has('logged_in') || $this->session->get('Role') != 'Admin') {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized access']);
    }

    $paymentModel = new PaymentModel();

    // Parse the month and year (e.g., "2025-04")
    [$year, $month] = explode('-', $monthYear);

    // Fetch payments for the specified month
    $payments = $paymentModel->getPaymentsWithDetails1ForMonth($year, $month);

    return $this->response->setJSON([
        'status' => 'success',
        'data' => $payments
    ]);
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