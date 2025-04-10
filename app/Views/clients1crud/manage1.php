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
                        <select id="coach" class="form-control" name="coach" id="coach" required>
                            <option value="">Select a Coach</option>
                        </select>
                    </div>
                    <!-- Coach Sched -->    
                    <div class="mb-3" id="coachschedSelectDiv">
                        <label for="coachsched" class="form-label">Select Schedules</label>
                        <select id="coachsched" class="form-control" multiple name="coachsched" required>
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

<!-- try Client Modal -->
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

        $("#coach").on('change', async function() {
    const value = $(this).val();
    console.log(value);

    const schedEl = $("#coachsched");
    schedEl.empty();

    try {
        const data = await $.get("<?= base_url('/getCoachSchedules') ?>/" + value);
        console.log(data);

        if (data.length === 0) {
            schedEl.append("<p>No schedules available.</p>");
            return;
        }

        data.forEach(sched => {
            const scheduleItem = `
                <option value="${sched.ID}">${sched.ScheduleDate} : ${sched.Start} - ${sched.End} </option>
            `;
            schedEl.append(scheduleItem);
        });
    } catch (error) {
        console.error("Error fetching schedules:", error);
        schedEl.append("<p>Failed to load schedules.</p>");
    }
    });
  });



        $("#editClientForm").submit(function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = $(this).serialize(); // Serialize form data for form-encoded submission
        const clientId = $("#editClientId").val(); // Get client ID

        $.ajax({
            url: "<?= base_url('/clients1/update/') ?>/" + clientId, // Corrected route
            type: "POST",
            data: formData, // Use form-encoded format, NOT JSON
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Updated!",
                        text: "Client details updated successfully.",
                        confirmButtonText: "OK"
                    }).then(() => {
                        location.reload(); // Reload the page
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Update Failed",
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Something went wrong. Please try again."
                });
                console.error(xhr.responseText);
            }
        });
    });
        ///submit edit
        
        $("#renewClientForm").submit(function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = $(this).serialize(); // Serialize form data for form-encoded submission
        const clientId = $("#renewClientId").val(); // Get client ID

        $.ajax({
            url: "<?= base_url('/clients1/renewupdate/') ?>/" + clientId, // Corrected route
            type: "POST",
            data: formData, // Use form-encoded format, NOT JSON
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Updated!",
                        text: "Client details updated successfully.",
                        confirmButtonText: "OK"
                    }).then(() => {
                        location.reload(); // Reload the page
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Update Failed",
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Something went wrong. Please try again."
                });
                console.error(xhr.responseText);
            }
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
$('#renewPlans').on('change', function () {
    var planId = $(this).val();
    if (planId) {
        fetchrenewCoach(planId);
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

                                
  //  document.getElementById("clients1Emailaddress").addEventListener("input", function() {
 //   var emailInput = this.value;
  //  var emailError = document.getElementById("emailError");
    
   // if (!emailInput.endsWith("@gmail.com")) {
  //      emailError.style.display = "block";
   // } else {
   //     emailError.style.display = "none";
  //  }
  //  });

    
    // Fetch Plans
    async function fetchPlans() {
        try {
            const data = await $.get("<?= base_url('/fetchPlans'); ?>");
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
            const data = await $.get(`<?= base_url('/fetchCoachPlan'); ?> ?planId=${planId}`);
            $('#coach').empty();
            $("#priceInput").val(data.Price);
            $('#coach').append('<option value="">Select a Coach</option>');
            data.forEach(coach => {
                $('#coach').append(`<option value="${coach.CoachID}">${coach.FullName}</option>`);
            });
        } catch (error) {
            console.error("Error fetching coaches:", error);
        }
    }

    // Edit Client
    async function editClient(id) {
    try {
        const res = await $.get('<?= base_url('/clients1/edit/'); ?>' + id);

        if (res && res.data) {
            const client = res.data;

            $("#editClientId").val(client.CustomerID);
            $("#editClients1Fname").val(client.Firstname);
            $("#editClients1Lname").val(client.Lastname);
            $("#editClients1Emailaddress").val(client.Email);
           /// $("#editPassword").val(client.Password);
            $("#editGender").val(client.Gender);
            $("#editDateofregistration").val(client.RegisteredDate);
            $("#editTworkout").val(client.types_of_workout);
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

async function renew(id) {
    try {
        const res = await $.get('<?= base_url('/clients1/renew/'); ?>' + id);

        if (res && res.data) {
            const client = res.data;
            $("#renewClientId").val(client.CustomerID);
            $("#renewDateofregistration").val(client.RegisteredDate);
            $("#renewTworkout").val(client.workout_type);
            $("#renewPlans").val(client.plans);  // Ensure this matches the dropdown ID
            $("#renewAmount").val(parseFloat(client.amount).toFixed(2));
            $("#renewDuration").val(client.duration);
            $("#renewCoach").val(client.coach);

            $("#tryClientModal").modal('show');

            // Fetch plans and set the selected one
            await fetchrenewPlans(client.PlanID);  // <== Added this line

            // Fetch coaches for the selected plan
            await fetchrenewCoach(client.PlanID, client.CoachID);

        } else {
            console.error('No data found in the response:', res);
        }
    } catch (error) {
        console.error('Error fetching client data:', error);
    }
}

async function fetchrenewCoach(planId, selectedCoachId = null) {
    try {
        const data = await $.get(`<?= base_url('/fetchCoachPlan'); ?>?planId=${planId}`);
        $('#renewCoach').empty();
        $('#renewCoach').append('<option value="">Select a Coach</option>');

        data.forEach(coach => {
            let selected = (coach.coachID == selectedCoachId) ? "selected" : "";
            $('#renewCoach').append(`<option value="${coach.coachID}" ${selected}>${coach.FullName}</option>`);
        });
    } catch (error) {
        console.error("Error fetching coaches:", error);
    }
}

async function fetchrenewPlans(selectedPlanId) {
    try {
        const data = await $.get("<?= base_url('/fetchPlans'); ?>");
        $('#renewPlans').empty(); // Ensure this matches the ID in `renew(id)`

        data.forEach(plan => {
            let selected = plan.PlanID == selectedPlanId ? "selected" : "";
            $('#renewPlans').append(`<option value="${plan.PlanID}" ${selected}>${plan.PlanName}</option>`);
        });
    } catch (error) {
        console.error("Error fetching plans:", error);
    }
}

async function renewUpdate() {
    let clientData = {
        RegisteredDate: $("#editDateofregistration").val().trim(),
        types_of_workout: $("#editTworkout").val().trim(),
        amount: $("#editAmount").val().trim(),
        duration: $("#editDuration").val().trim(),
        Membership_plan: $("#editPlanSelect").val(), // Include the plan
        coach: $("#editCoach").val() // Include the coach
    };
      if  (!clientData.amount || !clientData.duration || !clientData.plan) {
        alert("Please fill in all required fields.");
        return;
    }
    
    $.ajax({
        url: '<?= base_url('/clients1/renewupdate/'); ?>' + clientData.id,
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



async function fetchEditPlans(selectedPlanId) {
    try {
        const data = await $.get("<?= base_url('/fetchPlans'); ?>");
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
        const data = await $.get(`<?= base_url('/fetchCoachPlan'); ?>?planId=${planId}`);
        $('#editCoach').empty();
        $('#editCoach').append('<option value="">Select a Coach</option>');

        data.forEach(coach => {
            let selected = (coach.coachID == selectedCoachId) ? "selected" : "";
            $('#editCoach').append(`<option value="${coach.planID} ${coach.coachID}" ${selected}>${coach.FullName}</option>`);
        });
    } catch (error) {
        console.error("Error fetching coaches:", error);
    }

    async function updateClient() {
    let clientData = {
        id: $("#editClientId").val().trim(),
        first_name: $("#editClients1Fname").val().trim(),
        last_name: $("#editClients1Lname").val().trim(),
        user_name: $("#edituser").val().trim(),
        email_address: $("#editClients1Emailaddress").val().trim(),
        password: $("#editPassword").val().trim(),
        gender: $("#editGender").val().trim(),
        date_of_registration: $("#editDateofregistration").val().trim(),
        workout_type: $("#editTworkout").val().trim(),
        amount: $("#editPriceInput").val().trim(), // Ensure you read the right ID
        duration: $("#editDuration").val().trim(), // Ensure the ID matches
        plans: $("#editPlanSelect").val(),
        coach: $("#editCoach").val()
    };

    // Check for any empty required fields
    for (let key in clientData) {
        if (clientData[key] === "" && (key !== 'id' && key !== 'coach' && key !== 'duration')) {
            alert("Please fill in all required fields.");
            return;
        }
    }

    $.ajax({
        url: '<?= base_url('/clients1/update/'); ?>' + clientData.id,
        type: 'POST',
        data: clientData,
        dataType: 'json',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        success: function(response) {
            if (response.status === 'success') {
                alert("Client updated successfully!");
                window.location.reload();
            } else {
                alert("Failed to update client: " + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error during the update:", xhr.responseText);
            alert("There was an error updating the client.");
        }
    });
}

}






    
    
</script>

<?php $this->endSection(); ?>