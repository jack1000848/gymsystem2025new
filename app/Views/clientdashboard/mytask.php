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
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Coach</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Update Progress</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tasks)): ?>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?= esc($task['Firstname']) ?></td>
                            <td><?= esc($task['TaskTitle']) ?></td>
                            <td><?= esc($task['TaskDescription']) ?></td>
                            <td><?= esc($task['DueDate']) ?></td>
                            <td><?= esc($task['Status']) ?></td>
                            <td><?= esc($task['Progress']) ?>%</td>
                            <td>
                                <?php if ($task['Status'] === 'pending'): ?>
                                    <form action="<?= base_url('tasks/updateProgress/' . $task['TaskID']) ?>" method="post">
                                        <select name="steps_completed" onchange="this.form.submit()">
                                            <option value="0" <?= $task['Progress'] == 0 ? 'selected' : '' ?>>0 Steps</option>
                                            <option value="1" <?= $task['Progress'] == 33 ? 'selected' : '' ?>>Step 1 (33%)</option>
                                            <option value="2" <?= $task['Progress'] == 66 ? 'selected' : '' ?>>Step 2 (66%)</option>
                                            <option value="3" <?= $task['Progress'] == 100 ? 'selected' : '' ?>>Step 3 (100%)</option>
                                        </select>
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
</html>
<?= $this->endSection() ?>