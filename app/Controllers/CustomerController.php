<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\PlanModel;
use App\Models\CoachPlanView;
use App\Models\CreateMemberModel;
use App\Models\QrCodeModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Builder\Builder;

class CustomerController extends BaseController
{
    public function __construct()
    {
        $this->session = session(); // Initialize session
    }

    public function index()
    {
        if (!$this->session->has('logged_in')) {
            return redirect()->to('/joinus')->with('error', 'Please log in first.');
        }
        $fetchClients1 = new CustomerModel();
        $data['clients1'] = $fetchClients1->findAll();
        
        $maxId = $fetchClients1->selectMax('customerid')->first(); 
        $nextId = isset($maxId['customerid']) ? $maxId['customerid'] + 1 : 1;
        $data['next_id'] = $nextId;

        return view('clients1crud/manage1', $data);
    }

    public function linkcoach()
    {
        $coachModel = new CoachModel();
        $coaches = $coachModel->getCoaches();
        
        $data['coaches'] = $coaches;
        return view('clients1crud/add', $data);
    }

    public function getCoaches()
    {
        $planId = $this->request->getVar('planId');
        if (!$planId) {
            return $this->response->setJSON(['error' => 'Plan ID is required']);
        }
        $coachPlanModel = new \App\Models\CoachPlanView();
        $coaches = $coachPlanModel->where('PlanID', $planId)->findAll();
        return $this->response->setJSON($coaches);
    }

    public function getPlans()
    {
        $plansModel = new PlanModel();
        $plans = $plansModel->findAll();
        return $this->response->setJSON($plans);
    }

    public function getSchedules($id)
    {
        $scheduleModel = new \App\Models\ViewScheduleForAllUserModel();
        $schedules = $scheduleModel->where('CoachID', $id)->where('CustomerID', null)->findAll();
        return $this->response->setJSON($schedules);
    }

    public function getCount($tableName)
    {
        return "Hello world";
    }

    public function createClients1()
    {
        $data['clients1Password'] = '20_'. uniqid();
        $fetchClient = new CustomerModel();
        $data['customer'] = $fetchClient->findAll();
        $maxId = $fetchClient->selectMax('customerid')->first(); 
        $nextId = isset($maxId['customerid']) ? $maxId['customerid'] + 1 : 1;
        $data['next_id'] = $nextId;

        return view('clients1crud/manage1', $data);
    }

    public function storeClients1()
    {
        $insertClients = new CustomerModel();

        // Retrieve the email from the form input
        $email = $this->request->getPost('clients1Emailaddress');

        // Check if email is retrieved properly
        if (empty($email)) {
            return redirect()->back()->with('error', 'Email field is required.');
        }

        // Check if the email is a Gmail address
        if (!preg_match("/^[a-zA-Z0-9._%+-]+@gmail\.com$/", $email)) {
            return redirect()->to('/clients1')->with('error', 'Only Gmail addresses are allowed.');
        }

        $data = [
            'Firstname' => $this->request->getPost('clients1Fname'),
            'Lastname' => $this->request->getPost('clients1Lname'),
            'Address' => $this->request->getPost('clients1Adress'),
            'Gender' => $this->request->getPost('gender'),
            'Email' => $this->request->getPost('clients1Emailaddress'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'RegisteredDate' => $this->request->getPost('dateofregistration'),
            'types_of_workout' => $this->request->getPost('tworkout'),
            'Membership_plan' => $this->request->getPost('plans'),
            'ExpirationDate' => $this->request->getPost('dateofregistration'),
            'WorkoutTypeID' => null,
            'CurrentPlanID' => null,
            'CoachID' => $this->request->getPost('plans'),
            'WorkoutPlanID' => null,
        ];

        $insertClients->insert($data);
        $customerId = $insertClients->getInsertID();

        // Retrieve schedule IDs and coach ID
        $scheduleIds = $this->request->getPost('coachsched');
        $coachId = $this->request->getPost('coach');

        // Update the CoachSched table for each selected schedule
        if (!empty($scheduleIds) && !empty($coachId)) {
            $db = \Config\Database::connect();
            $builder = $db->table('CoachSched');

            foreach ($scheduleIds as $schedId) {
                $builder->where('CoachID', $coachId)
                        ->where('ID', $schedId)
                        ->update(['CustomerID' => $customerId]);
            }
        }

        session()->setFlashdata('success', 'Account created successfully.');
        return redirect()->to('/clients1');
    }

    public function editClients1($id)
    {
        $clients1Model = new CustomerModel();
        $editclient = $clients1Model->find($id);
    
        if (!$editclient) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Client not found'
            ]);
        }
    
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $editclient
        ]);
    }

    public function updateClients1($id)
    {
        $customerModel = new CustomerModel();
        $data = [
            'Firstname' => $this->request->getPost('clients1Fname'),
            'Lastname' => $this->request->getPost('clients1Lname'),
            'Address' => $this->request->getPost('clients1Fulladdress'),
            'Gender' => $this->request->getPost('gender'),
            'Email' => $this->request->getPost('clients1Emailaddress'),
        ];
    
        if (
            empty($data['Firstname']) ||
            empty($data['Lastname']) ||
            empty($data['Address']) ||
            empty($data['Gender']) ||
            empty($data['Email'])
        ) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'All fields are required!'
            ]);
        }
    
        $updated = $customerModel->update($id, $data);
    
        if ($updated) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Client updated successfully!'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to update client. Please try again.'
            ]);
        }
    }

    public function deleteClients1($id)
    {
        $deleteClients1 = new CustomerModel();
        $isDeleted = $deleteClients1->delete($id);

        if ($isDeleted) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Client deleted successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to delete client.'
            ]);
        }
    }

    public function toggleFreeze($id)
    {
        $customerModel = new CustomerModel();
        $client = $customerModel->find($id);

        if ($client) {
            $newStatus = $client['is_frozen'] ? 0 : 1;
            $customerModel->update($id, ['is_frozen' => $newStatus]);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => $newStatus ? 'Client frozen successfully.' : 'Client unfrozen successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Client not found.'
            ]);
        }
    }

    public function updaterenew($id)
    {
        $customerModel = new CustomerModel();
        $planModel = new PlanModel();
        $data = [
            'ExpirationDate' => $this->request->getPost('dateofregistration'),
            'types_of_workout' => $this->request->getPost('tworkout'),
            'Membership_plan' => $this->request->getPost('plans'),
            'CurrentPlanID' => $this->request->getPost('plans'),
            'PaidAmount' => $this->request->getPost('paidamount'),
            'CoachID' => $this->request->getPost('coach'),
            'amount' => $this->request->getPost('amount'),
            'duration' => $this->request->getPost('duration'),
        ];
        
        $plan = $planModel->find($data['Membership_plan']);
        if (!$plan) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid membership plan selected.',
            ]);
        }
        $startDate = new \DateTime($data['ExpirationDate']);
        $duration = $data['duration'] ?? 30;
        $endDate = (clone $startDate)->modify("+{$duration} days");
        $data['EndDate'] = $endDate->format('Y-m-d');
        $scheduleIds = $this->request->getPost('coachsched');
        $coachId = $data['CoachID'];
        if (!empty($scheduleIds) && !empty($coachId)) {
            $db = \Config\Database::connect();
            $builder = $db->table('CoachSched');
            $builder->where('CustomerID', $id)->update(['CustomerID' => null]);
            foreach ($scheduleIds as $schedId) {
                $builder->where('CoachID', $coachId)
                        ->where('ID', $schedId)
                        ->update(['CustomerID' => $id]);
            }
        }
        $updated = $customerModel->update($id, $data);
        $db = \Config\Database::connect();
        $sql = "INSERT INTO `paymenthistory` (`CustomerID`, `PlanID`, `PaidAmount`, `PaidDate`)
                VALUES (?, ?, ?, NOW())";
        $db->query($sql, [$id, $data["CurrentPlanID"], $data["PaidAmount"]]);

        if ($updated) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Client renewed successfully!'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to renew client. Please try again.'
            ]);
        }
    }

    public function try($id)
    {
        $clients1Model = new CustomerModel();
        $editclient = $clients1Model->find($id);
        if (!$editclient) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Client not found'
            ]);
        }
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $editclient
        ]);
    }
    
    public function viewClient($id)
    {
        $customerModel = new CustomerModel();
        $client = $customerModel->find($id);
        return view('clients1crud/client_view', ['client' => $client]);
    }
}