<?php
    $this ->extend('layout/maincoach');
    $this ->section('body');

    ?>

< <div class="p-2 row mb-3">

<div class="col-12 mb-2">
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#managePlanModal">Add a Schedule</button>

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
        <th>TIMEID</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Customer</th>
        <th>Schedule</th>
        <th>Action</th>


    </tr>
</thead>
<tbody>
<?php foreach ($time as $timeschedule): ?>

<tr>
<th scope="row"><?=$timeschedule['ID']; ?></th>
<td><?= $timeschedule['StartTime']; ?></td>
<td><?= $timeschedule['EndTime']; ?></td>
<td><?= $timeschedule['CustomerID']; ?></td>
<td><?= $timeschedule['ScheduleID']; ?></td>
 

<td>
<div class="btn-group">
<button type="button" class="btn btn-primary" onclick="editPlan(<?=$timeschedule['ID']; ?>)"> Edit</button>
<button type="button" class="btn btn-danger" onclick="deletePlan(<?=$timeschedule['ID']; ?>)"> Delete</button>
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
    <h1 class="modal-title fs-5" id="exampleModalLabel">Time Schedules</h1>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
  <form id="planForm">
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Start Time</label>
              <input type="time" class="form-control" name="start" id="starts" required>
    </div>
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">End Time</label>
              <input type="time" class="form-control" name="end" id="ends" required>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



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
        url: '/coach-timemanage/update/' + editId,
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
    url: '/coach-timemanage/store',
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
         console.log(xhr.responseText);  // Debugging output
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

    const plan = await $.get('/coach-timemanage/edit/' + id);

    
    if (plan !== null) {
       
        editId = plan.ScheduleID;
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

async function deletePlan(id) {
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

    await $.ajax({
        url: '/coach-timemanage/delete/' + id,
        type: 'DELETE',
        success: function(res) {
            Swal.fire({
                title: 'Success!',
                text: res.message,
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