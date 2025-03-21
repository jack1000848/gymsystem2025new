<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYM Master - Member Registration</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/createmember.css') ?>">
</head>
<body>
    <div class="background">
        <div class="register-box">
            <div class="logo">
                <img src="gym-logo.png" alt="Gym Master Logo">
                <h1>GYM MASTER</h1>
                <h2>Member Registration</h2>
            </div>
            <form action="join-now/store" method="POST">
                
		

        <div class="mb-3">
         <label for="clients1Fname" class="form-label">First Name</label>
         <input type="text" class="form-control" name="clients1Fname" placeholder="juan"required>
         </div>

           <div class="mb-3">
                 <label for="clients1Lname" class="form-label">Last Name</label>
                  <input type="text" class="form-control" name="clients1Lname" placeholder="dela cruz"required>
          </div>
    
            

             <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="text" class="form-control" name="password">
             </div>

            <div class="mb-3">
                <label for="clients1Fulladdress" class="form-label">Full Address</label>
               <input type="text" class="form-control" name="clients1Fulladdress" placeholder="Sta Elana Hagonoy, Bulacan"required>
            </div>

            <div class="mb-3">
              <label for="clients1Emailaddress" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="clients1Emailaddress" name="clients1Emailaddress" placeholder="example@gmail.com" required>
                   <small id="emailError" style="color: red; display: none;">Only Gmail addresses are allowed!</small>
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

    <!-- Gymtimeslot -->
    <div class="mb-3">
                        <label for="timeslot" class="form-label">Gym Time SLot</label>
                        <select id="timeslot" class="form-control" name="timeslot" required>
                            <option value="Day Class">Day Class</option>
                            <option value=">Evening Class">Evening Class</option>
                        </select>
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




            
        <button type="submit" class="btn btn-primary">Submit</button><br>
         
        <a href="<?= base_url('member-login') ?>" class="register">Back to Login</a>

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

    document.getElementById("clients1Emailaddress").addEventListener("input", function() {
    var emailInput = this.value;
    var emailError = document.getElementById("emailError");
    
    if (!emailInput.endsWith("@gmail.com")) {
        emailError.style.display = "block";
    } else {
        emailError.style.display = "none";
    }
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
</body>
</html>