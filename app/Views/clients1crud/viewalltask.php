<?php

$this->extend('layout/main'); // Extend the main layout
$this->section('body'); // Start the body section
?>

<div class="container my-5">
    <h2 class="text-center mb-4">All Tasks</h2>

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
                        <td><?= ucfirst($task['Status']) ?></td>
                        <td><?= $task['Progress'] ?>%</td>
                        <td>
                            <?php if (in_array($task['Status'], ['incomplete', 'completed'])): ?>
                                <a href="<?= base_url('admin-download-pdf/' . $task['TaskID']) ?>" class="btn btn-primary btn-sm">Download PDF</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="text-center text-muted fs-5 mt-4">
            <p>No tasks found in the system.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>