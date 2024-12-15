<?php
    $this ->extend('layout/bootstrapyt');
    $this ->section('body');

    ?>

    <h1> Add Client</h1>
    <form action="<?php echo site_url('client/store'); ?>" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">First Name</label>
              <input type="text" class="form-control" name="clientFirst">
    </div>
     
    <div class="mb-3">
         <label for="exampleFormControlTextarea1" class="form-label">Last Name</label>
             <input type="text" class="form-control" name="clientLast">
</div>

    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Age</label>
              <input type="text" class="form-control" name="clientAge">
    </div> 

    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Address</label>
              <input type="text" class="form-control" name="clientAdress">
    </div> 

    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Email</label>
              <input type="text" class="form-control" name="clientEmail">
    </div> 

    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Client Profile</label>
              <input type="file" class="form-control" name="clientProfile">
    </div> 

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<?php $this->endSection(); ?> 