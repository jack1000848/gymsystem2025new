<?php
    $this ->extend('layout/mainclient');
    $this ->section('body');

    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weight Progress Charts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .chart-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .chart-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .chart-wrapper {
            flex: 1;
            min-width: 0; /* Prevents overflow */
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
        #weightChart, #goalDiffChart {
            max-height: 300px;
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
        <?php if ($history): ?>
            <div class="chart-row">
                <!-- Weight Chart (Left) -->
                <div class="chart-wrapper">
                    <div class="card">
                        <div class="card-header">
                            <h3>Weight Progress (kg)</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="weightChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Weight Goal Difference Chart (Right) -->
                <?php if ($client['Weight_Goal'] !== null): ?>
                    <div class="chart-wrapper">
                        <div class="card">
                            <div class="card-header">
                                <h3>Weight Difference from Goal (kg)</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="goalDiffChart"></canvas>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="chart-wrapper no-data-message">
                        <p>Weight goal not set.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="no-data-message">
                <p>No body information records found.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Chart.js Script -->
    <?php if ($history): ?>
    <script>
        // Prepare data for the charts
        const history = <?= json_encode($history) ?>;
        const validHistory = history.filter(record => record.Weight != null);
        const labels = validHistory.map(record => new Date(record.RecordDate).toLocaleDateString('en-CA'));
        const weights = validHistory.map(record => record.Weight);
        const weightGoal = <?= $client['Weight_Goal'] !== null ? $client['Weight_Goal'] : 'null' ?>;
        const goalDiffs = weightGoal !== null ? validHistory.map(record => (record.Weight - weightGoal).toFixed(2)) : [];

        // Weight Chart (Left) - Bar Chart
        const weightCtx = document.getElementById('weightChart').getContext('2d');
        new Chart(weightCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Weight (kg)',
                    data: weights,
                    backgroundColor: '#007bff', // Solid blue bars
                    borderColor: '#0056b3',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { 
                        title: { display: true, text: 'Date', font: { size: 14 } }
                    },
                    y: { 
                        title: { display: true, text: 'Weight (kg)', font: { size: 14 } }, 
                        beginAtZero: false, 
                        ticks: { stepSize: 1 },
                        suggestedMin: Math.min(...weights) - 1, // Add padding below min value
                        suggestedMax: Math.max(...weights) + 1  // Add padding above max value
                    }
                },
                plugins: {
                    legend: { display: true, position: 'top', labels: { font: { size: 14 } } },
                    tooltip: { mode: 'index', intersect: false }
                }
            }
        });

        // Weight Goal Difference Chart (Right) - Bar Chart
        if (weightGoal !== null) {
            const goalDiffCtx = document.getElementById('goalDiffChart').getContext('2d');
            new Chart(goalDiffCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Difference from Goal (kg)',
                        data: goalDiffs,
                        backgroundColor: goalDiffs.map(value => value >= 0 ? '#ffc107' : '#dc3545'), // Yellow for positive, red for negative
                        borderColor: goalDiffs.map(value => value >= 0 ? '#e0a800' : '#c82333'),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { title: { display: true, text: 'Date', font: { size: 14 } } },
                        y: { 
                            title: { display: true, text: 'Difference (kg)', font: { size: 14 } }, 
                            ticks: { stepSize: 1 },
                            suggestedMin: Math.min(...goalDiffs, -1), // Allow negative values with padding
                            suggestedMax: Math.max(...goalDiffs, 1)   // Add padding above max value
                        }
                    },
                    plugins: {
                        legend: { display: true, position: 'top', labels: { font: { size: 14 } } },
                        tooltip: { mode: 'index', intersect: false }
                    }
                }
            });
        }
    </script>
    <?php endif; ?>
</body>
</html>

<?php $this->endSection(); ?> 