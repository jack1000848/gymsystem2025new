<?php
    $this->extend('layout/maincoach');
    $this->section('body');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Coach Schedules</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
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

        .btn-danger {
            background-color: #e74c3c;
            border: none;
            min-width: 100px;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        h1.modal-title {
            font-weight: bold;
            color: #2c3e50;
        }

        .modal-content {
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        .form-control, .select2-container--default .select2-selection--multiple {
            border-radius: 8px;
            font-size: 15px;
        }

        table.dataTable {
            width: 100% !important;
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

        .modal-footer button {
            min-width: 100px;
        }

        .btn-close {
            outline: none;
        }

        .select2-container {
            width: 100% !important;
        }

        .form-check-input {
            margin-top: 0.3rem;
        }

        .chart-container {
            width: 80%;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        canvas {
            max-width: 100%;
        }
    </style>
</head>
<body>
<div class="p-2 row mb-3">
    <h2>MANAGE MY SCHEDULES</h2>

    <!-- Chart Section -->
    <div class="col-12">
        <div class="chart-container">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>

    <!-- Success Message -->
    <?php if (session()->getFlashdata('success')) : ?>
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
                <?php foreach ($coach as $coachSched): ?>
                    <tr>
                        <th scope="row"><?= $coachSched['ID']; ?></th>
                        <td><?= $coachSched['ScheduleDate']; ?></td>
                        <td><?= $coachSched['Start']; ?></td>
                        <td><?= $coachSched['End']; ?></td>
                        <td><?= isset($coachSched['CustomerName']) ? $coachSched['CustomerName'] : 'N/A'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Equipment Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Equipment Picture</label>
                <input type="file" class="form-control" name="equipmentpic" required>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Amount</label>
                <input type="text" class="form-control" name="Eamount" required>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Quantity</label>
                <input type="text" class="form-control" name="Equantity" required>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Description</label>
                <input type="text" class="form-control" name="Ediscription" required>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Purchase Date</label>
                <input type="date" class="form-control" name="Epurchasedate" required>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        // Initialize DataTable
        let table = new DataTable('#myTable', {
            responsive: true
        });

        // Chart.js setup for bar chart
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo $chartLabels; ?>,
                datasets: [{
                    label: 'Check-ins per Day',
                    data: <?php echo $chartData; ?>,
                    backgroundColor: '#3498db',
                    borderColor: '#2980b9',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Check-ins'
                        },
                        ticks: {
                            stepSize: 1 // Whole numbers for check-in counts
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Coach Attendance (Last 30 Days)',
                        font: {
                            size: 18,
                            family: 'Segoe UI'
                        },
                        color: '#2c3e50'
                    }
                }
            }
        });

        // btn-save click handler (if needed)
        $("#btn-save").on('click', function(){
            alert('Client Added Successfully!');
        });
    });
</script>
</body>
</html>
<?php $this->endSection(); ?>