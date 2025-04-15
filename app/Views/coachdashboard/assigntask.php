<?= $this->extend('layout/maincoach') ?>
<?= $this->section('body') ?>

<div class="container my-5">
    <h2 class="text-center mb-4">Update Task Status</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Task: <?= esc($task['TaskTitle']) ?></h4>
        </div>
        <div class="card-body">
            <p><strong>Client:</strong> <?= esc($task['Firstname'] . ' ' . $task['Lastname']) ?></p>
            <p><strong>Current Status:</strong> <?= ucfirst($task['Status']) ?></p>
            <p><strong>Current Progress:</strong> <?= $task['Progress'] ?>%</p>
            <p><strong>Due Date:</strong> <?= date('F j, Y', strtotime($task['DueDate'])) ?></p>

            <form action="<?= base_url('tasks/save-task-status/' . $task['TaskID']) ?>" method="post">
                <div class="mb-3">
                    <label for="status" class="form-label">Update Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="pending" <?= $task['Status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="incomplete" <?= $task['Status'] === 'incomplete' ? 'selected' : '' ?>>Incomplete</option>
                        <option value="completed" <?= $task['Status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="progress" class="form-label">Progress (%)</label>
                    <input type="number" name="progress" id="progress" class="form-control" min="0" max="100" value="<?= $task['Progress'] ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="<?= base_url('tasks/coach') ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>