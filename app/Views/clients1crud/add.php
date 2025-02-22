<!DOCTYPE html>
<html>
   <head> 
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
   </head>
<body>
<?php

   
    $this->extend('layout/bootstrapyt');
    $this->section('body');

?>
 <h1> add </h1>
    <form action="/clients1/store" method="POST">
        
    <div class="mb-3">
                <label for="gymcode" class="form-label">Gym Code</label>
                <input type="text" class="form-control" name="gymcode" value="<?= $next_id; ?>" disabled readonly>
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
               <label for="clients1Username" class="form-label">Address</label>
               <input type="text" class="form-control" name="clients1Username" placeholder="jdcruz"required>
             </div>

             <div class="mb-3">
                  <label for="gender" class="form-label">Gender:</label>
                 <select id="gender" class="form-control" name="gender"required>
                   <option value="Male">Male</option>
                      <option value="Female">Female</option>   
                    </select>
                 </div>

                 <div class="mb-3">
                <label for="clients1Emailaddress" class="form-label">Email Address</label>
                <input type="text" class="form-control" name="clients1Emailaddress" placeholder="jdcruzmwamwa@gmail.com"required>
              </div>

             <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="text" class="form-control" name="password" value="<?= $clients1Password; ?>" readonly>
             </div>

            <<div class="mb-3">
        <label for ="dateofregistration" class="form-label">Date of Registration</label>
        <input type="date" class="form-control" name="dateofregistration"required>
   </div>

   <div class="mb-3">
                  <label for="tworkout" class="form-label">Types of Workout</label>
                 <select id="tworkout" class="form-control" name="tworkout"required>
                   <option value="Bulking">Bulking- Aimed at gaining muscle mass. </option>
                      <option value="Cutting">Cutting- Focused on fat loss while preserving muscle.</option>  
                      <option value="Endurance Training">Endurance Training- Designed to improve muscular strength using heavy weights and lower reps. </option>
                      <option value="Strength Training">Strength Training- Focuses on improving stamina through activities like running, cycling, or swimming.</option> 
                      <option value="Functional Fitness">Improves overall body performance through dynamic movements.</option> 
                    </select>
                 </div>

   <div class="mb-3">
   <label for="plans" class="form-label">Membership Plan</label>
<select id="planSelect" class="form-control" name="plans" required>
    <!-- Options will be dynamically added by AJAX -->
</select>

</div>

<div class="mb-3" id="coachSelectDiv">
<label for="coach" class="form-label">Select Coach</label>
<select id="coach" class="form-control" name="coach" required>
    <option value="">Select a Coach</option>
</select>

</div>

<div class="mb-3">
    <label for="amount" class="form-label">Total Amount</label>
    <input type="text" id="priceInput" class="form-control" name="amount" readonly>
</div>

<script>
</script>

    <div class="mb-3">
        <label for ="duration" class="form-label">Duration </label>
        <input type="number" class="form-control" name="duration"required>
   </div>




            
        <button type="submit" class="btn btn-primary">Submit</button>
 </form>


 <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
 </script>

 <script>
    $(function() {
    // Call the function when the document is ready
    fetchPlans();


    $('#planSelect').on('change', function() {
        var planId = $(this).val(); // Get the selected planId
        console.log(planId)
        if (planId) {
            fetchCoach(planId);
        }
    });


});


async function fetchCoach(planId) {
    try {
        const data = await $.get(`/fetchCoachPlan?planId=${planId}`);  

        $('#coach').empty();

        $('#coach').append('<option value="" selected>Select a Coach</option>');
        if (data.length > 0) {
            // Loop through the fetched coaches and populate the select options
            data.forEach(coach => {
                const option = `<option value="${coach.planID} ${coach.coachID}">${coach.FullName}</option>`;
                $('#coach').append(option);
            });
        } else {
            // If no coaches available, show a message
            $('#coach').append('<option value="">No coaches available</option>');
        }

        // Reapply Select2 after the options have been added
        $('#coach').trigger('change');
        
    } catch (error) {
        console.error("Error fetching coaches:", error);
    }
}

async function fetchPlans() {
    try {
        // Make an AJAX GET request to the server
        const data = await $.get("/fetchPlans");  // Correct URL based on route
        
        // Clear the existing options before appending new options
        $('#planSelect').empty();

        // Add the options dynamically to the select element
        data.forEach(plan => {
            const option = `<option value="${plan.PlanID}">${plan.PlanName}</option>`;
            $('#planSelect').append(option);
        });

        // Reapply Select2 after options have been added
        $('#planSelect').trigger('change');
        
    } catch (error) {
        console.error("Error fetching plans:", error);
    }
}

 </script>

 <?php $this->endSection(); ?>
 
</body>
 </html>