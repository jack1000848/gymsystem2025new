<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EquipmentModel;


class EquipmentController extends BaseController
{

    public function index()
{
    $fetchEquipment =new EquipmentModel();
        $data['gymequipment'] = $fetchEquipment ->findAll();


        return view('equipment/eqmanage', $data);
}



public function create()
{
    return view('equipment/create', $data);
}

    public function storeEquipment()
     {
        $insertEquipments = new EquipmentModel ();

        if($img = $this->request->getFile('equipmentpic'))  {
            if($img->isValid() && ! $img->hasMoved())  {
             $imageName = $img->getRandomName();
            $img->move('uploads/', $imageName); 

            }
       }

        
       $data = array(
        'EquipmentID' => $this->request->getPost('Eid'),          // Mapped 'name' to 'EquipmentID'
        //'Image' => $imageName,                                      // Mapped 'Equipment_pic' to 'Image'
        'Description' => $this->request->getPost('Ename'),    // Mapped 'description' to 'Description'
        'Amount' => $this->request->getPost('Eamount'),             // Mapped 'amount' to 'Amount'
        'Qty' => $this->request->getPost('Equantity'),              // Mapped 'quantity' to 'Qty'
        //'purchase_date' => $this->request->getPost('Epurchasedate') // 'purchase_date' is not mapped in allowed fields, consider adding it if necessary
    );
    

        $insertEquipments->insert($data);

        return redirect()->to('/gymequipment')->with('success', 'Equipment Added Successfully!');

    }


    public function edit($id)
{
    $equipmentModel = new EquipmentModel();

    // Fetch the equipment data by ID
    $equipment = $equipmentModel->find($id);

    if (!$equipment) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Equipment not found'
        ]);
    }

    return $this->response->setJSON([
        'status' => 'success',
        'data' => $equipment
    ]);
}


public function update($id)
{
    // Load the EquipmentModel
    $equipmentModel = new \App\Models\EquipmentModel();

    // Get the input data from the request (this is assuming you're sending POST data)
    $data = [
        'EquipmentID' => $this->request->getPost('EquipmentID'),
        'Description' => $this->request->getPost('Description'),
        'Amount' => $this->request->getPost('Amount'),
        'Qty' => $this->request->getPost('Qty'),
       
    ];

    // Optionally, you can validate the data before updating
    if (!$data['EquipmentID'] || !$data['Description'] || !$data['Amount'] || !$data['Qty']) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'All fields are required!'
        ]);
    }

    // Attempt to update the record in the database
    $updated = $equipmentModel->update($id, $data);

    // Check if the update was successful
    if ($updated) {
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Equipment updated successfully!'
        ]);
    } else {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update equipment. Please try again.'
        ]);
    }
}


public function deleteEquipment($id)
{
    $deleteEquipment = new EquipmentModel();

    $isDeleted = $deleteEquipment->delete($id);

    if ($isDeleted) {
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Equipment deleted successfully.'
        ]);
    } else {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to delete equipment.'
        ]);
    }
}



   


}


?>