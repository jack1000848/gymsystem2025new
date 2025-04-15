<?= $this->extend('layout/mainclient') ?>
<?= $this->section('body') ?>
<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
    }

    h2 {
        font-size: 1.75rem;
    }

    .table thead th {
        font-weight: 600;
        font-size: 0.95rem;
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

        .table, .table tbody, .table tr, .table td {
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
        }

        .table td::before {
            content: attr(data-label);
            position: absolute;
            left: 0;
            width: 50%;
            padding-left: 1rem;
            font-weight: bold;
            text-align: left;
        }
    }
</style>

<div class="container my-5">
    <div class="card shadow-lg border-0 rounded-4 p-4">
        <h2 class="mb-4 text-primary fw-bold">📝 My Tasks</h2>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Coach</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Subtasks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tasks)): ?>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td><?= esc($task['Firstname']) ?></td>
                                <td><?= esc($task['TaskTitle']) ?></td>
                                <td><?= esc($task['TaskDescription']) ?></td>
                                <td><?= esc($task['DueDate']) ?></td>
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
                                    <?php if ($task['Status'] === 'pending'): ?>
                                        <form action="<?= base_url('tasks/updateSubtasks/' . $task['TaskID']) ?>" method="post">
                                            <?php
                                            $subtaskModel = new \App\Models\SubtaskModel();
                                            $subtasks = $subtaskModel->where('TaskID', $task['TaskID'])->findAll();
                                            foreach ($subtasks as $subtask):
                                            ?>
                                                <div class="form-check">
                                                    <input type="checkbox" name="subtasks[<?= $subtask['SubtaskID'] ?>]" value="1" class="form-check-input" <?= $subtask['IsCompleted'] ? 'checked' : '' ?> onchange="this.form.submit()">
                                                    <label class="form-check-label"><?= esc($subtask['SubtaskName']) ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted"><?= $task['Status'] === 'completed' ? 'Completed' : 'Incomplete' ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (in_array($task['Status'], ['incomplete', 'completed'])): ?>
                                        <a href="<?= base_url('client-download-pdf/' . $task['TaskID']) ?>" class="btn btn-outline-primary btn-sm">
                                            📄 Download PDF
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No tasks assigned.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
