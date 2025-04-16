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
        padding: 2rem;
    }

    .btn-outline-primary {
        border-radius: 20px;
        transition: all 0.2s ease-in-out;
    }

    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white;
    }

    .table thead th {
        font-weight: 600;
        font-size: 0.95rem;
        vertical-align: middle;
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
    <div class="card">
        <h2 class="text-center mb-4 text-primary fw-bold">🏋️ All Gym Equipment</h2>

        <div class="mb-3 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">➕ Add Equipment</button>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table id="myTable" class="table table-hover align-middle">
                <thead class="table-primary text-white">
                    <tr>
                        <th>Equipment ID</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($gymequipment as $equipment): ?>
                        <tr>
                            <td><?= $equipment['EquipmentID']; ?></td>
                            <td><?= $equipment['Description']; ?></td>
                            <td><?= $equipment['Amount']; ?></td>
                            <td><?= $equipment['Qty']; ?></td>
                            <td>
                                <button onclick="editEquipment('<?= $equipment['EquipmentID']; ?>')" class="btn btn-sm btn-outline-primary">✏️ Edit</button>
                                <button onclick="deleteEquipment('<?= $equipment['EquipmentID']; ?>')" class="btn btn-sm btn-outline-danger">🗑️ Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modals remain unchanged -->
<?= $this->endSection(); ?>
