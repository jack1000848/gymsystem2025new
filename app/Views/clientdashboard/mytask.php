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
                    <th>Subtasks</th>
                    <th>Actions</th>
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
                                    <form action="<?= base_url('tasks/updateSubtasks/' . $task['TaskID']) ?>" method="post">
                                        <?php
                                        $subtaskModel = new \App\Models\SubtaskModel();
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
                            <td class="actions-cell">
                                <?php if (in_array($task['Status'], ['incomplete', 'completed'])): ?>
                                    <a href="<?= base_url('client-download-pdf/' . $task['TaskID']) ?>" class="btn btn-primary btn-sm download-pdf-btn">Download PDF</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No tasks assigned.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?= $this->endSection() ?>