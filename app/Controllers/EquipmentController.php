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
            'name' => $this->request->getPost('Ename'),
            'Equipment_pic' => $imageName, 
            'amount' => $this->request->getPost('Eamount'),
            'quantity' => $this->request->getPost('Equantity'),
            'description' => $this->request->getPost('Ediscription'),
            'purchase_date' => $this->request->getPost('Epurchasedate'),

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