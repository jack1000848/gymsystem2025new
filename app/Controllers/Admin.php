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
        $totalClients = $this->getCount('tbl_clients1');
        $totalClient = $this->getCount('tbl_clients');
        $totalEquipment = $this->getCount('tbl_equipment');

        // Pass the result to the view
        $data['totalClients'] = $totalClients; ///client
        $data['totalClient'] = $totalClient; ////coach /trainer
        $data['totalEquipment'] = $totalEquipment;

        return view('admin/index', $data);
    }
}

