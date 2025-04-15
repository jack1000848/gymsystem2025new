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
                    <th>Done</th>
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
                        <td>
                            <?php if ($task['Status'] !== 'completed'): ?>
                                <input type="checkbox" class="progress-checkbox" data-task-id="<?= $task['TaskID'] ?>" <?= $task['Progress'] >= 100 ? 'checked disabled' : '' ?>>
                            <?php else: ?>
                                <input type="checkbox" checked disabled>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($task['TaskTitle']) ?></td>
                        <td><?= esc($task['Firstname']) ?></td>
                        <td class="status-cell"><?= ucfirst($task['Status']) ?></td>
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

<script>
document.querySelectorAll('.progress-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const taskID = this.getAttribute('data-task-id');
        const isChecked = this.checked;

        fetch('<?= base_url('client-update-progress') ?>/' + taskID, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ increment: isChecked })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const row = document.querySelector(`tr[data-task-id="${taskID}"]`);
                const progressCell = row.querySelector('.progress-cell');
                const statusCell = row.querySelector('.status-cell');
                const actionsCell = row.querySelector('.actions-cell');

                // Update progress in the table
                progressCell.textContent = data.progress + '%';

                // Disable checkbox if progress reaches 100%
                if (data.progress >= 100) {
                    checkbox.checked = true;
                    checkbox.disabled = true;
                }

                // Update status if changed
                if (data.status) {
                    statusCell.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                }

                // Show Download PDF button if status becomes incomplete or completed
                let downloadBtn = actionsCell.querySelector('.download-pdf-btn');
                if (data.status === 'incomplete' || data.status === 'completed') {
                    if (!downloadBtn) {
                        downloadBtn = document.createElement('a');
                        downloadBtn.href = '<?= base_url('client-download-pdf') ?>/' + taskID;
                        downloadBtn.className = 'btn btn-primary btn-sm download-pdf-btn';
                        downloadBtn.textContent = 'Download PDF';
                        actionsCell.appendChild(downloadBtn);
                    }
                }

                alert('Task progress updated successfully.');
            } else {
                alert('Error: ' + data.message);
                this.checked = !isChecked; // Revert checkbox state
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the progress.');
            this.checked = !isChecked; // Revert checkbox state
        });
    });
});
</script>

<?= $this->endSection() ?>