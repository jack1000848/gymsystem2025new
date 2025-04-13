<?php
$this->extend('layout/mainclient');
$this->section('body');
?>

<div class="chart-container">
    <!-- Weight Charts Section -->
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

<!-- Scripts for Charts -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
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

    // Chart.js Weight Charts
    <?php if ($history): ?>
        // Prepare data for the weight charts
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
                    backgroundColor: '#007bff',
                    borderColor: '#0056b3',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { title: { display: true, text: 'Date', font: { size: 14 } } },
                    y: { 
                        title: { display: true, text: 'Weight (kg)', font: { size: 14 } }, 
                        beginAtZero: false, 
                        ticks: { stepSize: 1 },
                        suggestedMin: Math.min(...weights) - 1,
                        suggestedMax: Math.max(...weights) + 1
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
                        backgroundColor: goalDiffs.map(value => value >= 0 ? '#ffc107' : '#dc3545'),
                        borderColor: goalDiffs.map(value => value >= 0 ? '#e0a800' : '#c82333'),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { title: { display: true, text: 'Date', font: { size: 14 } } },
                        y: { 
                            title: { display: true, text: 'Difference (kg)', font: { size: 14 } }, 
                            ticks: { stepSize: 1 },
                            suggestedMin: Math.min(...goalDiffs, -1),
                            suggestedMax: Math.max(...goalDiffs, 1)
                        }
                    },
                    plugins: {
                        legend: { display: true, position: 'top', labels: { font: { size: 14 } } },
                        tooltip: { mode: 'index', intersect: false }
                    }
                }
            });
        }
    <?php endif; ?>
</script>

<style>
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
        margin-bottom: 40px;
    }
    .chart-wrapper {
        flex: 1;
        min-width: 0;
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
    #weightChart, #goalDiffChart, #monthly_checkin_chart, #daily_checkin_chart {
        max-height: 300px;
        width: 100%;
    }
    .no-data-message {
        text-align: center;
        color: #666;
        font-size: 1.1rem;
        margin-top: 20px;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .chart-container {
            margin: 20px 10px;
            padding: 15px;
            max-width: 100%;
        }
        .chart-row {
            flex-direction: column;
            gap: 15px;
            margin-bottom: 20px;
        }
        .chart-wrapper {
            width: 100%;
        }
        .card-header {
            padding: 10px;
        }
        .card-header h3 {
            font-size: 1.25rem;
        }
        .card-body {
            padding: 15px;
        }
        #weightChart, #goalDiffChart, #monthly_checkin_chart, #daily_checkin_chart {
            max-height: 250px;
        }
        .no-data-message {
            font-size: 1rem;
        }
    }
</style>

<?php $this->endSection(); ?>