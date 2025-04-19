<?php
$this->extend('layout/main');
$this->section('body');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: fixed;
            background-color: #f4f4f4;
        }
        .form-container {
            max-width: 400px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            color: #333;
        }
        .form-container label {
            display: block;
            margin: 10px 0 5px;
        }
        .form-container input, .form-container select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-container .price-display {
            margin: 10px 0;
            color: #333;
            font-weight: bold;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #0CA6F7;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px 0;
        }
        .form-container button.cancel {
            background-color: #ccc;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Payment</h2>
        <form action="<?= base_url('/payment/edit/' . $payment['PaymentHistoryID']) ?>" method="POST">
            <?= csrf_field() ?>
            <label for="CustomerID">Customer:</label>
            <select id="CustomerID" name="CustomerID" required>
                <option value="">Select Customer</option>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?= esc($customer['CustomerID']) ?>" <?= $customer['CustomerID'] == $payment['CustomerID'] ? 'selected' : '' ?>>
                        <?= esc($customer['CustomerName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="PaidAmount">Paid Amount:</label>
            <input type="number" id="PaidAmount" name="PaidAmount" step="0.01" value="<?= esc($payment['PaidAmount']) ?>" required>

            <label for="PaidDate">Paid Date:</label>
            <input type="date" id="PaidDate" name="PaidDate" value="<?= esc($payment['PaidDate']) ?>" required>

            <label for="PlanID">Plan:</label>
            <select id="PlanID" name="PlanID" required>
                <option value="">Select Plan</option>
                <?php foreach ($plans as $plan): ?>
                    <option value="<?= esc($plan['PlanID']) ?>" data-price="<?= esc($plan['Price']) ?>" <?= $plan['PlanID'] == $payment['PlanID'] ? 'selected' : '' ?>>
                        <?= esc($plan['PlanName']) . ' - ₱' . number_format($plan['Price'], 2) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="price-display" id="priceDisplay">
                Plan Price: $<?= number_format($plans[array_search($payment['PlanID'], array_column($plans, 'PlanID'))]['Price'], 2) ?>
            </div>

            <button type="submit">Update Payment</button>
            <a href="<?= base_url('/payment') ?>"><button type="button" class="cancel">Cancel</button></a>
        </form>
    </div>

    <script>
        // Update price display when a plan is selected
        document.getElementById('PlanID').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            const priceDisplay = document.getElementById('priceDisplay');
            if (price) {
                priceDisplay.textContent = `Plan Price: ₱${parseFloat(price).toFixed(2)}`;
            } else {
                priceDisplay.textContent = 'Plan Price: Select a plan to see the price.';
            }
        });

        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?= session()->getFlashdata('error') ?>',
            });
        <?php endif; ?>
    </script>
</body>
</html>

<?php $this->endSection(); ?>