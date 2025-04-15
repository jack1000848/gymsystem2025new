<?= $this->extend('layout/maincoach') ?>
<?= $this->section('body') ?>

<!-- Styling -->
<style>
    body {
        background-color: #f4f6f8;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
        max-width: 800px;
    }

    .card {
        border-radius: 1rem;
        box-shadow: 0 0.75rem 2rem rgba(0, 0, 0, 0.07);
        background-color: #fff;
    }

    .card-header {
        border-radius: 1rem 1rem 0 0;
    }

    .form-label {
        font-weight: 600;
    }

    .form-control,
    .form-select {
        border-radius: 0.5rem;
        box-shadow: none !important;
        border: 1px solid #ced4da;
        transition: border-color 0.2s ease-in-out;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        outline: none;
    }

    .btn {
        border-radius: 50px;
        padding: 0.4rem 1.2rem;
        font-weight: 500;
    }

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .remove-subtask {
        margin-left: 10px;
    }

    #add-subtask {
        margin-top: 10px;
        border-radius: 20px;
    }

    .alert {
        border-radius: 0.5rem;
        font-size: 0.95rem;
    }

    @media (max-width: 576px) {
        .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .input-group {
            flex-direction: column;
            gap: 0.5rem;
        }

        .remove-subtask {
            width: 100%;
        }
    }
</style>

<!-- Form Content -->
<div class="container my-5">
    <h2 class="text-center mb-4">Assign New Task</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <p><?= esc($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Create Task</h4>
        </div>
        <div class="card-body">
            <form action="<?= base_url('store') ?>" method="post">
                <div class="mb-3">
                    <label for="CustomerID" class="form-label">Select Client</label>
                    <select name="CustomerID" id="CustomerID" class="form-select" required>
                        <option value="">-- Select a Client --</option>
                        <?php if (isset($customers) && !empty($customers)): ?>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= esc($customer['CustomerID']) ?>" <?= old('CustomerID') == $customer['CustomerID'] ? 'selected' : '' ?>>
                                    <?= esc($customer['Firstname'] . ' ' . $customer['Lastname']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No clients available</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="TaskTitle" class="form-label">Task Title</label>
                    <input type="text" name="TaskTitle" id="TaskTitle" class="form-control" value="<?= old('TaskTitle') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="TaskDescription" class="form-label">Task Description</label>
                    <textarea name="TaskDescription" id="TaskDescription" class="form-control"><?= old('TaskDescription') ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="DueDate" class="form-label">Due Date</label>
                    <input type="date" name="DueDate" id="DueDate" class="form-control" value="<?= old('DueDate') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subtasks</label>
                    <div id="subtasks">
                        <div class="input-group mb-2">
                            <input type="text" name="subtasks[]" class="form-control" placeholder="Enter subtask" required>
                            <button type="button" class="btn btn-danger remove-subtask">Remove</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" id="add-subtask">Add Subtask</button>
                </div>

                <button type="submit" class="btn btn-primary">Assign Task</button>
                <a href="<?= base_url('tasks/coach') ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<!-- JS for adding/removing subtasks -->
<script>
document.getElementById('add-subtask').addEventListener('click', function() {
    const subtasksDiv = document.getElementById('subtasks');
    const newSubtask = document.createElement('div');
    newSubtask.className = 'input-group mb-2';
    newSubtask.innerHTML = `
        <input type="text" name="subtasks[]" class="form-control" placeholder="Enter subtask" required>
        <button type="button" class="btn btn-danger remove-subtask">Remove</button>
    `;
    subtasksDiv.appendChild(newSubtask);
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-subtask')) {
        e.target.parentElement.remove();
    }
});
</script>

<?= $this->endSection() ?>
