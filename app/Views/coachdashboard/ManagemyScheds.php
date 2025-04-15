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
                        <th scope="row"><?= $schedule['ID']; ?></th>
                        <td><?= $schedule['ScheduleDate']; ?></td>
                        <td><?= $schedule['Start']; ?></td>
                        <td><?= $schedule['End']; ?></td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" onclick="editPlan(<?= $schedule['ID']; ?>)">Edit</button>
                                <button type="button" class="btn btn-danger" onclick="deletePlan(<?= $schedule['ID']; ?>)">Delete</button>
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
<!-- JS Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        new DataTable('#clientTable', { responsive: true });

        flatpickr("#start_date", { dateFormat: "Y-m-d" });
        flatpickr("#start_time", { enableTime: true, noCalendar: true, dateFormat: "h:i K" });
        flatpickr("#end_date", { dateFormat: "Y-m-d" });
        flatpickr("#end_time", { enableTime: true, noCalendar: true, dateFormat: "h:i K" });

        flatpickr("#edit_start_date", { dateFormat: "Y-m-d" });
        flatpickr("#edit_start_time", { enableTime: true, noCalendar: true, dateFormat: "h:i K" });
        flatpickr("#edit_end_date", { dateFormat: "Y-m-d" });
        flatpickr("#edit_end_time", { enableTime: true, noCalendar: true, dateFormat: "h:i K" });

        // Update Schedule AJAX
        $('#editClientForm').submit(function (e) {
    e.preventDefault();
    let formData = $(this).serialize();
    console.log('Serialized Data:', formData); // DEBUG

    var id = $('#editClientId').val();
console.log("ID to update: ", id); // ✅ DEBUG: Check if this prints a correct ID

$.ajax({
    url: "<?= site_url('/coach-manage/update') ?>",
    method: 'POST',
    data: {
        id: id,
        startdate: $('#edit_start_date').val(),
        starttime: $('#edit_start_time').val(),
        enddate: $('#edit_end_date').val(),
        endtime: $('#edit_end_time').val()
    },
    success: function (response) {
        // Show success alert
        Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: 'Schedule updated successfully!',
        }).then(() => {
            location.reload(); // Refresh to show updated data
        });
    },
    error: function (xhr, status, error) {
        console.error("Update failed:", xhr.responseText);
        Swal.fire({
            icon: 'error',
            title: 'Failed!',
            text: 'Failed to update schedule. Please try again.',
        });
    }
});

    function editPlan(id) {
        $.ajax({
            url: "<?= site_url('/coach-manage/edit/') ?>/" + id,
            method: "GET",
            dataType: "JSON",
            success: function (data) {
                $('#editClientId').val(data.ID);  // data.ID should be valid (like 1, 2, 3)

                $('#edit_start_date').val(data.ScheduleDate);
                $('#edit_start_time').val(data.Start);
                $('#edit_end_date').val(data.ScheduleDate);
                $('#edit_end_time').val(data.End);
                $('#editClientModal').modal('show');
            }
        });
    }

    function deletePlan(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete this schedule.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= site_url('/coach-manage/delete/') ?>" + id, // Add the slash correctly
                    method: "POST",
                    success: function () {
                        Swal.fire('Deleted!', 'Schedule has been deleted.', 'success')
                            .then(() => { location.reload(); });
                    }
                });
            }
        });
    }
</script>

<?php $this->endSection(); ?>
