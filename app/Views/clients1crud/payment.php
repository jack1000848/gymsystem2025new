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
            padding: fixed;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            color: #333;
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
            background-color:#0CA6F7;
            color: white;
        } 

        tr:hover {
            background-color: #f5f5f5;
        }

        td {
            color: #333;
        }

        /* Placeholder for empty table */
        .empty-message {
            text-align: center;
            padding: fixed;
            color: #666;
            font-style: italic;
        }

        @media screen and (max-width: 600px) {
            th, td {
                padding: fixed;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h1>Payment History</h1>
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
    <?php foreach($payments as $payment): ?>
        <tr>
            <td><?= esc($payment['PaymentHistoryID']) ?></td>
            <td><?= esc($payment['CustomerID']) ?></td>
            <td><?= esc($payment['PaidAmount']) ?></td>
            <td><?= esc($payment['PaidDate']) ?></td>
            <td><?= esc($payment['PlanID']) ?></td>
          
        </tr>
    <?php endforeach; ?>
</tbody>
        </table>
    </div>

    <!-- Edit Modal -->
<div id="editModal" style="display:none;">
    <form id="editForm" method="post" action="<?= site_url('payment-history/update') ?>">
        <input type="hidden" name="PaymentHistoryID" id="editPaymentHistoryID">
        <label>Customer ID:</label><input type="number" name="CustomerID" id="editCustomerID"><br>
        <label>Paid Amount:</label><input type="text" name="PaidAmount" id="editPaidAmount"><br>
        <label>Paid Date:</label><input type="datetime-local" name="PaidDate" id="editPaidDate"><br>
        <label>Plan ID:</label><input type="number" name="PlanID" id="editPlanID"><br>
        <button type="submit">Update</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This payment history will be permanently deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= site_url('payment-history/delete/') ?>" + id;
            }
        });
    }

    function editPayment(id, customerId, amount, date, planId) {
        document.getElementById('editPaymentHistoryID').value = id;
        document.getElementById('editCustomerID').value = customerId;
        document.getElementById('editPaidAmount').value = amount;
        document.getElementById('editPaidDate').value = date.replace(" ", "T"); // to match datetime-local input
        document.getElementById('editPlanID').value = planId;

        Swal.fire({
            title: 'Edit Payment',
            html: document.getElementById('editModal'),
            showCancelButton: true,
            showConfirmButton: false,
            didOpen: () => {
                document.getElementById('editModal').style.display = 'block';
            },
            willClose: () => {
                document.getElementById('editModal').style.display = 'none';
            }
        });
    }
</script>

</body>
</html>





<?php $this->endSection(); ?>