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
        <tr>
            <th scope="col">#</th>
            <th scope="col">Gym Code</th>
            <th scope="col">QrCode</th>
            <th scope="col">First Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Username</th>
            <th scope="col">Password</th>
            <th scope="col">Full Address</th>
            <th scope="col">Email Address</th>
            <th scope="col">Phone Number</th>
            <th scope="col">Gender</th>
            <th scope="col">Date of Registration</th>
            <th scope="col">Types of Workout</th>
            <th scope="col">Monthly Plan</th>
            <th scope="col">Amount</th>
            <th scope="col">>Duration (in days)</th>
            <th scope="col">QrCode Path</th>
            <th scope="col">Action</th>

        </tr>
    </thread>
    <tbody>
    <?php foreach ($clients1 as $Clients): ?>

<tr>
<th scope="row"><?= $Clients['id']; ?></th>
<td><?= $Clients['gym_code']; ?></td>  
<td><?= $Clients['qrcode']; ?></td>
<td><?= $Clients['first_name']; ?></td>
<td><?= $Clients['last_name']; ?></td>
<td><?= $Clients['user_name']; ?></td>
<td><?= $Clients['password']; ?></td>
<td><?= $Clients['full_address']; ?></td>
<td><?= $Clients['email_address']; ?></td>
<td><?= $Clients['phone_number']; ?></td>
<td><?= $Clients['gender']; ?></td>
<td><?= $Clients['date_of_registration']; ?></td>
<td><?= $Clients['workout_type']; ?></td>
<td><?= $Clients['plans']; ?></td>
<td><?= $Clients['amount']; ?></td>
<td><?= $Clients['duration']; ?></td>
<td><img src="<?= base_url('') ?>" alt="QR Code" style="width: 100px;"></td>
<td>
<a href="/clients1/edit<?= $Clients['id']; ?>" class="btn btn-primary">Edit</a>
<a href="/clients1/delete<?= $Clients['id']; ?>" class="btn btn-danger">Delete</a>

</td>

    <?php endforeach; ?>
    </tbody>
 </table>




 
 <?php $this->endSection(); ?>