<?php
$this->extend('layout/main');
$this->section('body');
?>

<!-- Moved Select2 CSS to the top -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="p-2 row mb-3">
    <div class="col-12 mb-2">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPlanModal">Add Plan</button>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="col-12">
        <table id="myTable" class="display">
            <thead>
                <tr>
                    <th>Plan ID</th>
                    <th>Plan Name</th>
                    <th>Description</th>
                    <th>Duration in Months</th>
                    <th>Price</th>
                    <th>Active</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gymplans as $plan): ?>
                    <tr>
                        <th scope="row"><?= $plan['PlanID']; ?></th>
                        <td><?= esc($plan['PlanName']); ?></td>
                        <td><?= esc($plan['Description']); ?></td>
                        <td><?= $plan['Duration']; ?></td>
                        <td><?= $plan['Price']; ?></td>
                        <td><?= $plan['IsActive'] ? 'Yes' : 'No'; ?></td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" onclick="editPlan(<?= $plan['PlanID']; ?>)">Edit</button>
                                <button type="button" class="btn btn-danger" onclick="deletePlan(<?= $plan['PlanID']; ?>)">Delete</button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Plan Modal -->
<div class="modal fade" id="addPlanModal" tabindex="-1" aria-labelledby="addPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addPlanModalLabel">Add Plan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addPlanForm" action="<?= site_url('/gymplan/store'); ?>" method="POST">>
                    <div class="mb-3">
                        <label for="planName" class="form-label">Plan Name</label>
                        <input type="text" class="form-control" name="Pname" id="planName" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="durationim" class="form-label">Duration in Months</label>
                        <input type="number" class="form-control" id="durationim" name="durationim" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="coaches" class="form-label">Coach</label>
                        <select class="form-select" id="coaches" name="coaches[]" multiple="multiple">
                            <?php foreach ($coaches as $coach): ?>
                                <option value="<?= esc($coach['CoachID']); ?>"><?= esc($coach['Firstname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="active" class="form-label">Active</label>
                        <input type="checkbox" class="form-check-input" id="active" name="active" value="1" checked>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Plan</button>
                    </div>
                </foid=>
            </div>
        </div>
    </div>
</div>

<!-- Edit Plan Modal -->
<div class="modal fade" id="editPlanModal" tabindex="-1" aria-labelledby="editPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editPlanModalLabel">Edit Plan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPlanForm">
                    <input type="hidden" name="id" id="editPlanId">
                    <div class="mb-3">
                        <label for="editPlanName" class="form-label">Plan Name</label>
                        <input type="text" class="form-control" name="Pname" id="editPlanName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editDescription" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editDurationim" class="form-label">Duration in Months</label>
                        <input type="number" class="form-control" id="editDurationim" name="durationim" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPrice" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="editPrice" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCoaches" class="form-label">Coaches</label>
                        <select class="form-select" id="editCoaches" name="coaches[]" multiple="multiple">
                            <?php foreach ($coaches as $coach): ?>
                                <option value="<?= esc($coach['CoachID']); ?>"><?= esc($coach['Firstname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editActive" class="form-label">Active</label>
                        <input type="checkbox" class="form-check-input" id="editActive" name="active" value="1">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    // Initialize DataTable
    new DataTable('#myTable', {
        responsive: true
    });

    // Initialize Select2 for modals
    $('#coaches').select2({
        dropdownParent: $('#addPlanModal'),
        placeholder: "Select coaches",
        width: '100%',
        allowClear: true
    });

    $('#editCoaches').select2({
        dropdownParent: $('#editPlanModal'),
        placeholder: "Select coaches",
        width: '100%',
        allowClear: true
    });

    // Handle Add Plan Form
    $('#addPlanForm').on('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    $.ajax({
        url: '<?= base_url('/gymplans/store'); ?>', // Make sure this matches the POST route
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
        },
        error: function (xhr) {
            console.error(xhr.responseText);
        }
    });
});

    // Handle Edit Plan Form
    $('#editPlanForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to update this plan?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('/gymplans/store'); ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        Swal.fire('Updated!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr) {
                        Swal.fire('Error!', 'Could not update plan.', 'error');
                    }
                });
            }
        });
    });
});

// Load plan into edit modal
async function editPlan(id) {
    try {
        const response = await $.get('<?= base_url('/gymplans/edit/'); ?>' + id);
        if (response.status === 'success') {
            const plan = response.data;
            $('#editPlanId').val(plan.PlanID);
            $('#editPlanName').val(plan.PlanName);
            $('#editDescription').val(plan.Description);
            $('#editDurationim').val(plan.Duration);
            $('#editPrice').val(plan.Price);
            $('#editActive').prop('checked', plan.IsActive == 1);
            $('#editCoaches').val(plan.coaches).trigger('change');
            $('#editPlanModal').modal('show');
        } else {
            Swal.fire('Error!', 'Failed to fetch plan details.', 'error');
        }
    } catch (error) {
        Swal.fire('Error!', 'Failed to fetch plan details.', 'error');
    }
}

// Delete plan
async function deletePlan(id) {
    const result = await Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    });

    if (result.isConfirmed) {
        try {
            await $.ajax({
                url: '<?= base_url('/gymplans/delete/'); ?>' + id,
                type: 'DELETE',
                success: function () {
                    Swal.fire('Deleted!', 'Plan has been deleted.', 'success').then(() => {
                        location.reload();
                    });
                },
                error: function () {
                    Swal.fire('Error!', 'Failed to delete plan.', 'error');
                }
            });
        } catch (error) {
            Swal.fire('Error!', 'Something went wrong.', 'error');
        }
    }
}
</script>

<?php $this->endSection(); ?>
