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
    


    // Delete Client
    async function deleteClient(id) {
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
                url: '<?= base_url('/clients1/delete/'); ?>' + id, // Adjust URL for your delete route
                type: 'DELETE',
                success: function(response) {
                    // Show success alert
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'The client has been deleted successfully.',
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


    

    // Edit Client
    async function editClient(id) {
    try {
        const res = await $.get('<?= base_url('/clients1/edit/'); ?>' + id);

        if (res && res.data) {
            const client = res.data;

            $("#editClientId").val(client.id);
            $("#editGymcode").val(client.gym_code);
            $("#editClients1Fname").val(client.first_name);
            $("#editClients1Lname").val(client.last_name);
            $("#editClients1Username").val(client.user_name);
            $("#editClients1Emailaddress").val(client.email_address);
            $("#editPassword").val(client.password);
            $("#editGender").val(client.gender);
            $("#editDateofregistration").val(client.date_of_registration);
            $("#edittimeslot").val(client.timeslot);
            $("#editTworkout").val(client.workout_type);
            $("#editPlans").val(client.plans);
            $("#editAmount").val(parseFloat(client.amount).toFixed(2));
            $("#editDuration").val(client.duration);
            $("#editCoach").val(client.coach);

            $("#editClientModal").modal('show');

            // Fetch plans and set the selected one
            await fetchEditPlans(client.PlanID);

            // Fetch coaches for the selected plan
            await fetchEditCoach(client.PlanID, client.CoachID);

        } else {
            console.error('No data found in the response:', res);
        }
    } catch (error) {
        console.error('Error fetching client data:', error);
    }
}



async function updateClient() {
    let clientData = {
        CustomerID: $("#editClientId").val().trim(),
       // gym_code: $("#editGymcode").val().trim(),
       Firstname: $("#editClients1Fname").val().trim(),
       Lastname: $("#editClients1Lname").val().trim(),
        user_name: $("#edituser").val().trim(),
        Email: $("#editClients1Emailaddress").val().trim(),
        Password: $("#editPassword").val().trim(),
        Gender: $("#editGender").val().trim(),
        RegisteredDate: $("#editDateofregistration").val().trim(),
        types_of_workout: $("#editTworkout").val().trim(),
        GymTimeSlot: $("#timeslot").val().trim(),
        amount: $("#editAmount").val().trim(),
        duration: $("#editDuration").val().trim(),
        Membesrship_plan: $("#editPlanSelect").val(), // Include the plan
        coach: $("#editCoach").val() // Include the coach
    };

    if (!clientData.first_name || !clientData.last_name || !clientData.email_address ||
        !clientData.amount || !clientData.duration || !clientData.plan) {
        alert("Please fill in all required fields.");
        return;
    }
    
    $.ajax({
        url: '<?= base_url('/clients1/update/'); ?>' + clientData.CustomerID,
        type: 'POST',
        data: clientData,
        success: function(response) {
            if (response.status === 'success') {
                alert("Client updated successfully!");
                window.location.reload();
            } else {
                alert("Failed to update client: " + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error during the update:", error);
            alert("There was an error updating the client.");
        }
    });
}


    
    
</script>

<?php $this->endSection(); ?>