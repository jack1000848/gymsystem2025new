<?= $this->extend('layout/mainclient') ?>
<?= $this->section('body') ?>

<div class="container my-5">
    <h2 class="text-center mb-4">My Tasks</h2>

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
                    <th>Coach Name</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr data-task-id="<?= $task['TaskID'] ?>">
                        <td><?= esc($task['TaskTitle']) ?></td>
                        <td><?= esc($task['Firstname']) ?></td>
                        <td><?= ucfirst($task['Status']) ?></td>
                        <td class="progress-cell"><?= $task['Progress'] ?>%</td>
                        <td><?= date('F j, Y', strtotime($task['DueDate'])) ?></td>
                        <td class="actions-cell">
                            <?php if (in_array($task['Status'], ['incomplete', 'completed'])): ?>
                                <a href="<?= base_url('client-download-pdf/' . $task['TaskID']) ?>" class="btn btn-primary btn-sm download-pdf-btn">Download PDF</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="text-center text-muted fs-5 mt-4">
            <p>No tasks assigned to you.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>