<?php
    $this ->extend('layout/main');
    $this ->section('body');

    ?>
    <div class="p-2 row mb-3">

    <div class="col-12 mb-2">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Plan</button>
    
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
            <<th>ID</th>
            <th>Plan Name</th>
            <th>Description</th>
            <th>Duration in Months</th>
            <th>Price</th>
            <th>Trainer Included</th>
            <th>Creation Date</th>
            <th>Active</th>
            <th>Action</th>


        </tr>
    </thead>
    <tbody>
    <?php foreach ($gymplans as $plan): ?>

<tr>
<th scope="row"><?=$plan['id']; ?></th>
<td><?= $plan['plan_name']; ?></td>
<td><?= $plan['description']; ?></td>
<td><?= $plan['duration_in_months']; ?></td>
<td><?= $plan['price']; ?></td>
<td><?= $plan['trainer_included']; ?></td>
<td><?= $plan['creation_date']; ?></td>
<td><?= $plan['active']; ?></td>

<td>
<a href="/gymequipment<?= $plan['id']; ?>" class="btn btn-primary">Edit</a>
<a href="/gymequipment<?= $plan['id']; ?>" class="btn btn-danger">Delete</a>
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add a Plan</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="<?php echo site_url('/gymplans/store'); ?>" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Plan Name</label>
              <input type="text" class="form-control" name="Pname" required>
    </div>
     
    <div class="mb-3">
         <label for="exampleFormControlTextarea1" class="form-label">Description</label>
             <input type="text" class="form-control" name="description"required>
</div>
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Duration in Months</label>
              <input type="text" class="form-control" name="durationim"required>
    </div> 

    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Price</label>
              <input type="text" class="form-control" name="price"required>
    </div> 

    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label"> Trainer </label>
              <input type="text" class="form-control" name="trainer"required>
    </div> 
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label"> Creation Date </label>
              <input type="date" class="form-control" name="creation"required>
    </div> 
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label"> Active </label>
              <input type="text" class="form-control" name="active"required>
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
    $(document).ready(function(){

        let table = new DataTable('#myTable', {
            responsive: true
        });

        $("#btn-save").on('click', function(){

            alert('Client Added Successfully!')

        });

    });
   
</script>




<?php $this->endSection(); ?> 