<?php
    $this->extend('layout/maincoach');
    $this->section('body');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        h2 {
            font-size: 28px;
            font-weight: 600;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 20px 0;
            text-align: center;
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
            min-width: 100px;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        table.dataTable {
            width: 80% !important;
            margin: 20px auto;
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

        table.dataTable tbody td худшее качество изображения: 0.1
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
            width: 80%;
            margin: 20px auto;
        }
    </style>
</head>
<body>
<div class="p-2 row mb-3">
    <h2>COACH DASHBOARD</h2>

    <!-- Success Message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Schedule Table -->
    <div class="col-12">
        <table id="myTable" class="display dataTable">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th>Schedule Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Customer</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($coach) && is_array($coach)): ?>
                    <?php foreach ($coach as $coachSched): ?>
                        <tr>
                            <th scope="row"><?= esc($coachSched['ID']); ?></th>
                            <td><?= esc($coachSched['ScheduleDate']); ?></td>
                            <td><?= esc($coachSched['Start']); ?></td>
                            <td><?= esc($coachSched['End']); ?></td>
                            <td><?= isset($coachSched['CustomerName']) ? esc($coachSched['CustomerName']) : 'N/A'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No schedules available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function(){
        let table = new DataTable('#myTable', {
            responsive: true
        });
    });
</script>
</body>
</html>
<?php $this->endSection(); ?>