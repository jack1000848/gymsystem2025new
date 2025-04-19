<?php
$this->extend('layout/main');
$this->section('body');
?>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Integrated CSS from Gym Plans design -->
<style>
    body {
        background-color: #f4f4f4;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .btn-primary {
        background-color: #3498db;
        border: none;
        min-width: 100px;
    }

    .btn-primary:hover {
        background-color: #2980b9;
    }

    .btn-danger {
        background-color: #e74c3c;
        border: none;
        min-width: 100px;
    }

    .btn-danger:hover {
        background-color: #c0392b;
    }

    .btn-success {
        background-color: #28a745;
        border: none;
        min-width: 100px;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    h1.modal-title {
        font-weight: bold;
        color: #2c3e50;
    }

    .modal-content {
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }

    .form-control, .select2-container--default .select2-selection--single {
        border-radius: 8px;
        font-size: 15px;
    }

    table.dataTable {
        width: 100% !important;
        margin: 0 auto;
        background-color: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    table.dataTable thead th {
        background-color: #3498db;
        color: white;
        font-size: 16px;
        padding: 12px;
        text-transform: uppercase;
        text-align: center;
    }

    table.dataTable tbody td {
        font-size: 15px;
        color: #2c3e50;
        text-align: center;
        padding: 10px;
    }

    table.dataTable tbody tr:hover {
        background-color: #ecf0f1;
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 6px;
        padding: 6px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    .alert {
        border-radius: 10px;
        padding: 12px;
        font-size: 15px;
    }

    .modal-footer button {
        min-width: 100px;
    }

    .btn-close {
        outline: none;
    }

    .select2-container {
        width: 100% !important;
    }

    .price-display {
        margin: 10px 0;
        color: #2c3e50;
        font-weight: bold;
        font-size: 15px;
    }

    .empty-message {
        text-align: center;
        padding: 20px;
        color: #666;
        font-style: italic;
    }
</style>

<div class="p-2 row mb-3">
    <div class="col-12 mb-2">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentModal">Add Payment</button>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="col-12">
        <table id="paymentTable" class="display">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Customer</th>
                    <th>Paid Amount</th>
                    <th>Paid Date</th>
                    <th>Plan</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($payments)): ?>
                    <tr>
                        <td colspan="6" class="empty-message">No payments found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?= esc($payment['PaymentHistoryID']) ?></td>
                            <td><?= esc($payment['CustomerName']) ?></td>
                            <td>₱<?= number_format($payment['PaidAmount'], 2) ?></td>
                            <td><?= esc($payment['PaidDate']) ?></td>
                            <td><?= esc($payment['PlanName']) ?></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success" onclick="editPayment(<?= $payment['PaymentHistoryID'] ?>)">Edit</button>
                                    <button type="button" class="btn btn-danger" onclick="deletePayment(<?= $payment['PaymentHistoryID'] ?>)">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addPaymentModalLabel">Add Payment</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addPaymentForm" action="<?= base_url('/payment/add') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="customerId" class="form-label">Customer</label>
                        <select class="form-select" id="customerId" name="CustomerID" required>
                            <option value="">Select Customer</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= esc($customer['CustomerID']) ?>"><?= esc($customer['CustomerName']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="paidAmount" class="form-label">Paid Amount</label>
                        <input type="number" step="0.01" class="form-control" id="paidAmount" name="PaidAmount" required>
                    </div>
                    <div class="mb-3">
                        <label for="paidDate" class="form-label">Paid Date</label>
                        <input type="date" class="form-control" id="paidDate" name="PaidDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="planId" class="form-label">Plan</label>
                        <select class="form-select" id="planId" name="PlanID" required>
                            <option value="">Select Plan</option>
                            <?php foreach ($plans as $plan): ?>
                                <option value="<?= esc($plan['PlanID']) ?>" data-price="<?= esc($plan['Price']) ?>">
                                    <?= esc($plan['PlanName']) . ' - ₱' . number_format($plan['Price'], 2) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="price-display" id="priceDisplay">Plan Price: Select a plan to see the price.</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Payment Modal -->
<div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editPaymentModalLabel">Edit Payment</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPaymentForm" action="<?= base_url('/payment/update') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="PaymentHistoryID" id="editPaymentId">
                    <div class="mb-3">
                        <label for="editCustomerId" class="form-label">Customer</label>
                        <select class="form-select" id="editCustomerId" name="CustomerID" required>
                            <option value="">Select Customer</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= esc($customer['CustomerID']) ?>"><?= esc($customer['CustomerName']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editPaidAmount" class="form-label">Paid Amount</label>
                        <input type="number" step="0.01" class="form-control" id="editPaidAmount" name="PaidAmount" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPaidDate" class="form-label">Paid Date</label>
                        <input type="date" class="form-control" id="editPaidDate" name="PaidDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPlanId" class="form-label">Plan</label>
                        <select class="form-select" id="editPlanId" name="PlanID" required>
                            <option value="">Select Plan</option>
                            <?php foreach ($plans as $plan): ?>
                                <option value="<?= esc($plan['PlanID']) ?>" data-price="<?= esc($plan['Price']) ?>">
                                    <?= esc($plan['PlanName']) . ' - ₱' . number_format($plan['Price'], 2) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="price-display" id="editPriceDisplay">Plan Price: Select a plan to see the price.</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Payment</button>
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
    console.log('jQuery loaded and document ready');

    // Initialize DataTable
    new DataTable('#paymentTable', {
        responsive: true
    });

    // Initialize Select2 for modals
    $('#customerId, #planId').select2({
        dropdownParent: $('#addPaymentModal'),
        placeholder: "Select an option",
        width: '100%',
        allowClear: true
    });

    $('#editCustomerId, #editPlanId').select2({
        dropdownParent: $('#editPaymentModal'),
        placeholder: "Select an option",
        width: '100%',
        allowClear: true
    });

    // Update price display on plan selection
    function updatePriceDisplay(selectElement, displayElement) {
        $(selectElement).on('change', function () {
            console.log('Plan selected:', $(this).val());
            const price = $(this).find(':selected').data('price');
            if (price) {
                $(displayElement).text(`Plan Price: ₱${parseFloat(price).toFixed(2)}`);
                const amountInput = $(this).closest('form').find('input[name="PaidAmount"]');
                amountInput.val(parseFloat(price).toFixed(2));
            } else {
                $(displayElement).text('Plan Price: Select a plan to see the price.');
                $(this).closest('form').find('input[name="PaidAmount"]').val('');
            }
        });
    }

    updatePriceDisplay('#planId', '#priceDisplay');
    updatePriceDisplay('#editPlanId', '#editPriceDisplay');

    // Handle Add Payment Form
    $('#addPaymentForm').on('submit', function (e) {
        e.preventDefault();
        console.log('Add payment form submitted');
        const formData = new FormData(this);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        // Log form data for debugging
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        $.ajax({
            url: '<?= base_url('/payment/add') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log('AJAX success:', response);
                if (response.status === 'success') {
                    Swal.fire('Success!', response.message, 'success').then(() => {
                        $('#addPaymentModal').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire('Error!', response.message || 'Failed to add payment.', 'error');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', xhr, status, error);
                Swal.fire('Error!', 'Failed to add payment: ' + (xhr.responseText || error), 'error');
            }
        });
    });

    // Handle Edit Payment Form
    $('#editPaymentForm').on('submit', function (e) {
        e.preventDefault();
        console.log('Edit payment form submitted');
        const formData = new FormData(this);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to update this payment?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('/payment/update/') ?>' + $('#editPaymentId').val(),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log('Update success:', response);
                        if (response.status === 'success') {
                            Swal.fire('Updated!', response.message, 'success').then(() => {
                                $('#editPaymentModal').modal('hide');
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error!', response.message || 'Failed to update payment.', 'error');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Update error:', xhr, status, error);
                        Swal.fire('Error!', 'Could not update payment: ' + (xhr.responseText || error), 'error');
                    }
                });
            }
        });
    });

    // Reset forms on modal close
    $('#addPaymentModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('#customerId, #planId').val(null).trigger('change');
        $('#priceDisplay').text('Plan Price: Select a plan to see the price.');
    });

    $('#editPaymentModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('#editCustomerId, #editPlanId').val(null).trigger('change');
        $('#editPriceDisplay').text('Plan Price: Select a plan to see the price.');
    });
});

// Load payment into edit modal
async function editPayment(id) {
    try {
        console.log('Fetching payment:', id);
        const response = await $.get('<?= base_url('/payment/edit/') ?>' + id);
        console.log('Edit response:', response);
        if (response.status === 'success') {
            const payment = response.data;
            $('#editPaymentId').val(payment.PaymentHistoryID);
            $('#editCustomerId').val(payment.CustomerID).trigger('change');
            $('#editPaidAmount').val(parseFloat(payment.PaidAmount).toFixed(2));
            $('#editPaidDate').val(payment.PaidDate);
            $('#editPlanId').val(payment.PlanID).trigger('change');
            $('#editPriceDisplay').text(`Plan Price: ₱${parseFloat(payment.PlanPrice).toFixed(2)}`);
            $('#editPaymentModal').modal('show');
        } else {
            Swal.fire('Error!', response.message, 'error');
        }
    } catch (error) {
        console.error('Edit error:', error);
        Swal.fire('Error!', 'Failed to fetch payment details.', 'error');
    }
}

// Delete payment
async function deletePayment(id) {
    const result = await Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    });

    if (result.isConfirmed) {
        try {
            console.log('Deleting payment:', id);
            await $.ajax({
                url: '<?= base_url('/payment/delete/') ?>' + id,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>
                },
                success: function (response) {
                    console.log('Delete success:', response);
                    if (response.status === 'success') {
                        Swal.fire('Deleted!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Delete error:', xhr, status, error);
                    Swal.fire('Error!', 'Failed to delete payment.', 'error');
                }
            });
        } catch (error) {
            console.error('Delete error:', error);
            Swal.fire('Error!', 'Something went wrong.', 'error');
        }
    }
}
</script>

<?php $this->endSection(); ?>