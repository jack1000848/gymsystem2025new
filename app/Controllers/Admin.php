<?php

namespace App\Controllers;
use Config\Database;


class Admin extends BaseController
{
    private function getCount($tableName)
    {
        $db = \Config\Database::connect();
        $query = $db->query('SELECT * FROM ' . $tableName);
        $totalRows = $query->getNumRows();
    
       
        return $totalRows;
    }
    

    public function index()
    {
        

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
    session()->destroy(); // Destroy all session data
    return redirect()->to('/joinus')->with('success', 'You have been logged out.');
}


}

