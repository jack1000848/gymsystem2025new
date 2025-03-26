<?php
    $this ->extend('layout/maincoach');
    $this ->section('body');

    ?>

< <div class="p-2 row mb-3">

<div class="col-12 mb-2">
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#managePlanModal">Add a Schedule</button>

</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"/>
    <!-- Or for RTL support -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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
        <th>Schedule ID</th>
        <th>ScheduleDate</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Action</th>


    </tr>
</thead>
<tbody>
    
<?php foreach ($sched as $schedule): ?>

<tr>
<th scope="row"><?=$schedule['ScheduleID']; ?></th>
<td><?= $schedule['ScheduleDate']; ?></td>
<td><?= $schedule['Start']; ?></td>
<td><?= $schedule['End']; ?></td>
 

<td>
<div class="btn-group">
<button type="button" class="btn btn-primary" onclick="editPlan(<?=$schedule['ScheduleID']; ?>)"> Edit</button>
<button type="button" class="btn btn-danger" onclick="deletePlan(<?=$schedule['ScheduleID']; ?>)"> Delete</button>
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
    <h1 class="modal-title fs-5" id="exampleModalLabel"> Schedules</h1>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
  <form id="planForm">
 <div class="mb-3">
     <label for="exampleFormControlInput1" class="form-label">Start Date:</label>
     <input type="text" name="startdate" id="start_date">
</div>
<div class="mb-3">
     <label for="exampleFormControlInput1" class="form-label">Start Time:</label>
     <input type="text" name="starttime"  id="start_time">
</div>
<div class="mb-3">
     <label for="exampleFormControlInput1" class="form-label">End Time:</label>
     <input type="text" name="endtime"  id="end_time">
</div>
<div class="mb-3">
     <label for="exampleFormControlInput1" class="form-label">End Date:</label>
     <input type="text" name="enddate"  id="end_date">
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

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>

let editId = 0;
$(document).ready(function(){

    let table = new DataTable('#myTable', {
        responsive: true
    });

    ///this is the timeline of calendar day/time
    let startDate = '';
let endDate = '';

// Initialize start date picker
let startDatePicker = flatpickr("#start_date", { 
    dateFormat: "Y-m-d",
    onChange: function(selectedDates, dateStr) {
        startDate = dateStr;
        // Set minDate of end date picker
        endDatePicker.set('minDate', dateStr);
    }
});

// Initialize end date picker with SweetAlert validation
let endDatePicker = flatpickr("#end_date", { 
    dateFormat: "Y-m-d",
    onChange: function(selectedDates, dateStr) {
        endDate = dateStr;
        // Validate if end date is earlier than start date
        if (startDate && new Date(endDate) < new Date(startDate)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Date Range',
                text: 'End date cannot be earlier than start date!',
                confirmButtonText: 'OK'
            });
            endDatePicker.clear(); // Clear the wrong date
        }
    }
});

// Time pickers
flatpickr("#start_time", { 
    enableTime: true, 
    noCalendar: true, 
    dateFormat: "h:i K",
    time_24hr: false
});

flatpickr("#end_time", { 
    enableTime: true, 
    noCalendar: true, 
    dateFormat: "h:i K",
    time_24hr: false
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
        url: '<?= base_url('/coach-manage/update//'); ?>' + editId,
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
     url: '<?= site_url('coach-manage/store'); ?>',
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

    const plan = await $.get('<?= base_url('/coach-manage/edit/'); ?>' + id);

    
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
        url: '/coach-manage/delete/' + id,
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