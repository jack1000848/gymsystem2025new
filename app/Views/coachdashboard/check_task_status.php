<?= $this->extend('layout/maincoach') ?>
<?= $this->section('body') ?>

<div class="container my-5">
    <h2 class="text-center mb-4">Task Status</h2>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Task: <?= esc($task['TaskTitle']) ?></h4>
        </div>
        <div class="card-body">
            <p><strong>Client:</strong> <?= esc($task['Firstname'] . ' ' . $task['Lastname']) ?></p>
            <p><strong>Status:</strong> <?= ucfirst($task['Status']) ?></p>
            <p><strong>Progress:</strong> <?= $task['Progress'] ?>%</p>
            <p><strong>Due Date:</strong> <?= date('F j, Y', strtotime($task['DueDate'])) ?></p>
            <a href="<?= base_url('tasks/coach') ?>" class="btn btn-secondary">Back to Tasks</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>