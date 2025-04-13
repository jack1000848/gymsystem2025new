<?php
    $this ->extend('layout/maincoach');
    $this ->section('body');

    ?>
<!DOCTYPE html>
<html>
<head>
    <title>Coach Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>My Assigned Tasks</h2>
        <a href="<?= base_url('tasks/create') ?>" class="btn btn-success mb-3">Assign New Task</a>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?= esc($task['CustomerName']) ?></td>
                        <td><?= esc($task['TaskTitle']) ?></td>
                        <td><?= esc($task['TaskDescription']) ?></td>
                        <td><?= esc($task['DueDate']) ?></td>
                        <td><?= esc($task['Status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $this->endSection(); ?> 