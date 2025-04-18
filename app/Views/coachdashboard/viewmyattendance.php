<?php
    $this->extend('layout/maincoach');
    $this->section('body');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Attendance Logs</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-align: center;
        }

        h2 {
            font-size: 28px;
            font-weight: 600;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 20px;
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
            font-size: 18px;
            padding: 12px;
            text-transform: uppercase;
            text-align: center;
        }

        table.dataTable tbody td {
            font-size: 16px;
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

        .chart-container {
            width: 80%;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        canvas {
            max-width: 100%;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>MY LOGS</h2>

    <!-- Chart Section -->
    <div class="chart-container">
        <canvas id="attendanceChart"></canvas>
    </div>

    <!-- Attendance Table -->
    <?php if (!empty($attendance)) : ?>
        <table id="customerTable" class="display dataTable">
            <thead>
                <tr>
                    <th>Check-in Time</th>
                    <th>Check-out Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendance as $row) : ?>
                    <tr>
                        <td><?= esc($row['CheckInTime']) ?></td>
                        <td><?= !empty($row['CheckOutTime']) ? esc($row['CheckOutTime']) : 'Not checked out' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <div class="container">
            <h2>No attendance records found.</h2>
        </div>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {
        $('#customerTable').DataTable({
            responsive: true
        });

        // Chart.js setup
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
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
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
                            size: 18
                        }
                    }
                }
            }
        });
    });
</script>

</body>
</html>
<?php $this->endSection(); ?>