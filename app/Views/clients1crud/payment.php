<?php
$this->extend('layout/main');
$this->section('body');
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
        h1 {
            text-align: center;
            color: #333;
        }
        .table-container {
            max-width: 1000px;
            margin: 20px auto;
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
        .empty-message {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
        .add-payment-btn, .action-btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 2px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .add-payment-btn {
            background-color: #0CA6F7;
        }
        .edit-btn {
            background-color: #28a745;
        }
        .delete-btn {
            background-color: #dc3545;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            width: 400px;
            max-width: 90%;
        }
        .modal-content h2 {
            margin-top: 0;
        }
        .modal-content input, .modal-content select {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .modal-content button {
            padding: 10px;
            background-color: #0CA6F7;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .modal-content button.cancel {
            background-color: #ccc;
        }
    </style>
</head>
<body>
    <h1>Payment History</h1>
    <div style="text-align: center;">
        <button class="add-payment-btn" onclick="openModal()">Add Payment</button>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Customer</th>
                    <th>Paid Amount</th>
                    <th>Paid Date</th>
                    <th>Plan</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="paymentTableBody">
                <?php if (empty($payments)): ?>
                    <tr>
                        <td colspan="6" class="empty-message">No payments found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?= esc($payment['PaymentHistoryID']) ?></td>
                            <td><?= esc($payment['CustomerName']) ?></td>
                            <td><?= esc($payment['PaidAmount']) ?></td>
                            <td><?= esc($payment['PaidDate']) ?></td>
                            <td><?= esc($payment['PlanName']) ?></td>
                            <td>
                                <a href="<?= base_url('/payment/edit/' . $payment['PaymentHistoryID']) ?>" class="action-btn edit-btn">Edit</a>
                                <a href="<?= base_url('/payment/delete/' . $payment['PaymentHistoryID']) ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this payment?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Adding Payment -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <h2>Add Payment</h2>
            <form id="paymentForm" action="<?= base_url('/payment/add') ?>" method="post">
                <?= csrf_field() ?>
                <label for="CustomerID">Customer:</label>
                <select id="CustomerID" name="CustomerID" required>
                    <option value="">Select Customer</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?= esc($customer['CustomerID']) ?>"><?= esc($customer['CustomerName']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="PaidAmount">Paid Amount:</label>
                <input type="number" id="PaidAmount" name="PaidAmount" step="0.01" required>

                <label for="PaidDate">Paid Date:</label>
                <input type="date" id="PaidDate" name="PaidDate" required>

                <label for="PlanID">Plan:</label>
                <select id="PlanID" name="PlanID" required>
                    <option value="">Select Plan</option>
                    <?php foreach ($plans as $plan): ?>
                        <option value="<?= esc($plan['PlanID']) ?>"><?= esc($plan['PlanName']) ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Add Payment</button>
                <button type="button" class="cancel" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('paymentModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('paymentModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('paymentModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?= session()->getFlashdata('success') ?>',
            });
        <?php endif; ?>

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