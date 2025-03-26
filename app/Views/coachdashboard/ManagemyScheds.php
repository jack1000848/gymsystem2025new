<?php

$this->extend('layout/maincoach'); // Extend the main layout
$this->section('body'); // Start the body section
?>
    <<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
 
<div class="p-2 row mb-3">
    <!-- Add Client Button -->
    <div class="col-12 mb-2">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClientModal">Add My Schedule</button>
    </div>

    <!-- Success Message -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Client Table -->
    <div class="col-12">
        <table id="clientTable" class="display">
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

<!-- Add Sched Modal -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addClientModalLabel">Add sched</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo site_url('/coach-manage/store'); ?>" method="POST">
                    <!-- Gym Code -->
                    

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

                    <!-- Submit Button -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Client Modal -->
<div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editClientModalLabel">Edit sched</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editClientForm">
                    <input type="hidden" id="editClientId" name="id">

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

                    <!-- Submit Button -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"id="btn-update">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script   script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    $(document).ready(function () {
        // Initialize DataTable
        let table = new DataTable('#clientTable', {
            responsive: true
        });

        });
        

        // Fetch Plans and Coaches
        fetchPlans();
        $('#planSelect').on('change', function () {
            var planId = $(this).val();
            if (planId) {
                fetchCoach(planId);
            }
        });
        $('#editPlanSelect').on('change', function () {
    var planId = $(this).val();
    if (planId) {
        fetchEditCoach(planId);
    }
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



    



    

    // Edit Client
    


    
    
</script>

<?php $this->endSection(); ?>