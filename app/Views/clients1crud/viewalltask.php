<?php $this->extend('layout/main'); ?>
<?php $this->section('body'); ?>

<style>
    body {
    background-color: #f4f6f8;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 0.75rem 2rem rgba(0, 0, 0, 0.07);
}

.table thead th {
    font-weight: 600;
    font-size: 0.95rem;
    vertical-align: middle;
}

.progress {
    background-color: #e9ecef;
}

.btn-outline-primary {
    border-radius: 20px;
    transition: all 0.2s ease-in-out;
}

.btn-outline-primary:hover {
    background-color: #0d6efd;
    color: white;
}

@media (max-width: 768px) {
    .table thead {
        display: none;
    }

    .table,
    .table tbody,
    .table tr,
    .table td {
        display: block;
        width: 100%;
    }

    .table tr {
        margin-bottom: 1rem;
        border-bottom: 1px solid #ccc;
    }

    .table td {
        text-align: right;
        padding-left: 50%;
        position: relative;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }

    .table td::before {
        content: attr(data-label);
        position: absolute;
        left: 0;
        width: 50%;
        padding-left: 1rem;
        font-weight: bold;
        text-align: left;
        color: #333;
    }
}

</style>
<div class="container my-5">
    <div class="card shadow-lg border-0 rounded-4 p-4">
        <h2 class="text-center mb-4 text-primary fw-bold">📋 All Tasks</h2>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (!empty($tasks)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary text-white">
                        <tr>
                            <th>Task Title</th>
                            <th>Coach</th>
                            <th>Client</th>
                            <th>Description</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td><?= esc($task['TaskTitle']) ?></td>
                                <td><?= esc($task['CoachFirstname'] . ' ' . $task['CoachLastname']) ?></td>
                                <td><?= esc($task['ClientFirstname'] . ' ' . $task['ClientLastname']) ?></td>
                                <td><?= esc($task['TaskDescription']) ?></td>
                                <td><?= date('F j, Y', strtotime($task['DueDate'])) ?></td>
                                <td>
                                    <span class="badge bg-<?= $task['Status'] === 'completed' ? 'success' : ($task['Status'] === 'incomplete' ? 'danger' : 'warning') ?>">
                                        <?= ucfirst($task['Status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?= esc($task['Progress']) ?>%;" aria-valuenow="<?= esc($task['Progress']) ?>" aria-valuemin="0" aria-valuemax="100">
                                            <?= esc($task['Progress']) ?>%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if (in_array($task['Status'], ['incomplete', 'completed'])): ?>
                                        <a href="<?= base_url('admin-download-pdf/' . $task['TaskID']) ?>" class="btn btn-outline-primary btn-sm">
                                            📄 Download PDF
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center text-muted fs-5 mt-4">
                <p>No tasks found in the system.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
