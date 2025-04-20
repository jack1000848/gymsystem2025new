<?php $this->extend('layout/main'); ?>
<?php $this->section('body'); ?>

<!-- Integrated CSS from your provided design -->
<style>
    body {
        background-color: #f4f4f4;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
        max-width: 1400px;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
    }

    .text-primary {
        color: #3498db !important;
    }

    .table {
        width: 100% !important;
        margin: 0 auto;
        border-radius: 12px;
        overflow: hidden;
    }

    .table-primary {
        background-color: #3498db !important;
    }

    .table th {
        font-size: 16px;
        padding: 12px;
        text-transform: uppercase;
        text-align: center;
        color: white;
    }

    .table td {
        font-size: 15px;
        color: #2c3e50;
        text-align: center;
        padding: 10px;
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: #ecf0f1;
    }

    .badge {
        font-size: 14px;
        padding: 6px 12px;
        border-radius: 8px;
    }

    .bg-success {
        background-color: #27ae60 !important;
    }

    .bg-danger {
        background-color: #e74c3c !important;
    }

    .bg-warning {
        background-color: #f1c40f !important;
    }

    .progress {
        height: 20px;
        border-radius: 8px;
        background-color: #e0e0e0;
    }

    .progress-bar {
        background-color: #3498db !important;
        font-size: 12px;
        line-height: 20px;
    }

    .btn-outline-primary {
        border-color: #3498db;
        color: #3498db;
        border-radius: 8px;
        font-size: 14px;
        padding: 6px 12px;
    }

    .btn-outline-primary:hover {
        background-color: #3498db;
        color: white;
    }

    .alert {
        border-radius: 10px;
        padding: 12px;
        font-size: 15px;
        margin-bottom: 20px;
    }

    .text-muted {
        color: #7f8c8d !important;
    }

    .search-form {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }

    .search-form input {
        border-radius: 6px;
        padding: 6px 12px;
        border: 1px solid #ccc;
        font-size: 14px;
        width: 250px;
        transition: border-color 0.3s ease;
    }

    .search-form input:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
    }
</style>

<div class="container my-5">
    <div class="card shadow-lg border-0 rounded-4 p-4">
        <h2 class="text-center mb-4 text-primary fw-bold">📋 All Tasks</h2>

        <!-- Search Input -->
        <div class="search-form">
            <input type="text" id="searchInput" placeholder="Search by Client or Coach Name">
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (!empty($tasks)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="taskTable">
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

<!-- JavaScript for Client-Side Search -->
<script>
    (function() {
        // Cache DOM elements
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('taskTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        // Debounce function to limit search execution
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Search function
        function searchTable() {
            const searchValue = searchInput.value.trim().toLowerCase();

            for (let i = 0; i < rows.length; i++) {
                const coachCell = rows[i].getElementsByTagName('td')[1];
                const clientCell = rows[i].getElementsByTagName('td')[2];
                const coachText = coachCell ? coachCell.textContent.toLowerCase() : '';
                const clientText = clientCell ? clientCell.textContent.toLowerCase() : '';

                // Show/hide row based on search match
                rows[i].style.display = (coachText.includes(searchValue) || clientText.includes(searchValue)) ? '' : 'none';
            }
        }

        // Add debounced event listener
        searchInput.addEventListener('input', debounce(searchTable, 300));
    })();
</script>

<?= $this->endSection() ?>