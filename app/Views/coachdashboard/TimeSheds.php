<?php
    $this ->extend('layout/maincoach');
    $this ->section('body');

    ?>
    <style>
    body {
        background-color: #f4f4f4;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .btn-primary {
        background-color: #3498db;
        border: none;
        min-width: 100px;
    }

    .btn-primary:hover {
        background-color: #2980b9;
    }

    .btn-danger {
        background-color: #e74c3c;
        border: none;
        min-width: 100px;
    }

    .btn-danger:hover {
        background-color: #c0392b;
    }

    h1.modal-title {
        font-weight: bold;
        color: #2c3e50;
    }

    .modal-content {
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }

    .form-control, .select2-container--default .select2-selection--multiple {
        border-radius: 8px;
        font-size: 15px;
    }

    table.dataTable {
        width: 100% !important;
        margin: 0 auto;
        background-color: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    table.dataTable thead th {
        background-color: #3498db;
        color: white;
        font-size: 16px;
        padding: 12px;
        text-transform: uppercase;
        text-align: center;
    }

    table.dataTable tbody td {
        font-size: 15px;
        color: #2c3e50;
        text-align: center;
        padding: 10px;
    }

    table.dataTable tbody tr:hover {
        background-color: #ecf0f1;
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 6px;
        padding: 6px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    .alert {
        border-radius: 10px;
        padding: 12px;
        font-size: 15px;
    }

    .modal-footer button {
        min-width: 100px;
    }

    .btn-close {
        outline: none;
    }

    .select2-container {
        width: 100% !important;
    }

    .form-check-input {
        margin-top: 0.3rem;
    }
</style>
    <div class="p-2 row mb-3">

    <div class="col-12 mb-2">
    
    
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
        <th scope="col">ID</th>
        <th>Schedule Date</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Customer</th>
        
    </tr>
</thead>
<tbody>
<?php foreach ($coach as $coachSched): ?>
<tr>
    <th scope="row"><?= $coachSched['ID']; ?></th>
    <td><?= $coachSched['ScheduleDate']; ?></td>
    <td><?= $coachSched['Start']; ?></td>
    <td><?= $coachSched['End']; ?></td>
    <td><?= isset($coachSched['CustomerName']) ? $coachSched['CustomerName'] : 'Not Assigned'; ?></td>
    
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