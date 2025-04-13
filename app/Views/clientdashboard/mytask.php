<?= $this->extend('layout/mainclient') ?>
<?= $this->section('body') ?>
<!DOCTYPE html>
<html>
<head>
    <title>My Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>My Tasks</h2>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?= esc($task['TaskTitle']) ?></td>
                        <td><?= esc($task['TaskDescription']) ?></td>
                        <td><?= esc($task['DueDate']) ?></td>
                        <td><?= esc($task['Status']) ?></td>
                        <td>
                            <?php if ($task['Status'] === 'pending'): ?>
                                <a href="<?= base_url('tasks/complete/' . $task['TaskID']) ?>" class="btn btn-sm btn-primary">Mark Complete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?= $this->endSection() ?>