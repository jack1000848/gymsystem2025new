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
        $fetchEquipment = new EquipmentModel();
        //$data ['client'] = $fetchClient->where('id,'$id)->first();

        return view ('gymequipment', $data);
 
    }

    public function updateClient($id)
    {
        //update
    }

    public function deleteEquipment($id)
    {
        $deleteEquipment = new EquipmentModel();
        $deleteEquipment->delete($id);

        return redirect()->to('/gymequipment')->with('success', 'Equipment Deleted Successfully!');
    }


   


}


?>