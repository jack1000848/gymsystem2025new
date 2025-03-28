<?php

namespace App\Controllers;
use Config\Database;
use App\Models\CustomerModel;
use App\Models\CoachModel;


class Admin extends BaseController
{
    

    public function __construct()
{
    helper('url');
    $this->session = session();
    

    // Prevent back button after logout
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
}
    private function getCount($tableName)
    {
        $db = \Config\Database::connect();
        $query = $db->query('SELECT * FROM ' . $tableName);
        $totalRows = $query->getNumRows();
    
       
        return $totalRows;
    }
    

    public function index()
    {
        if (!$this->session->has('logged_in')) {
            return redirect()->to('/joinus')->with('error', 'Please log in first.');
        }

        if(!$this->session->get('Role') == null || $this->session->get('Role') != 'Admin'){
            return redirect()->to('/clientdashboard')->with('error', 'You are not authorized to access this page.');
        }
    

        // Call the private function using $this
        $totalClient = $this->getCount('coach');
        $totalClients = $this->getCount('customer');
        $totalEquipment = $this->getCount('equipment');

        // Pass the result to the view
        $data['totalClients'] = $totalClients; ///client
        $data['totalClient'] = $totalClient; ////coach /trainer
        $data['totalEquipment'] = $totalEquipment;

        $fetchClients1 =new CoachModel();
        $fetchClients = $fetchClients1->findAll();
        //loop through the data
        foreach($fetchClients as $client){
            //check if password_hash is null
            if($client['password_hash'] == null){
                //update the password_hash with the password
                $fetchClients1->update($client['CoachID'], ['password_hash' => password_hash($client['Password'], PASSWORD_BCRYPT)]);
            }
        }

        return view('admin/index', $data);
    }
    public function index1()
    {
        $model = new AttendanceLogModel();
        $data['customers'] = $model->getCustomers();

        return view('/admin/index', $data);
    }

    public function login()
    {
        $session = session();
        $request = service('request');

        // Get form input
        $username = $request->getPost('username');
        $password = $request->getPost('password');

        // Hardcoded admin credentials (replace with database check)
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

