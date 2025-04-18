<?php
    $this ->extend('layout/maincoach');
    $this ->section('body');

    ?>

<!DOCTYPE html>
<<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Attendance Chart</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
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
    <h2>COACH ATTENDANCE</h2>

    <!-- Chart Section -->
    <div class="col-12">
        <div class="chart-container">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>

    <!-- Debug Message (if no data) -->
    <?php if (empty(json_decode($chartData))): ?>
        <div class="alert alert-warning">
            No attendance data available for the last 30 days.
        </div>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function(){
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const labels = <?php echo isset($chartLabels) ? $chartLabels : '["No Data"]'; ?>;
        const data = <?php echo isset($chartData) ? $chartData : '[0]'; ?>;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Check-ins per Day',
                    data: data,
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
                            stepSize: 1
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
    });
</script>
</body>
</html>

<?php $this->endSection(); ?> 