<?= $this->extend('layout/mainclient') ?>
<?= $this->section('body') ?>
<!-- Add Google Fonts for Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    /* Style the chart card for a premium look */
    .premium-chart-card {
        background: linear-gradient(145deg, #f5f7fa, #e4e7eb);
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        padding: 20px;
        transition: transform 0.3s ease;
    }

    .premium-chart-card:hover {
        transform: translateY(-5px);
    }

    .premium-chart-card .card-header {
        background: linear-gradient(90deg, #007bff, #00c4ff);
        border-radius: 10px 10px 0 0;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 1.25rem;
    }

    .premium-chart-card .card-body {
        position: relative;
    }

    /* Style the legend for a premium look */
    .chart-legend {
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        margin-top: 10px;
    }

    .chart-legend .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
        transition: transform 0.2s ease;
    }

    .chart-legend .legend-item:hover {
        transform: scale(1.05);
    }

    .chart-legend .legend-color {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        margin-right: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    /* Ensure charts are centered and balanced */
    #taskProgressChart {
        width: 100% !important;
        max-width: 400px;
        height: auto !important;
        margin: auto;
        display: block;
    }

    #daily_checkin_chart {
        width: 100% !important;
        height: 400px !important;
    }

    .chart-row .chart-wrapper {
        display: flex;
        justify-content: center;
        align-items: stretch;
    }

    .chart-row .card {
        width: 100%;
        height: 100%;
    }
</style>

<center><h2>Welcome, <?= esc($client['Firstname'] . ' ' . $client['Lastname']) ?>!</h2></center>
<div class="container my-5">
    <?php if ($history): ?>
        <div class="row g-4">
            <!-- Weight Chart (Left) -->
            <div class="col-12 col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Weight Progress (kg)</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="weightChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Weight Goal Difference Chart (Right) -->
            <div class="col-12 col-md-6">
                <?php if ($client['Weight_Goal'] !== null): ?>
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white text-center">
                            <h4 class="mb-0">Weight Difference from Goal (kg)</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="goalDiffChart"></canvas>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                        <p class="mb-0">Weight goal not set.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center text-muted fs-5 mt-4">
            <p>No body information records found.</p>
        </div>
    <?php endif; ?>

    <!-- Combined Task Progress and Daily Check-ins Section -->
    <?php if (!empty($attendanceRecords) || !empty($tasks)): ?>
        <div class="chart-row mt-4 row g-4">
            <!-- Task Progress Chart (Left) -->
            <?php if (!empty($tasks)): ?>
                <div class="col-12 col-md-6 chart-wrapper">
                    <div class="card premium-chart-card">
                        <div class="card-header text-white text-center">
                            <h4 class="mb-0">Task Completion Progress (%)</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="taskProgressChart"></canvas>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-12 col-md-6 chart-wrapper">
                    <div class="card">
                        <div class="card-header">
                            <h3>Task Completion Progress</h3>
                        </div>
                        <div class="card-body text-center text-muted">
                            <p>No tasks assigned.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Daily Check-ins Chart (Right) -->
            <?php if (!empty($attendanceRecords)): ?>
                <div class="col-12 col-md-6 chart-wrapper">
                    <div class="card">
                        <div class="card-header">
                            <h3>Daily Check-ins (Last 30 Days)</h3>
                        </div>
                        <div class="card-body">
                            <div id="daily_checkin_chart"></div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-12 col-md-6 chart-wrapper">
                    <div class="card">
                        <div class="card-header">
                            <h3>Daily Check-ins</h3>
                        </div>
                        <div class="card-body text-center text-muted">
                            <p>No attendance records found.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php if ($history || !empty($tasks) || !empty($attendanceRecords)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    // Debug: Log data to console
    console.log('Tasks:', <?= json_encode($tasks) ?>);
    console.log('Attendance Records:', <?= json_encode($attendanceRecords) ?>);

    // Existing Weight Chart Code
    const history = <?= json_encode($history) ?>;
    const validHistory = history.filter(record => record.Weight != null);
    const labels = validHistory.map(record => new Date(record.RecordDate).toLocaleDateString('en-CA'));
    const weights = validHistory.map(record => record.Weight);
    const weightGoal = <?= $client['Weight_Goal'] !== null ? $client['Weight_Goal'] : 'null' ?>;
    const goalDiffs = weightGoal !== null ? validHistory.map(record => (record.Weight - weightGoal).toFixed(2)) : [];

    new Chart(document.getElementById('weightChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Weight (kg)',
                data: weights,
                backgroundColor: '#007bff',
                borderColor: '#0056b3',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { title: { display: true, text: 'Date' } },
                y: {
                    title: { display: true, text: 'Weight (kg)' },
                    beginAtZero: false,
                    suggestedMin: Math.min(...weights) - 1,
                    suggestedMax: Math.max(...weights) + 1
                }
            }
        }
    });

    if (weightGoal !== null) {
        new Chart(document.getElementById('goalDiffChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Difference from Goal (kg)',
                    data: goalDiffs,
                    backgroundColor: goalDiffs.map(val => val >= 0 ? '#ffc107' : '#dc3545'),
                    borderColor: goalDiffs.map(val => val >= 0 ? '#e0a800' : '#c82333'),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { title: { display: true, text: 'Date' } },
                    y: {
                        title: { display: true, text: 'Difference (kg)' },
                        suggestedMin: Math.min(...goalDiffs, -1),
                        suggestedMax: Math.max(...goalDiffs, 1)
                    }
                }
            }
        });
    }

    // Task Progress Chart (Premium Donut Chart)
    <?php if (!empty($tasks)): ?>
        const taskProgressChart = new Chart(document.getElementById('taskProgressChart'), {
            type: 'doughnut',
            data: {
                labels: [
                    <?php foreach ($tasks as $task): ?>
                        "<?= esc($task['TaskTitle'], 'js') ?>",
                    <?php endforeach; ?>
                ],
                datasets: [{
                    label: 'Task Completion (%)',
                    data: [
                        <?php foreach ($tasks as $task): ?>
                            <?= $task['Progress'] ?>,
                        <?php endforeach; ?>
                    ],
                    backgroundColor: [
                        <?php foreach ($tasks as $task): ?>
                            <?php
                            $startColor = $task['Status'] === 'completed' ? 'rgba(40, 167, 69, 0.8)' :
                                          ($task['Status'] === 'incomplete' ? 'rgba(220, 53, 69, 0.8)' : 'rgba(0, 123, 255, 0.8)');
                            $endColor = $task['Status'] === 'completed' ? 'rgba(20, 90, 40, 0.8)' :
                                        ($task['Status'] === 'incomplete' ? 'rgba(150, 30, 50, 0.8)' : 'rgba(0, 80, 180, 0.8)');
                            ?>
                            (ctx) => {
                                const canvas = ctx.chart.ctx;
                                const gradient = canvas.createLinearGradient(0, 0, 0, 400);
                                gradient.addColorStop(0, '<?= $startColor ?>');
                                gradient.addColorStop(1, '<?= $endColor ?>');
                                return gradient;
                            },
                        <?php endforeach; ?>
                    ],
                    borderColor: [
                        <?php foreach ($tasks as $task): ?>
                            <?php
                            $color = $task['Status'] === 'completed' ? "'rgba(40, 167, 69, 1)'" :
                                     ($task['Status'] === 'incomplete' ? "'rgba(220, 53, 69, 1)'" : "'rgba(0, 123, 255, 1)'");
                            ?>
                            <?= $color ?>,
                        <?php endforeach; ?>
                    ],
                    borderWidth: 2,
                    borderAlign: 'inner',
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 1500
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 20,
                            padding: 15,
                            font: {
                                family: 'Poppins, sans-serif',
                                size: 14,
                                weight: '600'
                            },
                            usePointStyle: true,
                            pointStyle: 'circle',
                            generateLabels: (chart) => {
                                const data = chart.data;
                                return data.labels.map((label, i) => ({
                                    text: label,
                                    fillStyle: data.datasets[0].backgroundColor[i](chart),
                                    strokeStyle: data.datasets[0].borderColor[i],
                                    lineWidth: 2,
                                    hidden: !chart.getDataVisibility(i),
                                    index: i
                                }));
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: { family: 'Poppins, sans-serif', size: 14, weight: 'bold' },
                        bodyFont: { family: 'Poppins, sans-serif', size: 12 },
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                return `${label}: ${value}%`;
                            }
                        }
                    },
                    datalabels: {
                        anchor: 'center',
                        align: 'center',
                        formatter: (value, context) => {
                            const statuses = [
                                <?php foreach ($tasks as $task): ?>
                                    "<?= ucfirst($task['Status']) ?>",
                                <?php endforeach; ?>
                            ];
                            const label = context.chart.data.labels[context.dataIndex].length > 10 ?
                                context.chart.data.labels[context.dataIndex].substring(0, 10) + '...' :
                                context.chart.data.labels[context.dataIndex];
                            return `${label}\n${value}%\n${statuses[context.dataIndex]}`;
                        },
                        color: '#fff',
                        textShadow: '0 0 5px rgba(0, 0, 0, 0.5)',
                        font: {
                            family: 'Poppins, sans-serif',
                            weight: '600',
                            size: 10
                        },
                        textAlign: 'center'
                    }
                },
                cutout: '60%',
                aspectRatio: 1.5,
                elements: {
                    arc: {
                        borderWidth: 2,
                        shadowColor: 'rgba(0, 123, 255, 0.5)',
                        shadowBlur: 20,
                        shadowOffsetX: 0,
                        shadowOffsetY: 0
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    <?php endif; ?>

    // Daily Check-ins Chart
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(() => {
        drawDailyCheckinChart();
    });

    function drawDailyCheckinChart() {
        const attendance = <?= json_encode($attendanceRecords) ?>;
        console.log('Attendance Data for Chart:', attendance); // Debug

        const today = new Date();
        const thirtyDaysAgo = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);

        const dailyData = {};
        if (attendance && attendance.length > 0) {
            attendance.forEach(record => {
                const date = new Date(record.InDate);
                if (date >= thirtyDaysAgo && date <= today) {
                    const day = date.toLocaleDateString('en-CA');
                    dailyData[day] = (dailyData[day] || 0) + 1;
                }
            });
        }

        const dataTable = [['Date', 'Check-ins']];
        const sortedDays = Object.keys(dailyData).sort();
        if (sortedDays.length > 0) {
            sortedDays.forEach(day => {
                dataTable.push([day, dailyData[day]]);
            });
        } else {
            dataTable.push(['No Data', 0]);
        }

        const data = google.visualization.arrayToDataTable(dataTable);
        const options = {
            hAxis: { title: 'Date', titleTextStyle: { fontSize: 14 }, slantedText: true, slantedTextAngle: 45 },
            vAxis: { title: 'Check-ins', titleTextStyle: { fontSize: 14 }, minValue: 0 },
            colors: ['#007bff'],
            legend: 'none',
            backgroundColor: 'transparent',
            chartArea: { width: '80%', height: '70%' }
        };

        const chart = new google.visualization.ColumnChart(document.getElementById('daily_checkin_chart'));
        chart.draw(data, options);

        window.addEventListener('resize', () => {
            chart.draw(data, options);
        });
    }
</script>
<?php endif; ?>

<?= $this->endSection() ?>