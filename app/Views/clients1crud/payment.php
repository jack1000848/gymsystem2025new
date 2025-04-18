<?php
$this->extend('layout/main'); // Extend the main layout
$this->section('body'); // Start the body section
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        .payment-container {
            max-width: 500px;
            margin: 0 auto 30px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
        .table-container {
            max-width: 1000px;
            margin: 0 auto;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #0CA6F7;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        td {
            color: #333;
        }
        .empty-message {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
        @media screen and (max-width: 600px) {
            th, td {
                padding: 8px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h1>Payment History</h1>

    <!-- Payment Form -->
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
                });
            </script>
        <?php endif; ?>
        <form action="/payment/add-payment" method="post">
            <div class="form-group">
                <label for="plan_id">Select Plan</label>
                <select id="plan_id" name="plan_id" required>
                    <option value="">Choose a plan</option>
                    <?php if (!empty($plans)): ?>
                        <?php foreach ($plans as $plan): ?>
                            <option value="<?= esc($plan['PlanID']) ?>" data-price="<?= esc($plan['Price'] ?? '') ?>">
                                <?= esc($plan['PlanName']) ?> <?= isset($plan['Price']) ? '- $' . esc($plan['Price']) : '' ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>No plans available</option>
                    <?php endif; ?>
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

    <!-- Payment History Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Payment History ID</th>
                    <th>Customer ID</th>
                    <th>Paid Amount</th>
                    <th>Paid Date</th>
                    <th>Plan ID</th>
                </tr>
            </thead>
            <tbody id="paymentTableBody">
                <?php if (empty($payments)): ?>
                    <tr>
                        <td colspan="5" class="empty-message">No payments found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?= esc($payment['PaymentHistoryID']) ?></td>
                            <td><?= esc($payment['CustomerID']) ?></td>
                            <td><?= esc($payment['PaidAmount']) ?></td>
                            <td><?= esc($payment['PaidDate']) ?></td>
                            <td><?= esc($payment['PlanID']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Auto-fill amount based on selected plan (if price is available)
        document.getElementById('plan_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            document.getElementById('amount').value = price || '';
        });
    </script>
</body>
</html>

<?php $this->endSection(); ?>