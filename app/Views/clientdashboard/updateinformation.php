
<?= $this->extend('layout/mainclient') ?>
<?= $this->section('body') ?>


                    <!-- uupdate--bodyinformation.php -->
    <div class="container mt-5">
        <h2>Body Information History</h2>
        <?php if (session()->has('success')): ?>
            <div class="alert alert-success">
                <p><?= esc(session('success')) ?></p>
            </div>
        <?php endif; ?>
        
        <!-- Chart -->
        <canvas id="weightChart" height="100"></canvas>

        <!-- History Table -->
        <h3 class="mt-5">History</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Height (cm)</th>
                    <th>Weight (kg)</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($history): ?>
                    <?php foreach ($history as $record): ?>
                        <tr>
                            <td><?= esc($record['RecordDate']) ?></td>
                            <td><?= esc($record['Height'] ?? '-') ?></td>
                            <td><?= esc($record['Weight'] ?? '-') ?></td>
                            <td><?= esc($record['Notes'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="<?= base_url('/bodyInfo') ?>" class="btn btn-primary">Update Body Info</a>
    </div>

    <script>
        // Prepare data for the chart
        const history = <?= json_encode($history) ?>;
        const labels = history.map(record => record.RecordDate);
        const weights = history.map(record => record.Weight);

        // Create the chart
        const ctx = document.getElementById('weightChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Weight (kg)',
                    data: weights,
                    borderColor: 'blue',
                    fill: false
                }]
            },
            options: {
                scales: {
                    x: { title: { display: true, text: 'Date' } },
                    y: { title: { display: true, text: 'Weight (kg)' } }
                }
            }
        });
    </script>
<?= $this->endSection() ?>