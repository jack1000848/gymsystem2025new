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

    <a href="<?= base_url('create') ?>" class="btn btn-primary mb-3">Assign New Task</a>

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
                    <tr data-task-id="<?= $task['TaskID'] ?>">
                        <td><?= esc($task['TaskTitle']) ?></td>
                        <td><?= esc($task['Firstname'] ) ?></td>
                        <td>
                            <select class="form-select status-select" data-task-id="<?= $task['TaskID'] ?>">
                                <option value="pending" <?= $task['Status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="incomplete" <?= $task['Status'] === 'incomplete' ? 'selected' : '' ?>>Incomplete</option>
                                <option value="completed" <?= $task['Status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                        </td>
                        <td class="progress-cell"><?= $task['Progress'] ?>%</td>
                        <td><?= date('F j, Y', strtotime($task['DueDate'])) ?></td>
                        <td>
                            <?php if (in_array($task['Status'], ['incomplete', 'completed'])): ?>
                                <a href="<?= base_url('download-pdf/' . $task['TaskID']) ?>" class="btn btn-primary btn-sm">Download PDF</a>
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

<script>
document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', function() {
        const taskID = this.getAttribute('data-task-id');
        const newStatus = this.value;

        // Send AJAX request to update the status
        fetch('<?= base_url('tasks/update-status-direct') ?>/' + taskID, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update progress if status is 'completed'
                if (newStatus === 'completed') {
                    const row = document.querySelector(`tr[data-task-id="${taskID}"]`);
                    const progressCell = row.querySelector('.progress-cell');
                    progressCell.textContent = '100%';
                }

                // Show/hide Download PDF button based on status
                const actionsCell = document.querySelector(`tr[data-task-id="${taskID}"] td:last-child`);
                if (newStatus === 'incomplete' || newStatus === 'completed') {
                    if (!actionsCell.querySelector('a')) {
                        const downloadLink = document.createElement('a');
                        downloadLink.href = '<?= base_url('tasks/download-pdf') ?>/' + taskID;
                        downloadLink.className = 'btn btn-primary btn-sm';
                        downloadLink.textContent = 'Download PDF';
                        actionsCell.appendChild(downloadLink);
                    }
                } else {
                    const downloadLink = actionsCell.querySelector('a');
                    if (downloadLink) {
                        downloadLink.remove();
                    }
                }

                alert('Task status updated successfully.');
            } else {
                alert('Error: ' + data.message);
                // Revert the dropdown to the previous value
                this.value = data.oldStatus;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the status.');
            // Revert the dropdown (you'd need to store the old value if needed)
        });
    });
});

fetch('<?= base_url('update-status-direct') ?>/' + taskID, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({ status: newStatus })
})
</script>

<?= $this->endSection() ?>