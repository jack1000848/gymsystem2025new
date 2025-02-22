<?php

    $this->extend('layout/bootstrapyt');
    $this->section('body');

?>
<?php if (session()->getFlashdata('success')) :?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo session ()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>
 <h1> List </h1>
 <a href="/clients1/create" class="btn btn=primary" id="btn-save">Add A Client</a>
 <table class="table">
    <thread>
    
            <th scope="col">Client ID</th>
            <th scope="col">First Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Address</th>
            <th scope="col">Gender</th>
            <th scope="col">Email Address</th>
            <th scope="col">Password</th>
            <th scope="col">Register Date</th>
            <th scope="col">Types of Workout</th>
            <th scope="col">Membership Plan</th>
            <th scope="col">QR Code</th>
            <th scope="col">Action</th>
            
        </tr>
    </thread>
    <tbody>
    <?php foreach ($clients1 as $Clients): ?>
<tr>
    <th scope="row"><?= $Clients['CustomerID']; ?></th> <!-- Mapped 'id' to 'CustomerID' -->
    <td><?= $Clients['Firstname']; ?></td> <!-- Mapped 'first_name' to 'Firstname' -->
    <td><?= $Clients['Lastname']; ?></td> <!-- Mapped 'last_name' to 'Lastname' -->
    <td><?= $Clients['Address']; ?></td> <!-- Mapped 'full_address' to 'Address' -->
    <td><?= $Clients['Gender']; ?></td> <!-- Mapped 'gender' -->
    <td><?= $Clients['Email']; ?></td> <!-- Mapped 'email_address' to 'Email' -->
    <td><?= $Clients['Password']; ?></td> <!-- Mapped 'password' to 'Password' -->
    <td><?= $Clients['RegisteredDate']; ?></td>
    <td><?= $Clients['types_of_workout']; ?></td>
    <td><?= $Clients['Membesrship_plan']; ?></td>
    <td><img id="qrCodeImage<?= $Clients['CustomerID']; ?>" src="" alt="QR Code" style="width: 100px;"></td>
    <td>
        <a href="/clients1/edit<?= $Clients['CustomerID']; ?>" class="btn btn-primary">Edit</a>
        <a href="/clients1/delete<?= $Clients['CustomerID']; ?>" class="btn btn-danger">Delete</a>
    </td>
    <td><?= $Clients['WorkoutTypeID']; ?></td> <!-- Mapped 'workout_type' to 'WorkoutTypeID' -->
    <td><?= $Clients['CurrentPlanID']; ?></td> <!-- Mapped 'plans' to 'CurrentPlanID' -->
    <td><?= $Clients['Duration'] ?? ''; ?></td> <!-- Mapped 'duration' --> 
   
</tr>
<?php endforeach; ?>

    </tbody>
 </table>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script>
    function generateQRCode(clientId) {
        const qr = new QRious({
            element: document.createElement('canvas'),
            value: `Customer ID: ${clientId}`,
            size: 200,
            background: 'white',
            foreground: 'black',
        });

        // Update the image source to the QR code generated data URL
        const qrImageElement = document.getElementById(`qrCodeImage${clientId}`);
        if (qrImageElement) {
            qrImageElement.src = qr.toDataURL();
        }
    }

    // Call the generateQRCode function for each client when the page loads
    window.onload = function() {
        <?php foreach ($clients1 as $Clients): ?>
            generateQRCode(<?= $Clients['CustomerID']; ?>); // Generate QR code for each client
        <?php endforeach; ?>
    }

</script>
 

 
 <?php $this->endSection(); ?>