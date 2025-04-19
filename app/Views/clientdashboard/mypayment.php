<?php
$this->extend('layout/mainclient');
$this->section('body');
?>

<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/2.1.8/css/dataTables.min.css" rel="stylesheet" />

<!-- Integrated CSS -->
<style>
    body {
        background-color: #f4f4f4;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    h1 {
        font-weight: bold;
        color: #2c3e50;
        text-align: center;
        margin-bottom: 20px;
    }

    table.dataTable {
        width: 100% !important;
        margin: 0 auto;
        background-color: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    table.dataTable thead th {
        background-color: #3498db;
        color: white;
        font-size: 16px;
        padding: 12px;
        text-transform: uppercase;
        text-align: center;
    }

    table.dataTable tbody td {
        font-size: 15px;
        color: #2c3e50;
        text-align: center;
        padding: 10px;
    }

    table.dataTable tbody tr:hover {
        background-color: #ecf0f1;
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 6px;
        padding: 6px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    .alert {
        border-radius: 10px;
        padding: 12px;
        font-size: 15px;
    }
</style>

<div class="p-2 row mb-3">
    <div class="col-12">
        <h1>My Payment History</h1>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <table id="myPaymentTable" class="display">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Paid Amount</th>
                    <th>Paid Date</th>
                    <th>Plan</th>
                    <th>Plan Price</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($payments)): ?>
                    <tr>
                        <td colspan="5" class="text-center">No payments found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?= esc($payment['PaymentHistoryID']) ?></td>
                            <td>₱<?= number_format($payment['PaidAmount'], 2) ?></td>
                            <td><?= esc($payment['PaidDate']) ?></td>
                            <td><?= esc($payment['PlanName']) ?></td>
                            <td>₱<?= number_format($payment['Price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JS Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

<script>
$(document).ready(function () {
    // Initialize DataTable
    new DataTable('#myPaymentTable', {
        responsive: true,
        order: [[2, 'desc']] // Sort by Paid Date (column 2) in descending order
    });
});
</script>

<?php $this->endSection(); ?>