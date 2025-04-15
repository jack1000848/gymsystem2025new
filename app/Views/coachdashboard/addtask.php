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

    <a href="<?= base_url('tasks/create') ?>" class="btn btn-primary mb-3">Assign New Task</a>

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
                        <td><?= esc($task['Firstname']) ?></td>
                        <td class="status-cell"><?= ucfirst($task['Status']) ?></td>
                        <td class="progress-cell"><?= $task['Progress'] ?>%</td>
                        <td><?= date('F j, Y', strtotime($task['DueDate'])) ?></td>
                        <td class="actions-cell">
                            <button class="btn btn-warning btn-sm update-status-btn" 
                                    data-task-id="<?= $task['TaskID'] ?>" 
                                    data-current-status="<?= $task['Status'] ?>" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#updateStatusModal">Update Status</button>
                            <?php if (in_array($task['Status'], ['incomplete', 'completed'])): ?>
                                <a href="<?= base_url('tasks/download-pdf/' . $task['TaskID']) ?>" class="btn btn-primary btn-sm download-pdf-btn">Download PDF</a>
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

<!-- Modal for Updating Status -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update Task Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modalTaskId">
                <div class="mb-3">
                    <label for="statusSelect" class="form-label">Select Status</label>
                    <select class="form-select" id="statusSelect">
                        <option value="pending">Pending</option>
                        <option value="incomplete">Incomplete</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveStatusBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Include Bootstrap JS for Modal Functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const updateStatusButtons = document.querySelectorAll('.update-status-btn');
    const modal = document.getElementById('updateStatusModal');
    const taskIdInput = document.getElementById('modalTaskId');
    const statusSelect = document.getElementById('statusSelect');
    const saveStatusBtn = document.getElementById('saveStatusBtn');

    // When the Update Status button is clicked, populate the modal
    updateStatusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const taskId = this.getAttribute('data-task-id');
            const currentStatus = this.getAttribute('data-current-status');
            taskIdInput.value = taskId;
            statusSelect.value = currentStatus; // Pre-select the current status
        });
    });

    // When the Save button in the modal is clicked, send AJAX request
    saveStatusBtn.addEventListener('click', function() {
        const taskId = taskIdInput.value;
        const newStatus = statusSelect.value;

        // Send AJAX request to update the status
        fetch('<?= base_url('update-status-modal/') ?>/' + taskId, {
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
                // Update the table row dynamically
                const row = document.querySelector(`tr[data-task-id="${taskId}"]`);
                const statusCell = row.querySelector('.status-cell');
                const progressCell = row.querySelector('.progress-cell');
                const actionsCell = row.querySelector('.actions-cell');

                // Update status in the table
                statusCell.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);

                // Update progress if status is 'completed'
                if (newStatus === 'completed') {
                    progressCell.textContent = '100%';
                }

                // Show/hide Download PDF button based on status
                let downloadBtn = actionsCell.querySelector('.download-pdf-btn');
                if (newStatus === 'incomplete' || newStatus === 'completed') {
                    if (!downloadBtn) {
                        downloadBtn = document.createElement('a');
                        downloadBtn.href = '<?= base_url('tasks/download-pdf') ?>/' + taskId;
                        downloadBtn.className = 'btn btn-primary btn-sm download-pdf-btn';
                        downloadBtn.textContent = 'Download PDF';
                        actionsCell.appendChild(downloadBtn);
                    }
                } else {
                    if (downloadBtn) {
                        downloadBtn.remove();
                    }
                }

                // Close the modal
                const modalInstance = bootstrap.Modal.getInstance(modal);
                modalInstance.hide();

                alert('Task status updated successfully.');
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the status.');
        });
    });
});
</script>

<?= $this->endSection() ?>