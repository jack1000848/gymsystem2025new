<?php
    $this ->extend('layout/main');
    $this ->section('body');

    ?>
    <div class="p-2 row mb-3">

    <div class="col-12 mb-2">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Equipment</button>
    
    </div>

    
    <?php if (session()->getFlashdata('success')) :?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
        <?php endif;?>

    
    <div class="col-12">
    <table id="myTable" class="display">
    <thead>
        <tr>
            <th>Equipment ID</th>
            <th>Desicription</th>
            <th>Ammount</th>
            <th>Quantity</th>
            <th>Action</th>


        </tr>
    </thead>
    <tbody>
    <?php foreach ($gymequipment as $equipment): ?>

<tr>
<td><?= $equipment['EquipmentID']; ?></td>
<td><?= $equipment['Description']; ?></td>
<td><?= $equipment['Amount']; ?></td>
<td><?= $equipment['Qty']; ?></td>


<td>
  <span onclick="editEquipment('<?= $equipment['EquipmentID']; ?>')" class="btn btn-sm btn-primary">
  Edit
  </span>
  <span onclick="deleteEquipment('<?= $equipment['EquipmentID']; ?>')" class="btn btn-sm btn-primary">
  Delete
  </span>
  <span>

  </span>
</td>


        </tr>

        <?php endforeach; ?>
    </tbody>
</table>

    </div>


    </div>

  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modalTitle">Add</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="<?php echo site_url('/gymequipment/store'); ?>" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Name</label>
              <input type="text" class="form-control" name="Ename" required>
    </div>
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Amount</label>
              <input type="text" class="form-control" name="Eamount"required>
    </div> 
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Quantity</label>
              <input type="text" class="form-control" name="Equantity"required>
    </div> 
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="btn-save">Save changes</button>
      </div>
    </form>
    </div>
  </div>





</div>

    <!--Edit-->
  
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="">Edit</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
    <div class="mb-3">
      <input type="hidden" id="id"/>
         <label for="eqName" class="form-label">Name</label>
              <input type="text" id="eqName" placeholder="Leg Press" class="form-control" required>
    </div>
    <div class="mb-3">
         <label for="eqAmount" class="form-label">Amount</label>
              <input type="number" id="eqAmount" class="form-control" required>
    </div> 
    <div class="mb-3">
         <label for="eqQuantity" class="form-label">Quantity</label>
              <input type="number" id="eqQuantity" class="form-control" required>
    </div>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="btn-update">Save changes</button>
      </div>
    
    </div>
  </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>
    $(document).ready(function(){

        let table = new DataTable('#myTable', {

            responsive: true
        });

        $("#btn-save").on('click', function(){

            alert('Equipment Added Successfully!')

        });

        $("#btn-update").on('click', function(){
          updateEquipment();
        });

    });

    async function deleteEquipment(id) {
    const { isConfirmed } = await Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    });

    if (isConfirmed) {
        try {
            const response = await $.ajax({
                url: '/gymequipment/delete/' + id, 
                type: 'DELETE',
                success: function(response) {
                   
                        // Show success alert
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Your equipment has been deleted.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        window.location.reload();
                   
                },
                error: function(xhr, status, error) {
                    // Handle AJAX error
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong. Please try again later.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        } catch (error) {
            Swal.fire({
                title: 'Error!',
                text: 'There was an error processing your request.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    } 
}



    async function editEquipment(id) {
    try {
        const res = await $.get('/gymequipment/edit/' + id);

        if (res && res.data) {
            const equipment = res.data;

            $("#id").val(equipment.EquipmentID);
            $("#eqName").val(equipment.Description);
            $("#eqAmount").val(parseFloat(equipment.Amount).toFixed(2));
            $("#eqQuantity").val(equipment.Qty);
            $("#editModal").modal('show');

        } else {
            console.error('No data found in the response:', res);
        }
    } catch (error) {
        console.error('Error fetching equipment data:', error);
    }
  }

    function updateEquipment() {
    let equipmentData = {
        EquipmentID: $("#id").val().trim(),
        Description: $("#eqName").val().trim(),
        Amount: $("#eqAmount").val().trim(),
        Qty: $("#eqQuantity").val().trim(),
        
    };

    if (!equipmentData.EquipmentID || !equipmentData.Description || !equipmentData.Amount || !equipmentData.Qty) {
        alert("Please fill in all fields.");
        return;
    }

    if(isNaN(equipmentData.Amount) || equipmentData.Amount <= 0
      || isNaN(equipmentData.Qty) || equipmentData.Qty <= 0    
  ){
      alert("Invalid Amount");
      return;

    }

    $.ajax({
        url: '/gymequipment/update/' + equipmentData.EquipmentID, // Adjust URL for your update route
        type: 'POST',
        data: equipmentData, 
        success: function(response) {
            if (response.status === 'success') {
                alert("Equipment updated successfully!");
                window.location.reload();
            } else {
                alert("Failed to update equipment: " + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error during the update:", error);
            alert("There was an error updating the equipment.");
        }
    });
  }


   
</script>




<?php $this->endSection(); ?> 