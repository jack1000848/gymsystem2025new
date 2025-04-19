<?php

namespace App\Controllers;

use Config\Database;
use App\Models\CustomerModel;
use App\Models\CoachModel;
use App\Models\GenderChartModel;
use App\Models\paymentModel;
use CodeIgniter\I18n\Time;

class Admin extends BaseController
{
    protected $paymentModel;

    public function __construct()
    {
        helper('url');
        $this->session = session();
        $this->paymentModel = new paymentModel();

        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
    }

    private function getCount($tableName)
    {
        $db = \Config\Database::connect();
        $query = $db->query('SELECT * FROM ' . $tableName);
        return $query->getNumRows();
    }

    public function index()
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

        $userModel = new GenderChartModel();
        $maleCount = $userModel->where('gender', 'Male')->countAllResults();
        $femaleCount = $userModel->where('gender', 'Female')->countAllResults();

        $coachModel = new CoachModel();
        $clientModel = new CustomerModel();
        $coachCount = $coachModel->countAll();
        $clientCount = $clientModel->countAll();

        $totalClient = $this->getCount('coach');
        $totalClients = $this->getCount('customer');
        $totalEquipment = $this->getCount('equipment');

        $fetchClients1 = new CoachModel();
        $fetchClients = $fetchClients1->findAll();
        foreach ($fetchClients as $client) {
            if ($client['password_hash'] == null) {
                $fetchClients1->update($client['CoachID'], ['password_hash' => password_hash($client['Password'], PASSWORD_BCRYPT)]);
            }
        }

        $data = [
            'male' => $maleCount,
            'female' => $femaleCount,
            'coachCount' => $coachCount,
            'clientCount' => $clientCount,
            'totalClients' => $totalClients,
            'totalClient' => $totalClient,
            'totalEquipment' => $totalEquipment,
            'monthlyCheckinData' => $this->getMonthlyCheckins(),
            'monthlyCoachAttendance' => $this->getMonthlyCoachAttendance(),
            'monthlyPaymentData' => $this->getMonthlyPayments() // Ensure this is included
        ];

        return view('admin/index', $data);
    }

    private function getMonthlyCheckins()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('viewcustomerattendance');
        $checkinData = [];
        $totalCheckins = 0;

        $data = [['Month', 'Check-ins']];

        for ($i = 4; $i >= 0; $i--) {
            $monthStart = Time::now()->subMonths($i)->modify('first day of this month')->setTime(0, 0, 0);
            $monthEnd = Time::now()->subMonths($i)->modify('last day of this month')->setTime(23, 59, 59);

            $builder->resetQuery();
            $builder->where('CheckIn >=', $monthStart->toDateTimeString());
            $builder->where('CheckIn <=', $monthEnd->toDateTimeString());

            $checkinCount = $builder->countAllResults();
            $monthLabel = $monthStart->format('F Y');

            $data[] = [$monthLabel, $checkinCount];
            $totalCheckins += $checkinCount;
        }

        return [
            'data' => $data,
            'total' => $totalCheckins
        ];
    }

    private function getMonthlyCoachAttendance()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('coachattendance');
        $data = [['Month', 'Coach Check-ins']];
        $totalCheckins = 0;

        for ($i = 4; $i >= 0; $i--) {
            $monthStart = Time::now()->subMonths($i)->modify('first day of this month')->setTime(0, 0, 0);
            $monthEnd = Time::now()->subMonths($i)->modify('last day of this month')->setTime(23, 59, 59);

            $builder->resetQuery();
            $builder->where('CheckInTime >=', $monthStart->toDateTimeString());
            $builder->where('CheckInTime <=', $monthEnd->toDateTimeString());

            $checkinCount = $builder->countAllResults();
            $monthLabel = $monthStart->format('F Y');

            $data[] = [$monthLabel, $checkinCount];
            $totalCheckins += $checkinCount;
        }

        return [
            'data' => $data,
            'total' => $totalCheckins
        ];
    }

    private function getMonthlyPayments()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('paymenthistory');
        $data = [['Month', 'Total Paid Amount']];
        $totalPayments = 0;

        for ($i = 4; $i >= 0; $i--) {
            $monthStart = Time::now()->subMonths($i)->modify('first day of this month')->setTime(0, 0, 0);
            $monthEnd = Time::now()->subMonths($i)->modify('last day of this month')->setTime(23, 59, 59);

            $builder->resetQuery();
            $builder->selectSum('PaidAmount', 'total_amount');
            $builder->where('PaidDate >=', $monthStart->toDateString());
            $builder->where('PaidDate <=', $monthEnd->toDateString());

            $result = $builder->get()->getRow();
            $totalAmount = $result->total_amount ? (float)$result->total_amount : 0;
            $monthLabel = $monthStart->format('F Y');

            $data[] = [$monthLabel, $totalAmount];
            $totalPayments += $totalAmount;
        }

        return [
            'data' => $data,
            'total' => $totalPayments
        ];
    }

    public function login()
    {
        $session = session();
        $request = service('request');

        $username = $request->getPost('username');
        $password = $request->getPost('password');

        if ($username === 'admin' && $password === 'admin') {
            $session->set('logged_in', true);
            return redirect()->to('/admin');
        } else {
            return redirect()->to('/joinus')->with('error', 'Invalid username or password.');
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/joinus')->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }
}
?>