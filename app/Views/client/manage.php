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
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Password</th>
            <th>Address</th>
            <th>Email</th>
            <th>Create Profile</th>
            <th>Action</th>


        </tr>
    </thead>
    <tbody>
    <?php foreach ($clients as $client): ?>
    <tr>
        <th scope="row"><?= $client['CoachID']; ?></th>
        <td><?= $client['Firstname']; ?></td>
        <td><?= $client['Lastname']; ?></td>
        <td><?= $client['Password']; ?></td>
        <td></td>
        <td><?= $client['Email']; ?></td>
        <td><img src="/uploads/<?= $client['Avatar']; ?>" alt="Profile" width="100"></td>
        <td>
            <a href="/client/<?= $client['CoachID']; ?>" class="btn btn-primary">Edit</a>
            <a href="/client/delete/<?= $client['CoachID']; ?>" class="btn btn-danger">Delete</a>
        </td>
    </tr>
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
      <form action="<?php echo site_url('client/store'); ?>" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
      <div class="mb-3">
    <label for="gymcode" class="form-label">Gym Code</label>
    <input type="text" class="form-control" name="gymcode" value="<?= $next_id; ?>" disabled readonly>
</div>

             </div>

    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">First Name</label>
              <input type="text" class="form-control" name="clientFirst" required>
    </div>
     
    <div class="mb-3">
         <label for="exampleFormControlTextarea1" class="form-label">Last Name</label>
             <input type="text" class="form-control" name="clientLast"required>
</div>

<div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="text" class="form-control" name="password" value="<?= isset($clientPassword) ? $clientPassword : ''; ?>"required>

    </div>

   

    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Address</label>
              <input type="text" class="form-control" name="clientAdress"required>
    </div> 

    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Email</label>
              <input type="text" class="form-control" name="clientEmail"required>
    </div> 

    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Client Profile</label>
              <input type="file" class="form-control" name="clientProfile"required>
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
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>
    $(document).ready(function(){

        let table = new DataTable('#myTable', {
            responsive: true
        });

        $("#btn-save").on('click', function(){


        });

    });
   
</script>
</script>




<?php $this->endSection(); ?> 