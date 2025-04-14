<?= $this->extend('layout/mainclient') ?>
<?= $this->section('body') ?>
<center><h2>Welcome, <?= esc($client['Firstname'] . ' ' . $client['Lastname']) ?>!</h2></center>

<style>
#taskProgressChart {
    width: 100% !important;
    max-width: 400px;
    height: auto !important;
    margin: auto;
    display: block;
}
</style>
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

   <!-- Donut + Daily Check-ins Charts Section -->
<?php if (!empty($tasks) || $attendance): ?>
    <div class="row g-4 mt-4">
        <!-- Donut Chart (Left) -->
        <?php if (!empty($tasks)): ?>
            <div class="col-12 col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Task Completion Progress (%)</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="taskProgressChart"></canvas>
                    </div>
                </div>
            </div>
        <?php endif; ?>

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

<?php if ($history || !empty($tasks) || $attendance): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
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

    // Task Progress Chart (Donut Chart)
<?php if (!empty($tasks)): ?>
new Chart(document.getElementById('taskProgressChart'), {
    type: 'doughnut',
    data: {
        labels: [
            <?php foreach ($tasks as $task): ?>
                "<?= esc($task['TaskTitle'], 'js') ?>",
            <?php endforeach; ?>
        ],
        datasets: [{
            label: 'Task Completion',
            data: [
                <?php foreach ($tasks as $task): ?>
                    <?= $task['Progress'] ?>,
                <?php endforeach; ?>
            ],
            backgroundColor: [
                '#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f',
                '#edc948', '#b07aa1', '#ff9da7', '#9c755f', '#bab0ab'
            ],
            borderColor: '#ffffff',
            borderWidth: 3,
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        animation: {
            animateScale: true,
            animateRotate: true
        },
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    padding: 20,
                    boxWidth: 14,
                    color: '#333',
                    font: {
                        size: 14,
                        family: 'Arial, sans-serif'
                    }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${context.label}: ${context.parsed}% completed`;
                    }
                }
            },
            datalabels: {
                color: '#fff',
                textAlign: 'center',
                font: {
                    weight: 'bold',
                    size: 12
                },
                formatter: (value, context) => {
                    return `${value}%`;
                }
            }
        },
        cutout: '50%',
        aspectRatio: 1.5
    },
    plugins: [ChartDataLabels]
});
<?php endif; ?>


    // Existing Attendance Charts
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(() => {
        drawMonthlyCheckinChart();
        drawDailyCheckinChart();
    });

    function drawMonthlyCheckinChart() {
        const attendance = <?= json_encode($attendance) ?>;
        const monthlyData = {};
        attendance.forEach(record => {
            const date = new Date(record.InDate);
            const monthYear = date.toLocaleString('en-US', { month: 'short', year: 'numeric' });
            monthlyData[monthYear] = (monthlyData[monthYear] || 0) + 1;
        });

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

        document.getElementById('checkin_total').innerHTML = `<h4 class="text-center mt-3">Total Check-ins: <strong>${totalCheckins}</strong></h4>`;
    }

    function drawDailyCheckinChart() {
        const attendance = <?= json_encode($attendance) ?>;
        const today = new Date();
        const thirtyDaysAgo = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);

        const dailyData = {};
        attendance.forEach(record => {
            const date = new Date(record.InDate);
            if (date >= thirtyDaysAgo && date <= today) {
                const day = date.toLocaleDateString('en-CA');
                dailyData[day] = (dailyData[day] || 0) + 1;
            }
        });

        const dataTable = [['Date', 'Check-ins']];
        const sortedDays = Object.keys(dailyData).sort();
        sortedDays.forEach(day => {
            dataTable.push([day, dailyData[day]]);
        });

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