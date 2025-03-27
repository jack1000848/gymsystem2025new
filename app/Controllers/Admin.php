<?php

namespace App\Controllers;
use Config\Database;


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
        if (!session()->has('logged_in')) {
            return redirect()->to('/joinus')->with('error', 'Please login first.');
        }
    

        // Call the private function using $this
        $totalClient = $this->getCount('coach');
        $totalClients = $this->getCount('customer');
        $totalEquipment = $this->getCount('equipment');

        // Pass the result to the view
        $data['totalClients'] = $totalClients; ///client
        $data['totalClient'] = $totalClient; ////coach /trainer
        $data['totalEquipment'] = $totalEquipment;

        return view('admin/index', $data);
    }
    public function index1()
    {
        $model = new AttendanceLogModel();
        $data['customers'] = $model->getCustomers();

        return view('/admin/index', $data);
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

