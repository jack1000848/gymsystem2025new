<?php
$this->extend('layout/main'); // Extend the main layout
$this->section('body'); // Start the body section
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <h1 style="text-align: center; color: #333;">Payment History</h1>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 20px;
    }

    .table-container {
        max-width: 1100px;
        margin: 0 auto;
    }

    .card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        overflow-x: auto;
    }

    .card-body {
        padding: 20px;
    }

    .styled-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 16px;
    }

    .styled-table thead tr {
        background-color: #0CA6F7;
        color: #ffffff;
        text-align: left;
    }

    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
    }

    .styled-table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .styled-table td {
        color: #333;
    }

    @media screen and (max-width: 768px) {
        .styled-table th,
        .styled-table td {
            padding: 10px;
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
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Optional JavaScript to demonstrate dynamic data insertion -->
    <script>
        // Example: Uncomment to simulate adding data dynamically
        /*
        const paymentData = [
            { paymentHistoryId: 'PH001', customerId: 'CUST101', paidAmount: '$150.00', paidDate: '2025-04-01' },
            { paymentHistoryId: 'PH002', customerId: 'CUST102', paidAmount: '$89.99', paidDate: '2025-04-05' },
            { paymentHistoryId: 'PH003', customerId: 'CUST103', paidAmount: '$250.50', paidDate: '2025-04-10' }
        ];

        const tbody = document.getElementById('paymentTableBody');
        tbody.innerHTML = ''; // Clear placeholder

        paymentData.forEach(data => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${data.paymentHistoryId}</td>
                <td>${data.customerId}</td>
                <td>${data.paidAmount}</td>
                <td>${data.paidDate}</td>
            `;
            tbody.appendChild(row);
        });
        */
    </script>
</body>
</html>





<?php $this->endSection(); ?>