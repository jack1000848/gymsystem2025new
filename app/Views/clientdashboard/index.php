<?php
    $this ->extend('layout/mainclient');
    $this ->section('body');

    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Body Progress Chart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .chart-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 15px;
            border-radius: 8px 8px 0 0;
        }
        .card-body {
            padding: 20px;
        }
        #bodyChart {
            max-height: 400px;
        }
        .no-data-message {
            text-align: center;
            color: #666;
            font-size: 1.1rem;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="chart-container">
        <div class="card">
            <div class="card-header">
                <h3>Your Body Progress (Weight & Height)</h3>
            </div>
            <div class="card-body">
                <?php if ($history): ?>
                    <canvas id="bodyChart"></canvas>
                <?php else: ?>
                    <p class="no-data-message">No body information records found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <?php if ($history): ?>
    <script>
        // Prepare data for the chart
        const history = <?= json_encode($history) ?>;
        const validHistory = history.filter(record => record.Weight != null && record.Height != null);
        const labels = validHistory.map(record => new Date(record.RecordDate).toLocaleDateString('en-CA'));
        const weights = validHistory.map(record => record.Weight);
        const heights = validHistory.map(record => record.Height);

        // Create the dual-axis chart
        const ctx = document.getElementById('bodyChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Weight (kg)',
                        data: weights,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        fill: false,
                        tension: 0.3,
                        pointRadius: 5,
                        pointBackgroundColor: '#007bff'
                    },
                    {
                        label: 'Height (cm)',
                        data: heights,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        fill: false,
                        tension: 0.3,
                        pointRadius: 5,
                        pointBackgroundColor: '#28a745'
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date',
                            font: {
                                size: 14
                            }
                        }
                    },
                    'y-weight': {
                        type: 'linear',
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Weight (kg)',
                            font: {
                                size: 14
                            }
                        },
                        beginAtZero: false,
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            stepSize: 1
                        }
                    },
                    'y-height': {
                        type: 'linear',
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Height (cm)',
                            font: {
                                size: 14
                            }
                        },
                        beginAtZero: false,
                        ticks: {
                            stepSize: 0.5
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed.y;
                                return label;
                            }
                        }
                    }
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                }
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>

<?php $this->endSection(); ?> 