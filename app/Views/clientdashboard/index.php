<?= $this->extend('layout/mainclient') ?>
<?= $this->section('body') ?>
<center><h2>Welcome, <?= esc($client['Firstname'] . ' ' . $client['Lastname']) ?>!</h2> </center>
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
</div>
<!-- Attendance Charts Section -->
<?php if ($attendance): ?>
        <div class="chart-row">
            <!-- Monthly Check-ins Chart (Left) -->
            <div class="chart-wrapper">
                <div class="card">
                    <div class="card-header">
                        <h3>Monthly Check-ins</h3>
                    </div>
                    <div class="card-body">
                        <div id="monthly_checkin_chart"></div>
                        <div id="checkin_total"></div>
                    </div>
                </div>
            </div>

            <!-- Daily Check-ins Chart (Right) -->
            <div class="chart-wrapper">
                <div class="card">
                    <div class="card-header">
                        <h3>Daily Check-ins (Last 30 Days)</h3>
                    </div>
                    <div class="card-body">
                        <div id="daily_checkin_chart"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="no-data-message">
            <p>No attendance records found.</p>
        </div>
    <?php endif; ?>
</div>
<head>
    <title>My Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            max-width: 800px;
            margin: 0 auto 30px auto;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>My Tasks</h2>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Task Progress Chart -->
        <?php if (!empty($tasks)): ?>
            <div class="chart-container">
                <canvas id="taskProgressChart"></canvas>
            </div>
            <script>
                const ctx = document.getElementById('taskProgressChart').getContext('2d');
                const taskData = {
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
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                };

                new Chart(ctx, {
                    type: 'bar',
                    data: taskData,
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Percentage Completion (%)'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Tasks'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y + '%';
                                    }
                                }
                            }
                        }
                    }
                });
            </script>
        <?php endif; ?>

        <!-- Task Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>Coach</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Subtasks</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tasks)): ?>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?= esc($task['CoachName']) ?></td>
                            <td><?= esc($task['TaskTitle']) ?></td>
                            <td><?= esc($task['TaskDescription']) ?></td>
                            <td><?= esc($task['DueDate']) ?></td>
                            <td><?= esc($task['Status']) ?></td>
                            <td><?= esc($task['Progress']) ?>%</td>
                            <td>
                                <?php if ($task['Status'] === 'pending'): ?>
                                    <form action="<?= base_url('tasks/updateSubtasks/' . $task['TaskID']) ?>" method="post">
                                        <?php
                                        $subtaskModel = new \App\Models\Subtask();
                                        $subtasks = $subtaskModel->where('TaskID', $task['TaskID'])->findAll();
                                        foreach ($subtasks as $subtask):
                                        ?>
                                            <div class="form-check">
                                                <input type="checkbox" name="subtasks[<?= $subtask['SubtaskID'] ?>]" value="1" class="form-check-input" <?= $subtask['IsCompleted'] ? 'checked' : '' ?> onchange="this.form.submit()">
                                                <label class="form-check-label"><?= esc($subtask['SubtaskName']) ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted"><?= $task['Status'] === 'completed' ? 'Completed' : 'Incomplete' ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No tasks assigned.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
<?php if ($history): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
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
    ///// eto chart baba 
    // Load Google Charts library for attendance charts
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(() => {
        drawMonthlyCheckinChart();
        drawDailyCheckinChart();
    });

    function drawMonthlyCheckinChart() {
        // Prepare data for monthly check-ins
        const attendance = <?= json_encode($attendance) ?>;
        
        // Group check-ins by month
        const monthlyData = {};
        attendance.forEach(record => {
            const date = new Date(record.InDate);
            const monthYear = date.toLocaleString('en-US', { month: 'short', year: 'numeric' });
            monthlyData[monthYear] = (monthlyData[monthYear] || 0) + 1;
        });

        // Format data for Google Charts
        const dataTable = [['Month', 'Check-ins']];
        let totalCheckins = 0;
        for (const [month, count] of Object.entries(monthlyData)) {
            dataTable.push([month, count]);
            totalCheckins += count;
        }

        const data = google.visualization.arrayToDataTable(dataTable);

        const options = {
            hAxis: { title: 'Month', titleTextStyle: { fontSize: 14 } },
            vAxis: { title: 'Check-ins', titleTextStyle: { fontSize: 14 }, minValue: 0 },
            colors: ['#28a745'],
            legend: 'none',
            backgroundColor: 'transparent',
            chartArea: { width: '80%', height: '70%' }
        };

        const chart = new google.visualization.ColumnChart(document.getElementById('monthly_checkin_chart'));
        chart.draw(data, options);

        window.addEventListener('resize', () => {
            chart.draw(data, options);
        });

        // Show total check-ins below the chart
        document.getElementById('checkin_total').innerHTML = `<h4 class="text-center mt-3">Total Check-ins: <strong>${totalCheckins}</strong></h4>`;
    }

    function drawDailyCheckinChart() {
        // Prepare data for daily check-ins (last 30 days)
        const attendance = <?= json_encode($attendance) ?>;
        const today = new Date();
        const thirtyDaysAgo = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);

        // Group check-ins by day (last 30 days)
        const dailyData = {};
        attendance.forEach(record => {
            const date = new Date(record.InDate);
            if (date >= thirtyDaysAgo && date <= today) {
                const day = date.toLocaleDateString('en-CA');
                dailyData[day] = (dailyData[day] || 0) + 1;
            }
        });

        // Format data for Google Charts
        const dataTable = [['Date', 'Check-ins']];
        const sortedDays = Object.keys(dailyData).sort();
        sortedDays.forEach(day => {
            dataTable.push([day, dailyData[day]]);
        });

        // If no data in the last 30 days, show a placeholder
        if (dataTable.length === 1) {
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
