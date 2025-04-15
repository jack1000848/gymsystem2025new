<?php
    $this ->extend('layout/mainclient');
    $this ->section('body');

    ?>
    <div class="p-2 row mb-3">

    <div class="col-12 mb-2">
    <style>
/* Card-like table container */
#myTable_wrapper {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}

/* Table styling */
#myTable {
    width: 100% !important;
    border-collapse: collapse;
    font-size: 15px;
}

#myTable th {
    background-color: #f8f9fa;
    color: #333;
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

#myTable td {
    padding: 12px;
    border-bottom: 1px solid #f0f0f0;
}

#myTable tbody tr:hover {
    background-color: #f1f1f1;
    cursor: pointer;
}

/* Modal form enhancement */
.modal-content {
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.modal-body {
    background-color: #fefefe;
    border-radius: 0 0 20px 20px;
}

/* Form inputs */
.form-control {
    border-radius: 12px;
    border: 1px solid #ced4da;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

/* Flash message */
.alert-success {
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 200, 83, 0.1);
}

/* Button enhancements */
.btn {
    border-radius: 12px;
}

.btn-close {
    filter: brightness(0.6);
}

</style>

    
    </div>

    
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
            
            <th>Name</th>
            <th style="display:none;">Qty</th>
     


        </tr>
    </thead>
    <tbody>
    <?php foreach ($viewequipment as $equipment): ?>

<tr>
<td><?= $equipment['Description']; ?></td>
<td style="display:none;"><?= $equipment['Qty']; ?></td>






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
    
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="<?php echo site_url('/gymequipment/store'); ?>" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Name</label>
              <input type="text" class="form-control" name="Ename" required>
    </div>
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Equipment Picture</label>
              <input type="file" class="form-control" name="equipmentpic"required>
    </div> 
    <div class="mb-3">
         <label for="exampleFormControlTextarea1" class="form-label">Amount</label>
             <input type="text" class="form-control" name="Eamount"required>
</div>
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Quantity</label>
              <input type="text" class="form-control" name="Equantity"required>
    </div> 

    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">description</label>
              <input type="text" class="form-control" name="Ediscription"required>
    </div> 

    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Purchase Date</label>
              <input type="date" class="form-control" name="Epurchasedate"required>
    </div> 

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