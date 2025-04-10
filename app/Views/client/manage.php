<?php
    $this ->extend('layout/main');
    $this ->section('body');

    ?>

    

    <div class="p-2 row mb-3">

    <div class="col-12 mb-2">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Coach</button>
    
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
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Password</th>
            <th>Address</th>
            <th>Email</th>
            <th>QR Code</th>
            <th>Action</th>


        </tr>
    </thead>
    <tbody>
    <?php foreach ($coaches as $coach): ?>
    <tr>
        <th scope="row"><?= $coach['CoachID']; ?></th>
        <td><?= $coach['Firstname']; ?></td>
        <td><?= $coach['Lastname']; ?></td>
        <td><?= $coach['password_hash']; ?></td>
        <td><?= $coach['Address']; ?></td>
        <td><?= $coach['Email']; ?></td>
        <<td><img id="qrCodeImage<?= $coach['CoachID']; ?>" src="" alt="QR Code" style="width: 100px;"></td>
        <td>

    <span onclick="editCoach('<?= $coach['CoachID']; ?>')" class="btn btn-sm btn-primary">
  Edit
  </span>
  <span onclick="deleteCoach('<?= $coach['CoachID']; ?>')" class="btn btn-sm btn-primary">
  Delete
  </span>
  
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
      <form action="<?php echo site_url('/coach/store'); ?>" method="POST" enctype="multipart/form-data">
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
         <label for="exampleFormControlInput1" class="form-label">Email</label>
              <input type="text" class="form-control" name="clientEmail"required>
              <small id="emailError" style="color: red; display: none;">Only Gmail addresses are allowed!</small>
    </div> 
<div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="text" class="form-control" name="password" value="<?= isset($clientPassword) ? $clientPassword : ''; ?>"required>

    </div>

    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Address</label>
              <input type="text" class="form-control" name="clientAdress"required>
    </div> 

    

    


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="btn-save">Save changes</button>
      </div>
    </div>
  </div>
</div>
    </form>

    <!--edit-->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Coach</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="mb-3">
      <input type="hidden" id="editid" name="CoachID">
         <label for="editClients1Fname" class="form-label">First Name</label>
              <input type="text" id="editClients1Fname" class="form-control" name="Firstname" required>
    </div>
     
    <div class="mb-3">
         <label for="editClients1lname" class="form-label">Last Name</label>
             <input type="text" id="editClients1lname" class="form-control" name="Lastname"required>
</div>
<div class="mb-3">
         <label for="editemail" class="form-label">Email</label>
              <input type="text" id="editemail" class="form-control" name="Email"required>
    </div> 
<div class="mb-3">
        <label for="editpassword" class="form-label">Password</label>
        <input type="text" id="editpassword" class="form-control" name="passwords" required>

    </div>

    <div class="mb-3">
         <label for="editaddress" class="form-label">Address</label>
              <input type="text" id="editaddress" class="form-control" name="Address"required>
    </div> 

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btn-update">Save changes</button>
      </div>
    </div>
  </div>
</div>





<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

<script>
    $(document).ready(function(){

        let table = new DataTable('#myTable', {
            responsive: true
        });

        $("#btn-update").on('click', function(){
          updateCoach();
         });      
  });
   // Function to generate QR Code
   function generateQRCode(coachId) {
        const qr = new QRious({
            element: document.createElement('canvas'),
            value: `${coachId}`,
            size: 200,
            background: 'white',
            foreground: 'black',
        });

        const qrImageElement = document.getElementById('qrCodeImage' + coachId);
    if (qrImageElement) {
        qrImageElement.src = qr.toDataURL();
        }
    }

    // Generate QR Codes for all clients
    window.onload = function () {
        <?php foreach ($coaches as $coach) : ?>
            generateQRCode(<?= $coach['CoachID']; ?>);
        <?php endforeach; ?>
    };

  document.getElementById("clientEmail").addEventListener("input", function() {
    var emailInput = this.value;
    var emailError = document.getElementById("emailError");
    
    if (!emailInput.endsWith("@gmail.com")) {
        emailError.style.display = "block";
    } else {
        emailError.style.display = "none";
    }
    });

    async function editCoach(id) {
    try {
        const res = await $.get('<?= base_url('/coach/edit/') ?>' + id);
        console.log(res)
        if (res && res.data) {
            const coach = res.data;

          $("#editid").val(coach.CoachID);
            $("#editClients1Fname").val(coach.Firstname);
          //  $("#lname").val((coach.Middlename)(2));
            $("#editClients1lname").val(coach.Lastname);
            $("#editemail").val(coach.Email)
            $("#editpassword").val(coach.passwords)
            //$("#cprofile").val(coach.RegisteredDate)
            //0$("#eqQuantity").val(coach.Status)
            $("#editaddress").val(coach.Address)
            //$("#eqQuantity").val(coach.Avatar)
            $("#editModal").modal('show');

        } else {
            console.error('No data found in the response:', res);
        }
    } catch (error) {
        console.error('Error fetching equipment data:', error);
    }
  }
 

  async function updateCoach(id) {

    let CoachData = {
        CoachID: $("#editid").val().trim(),
        Firstname: $("#editClients1Fname").val().trim(),
        Lastname: $("#editClients1lname").val().trim(),
        password_hash: $("#editpassword").val().trim(),
        Address: $("#editaddress").val().trim(),
        Email: $("#editemail").val().trim(),
        
    };

    //if (!equipmentData.EquipmentID || !equipmentData.Description || !equipmentData.Amount || !equipmentData.Qty) {
     //   alert("Please fill in all fields.");
      //  return;
  // }

  // if(isNaN(equipmentData.Amount) || equipmentData.Amount <= 0
  //    || isNaN(equipmentData.Qty) || equipmentData.Qty <= 0    
  //){
   //   alert("Invalid Amount");
     // return;
//
   // }

    $.ajax({
        url: '<?= base_url('/coach/update/') ?>' + CoachData.CoachID, // Adjust URL for your update route
        type: 'POST',
        data: CoachData, 
        success: function(response) {
            if (response.status === 'success') {
                alert("Coach Updated Successfully!");
                window.location.reload();
            } else {
                alert("Failed to update Coach: " + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error during the update:", error);
            alert("There was an error updating the Coach.");
        }
    });

     async function deleteCoach(id) {
    const { isConfirmed } = await Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    });

    if (isConfirmed) {
        try {
            const response = await $.ajax({
                url: '<?= base_url('/coach/delete/') ?>' + id,
                type: 'DELETE', // Use POST instead of DELETE
                data: { _method: 'DELETE' }, // Send _method override
                success: function(response) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong. Please try again later.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        } catch (error) {
            Swal.fire({
                title: 'Error!',
                text: 'There was an error processing your request.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    }
}
           

    document.getElementById("clientEmail").addEventListener("input", function() {
    var emailInput = this.value;
    var emailError = document.getElementById("emailError");
    
    if (!emailInput.endsWith("@gmail.com")) {
        emailError.style.display = "block";
    } else {
        emailError.style.display = "none";
    }
    });


  }
  async function deleteCoach(id) {
    const { isConfirmed } = await Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    });

    if (isConfirmed) {
        try {
            const response = await $.ajax({
                url: '<?= base_url('/coach/delete/') ?>' + id,
                type: 'DELETE', // Use POST instead of DELETE
                data: { _method: 'DELETE' }, // Send _method override
                success: function(response) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong. Please try again later.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        } catch (error) {
            Swal.fire({
                title: 'Error!',
                text: 'There was an error processing your request.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    }
}

</script>





<?php $this->endSection(); ?> 