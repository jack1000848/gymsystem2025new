<?= $this->extend('layout/maincoach') ?>
<?= $this->section('body') ?>

<div class="container my-5">
    <h2 class="text-center mb-4">Manage Client Tasks</h2>

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

    <?php if (!empty($tasks)): ?>
        <table class="table table-bordered table-striped">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Task Title</th>
                    <th>Client Name</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?= esc($task['TaskTitle']) ?></td>
                        <td><?= esc($task['Firstname'] . ' ' . $task['Lastname']) ?></td>
                        <td><?= ucfirst($task['Status']) ?></td>
                        <td><?= $task['Progress'] ?>%</td>
                        <td><?= date('F j, Y', strtotime($task['DueDate'])) ?></td>
                        <td>
                            <?php if ($task['Status'] !== 'completed'): ?>
                                <a href="<?= base_url('coach/mark-task-completed/' . $task['TaskID']) ?>" class="btn btn-success btn-sm">Mark as Completed</a>
                            <?php else: ?>
                                <a href="<?= base_url('coach/download-pdf/' . $task['TaskID']) ?>" class="btn btn-primary btn-sm">Download PDF</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="text-center text-muted fs-5 mt-4">
            <p>No tasks found.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>