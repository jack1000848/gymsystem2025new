<?php
$this->extend('layout/main'); // Extend the main layout
$this->section('body'); // Start the body section
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Payment</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .payment-container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .payment-container h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        select, input[type="number"], input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #0CA6F7;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0990d1;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h2>Add Payment</h2>
        <?php if (session()->has('error')): ?>
            <div class="error-message"><?= esc(session('error')) ?></div>
        <?php endif; ?>
        <?php if (session()->has('success')): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '<?= esc(session('success')) ?>',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = '/clientdashboard/mypayment';
                });
            </script>
        <?php endif; ?>
        <form action="/payment/add-payment" method="post">
            <div class="form-group">
                <label for="plan_id">Select Plan</label>
                <select id="plan_id" name="plan_id" required>
                    <option value="">Choose a plan</option>
                    <?php foreach ($plans as $plan): ?>
                        <option value="<?= esc($plan['PlanID']) ?>" data-price="<?= esc($plan['Price']) ?>">
                            <?= esc($plan['PlanName']) ?> - $<?= esc($plan['Price']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Amount ($)</label>
                <input type="number" id="amount" name="amount" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label for="paid_date">Payment Date</label>
                <input type="date" id="paid_date" name="paid_date" value="<?= date('Y-m-d') ?>" required>
            </div>
            <button type="submit">Add Payment</button>
        </form>
    </div>

    <script>
        // Optional: Auto-fill amount based on selected plan
        document.getElementById('plan_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            document.getElementById('amount').value = price || '';
        });
    </script>
</body>
</html>

<?php $this->endSection(); ?>