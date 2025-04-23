<?php
$this->extend('layout/main');
$this->section('body');
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" >
<style>
    .col-lg-3.col-6 a:hover .small-box {
    opacity: 0.9;
}
    #payment_bar_chart {
        height: 420px;
        width: 100%;
        padding: 20px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.4s ease-in-out;
        animation: fadeIn 1s ease-in-out;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    #payment_bar_chart:hover {
        transform: scale(1.02);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    #payment_total {
        margin-top: 15px;
        text-align: center;
        font-size: 1.2rem;
        font-weight: 600;
        color: #1c1c1e;
        background: linear-gradient(90deg, #00c6ff, #0072ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    #coach_checkin_bar_chart {
        height: 420px;
        width: 100%;
        padding: 20px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.4s ease-in-out;
        animation: fadeIn 1s ease-in-out;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    #coach_checkin_bar_chart:hover {
        transform: scale(1.02);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    #coach_checkin_total {
        margin-top: 15px;
        text-align: center;
        font-size: 1.2rem;
        font-weight: 600;
        color: #1c1c1e;
        background: linear-gradient(90deg, #00c6ff, #0072ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    #checkin_bar_chart {
        height: 420px;
        width: 100%;
        padding: 20px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.4s ease-in-out;
        animation: fadeIn 1s ease-in-out;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    #checkin_bar_chart:hover {
        transform: scale(1.02);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    #checkin_total {
        margin-top: 15px;
        text-align: center;
        font-size: 1.2rem;
        font-weight: 600;
        color: #1c1c1e;
        background: linear-gradient(90deg, #00c6ff, #0072ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
        .small-box a:hover .inner {
    opacity: 0.9;
}
    }
</style>


<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <!-- Total Members -->
            <div class="col-lg-3 col-6">
                <a href="<?= base_url('clients1') ?>" style="text-decoration: none; color: inherit; display: block;">
                    <div class="small-box text-bg-primary">
                        <div class="inner">
                            <h3><?= $totalClients ?><sup class="fs-5"></sup></h3>
                            <p>Total Members</p>
                        </div>
                        <div class="icon position-absolute top-0 end-0 p-3" style="opacity: 0.3;">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Paid Amount -->
            <div class="col-lg-3 col-6">
                <a href="<?= base_url('payment') ?>" style="text-decoration: none; color: inherit; display: block;">
                    <div class="small-box" style="background-color: #add8e6;">
                        <div class="inner">
                            <h3>₱<?= number_format($totalPaidAmount, 2) ?><sup class="fs-5"></sup></h3>
                            <p>Total Paid Amount</p>
                        </div>
                        <div class="icon position-absolute top-0 end-0 p-3" style="opacity: 0.3;">
                            <i class="fas fa-receipt fa-3x"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Coach -->
            <div class="col-lg-3 col-6">
                <a href="<?= base_url('coach') ?>" style="text-decoration: none; color: inherit; display: block;">
                    <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3><?= $totalClient ?><sup class="fs-5"></sup></h3>
                            <p>Total Coach</p>
                        </div>
                        <div class="icon position-absolute top-0 end-0 p-3" style="opacity: 0.3;">
                            <i class="fas fa-user-tie fa-3x"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Equipments -->
            <div class="col-lg-3 col-6">
                <a href="<?= base_url('gymequipment') ?>" style="text-decoration: none; color: inherit; display: block;">
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3><?= $totalEquipment ?><sup class="fs-5"></sup></h3>
                            <p>Total Equipments</p>
                        </div>
                        <div class="icon position-absolute top-0 end-0 p-3" style="opacity: 0.3;">
                            <i class="fas fa-dumbbell fa-3x"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Active Members -->
            <div class="col-lg-3 col-6">
                <a href="<?= base_url('clients1') ?>" style="text-decoration: none; color: inherit; display: block;">
                    <div class="small-box text-bg-danger">
                        <div class="inner">
                            <h3><?= $activeMembers ?><sup class="fs-5"></sup></h3>
                            <p>Active Members</p>
                        </div>
                        <div class="icon position-absolute top-0 end-0 p-3" style="opacity: 0.3;">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Equipment Expenses -->
            <div class="col-lg-3 col-6">
                <a href="<?= base_url('gymequipment') ?>" style="text-decoration: none; color: inherit; display: block;">
                    <div class="small-box" style="background-color: rgb(99, 224, 208);">
                        <div class="inner">
                            <h3>₱<?= number_format($totalEquipmentExpense, 2) ?><sup class="fs-5"></sup></h3>
                            <p>Equipment Expenses</p>
                        </div>
                        <div class="icon position-absolute top-0 end-0 p-3" style="opacity: 0.3;">
                            <i class="fas fa-money-bill-wave fa-3x"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load("current", {packages: ["corechart"]});
            google.charts.setOnLoadCallback(function () {
                drawChart();
                drawRoleChart();
                drawCheckinChart();
                drawCoachChart();
                drawPaymentChart();
            });

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Gender', 'Percentage'],
                    ['Male', <?= $male ?>],
                    ['Female', <?= $female ?>]
                ]);

                var options = {
                    pieHole: 0.4,
                    colors: ['#dc3912', '#3366cc'],
                    pieSliceText: 'percentage',
                    legend: { position: 'right', textStyle: { fontSize: 13 } },
                    backgroundColor: 'transparent',
                    chartArea: {
                        width: '100%',
                        height: '80%'
                    }
                };

                var chart = new google.visualization.PieChart(document.getElementById('donut_chart'));
                chart.draw(data, options);

                window.addEventListener('resize', function () {
                    chart.draw(data, options);
                });
            }

            function drawRoleChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Role', 'Percentage'],
                    ['Client', <?= $clientCount ?>],
                    ['Coach', <?= $coachCount ?>]
                ]);

                var options = {
                    pieHole: 0.4,
                    colors: ['#3366cc', '#dc3912'],
                    pieSliceText: 'percentage',
                    legend: { position: 'right' },
                    backgroundColor: 'transparent',
                    chartArea: {
                        width: '100%',
                        height: '80%'
                    }
                };

                var chart = new google.visualization.PieChart(document.getElementById('role_donut'));
                chart.draw(data, options);

                window.addEventListener('resize', function () {
                    chart.draw(data, options);
                });
            }

            function drawCheckinChart() {
                const monthlyData = <?php echo json_encode($monthlyCheckinData['data']); ?>;
                const totalCheckins = <?php echo json_encode($monthlyCheckinData['total']); ?>;

                const data = google.visualization.arrayToDataTable(monthlyData);

                const options = {
                    hAxis: { title: 'Month' },
                    vAxis: { title: 'Check-ins' },
                    colors: ['#28a745'],
                    legend: 'none',
                    backgroundColor: 'transparent'
                };

                const chart = new google.visualization.ColumnChart(document.getElementById('checkin_bar_chart'));
                chart.draw(data, options);

                window.addEventListener('resize', () => {
                    chart.draw(data, options);
                });

                document.getElementById('checkin_total').innerHTML = `<h4 class="text-center mt-3">Total Check-ins: <strong>${totalCheckins}</strong></h4>`;
            }

            function drawCoachChart() {
                const monthlyCoachData = <?php echo json_encode($monthlyCoachAttendance['data']); ?>;
                const totalCoachCheckins = <?php echo json_encode($monthlyCoachAttendance['total']); ?>;

                const data = google.visualization.arrayToDataTable(monthlyCoachData);

                const options = {
                    hAxis: { title: 'Month' },
                    vAxis: { title: 'Coach Check-ins' },
                    colors: ['#007bff'],
                    legend: 'none',
                    backgroundColor: 'transparent'
                };

                const chart = new google.visualization.ColumnChart(document.getElementById('coach_checkin_bar_chart'));
                chart.draw(data, options);

                window.addEventListener('resize', () => {
                    chart.draw(data, options);
                });

                document.getElementById('coach_checkin_total').innerHTML = `<h4 class="text-center mt-3">Total Coach Check-ins: <strong>${totalCoachCheckins}</strong></h4>`;
            }

            function drawPaymentChart() {
                const paymentData = <?php echo json_encode($monthlyPaymentData['data']); ?>;
                const totalPayments = <?php echo json_encode($monthlyPaymentData['total']); ?>;

                const data = google.visualization.arrayToDataTable(paymentData);

                const options = {
                    hAxis: { title: 'Month' },
                    vAxis: { title: 'Total Paid Amount (₱)' },
                    colors: ['#ff9900'],
                    legend: 'none',
                    backgroundColor: 'transparent'
                };

                const chart = new google.visualization.ColumnChart(document.getElementById('payment_bar_chart'));
                chart.draw(data, options);

                window.addEventListener('resize', () => {
                    chart.draw(data, options);
                });

                document.getElementById('payment_total').innerHTML = `<h4 class="text-center mt-3">Total Payments: <strong>₱${parseFloat(totalPayments).toFixed(2)}</strong></h4>`;
            }
        </script>

        <div class="card">
            <div class="card-title">Monthly Clients Check-in Report</div>
            <div id="checkin_bar_chart"></div>
            <div id="checkin_total"></div>
        </div>

        <div class="card mt-4">
            <div class="card-title">Monthly Coach Check-in Report</div>
            <div id="coach_checkin_bar_chart"></div>
            <div id="coach_checkin_total"></div>
        </div>

        <div class="card mt-4">
            <div class="card-title">Monthly Payment Report</div>
            <div id="payment_bar_chart"></div>
            <div id="payment_total"></div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm p-3 mb-5 bg-white rounded h-100">
                    <div class="card-body">
                        <h5 class="card-title text-center">Role Distribution</h5>
                        <div id="role_donut" style="width: 100%; height: 350px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm p-3 mb-5 bg-white rounded h-100">
                    <div class="card-body">
                        <h5 class="card-title text-center">Gender Chart</h5>
                        <div id="donut_chart" style="width: 100%; height: 350px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>