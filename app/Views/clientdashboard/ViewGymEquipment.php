<?php
    $this ->extend('layout/mainclient');
    $this ->section('body');

    ?>


   <div class="p-2 row mb-3">

<div class="col-12 mb-2">

    <center><h1>Gym Equipments</h1></center>
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
        
        <th>Description</th>
        <th>Qty</th>
 


    </tr>
</thead>
<tbody>
<?php foreach ($viewequipment as $equipment): ?>

<tr>
<td><?= $equipment['Description']; ?></td>
<td><?= $equipment['Qty']; ?></td>






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
<style>
    /* General styling */
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    h1 {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 20px;
    }

    /* Alert */
    .alert {
        width: 100%;
        max-width: 800px;
        margin: 10px auto;
    }

    /* Table Styling */
    table.display {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0px 0px 10px rgba(14, 185, 228, 0.98);
        border-radius: 10px;
        overflow: hidden;
    }

    table.display th, table.display td {
        padding: 12px 20px;
        text-align: left;
        border-bottom: 1px solid #eaeaea;
    }

    table.display th {
        background-color:rgb(12, 208, 243);
        color: #fff;
        font-weight: 600;
    }

    table.display tbody tr:hover {
        background-color: #f1f1f1;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 12px;
        padding: 20px;
        background-color: #fff;
    }

    .modal-header {
        border-bottom: none;
        display: flex;
        justify-content: flex-end;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
    }

    .form-control {
        border-radius: 8px;
        padding: 10px;
        font-size: 14px;
    }

    .btn-close {
        background-color: transparent;
        border: none;
    }

    /* Button override (if you add buttons later) */
    .btn {
        border-radius: 8px;
        padding: 10px 16px;
        font-weight: 500;
    }
</style>



<?php $this->endSection(); ?> 