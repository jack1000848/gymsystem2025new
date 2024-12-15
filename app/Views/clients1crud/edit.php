<?php

    $this->extend('layout/bootstrapyt');
    $this->section('body');

?>
 <h1> Edit Client Information </h1>
    <form action="/clients1/update<?= $clients1['id']; ?>" method="POST" enctype="multipart/form">
        
    <div class="mb-3">
                <label for="gymcode" class="form-label">Gym Code</label>
                <input type="text" class="form-control" name="gymcode"  readonly>
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
               <label for="clients1Username" class="form-label">Username</label>
               <input type="text" class="form-control" name="clients1Username" placeholder="jdcruz"required>
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
                <input type="text" class="form-control" name="clients1Emailaddress" placeholder="jdcruzmwamwa@gmail.com"required>
              </div>

              <div class="mb-3">
                 <label for="clients1Phonenumber" class="form-label">Phone Number</label>
                   <input type="text" class="form-control" name="clients1Phonenumber" placeholder="09123456789"required>
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
        <option value="1 Month/Basic">1 Month/Basic</option>
        <option value="1 Month/Pro">1 Month gym equipments all access</option>
        <option value="3 Months/Premium">1Month Premium all access with fitness Coach</option>
    </select>
</div>
<div class="mb-3" id="coachSelectDiv" style="display: none;">
<select id="coach" class="form-control" name="coach">
    <option value="">Select a Coach</option>
    <?php if (!empty($coaches)): ?>
        <?php foreach ($coaches as $coach): ?>
            <option value="<?= esc($coach['id']) ?>"><?= esc($coach['first_name']) . ' ' . esc($coach['last_name']) ?></option>
        <?php endforeach; ?>
    <?php else: ?>
        <option value="">No coaches available</option>
    <?php endif; ?>
</select>
</div>

<div class="mb-3">
    <label for="amount" class="form-label">Total Amount</label>
    <input type="text" id="priceInput" class="form-control" name="amount" readonly>
</div>

<script>
    const planSelect = document.getElementById('planSelect');
    const priceInput = document.getElementById('priceInput');
    const coachSelectDiv = document.getElementById('coachSelectDiv');

    const prices = {
        '1 Month/Basic': 1000,
        '1 Month/Pro': 1500,
        '3 Months/Premium': 3500
    };

    planSelect.addEventListener('change', () => {
        const selectedPlan = planSelect.value;
        const price = prices[selectedPlan];
        priceInput.value = price;

        if (selectedPlan === '3 Months/Premium') {
            coachSelectDiv.style.display = 'block';
        } else {
            coachSelectDiv.style.display = 'none';
        }
    });
</script>

    <div class="mb-3">
        <label for ="duration" class="form-label">Duration </label>
        <input type="number" class="form-control" name="duration"required>
   </div>


<button type="submit" class="btn btn-primary">Submit</button>
</form>



 <?php $this->endSection(); ?>

 