<?= $this->extend('layout/mainclient') ?>
<?= $this->section('body') ?>

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

<?php if ($history): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
</script>
<?php endif; ?>

<?= $this->endSection() ?>
