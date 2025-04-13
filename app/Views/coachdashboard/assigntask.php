<?php
    $this ->extend('layout/maincoach');
    $this ->section('body');

    ?>
    <title>Create Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
        <h2>Assign Task</h2>
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
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?= $customer['CustomerID'] ?>"><?= esc($customer['Firstname']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="TaskTitle" class="form-label">Task Title</label>
                <input type="text" name="TaskTitle" id="TaskTitle" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="TaskDescription" class="form-label">Description</label>
                <textarea name="TaskDescription" id="TaskDescription" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="DueDate" class="form-label">Due Date</label>
                <input type="date" name="DueDate" id="DueDate" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Assign Task</button>
        </form>
    </div>
<?php $this->endSection(); ?> 