<?php
    $this ->extend('layout/main');
    $this ->section('body');

    ?>
    <div class="p-2 row mb-3">

    <div class="col-12 mb-2">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Coach</button>
    
    </div>

    <
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
            <th scope="col">Monthly Plan</th>
            <th scope="col">Amount</th>
            <th scope="col">Action</th>


        </tr>
    </thead>
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
<td><?= $Clients['plans']; ?></td>
<td><?= $Clients['amount']; ?></td>

<td>
<a href="/clients1/edit<?= $Clients['id']; ?>" class="btn btn-primary">Edit</a>
<a href="/clients1/delete<?= $Clients['id']; ?>" class="btn btn-danger">Delete</a>

</td>

    <?php endforeach; ?>
    </tbody>
 </table>



    </div>


    </div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="<?php echo site_url('/clients1/store'); ?>" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
    <div class="mb-3">
                <label for="gymcode" class="form-label">Gym Code</label>
                <input type="text" class="form-control" name="gymcode" value="<?= $gymcode; ?>" readonly>
             </div>

         

        <div class="mb-3">
         <label for="clients1Fname" class="form-label">First Name</label>
         <input type="text" class="form-control" name="clients1Fname" placeholder="juan"required>
         </div>

           <div class="mb-3">
                 <label for="clients1Lname" class="form-label">Last Name</label>
                  <input type="text" class="form-control" name="clients1Lname" placeholder="dela cruz"required>
          </div>
    
             <div class="mb-3">
               <label for="clients1Username" class="form-label">Username</label>
               <input type="text" class="form-control" name="clients1Username" placeholder="jdcruz"required>
             </div>

             <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="text" class="form-control" name="password" value="<?= $clients1Password; ?>" readonly>
             </div>

            <div class="mb-3">
                <label for="clients1Fulladdress" class="form-label">Full Address</label>
               <input type="text" class="form-control" name="clients1Fulladdress" placeholder="Sta Elana Hagonoy, Bulacan"required>
            </div>

             <div class="mb-3">
                <label for="clients1Emailaddress" class="form-label">Email Address</label>
                <input type="text" class="form-control" name="clients1Emailaddress" placeholder="jdcruzmwamwa@gmail.com"required>
              </div>

              <div class="mb-3">
                 <label for="clients1Phonenumber" class="form-label">Phone Number</label>
                   <input type="text" class="form-control" name="clients1Phonenumber" placeholder="09123456789"required>
              </div>
    
            <div class="mb-3">
                  <label for="gender" class="form-label">Gender:</label>
                 <select id="gender" class="form-control" name="gender"required>
                   <option value="Male">Male</option>
                      <option value="Female">Female</option>   
                    </select>
                 </div>


    <div class="mb-3">
        <label for ="dateofregistration" class="form-label">Date of Registration</label>
        <input type="date" class="form-control" name="dateofregistration"required>
   </div>

   <div class="mb-3">
    <label for="plans" class="form-label"> Choose Plan</label>
    <select id="plans" class="form-control" name="plans" required>
        <option value="1month/s">1 Month/basic </option>
        <option value="1month/s+pro">1 Month/pro </option>
        <option value="3month/s+premium">3 Months/premium </option>
    </select>

   </div>

   <div class="mb-3">
   <label for="amount" class="form-label">Amount</label>
   <input type="text" class="form-control" name="amount" required></input>
   </div>

   <div class="mb-3">
   <label for="qrcode" class="form-label">QR Code</label>
   <input type="text" class="form-control" name="qrcode" required></input>
   </div>
              

 </form>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="btn-save">Save changes</button>
      </div>
    </div>
  </div>
</div>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>
    $(document).ready(function() {
    // ... your existing code ...

    $("#btn-save").on("click", function() {
        let hasEmptyFields = false;

        // Assuming you have input fields with classes like "required-field"
        $(".required-field").each(function() {
            if ($(this).val().trim() === "") {
                hasEmptyFields = true;
                $(this).addClass("error"); // Add an error class for styling
            } else {
                $(this).removeClass("error");
            }
        });

        if (hasEmptyFields) {
            alert("Please fill in all required fields.");
            return false; // Prevent form submission
        } else {
            // Proceed with form submission or other actions
          //  alert("Coach Added Successfully!");
        }
    });
});
</script>




<?php $this->endSection(); ?> 