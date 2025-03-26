<?php
$this->extend('layout/maincoach');
$this->section('body');
?>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="p-2 row mb-3">
    <div class="col-12 mb-2">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClientModal">Add My Schedule</button>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="col-12">
        <table id="clientTable" class="display">
            <thead>
                <tr>
                    <th>Schedule ID</th>
                    <th>ScheduleDate</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sched as $schedule): ?>
                    <tr>
                        <th scope="row"><?= $schedule['ScheduleID']; ?></th>
                        <td><?= $schedule['ScheduleDate']; ?></td>
                        <td><?= $schedule['Start']; ?></td>
                        <td><?= $schedule['End']; ?></td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" onclick="editPlan(<?= $schedule['ScheduleID']; ?>)">Edit</button>
                                <button type="button" class="btn btn-danger" onclick="deletePlan(<?= $schedule['ScheduleID']; ?>)">Delete</button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Add Schedule</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="<?= site_url('/coach-manage/store'); ?>" method="POST">
                    <div class="mb-3">
                        <label>Start Date:</label>
                        <input type="text" name="startdate" id="start_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Start Time:</label>
                        <input type="text" name="starttime" id="start_time" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>End Date:</label>
                        <input type="text" name="enddate" id="end_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>End Time:</label>
                        <input type="text" name="endtime" id="end_time" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Schedule Modal -->
<div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Edit Schedule</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editClientForm">
                    <input type="hidden" id="editClientId" name="id">
                    <div class="mb-3">
                        <label>Start Date:</label>
                        <input type="text" name="startdate" id="edit_start_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Start Time:</label>
                        <input type="text" name="starttime" id="edit_start_time" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>End Date:</label>
                        <input type="text" name="enddate" id="edit_end_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>End Time:</label>
                        <input type="text" name="endtime" id="edit_end_time" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn-update">Update Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        // DataTable Initialization
        new DataTable('#clientTable', {
            responsive: true
        });

        // Initialize Flatpickr for Add Modal
        flatpickr("#start_date", { dateFormat: "Y-m-d" });
        flatpickr("#start_time", { enableTime: true, noCalendar: true, dateFormat: "h:i K" });
        flatpickr("#end_date", { dateFormat: "Y-m-d" });
        flatpickr("#end_time", { enableTime: true, noCalendar: true, dateFormat: "h:i K" });

        // Initialize Flatpickr for Edit Modal
        flatpickr("#edit_start_date", { dateFormat: "Y-m-d" });
        flatpickr("#edit_start_time", { enableTime: true, noCalendar: true, dateFormat: "h:i K" });
        flatpickr("#edit_end_date", { dateFormat: "Y-m-d" });
        flatpickr("#edit_end_time", { enableTime: true, noCalendar: true, dateFormat: "h:i K" });
    });

    // Example functions (add your logic)
    function editPlan(id) {
        console.log("Edit Schedule ID:", id);
        $('#editClientModal').modal('show');
    }

    function deletePlan(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete schedule ID: " + id,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        });
    }
</script>

<?php $this->endSection(); ?>
