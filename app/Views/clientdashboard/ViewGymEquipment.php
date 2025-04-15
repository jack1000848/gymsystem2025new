<?php
    $this ->extend('layout/mainclient');
    $this ->section('body');

    ?>
    <div class="p-2 row mb-3">

    <div class="col-12 mb-2">
    <style>
/* ===== Table Container Styling ===== */
#myTable_wrapper {
    background: #ffffff;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.07);
    transition: all 0.3s ease-in-out;
}

/* ===== DataTable Styling ===== */
#myTable {
    font-size: 15px;
    border-collapse: collapse;
}

#myTable th {
    background: #f5f7fa;
    color: #495057;
    padding: 14px 16px;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
}

#myTable td {
    padding: 12px 16px;
    color: #212529;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
}

#myTable tbody tr:hover {
    background-color: #f1f3f5;
    transition: 0.3s ease;
    transform: scale(1.01);
}

/* ===== Modal Styling ===== */
.modal-content {
    border-radius: 18px;
    border: none;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
    animation: fadeIn 0.3s ease;
}

.modal-header {
    border-bottom: none;
    background-color: #f8f9fa;
    border-radius: 18px 18px 0 0;
}

.modal-body {
    padding: 30px;
    background-color: #fff;
    border-radius: 0 0 18px 18px;
}

/* ===== Form Input Fields ===== */
.form-control {
    border-radius: 10px;
    border: 1px solid #ced4da;
    padding: 12px;
    transition: border-color 0.3s, box-shadow 0.3s;
}

.form-control:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}

/* ===== Flash Message Styling ===== */
.alert-success {
    background-color: #d1e7dd;
    border: none;
    border-left: 5px solid #198754;
    border-radius: 10px;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.15);
}

/* ===== Button and Close Icon ===== */
.btn-close {
    background-color: #e9ecef;
    border-radius: 50%;
    padding: 0.5rem;
    transition: all 0.2s ease;
}

.btn-close:hover {
    background-color: #ced4da;
}

/* ===== Animations ===== */
@keyframes fadeIn {
    0% { opacity: 0; transform: scale(0.95); }
    100% { opacity: 1; transform: scale(1); }
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
            <th>Qty</th>
     


        </tr>
    </thead>
    <tbody>
    <?php foreach ($viewequipment as $equipment): ?>

<tr>
<td><?= $equipment['Description']; ?></td>
<td ><?= $equipment['Qty']; ?></td>






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