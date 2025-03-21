<?php
    $this ->extend('layout/main');
    $this ->section('body');

    ?>
    <div class="p-2 row mb-3">

    <div class="col-12 mb-2">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#managePlanModal">Add Plan</button>
    
    </div>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    
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
            <th>Plan ID</th>
            <th>Plan Name</th>
            <th>Description</th>
            <th>Duration in Months</th>
            <th>Gym Time Slot</th>
            <th>Trainer Included</th>
            <th>Price</th>
            <th>Active</th>
            <th>Action</th>


        </tr>
    </thead>
    <tbody>
    <?php foreach ($gymplans as $plan): ?>

<tr>
<th scope="row"><?=$plan['PlanID']; ?></th>
<td><?= $plan['PlanName']; ?></td>
<td><?= $plan['Description']; ?></td>
<td><?= $plan['Duration']; ?></td>
<td><?= $plan['GymTimeSlot']; ?></td>
<td><?= $plan['TrainerIncluded']; ?></td>
<td><?= $plan['Price']; ?></td>
<td><?= $plan['IsActive']; ?></td>


<td>
    <div class="btn-group">
    <button type="button" class="btn btn-primary" onclick="editPlan(<?=$plan['PlanID']; ?>)">Edit</button>
    <button type="button" class="btn btn-danger" onclick="deletePlan(<?=$plan['PlanID']; ?>)">Delete</button>
    </div>
 </td>


        </tr>

        <?php endforeach; ?>
    </tbody>
</table>

    </div>
    </div>
    
    

<!-- edit -->
<div class="modal fade" id="managePlanModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel"> a Plan</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="planForm">
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Plan Name</label>
              <input type="text" class="form-control" name="Pname" id="planName" required>
    </div>
     
    <div class="mb-3">
         <label for="exampleFormControlTextarea1" class="form-label">Description</label>
             <input type="text" class="form-control" id="description" name="description"required>
</div>
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Duration in Months</label>
              <input type="text" class="form-control" id="durationim" name="durationim"required>
    </div> 

    <div class="mb-3">
                  <label for="gender" class="form-label">Time Slot:</label>
                 <select id="gender" class="form-control" id="timeslot" name="timeslot"required>
                   <option value="Day Class">Morning Class</option>
                      <option value="Evening Class">Night Class</option>   
                    </select>
                 </div>

    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Price</label>
              <input type="text" class="form-control" id="price" name="price"required>
    </div> 

    <div class="mb-3">
    <select class="form-select" id="coaches" name="coaches[]" multiple="multiple">
    <?php foreach ($coaches as $coach): ?>
        <option value="<?= esc($coach['CoachID']); ?>"><?= esc($coach['Firstname']); ?></option>
    <?php endforeach; ?>
</select>

    </div> 
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label"> Creation Date </label>
              <input type="date" class="form-control" id="creation" name="creation"required>
    </div> 
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label"> Active </label>
              <input type="checkbox" class="form-control" id="active" name="active"required>
    </div> 

    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="btn-save">Save changes</button>
      </div>
    
      
      
    </div>
  </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script   script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>

    let editId = 0;
    $(document).ready(function(){

        let table = new DataTable('#myTable', {
            responsive: true
        });

        $("#managePlanModal").on('hidden.bs.modal', function(evt) {
            editId = 0;
        });


        $("#planForm").on('submit', async function(evt) {
        evt.preventDefault();
        evt.stopPropagation();

         console.log(editId);


        const form = $(this);
        const data = new FormData(this);
        const isActive = $("#active").is(":checked");

        data.append('active', isActive);

        const { isConfirmed } = await Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, save it!'
        });

        if (!isConfirmed) return;

        if(editId !== 0){
            data.append('id', editId);
            $.ajax({
            url: '/gymplans/update/' + editId,
            type: 'POST',
            data: data,
            processData: false, 
            contentType: false, 
            success: function(res) {
                Swal.fire({
                    title: 'Success!',
                    text: "Plan Updated Successfully",
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'Error!',
                    text: "Something went wrong. Please try again.",
                    icon: 'error',
            })

            }
        });
            return;        
        }

        $.ajax({
        url: 'gymplans/store',
        type: 'POST',
        data: data,
        processData: false, 
        contentType: false, 
        success: function(res) {
            Swal.fire({
                title: 'Success!',
                text: "Plan Added Successfully",
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.reload();
            });
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error!',
                text: "Something went wrong. Please try again.",
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
        });
        });
    });

    async function editPlan(id){

        const plan = await $.get('/gymplans/edit/' + id);
        
        if (plan !== null) {
           
            editId = plan.PlanID;
            console.log(plan);
            
            $("#planName").val(plan.PlanName);
            $("#description").val(plan.Description);
            $("#durationim").val(plan.Duration);
            $("#timeslot").val(plan.GymTimeSlot);
            $("#coaches").val(plan.CoachID);
            $("#price").val(plan.Price);            
            //$("#creation").val(plan.CreationDate);
            $("#active").prop('checked', plan.IsActive);
            $("#managePlanModal").modal('show');
        }

    }

    async function deletePlan(id){
        const { isConfirmed } = await Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        });

        if (!isConfirmed) return;

       const response = await  $.ajax({        
        url: '/gymplans/delete/' + id,
        type: 'DELETE',
        success: function(res) {
            Swal.fire({
                title: 'Success!',
                text: "Plan Deleted Successfully",
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.reload();
            });
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error!',
                text: "Something went wrong. Please try again.",
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
        });
    }  

   
</script>




<?php $this->endSection(); ?> 