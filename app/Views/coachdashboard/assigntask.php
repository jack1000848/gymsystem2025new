<?php
    $this ->extend('layout/maincoach');
    $this ->section('body');

    ?>
 <!DOCTYPE html>
<html>
<head>
    <title>Create Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Assign New Task</h2>
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <p><?= esc($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="<?= base_url('tasks/store') ?>" method="post">
            <div class="mb-3">
                <label for="CustomerID" class="form-label">Client</label>
                <select name="CustomerID" id="CustomerID" class="form-select" required>
                    <option value="">Select Client</option>
                    <?php if (!empty($customers)): ?>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= $customer['CustomerID'] ?>"><?= esc($customer['CustomerName']) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No clients assigned</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="TaskTitle" class="form-label">Task Title</label>
                <input type="text" name="TaskTitle" id="TaskTitle" class="form-control" value="<?= old('TaskTitle') ?>" required>
            </div>
            <div class="mb-3">
                <label for="TaskDescription" class="form-label">Description</label>
                <textarea name="TaskDescription" id="TaskDescription" class="form-control"><?= old('TaskDescription') ?></textarea>
            </div>
            <div class="mb-3">
                <label for="DueDate" class="form-label">Due Date</label>
                <input type="date" name="DueDate" id="DueDate" class="form-control" value="<?= old('DueDate') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Subtasks</label>
                <div id="subtasks">
                    <div class="subtask-entry mb-2">
                        <input type="text" name="subtasks[]" class="form-control" placeholder="Enter subtask (e.g., Incline Chest)" required>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary mt-2" onclick="addSubtask()">Add Another Subtask</button>
            </div>
            <button type="submit" class="btn btn-primary">Assign Task</button>
        </form>
    </div>

    <script>
        function addSubtask() {
            const subtaskDiv = document.createElement('div');
            subtaskDiv.classList.add('subtask-entry', 'mb-2');
            subtaskDiv.innerHTML = `
                <input type="text" name="subtasks[]" class="form-control" placeholder="Enter subtask (e.g., Dumbbell Bench Press)" required>
                <button type="button" class="btn btn-danger btn-sm mt-1" onclick="this.parentElement.remove()">Remove</button>
            `;
            document.getElementById('subtasks').appendChild(subtaskDiv);
        }
    </script>
</body>
</html>
<?php $this->endSection(); ?> 