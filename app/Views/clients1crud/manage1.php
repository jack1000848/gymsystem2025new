<?php
$this->extend('layout/main'); // Extend the main layout
$this->section('body'); // Start the body section
?>
 
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
                    <th style="display: none">Password</th>
                    <th>Register Date</th>
                    <th>Types of Workout</th>
                    <th>Gym Time SLot</th>
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
                        <td style="display: none"><?= $client['Password']; ?></td>
                        <td><?= $client['RegisteredDate']; ?></td>
                        <td><?= $client['types_of_workout']; ?></td>
                        <td><?= $client['GymTimeSlot']; ?></td>
                        <td><?= $client['Membesrship_plan']; ?></td>
                        <td><img id="qrCodeImage<?= $client['CustomerID']; ?>" src="" alt="QR Code" style="width: 100px;"></td>
                        <td>
                            <span onclick="editClient('<?= $client['CustomerID']; ?>')" class="btn btn-sm btn-primary">Edit</span>
                            <span onclick="deleteClient('<?= $client['CustomerID']; ?>')" class="btn btn-sm btn-danger">Delete</span>
                            <span onclick="toggleFreeze('<?= $client['CustomerID']; ?>')" 
                                 class="btn btn-sm <?= $client['is_frozen'] ? 'btn-success' : 'btn-warning' ?>">
                                    <?= $client['is_frozen'] ? 'Unfreeze' : 'Freeze' ?>
                           </span>
                           <a href="/clients1/view/<?= $client['CustomerID']; ?>" class="btn btn-sm btn-info">View</a>
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
                <form action="/clients1/store" method="POST">
                    <!-- Gym Code -->
                    <div class="mb-3">
                        <label for="gymcode" class="form-label">Gym Code</label>
                        <input type="text" class="form-control" name="gymcode" value="<?= $next_id; ?>" disabled readonly>
                    </div>

                    <!-- First Name -->
                    <div class="mb-3">
                        <label for="clients1Fname" class="form-label">First Name</label>
                        <input type="text" class="form-control" name="clients1Fname" placeholder="Juan" required>
                    </div>

                    <!-- Last Name -->
                    <div class="mb-3">
                        <label for="clients1Lname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="clients1Lname" placeholder="Dela Cruz" required>
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label for="clients1Username" class="form-label">Address</label>
                        <input type="text" class="form-control" name="clients1Username" placeholder="123 Main St" required>
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
                     <div class="mb-3">
                        <label for="timeslot" class="form-label">Gym Time SLot</label>
                        <select id="timeslot" class="form-control" name="timeslot" required>
                            <option value="Day Class">Day Class</option>
                            <option value="Evening Class">Evening Class</option>
                        </select>
                        </div>

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
                        <select id="coach" class="form-control" name="coach" required>
                            <option value="">Select a Coach</option>
                        </select>
                    </div>

                    <!-- Total Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Total Amount</label>
                        <input type="text" id="priceInput" class="form-control" name="amount" readonly>
                    </div>

                    <!-- Duration -->
                    <div class="mb-3">
                        <label for="duration" class="form-label">Duration</label>
                        <input type="number" class="form-control" name="duration" required>
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
                <form id="editClientForm">
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
                    <div class="mb-3">
                        <label for="edituser" class="form-label">Username</label>
                        <input type="text" class="form-control" id="edituser" name="clients1Username" required>
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label for="editaddress" class="form-label">Address</label>
                        <input type="text" class="form-control" id="editaddress" name="clients1Username" required>
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

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="editPassword" class="form-label">Password</label>
                        <input type="text" class="form-control" id="editPassword" name="password" required>
                    </div>

                    <!-- Date of Registration -->
                    <div class="mb-3">
                        <label for="editDateofregistration" class="form-label">Date of Registration</label>
                        <input type="date" class="form-control" id="editDateofregistration" name="dateofregistration" required>
                    </div>

                     <!-- Gymtimeslot -->
                     <div class="mb-3">
                        <label for="edittimeslot" class="form-label">Gym Time SLot</label>
                        <select id="edittimeslot" class="form-control" name="edittimeslot" required>
                            <option value="Day Class">Day Class</option>
                            <option value=">Evening Class">Evening Class</option>
                        </select>
                        </div>

                    <!-- Types of Workout -->
                    <div class="mb-3">
                        <label for="editTworkout" class="form-label">Types of Workout</label>
                        <select id="editTworkout" class="form-control" name="tworkout" required>
                            <option value="Bulking">Bulking</option>
                            <option value="Cutting">Cutting</option>
                            <option value="Endurance Training">Endurance Training</option>
                            <option value="Strength Training">Strength Training</option>
                            <option value="Functional Fitness">Functional Fitness</option>
                        </select>
                    </div>

                   <!-- Membership Plan -->
                <div class="mb-3">
                       <label for="editPlanSelect" class="form-label">Membership Plan</label>
                   <select id="editPlanSelect" class="form-control" name="plans" required>
                      <!-- Options will be dynamically added by AJAX -->
                   </select>
                </div>

                <!-- Coach Selection -->
            <div class="mb-3">
                <label for="editCoach" class="form-label">Select Coach</label>
                    <select id="editCoach" class="form-control" name="coach" required>
                <option value="">Select a Coach</option>
                    </select>
            </div>


                    <!-- Total Amount -->
                    <div class="mb-3">
                        <label for="editAmount" class="form-label">Total Amount</label>
                        <input type="text" id="editPriceInput" class="form-control" name="amount" readonly>
                    </div>

                    <!-- Duration -->
                    <div class="mb-3">
                        <label for="editDuration" class="form-label">Duration</label>
                        <input type="number" class="form-control" id="editDuration" name="duration" readonly>
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
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
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
                url: '/clients1/delete/' + id, // Adjust URL for your delete route
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


    // Function to generate QR Code
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
    window.onload = function () {
        <?php foreach ($clients1 as $client) : ?>
            generateQRCode(<?= $client['CustomerID']; ?>);
        <?php endforeach; ?>
    };

    // Fetch Plans
    async function fetchPlans() {
        try {
            const data = await $.get("/fetchPlans");
            $('#planSelect').empty();
            data.forEach(plan => {
                $('#planSelect').append(`<option value="${plan.PlanID}">${plan.PlanName}</option>`);
            });
        } catch (error) {
            console.error("Error fetching plans:", error);
        }
    }

    // Fetch Coaches
    async function fetchCoach(planId) {
        try {
            const data = await $.get(`/fetchCoachPlan?planId=${planId}`);
            $('#coach').empty();
            $('#coach').append('<option value="">Select a Coach</option>');
            data.forEach(coach => {
                $('#coach').append(`<option value="${coach.planID} ${coach.coachID}">${coach.FullName}</option>`);
            });
        } catch (error) {
            console.error("Error fetching coaches:", error);
        }
    }

    // Edit Client
    async function editClient(id) {
    try {
        const res = await $.get('/clients1/edit/' + id);

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

async function toggleFreeze(clientId) {
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
            const response = await fetch('/customer/toggleFreeze/' + clientId, {
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
                    location.reload(); // Refresh the page to reflect changes
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



async function fetchEditPlans(selectedPlanId) {
    try {
        const data = await $.get("/fetchPlans");
        $('#editPlanSelect').empty();
        
        data.forEach(plan => {
            let selected = plan.PlanID == selectedPlanId ? "selected" : "";
            $('#editPlanSelect').append(`<option value="${plan.PlanID}" ${selected}>${plan.PlanName}</option>`);
        });
    } catch (error) {
        console.error("Error fetching plans:", error);
    }
}

async function fetchEditCoach(planId, selectedCoachId = null) {
    try {
        const data = await $.get(`/fetchCoachPlan?planId=${planId}`);
        $('#editCoach').empty();
        $('#editCoach').append('<option value="">Select a Coach</option>');

        data.forEach(coach => {
            let selected = (coach.coachID == selectedCoachId) ? "selected" : "";
            $('#editCoach').append(`<option value="${coach.planID} ${coach.coachID}" ${selected}>${coach.FullName}</option>`);
        });
    } catch (error) {
        console.error("Error fetching coaches:", error);
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
        url: '/clients1/update/' + clientData.CustomerID,
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