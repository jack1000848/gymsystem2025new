<?php

$this->extend('layout/main'); // Extend the main layout
$this->section('body'); // Start the body section
?>
 
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Integrated CSS from your provided design -->
<style>
    body {
        background-color: #f4f4f4;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .btn-primary {
        background-color: #3498db;
        border: none;
        min-width: 100px;
    }

    .btn-primary:hover {
        background-color: #2980b9;
    }

    .btn-danger {
        background-color: #e74c3c;
        border: none;
        min-width: 100px;
    }

    .btn-danger:hover {
        background-color: #c0392b;
    }

    .btn-outline-success {
        border-color: #27ae60;
        color: #27ae60;
        min-width: 100px;
    }

    .btn-outline-success:hover {
        background-color: #27ae60;
        color: white;
    }

    .btn-info {
        background-color: #17a2b8;
        border: none;
        min-width: 100px;
    }

    .btn-info:hover {
        background-color: #138496;
    }

    .btn-warning {
        background-color: #f1c40f;
        border: none;
        min-width: 100px;
    }

    .btn-warning:hover {
        background-color: #d4ac0d;
    }

    .btn-success {
        background-color: #27ae60;
        border: none;
        min-width: 100px;
    }

    .btn-success:hover {
        background-color: #219653;
    }

    h1.modal-title {
        font-weight: bold;
        color: #2c3e50;
    }

    .modal-content {
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }

    .form-control, .select2-container--default .select2-selection--single, .select2-container--default .select2-selection--multiple {
        border-radius: 8px;
        font-size: 15px;
    }

    table.dataTable {
        width: 100% !important;
        margin: 0 auto;
        background-color: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    table.dataTable thead th {
        background-color: #3498db;
        color: white;
        font-size: 16px;
        padding: 12px;
        text-transform: uppercase;
        text-align: center;
    }

    table.dataTable tbody td {
        font-size: 15px;
        color: #2c3e50;
        text-align: center;
        padding: 10px;
    }

    table.dataTable tbody tr:hover {
        background-color: #ecf0f1;
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 6px;
        padding: 6px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    .alert {
        border-radius: 10px;
        padding: 12px;
        font-size: 15px;
    }

    .modal-footer button {
        min-width: 100px;
    }

    .btn-close {
        outline: none;
    }

    .select2-container {
        width: 100% !important;
    }
</style>
<div class="p-2 row mb-3">
    <!-- Add Client Button -->
    <div class="col-12 mb-2">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClientModal">Add Client</button>
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
                    <th>Client ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Address</th>
                    <th>Gender</th>
                    <th>Email Address</th>
                    <th style="display:none;">Password</th>
                    <th>Register Date</th>
                    <th>Types of Workout</th>
                    <th>Membership Plan</th>
                    <th>QR Code</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients1 as $client) : ?>
                    <tr>
                        <td><?= $client['CustomerID']; ?></td>
                        <td><?= $client['Firstname']; ?></td>
                        <td><?= $client['Lastname']; ?></td>
                        <td><?= $client['Address']; ?></td>
                        <td><?= $client['Gender']; ?></td>
                        <td><?= $client['Email']; ?></td>
                        <td style="display:none;"><?= $client['password_hash']; ?></td>
                        <td><?= $client['RegisteredDate']; ?></td>
                        <td><?= $client['types_of_workout']; ?></td>
                        <td><?= $client['Membership_plan']; ?></td>
                        <td><img id="qrCodeImage<?= $client['CustomerID']; ?>" src="" alt="QR Code" style="width: 100px;"></td>
                        <td>
                            <span onclick="editClient('<?= $client['CustomerID']; ?>')" class="btn btn-sm btn-primary">Edit</span>
                            <span onclick="renew('<?= $client['CustomerID']; ?>')" class="btn btn-sm btn-outline-success">Renew</span>
                            <span onclick="deleteClient('<?= $client['CustomerID']; ?>')" class="btn btn-sm btn-danger">Delete</span>
                            <span onclick="toggleFreeze('<?= $client['CustomerID']; ?>')" 
                                 class="btn btn-sm <?= $client['is_frozen'] ? 'btn-success' : 'btn-warning' ?>">
                                    <?= $client['is_frozen'] ? 'Unfreeze' : 'Freeze' ?>
                           </span>
                           <a href="<?= base_url('clients1/view/' . $client['CustomerID']); ?>" class="btn btn-sm btn-info">View</a>
                             
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Client Modal -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addClientModalLabel">Add Client</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo site_url('/clients1/store'); ?>" method="POST">
                    
                    

                    <!-- First Name -->
                    <div class="mb-3">
                        <label for="clients1Fname" class="form-label">First Name</label>
                        <input type="text" class="form-control" name="clients1Fname" placeholder="Juan"  required>
                    </div>

                    <!-- Last Name -->
                    <div class="mb-3">
                        <label for="clients1Lname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="clients1Lname" placeholder="Dela Cruz" required>
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label for="clients1Adress" class="form-label">Address</label>
                        <input type="text" class="form-control" name="clients1Adress" placeholder="123 Main St" required>
                    </div>

                    <!-- Gender -->
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select id="gender" class="form-control" name="gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label for="clients1Emailaddress" class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="clients1Emailaddress" placeholder="juan.delacruz@gmail.com" required>
                        <small id="emailError" style="color: red; display: none;">Only Gmail addresses are allowed!</small>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="text" class="form-control" name="password" value="<?= isset($client1Password) ? $clients1Password : ''; ?>"required>
                    </div>

                    <!-- Date of Registration -->
                    <div class="mb-3">
                        <label for="dateofregistration" class="form-label">Date of Registration</label>
                        <input type="date" class="form-control" name="dateofregistration" required>
                    </div>

                     <!-- Gymtimeslot -->
                    

                    <!-- Types of Workout -->
                    <div class="mb-3">
                        <label for="tworkout" class="form-label">Types of Workout</label>
                        <select id="tworkout" class="form-control" name="tworkout" required>
                            <option value="Bulking">Bulking</option>
                            <option value="Cutting">Cutting</option>
                            <option value="Endurance Training">Endurance Training</option>
                            <option value="Strength Training">Strength Training</option>
                            <option value="Functional Fitness">Functional Fitness</option>
                        </select>
                    </div>

                    <!-- Membership Plan -->
                    <div class="mb-3">
                        <label for="plans" class="form-label">Membership Plan</label>
                        <select id="planSelect" class="form-control" name="plans" required>
                            <!-- Options will be dynamically added by AJAX -->
                        </select>
                    </div>

                    <!-- Coach -->
                    <div class="mb-3" id="coachSelectDiv">
                        <label for="coach" class="form-label">Select Coach</label>
                        <select id="coach" name="coach" class="form-control" name="coach" id="coach" required>
                            <option value="">Select a Coach</option>
                        </select>
                    </div>
                    <!-- Coach Sched -->    
                    <div class="mb-3" id="coachschedSelectDiv">
                        <label for="coachsched" class="form-label">Select Schedules</label>
                        <select id="coachsched" class="form-control" multiple name="coachsched[]" required>
                            <option value="">Select a Schedule</option>
                        </select>
                    </div>

                    <!-- Total Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Total Amount</label>
                        <input type="text" id="priceInput" readonly disabled class="form-control" name="amount" readonly>
                    </div>

                    <!-- Duration -->
                    <div class="mb-3">
                        <label for="duration" class="form-label">Duration</label>
                        <input type="number" id="duration" readonly disabled class="form-control" name="duration" required>
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
                <h1 class="modal-title fs-5" id="editClientModalLabel">Edit Client</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editClientForm"  >
                    <input type="hidden" id="editClientId" name="id">

                    <!-- Gym Code
                     <div class="mb-3">
                        <label for="editGymcode" class="form-label">Gym Code</label>
                        <input type="text" class="form-control" id="editGymcode" name="gymcode" disabled readonly>
                    </div> -->
                    

                    <!-- First Name -->
                    <div class="mb-3">
                        <label for="editClients1Fname" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="editClients1Fname" name="clients1Fname" required>
                    </div>

                    <!-- Last Name -->
                    <div class="mb-3">
                        <label for="editClients1Lname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="editClients1Lname" name="clients1Lname" required>
                    </div>
                    
                                        <!-- Address -->
                    <div class="mb-3">
                        <label for="editaddress" class="form-label">Address</label>
                        <input type="text" class="form-control" id="editaddress" name="clients1Fulladdress" required>
                    </div>
                    <!-- Gender -->
                    <div class="mb-3">
                        <label for="editGender" class="form-label">Gender</label>
                        <select id="editGender" class="form-control" name="gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label for="editClients1Emailaddress" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="editClients1Emailaddress" name="clients1Emailaddress" required>
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

<!-- renewal Client Modal -->
<div class="modal fade" id="tryClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editClientModalLabel">Renew Client</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="renewClientForm">
                    <input type="hidden" id="renewClientId" name="id">


                    <!-- Date of Registration -->
                    <div class="mb-3">
                        <label for="renewDateofregistration" class="form-label">Date of Registration</label>
                        <input type="date" class="form-control" id="renewDateofregistration" name="dateofregistration" required>
                    </div>

                     <!-- Gymtimeslot -->
                     

                    <!-- Types of Workout -->
                    <div class="mb-3">
                        <label for="renewTworkout" class="form-label">Types of Workout</label>
                        <select id="renewTworkout" class="form-control" name="tworkout" required>
                            <option value="Bulking">Bulking</option>
                            <option value="Cutting">Cutting</option>
                            <option value="Endurance Training">Endurance Training</option>
                            <option value="Strength Training">Strength Training</option>
                            <option value="Functional Fitness">Functional Fitness</option>
                        </select>
                    </div>

                   <!-- Membership Plan -->
                <div class="mb-3">
                       <label for="renewPlanSelect" class="form-label">Membership Plan</label>
                   <select id="renewPlans" class="form-control" name="plans" required>
                      <!-- Options will be dynamically added by AJAX -->
                   </select>
                </div>

                <!-- Coach Selection -->
            <div class="mb-3">
                <label for="renewCoach" class="form-label">Select Coach</label>
                    <select id="renewCoach" class="form-control" name="coach" required>
                <option value="">Select a Coach</option>
                    </select>
            </div>
                    <!-- Coach Sched -->
                    <div class="mb-3" id="coachschedSelectDiv">
                        <label for="renewcoachsched" class="form-label">Select Schedules</label>
                        <select id="renewcoachsched" class="form-control" multiple name="coachsched[]" required>
                            <option value="">Select a Schedule</option>
                        </select>
                    </div>

                    <!-- Total Amount -->
                    <div class="mb-3">
                        <label for="renewtAmount" class="form-label">Total Amount</label>
                        <input type="text" id="renewPriceInput" class="form-control" name="amount" readonly>
                    </div>

                    <!-- Duration -->
                    <div class="mb-3">
                        <label for="renewDuration" class="form-label">Duration</label>
                        <input type="number" class="form-control" id="renewDuration" name="duration" readonly>
                    </div>

                    <!-- Submit Button -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"id="btn-update1">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery (required for AJAX) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS (bundle version for Bootstrap 5) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Your custom JavaScript file -->
<script src="path/to/your/script.js"></script>
<script>
$(document).ready(function () {
    // Initialize DataTable
    let table = new DataTable('#clientTable', {
        responsive: true
    });

    // Initialize Select2 for multiple select fields
    $('#coachsched, #renewcoachsched').select2({
        placeholder: "Select a Schedule",
        allowClear: true,
        width: '100%'
    });

    // Fetch schedules for Add Client modal
    $("#coach").on('change', async function() {
        const coachId = $(this).val();
        const schedEl = $("#coachsched");
        schedEl.empty().append('<option value="">Select a Schedule</option>').trigger('change');

        if (!coachId) return;

        try {
            const data = await $.get("<?= base_url('/getSchedules/') ?>" + coachId);
            console.log('Add Client Schedules:', data);

            if (!Array.isArray(data) || data.length === 0) {
                schedEl.append('<option value="">No schedules available</option>').trigger('change');
                return;
            }

            data.forEach(sched => {
                schedEl.append(`<option value="${sched.ID}">${sched.ScheduleDate} : ${sched.Start} - ${sched.End}</option>`);
            });
            schedEl.trigger('change');
        } catch (error) {
            console.error("Error fetching add client schedules:", error);
            schedEl.append('<option value="">Failed to load schedules</option>').trigger('change');
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load coach schedules. Please try again.',
            });
        }
    });

    // Fetch schedules for Renew Client modal
    $("#renewCoach").on('change', async function() {
        const coachId = $(this).val();
        const schedEl = $("#renewcoachsched");
        schedEl.empty().append('<option value="">Select a Schedule</option>').trigger('change');

        if (!coachId) return;

        try {
            const data = await $.get("<?= base_url('/getSchedules/') ?>" + coachId);
            console.log('Renew Client Schedules:', data);

            if (!Array.isArray(data) || data.length === 0) {
                schedEl.append('<option value="">No schedules available</option>').trigger('change');
                return;
            }

            data.forEach(sched => {
                schedEl.append(`<option value="${sched.ID}">${sched.ScheduleDate} : ${sched.Start} - ${sched.End}</option>`);
            });
            schedEl.trigger('change');
        } catch (error) {
            console.error("Error fetching renew client schedules:", error);
            schedEl.append('<option value="">Failed to load schedules</option>').trigger('change');
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load coach schedules. Please try again.',
            });
        }
    });

    // Edit Client Form Submission
    $("#editClientForm").submit(function(event) {
        event.preventDefault();
        const formData = $(this).serialize();
        const clientId = $("#editClientId").val();

        $.ajax({
            url: "<?= base_url('/clients1/update/') ?>" + clientId,
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Updated!",
                        text: response.message,
                        confirmButtonText: "OK"
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Update Failed",
                        text: response.message || "Failed to update client.",
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Something went wrong. Please try again.",
                });
                console.error(xhr.responseText);
            }
        });
    });

    // Renew Client Form Submission
    $("#renewClientForm").submit(function(event) {
        event.preventDefault();
        const formData = $(this).serialize();
        const clientId = $("#renewClientId").val();

        $.ajax({
            url: "<?= base_url('/clients1/updaterenew/') ?>" + clientId,
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Renewed!",
                        text: response.message,
                        confirmButtonText: "OK"
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Renewal Failed",
                        text: response.message || "Failed to renew client.",
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Something went wrong. Please try again.",
                });
                console.error(xhr.responseText);
            }
        });
    });

    // Fetch Plans and Coaches
    fetchPlans();
    $('#planSelect').on('change', function() {
        var planId = $(this).val();
        if (planId) fetchCoach(planId);
    });
    $('#renewPlans').on('change', function() {
        var planId = $(this).val();
        if (planId) fetchrenewCoach(planId);
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
                await $.ajax({
                    url: '<?= base_url('/clients1/delete/'); ?>' + id,
                    type: 'DELETE',
                    success: function(response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'The client has been deleted successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
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

    // Generate QR Code
    function generateQRCode(clientId) {
        const qr = new QRious({
            element: document.createElement('canvas'),
            value: `${clientId}`,
            size: 200,
            background: 'white',
            foreground: 'black',
        });

        const qrImageElement = document.getElementById('qrCodeImage' + clientId);
        if (qrImageElement) {
            qrImageElement.src = qr.toDataURL();
        }
    }

    // Generate QR Codes for all clients
    window.onload = function() {
        <?php foreach ($clients1 as $client) : ?>
            generateQRCode(<?= $client['CustomerID']; ?>);
        <?php endforeach; ?>
    };

    // Fetch Plans
    async function fetchPlans() {
        try {
            const data = await $.get("<?= base_url('/fetchPlans'); ?>");
            console.log('Plans:', data);
            $('#planSelect').empty().append('<option value="">Select a Plan</option>');
            $('#renewPlans').empty().append('<option value="">Select a Plan</option>');

            data.forEach(plan => {
                $('#planSelect').append(`<option value="${plan.PlanID}">${plan.PlanName}</option>`);
                $('#renewPlans').append(`<option value="${plan.PlanID}">${plan.PlanName}</option>`);
            });
        } catch (error) {
            console.error("Error fetching plans:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load plans. Please try again.',
            });
        }
    }

    // Fetch Coaches for Add Client
    async function fetchCoach(planId) {
        try {
            const data = await $.get(`<?= base_url('/fetchCoachPlan'); ?>?planId=${planId}`);
            console.log('Add Client Coaches:', data);
            $('#coach').empty().append('<option value="">Select a Coach</option>');
            $("#priceInput").val(data[0]?.Price ? parseFloat(data[0].Price).toFixed(2) : '');
            $("#duration").val(data[0]?.Duration || '');

            data.forEach(coach => {
                if (coach.CoachID !== null) {
                    $('#coach').append(`<option value="${coach.CoachID}">${coach.FullName}</option>`);
                }
            });
        } catch (error) {
            console.error("Error fetching add client coaches:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load coaches. Please try again.',
            });
        }
    }

    // Fetch Coaches for Renew Client
    async function fetchrenewCoach(planId, selectedCoachId = null) {
        try {
            const data = await $.get(`<?= base_url('/fetchCoachPlan'); ?>?planId=${planId}`);
            console.log('Renew Client Coaches:', data);
            $('#renewCoach').empty().append('<option value="">Select a Coach</option>');
            $('#renewPriceInput').val(data[0]?.Price ? parseFloat(data[0].Price).toFixed(2) : '');
            $('#renewDuration').val(data[0]?.Duration || '');

            data.forEach(coach => {
                if (coach.CoachID !== null) {
                    let selected = (coach.CoachID == selectedCoachId) ? "selected" : "";
                    $('#renewCoach').append(`<option value="${coach.CoachID}" ${selected}>${coach.FullName}</option>`);
                }
            });
        } catch (error) {
            console.error("Error fetching renew client coaches:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load coaches. Please try again.',
            });
        }
    }

    // Edit Client
    async function editClient(id) {
        try {
            const res = await $.get('<?= base_url('/clients1/edit/'); ?>' + id);
            if (res && res.data) {
                const client = res.data;
                console.log('Edit Client Data:', client);
                $("#editClientId").val(client.CustomerID);
                $("#editClients1Fname").val(client.Firstname);
                $("#editClients1Lname").val(client.Lastname);
                $("#editaddress").val(client.Address);
                $("#editGender").val(client.Gender);
                $("#editClients1Emailaddress").val(client.Email);
                $("#editClientModal").modal('show');
            } else {
                console.error('No data found in the response:', res);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load client data.',
                });
            }
        } catch (error) {
            console.error('Error fetching client data:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load client data.',
            });
        }
    }

    // Toggle Freeze
    async function toggleFreeze(CustomerID) {
        const { isConfirmed } = await Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to change the freeze status of this client.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
        });

        if (isConfirmed) {
            try {
                const response = await fetch('<?= base_url('/customer/toggleFreeze/'); ?>' + CustomerID, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();
                if (response.ok) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Something went wrong!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
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

    // Renew Client
    async function renew(id) {
        try {
            const res = await $.get('<?= base_url('/clients1/try/'); ?>' + id);
            if (res && res.data) {
                const client = res.data;
                console.log('Renew Client Data:', client);
                $("#renewClientId").val(client.CustomerID);
                $("#renewDateofregistration").val(client.RegisteredDate);
                $("#renewTworkout").val(client.types_of_workout);
                $("#renewPriceInput").val(client.amount ? parseFloat(client.amount).toFixed(2) : '');
                $("#renewDuration").val(client.duration || '');
                $("#tryClientModal").modal('show');

                // Fetch plans and set the selected one
                await fetchrenewPlans(client.Membership_plan);
                // Fetch coaches and set the selected one
                await fetchrenewCoach(client.Membership_plan, client.CoachID);
                // Fetch schedules for the selected coach
                if (client.CoachID) {
                    await fetchRenewSchedules(client.CoachID);
                } else {
                    console.log('No CoachID found for client:', client.CustomerID);
                    $('#renewcoachsched').empty().append('<option value="">Select a Coach first</option>').trigger('change');
                }
            } else {
                console.error('No data found in the response:', res);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load client data.',
                });
            }
        } catch (error) {
            console.error('Error fetching client data:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load client data.',
            });
        }
    }

    // Fetch Plans for Renewal
    async function fetchrenewPlans(selectedPlanId) {
        try {
            const data = await $.get("<?= base_url('/fetchPlans'); ?>");
            console.log('Renew Plans:', data);
            $('#renewPlans').empty().append('<option value="">Select a Plan</option>');

            data.forEach(plan => {
                let selected = (plan.PlanID == selectedPlanId) ? "selected" : "";
                $('#renewPlans').append(`<option value="${plan.PlanID}" ${selected}>${plan.PlanName}</option>`);
            });
        } catch (error) {
            console.error("Error fetching renew plans:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load plans. Please try again.',
            });
        }
    }

    // Fetch Schedules for Renew Client
    async function fetchRenewSchedules(coachId) {
        const schedEl = $("#renewcoachsched");
        schedEl.empty().append('<option value="">Select a Schedule</option>').trigger('change');

        if (!coachId) {
            console.log('No CoachID provided for schedule fetch');
            schedEl.append('<option value="">Select a Coach first</option>').trigger('change');
            return;
        }

        try {
            const data = await $.get(`<?= base_url('/getSchedules/'); ?>${coachId}`);
            console.log('Renew Client Schedules:', data);

            if (!Array.isArray(data) || data.length === 0) {
                console.log('No schedules returned for CoachID:', coachId);
                schedEl.append('<option value="">No schedules available</option>').trigger('change');
                return;
            }

            data.forEach(sched => {
                schedEl.append(`<option value="${sched.ID}">${sched.ScheduleDate} : ${sched.Start} - ${sched.End}</option>`);
            });
            schedEl.trigger('change');
        } catch (error) {
            console.error("Error fetching renew client schedules:", error);
            schedEl.append('<option value="">Failed to load schedules</option>').trigger('change');
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load coach schedules. Check console for details.',
            });
        }
    }
});
</script>

<?php $this->endSection(); ?>