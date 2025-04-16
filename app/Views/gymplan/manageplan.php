<?php
$this->extend('layout/main');
$this->section('body');
?>

<div class="p-2 row mb-3">
    <div class="col-12 mb-2">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPlanModal">Add Plan</button>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
                <form id="addPlanForm">
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
                        <label for="exampleFormControlInput1" class="form-label">Coach</label>
                        <select class="form-select" id="coaches" name="coaches[]" multiple="multiple">
                        <?php foreach ($coaches as $coach): ?>
                            <option value="<?= esc($coach['CoachID']); ?>"><?= esc($coach['Firstname']); ?></option>
                        <?php endforeach; ?>
                    </select>
                        </div> 
                    </div>
                    <div class="mb-3">
                        <label for="active" class="form-label">Active</label>
                        <input type="checkbox" class="form-check-input" id="active" name="active" value="1" checked>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Plan</button>
                    </div>
                </form>
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    let table = new DataTable('#myTable', {
        responsive: true
    });

    // Initialize Select2 for coaches
    $('#coaches, #editCoaches').select2({
        placeholder: "Select coaches",
        allowClear: true
    });

    // Handle Add Plan Form Submission
    $('#addPlanForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to save this plan?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, save it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('/gymplans/store'); ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success'
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to save plan. Please try again.',
                            icon: 'error'
                        });
                        console.log(xhr.responseText);
                    }
                });
            }
        });
    });

    // Handle Edit Plan Form Submission
    $('#editPlanForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to update this plan?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('/gymplans/store'); ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success'
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to update plan. Please try again.',
                            icon: 'error'
                        });
                        console.log(xhr.responseText);
                    }
                });
            }
        });
    });
});

// Edit Plan
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

            // Populate coaches (assuming plan.coaches is an array of CoachIDs)
            $('#editCoaches').val(plan.coaches).trigger('change');

            $('#editPlanModal').modal('show');
        } else {
            Swal.fire({
                title: 'Error!',
                text: 'Failed to fetch plan details.',
                icon: 'error'
            });
        }
    } catch (error) {
        Swal.fire({
            title: 'Error!',
            text: 'Failed to fetch plan details.',
            icon: 'error'
        });
        console.log(error);
    }
}

// Delete Plan
async function deletePlan(id) {
    const result = await Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    });

    if (result.isConfirmed) {
        try {
            await $.ajax({
                url: '<?= base_url('/gymplans/delete/'); ?>' + id,
                type: 'DELETE',
                success: function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Plan deleted successfully.',
                        icon: 'success'
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to delete plan. Please try again.',
                        icon: 'error'
                    });
                    console.log(xhr.responseText);
                }
            });
        } catch (error) {
            Swal.fire({
                title: 'Error!',
                text: 'Failed to delete plan.',
                icon: 'error'
            });
            console.log(error);
        }
    }
}
</script>

<?php $this->endSection(); ?>