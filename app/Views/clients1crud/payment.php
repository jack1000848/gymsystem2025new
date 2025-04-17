<?= $this->extend('layout/main') ?>
<?= $this->section('body') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment History</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
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

        .btn {
            padding: 6px 10px;
            margin-right: 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .btn-edit {
            background-color: #4CAF50;
            color: white;
        }

        .btn-delete {
            background-color: #f44336;
            color: white;
        }

    </style>
</head>
<body>
    <h1>Payment History</h1>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Customer ID</th>
                    <th>Paid Amount</th>
                    <th>Paid Date</th>
                    <th>Plan ID</th>
                    <th>Actions</th>
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
                        <td>
                            <button class="btn btn-edit" onclick="editPayment(<?= $payment['PaymentHistoryID'] ?>, <?= $payment['CustomerID'] ?>, <?= $payment['PaidAmount'] ?>, '<?= $payment['PaidDate'] ?>', <?= $payment['PlanID'] ?>)">Edit</button>
                            <button class="btn btn-delete" onclick="confirmDelete(<?= $payment['PaymentHistoryID'] ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <form id="editForm" action="<?= site_url('payment-history/update') ?>" method="post" style="display:none;">
        <input type="hidden" name="PaymentHistoryID" id="editID">
        <label>Customer ID:</label><input type="number" name="CustomerID" id="editCustomer"><br>
        <label>Paid Amount:</label><input type="number" name="PaidAmount" id="editAmount" step="0.01"><br>
        <label>Paid Date:</label><input type="datetime-local" name="PaidDate" id="editDate"><br>
        <label>Plan ID:</label><input type="number" name="PlanID" id="editPlan"><br>
        <button type="submit">Update</button>
    </form>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the record.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= site_url('payment-history/delete/') ?>" + id;
                }
            });
        }

        function editPayment(id, customerId, paidAmount, paidDate, planId) {
            document.getElementById('editID').value = id;
            document.getElementById('editCustomer').value = customerId;
            document.getElementById('editAmount').value = paidAmount;
            document.getElementById('editDate').value = paidDate.replace(" ", "T");
            document.getElementById('editPlan').value = planId;

            Swal.fire({
                title: 'Edit Payment',
                html: document.getElementById('editForm'),
                showCancelButton: true,
                showConfirmButton: false,
                didOpen: () => {
                    document.getElementById('editForm').style.display = 'block';
                },
                willClose: () => {
                    document.getElementById('editForm').style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>

<?= $this->endSection() ?>
