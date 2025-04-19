<?php
$this->extend('layout/main');
$this->section('body');
?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css" rel="stylesheet" integrity="sha256-0e0e1tLh1z6tP2bW5qG3hIdM6b1f0qLqK0gG9N1j1E=" crossorigin="anonymous">

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" integrity="sha256-8WKr1tB7hH9yG2bQN4yJ6R4dEzS2v4G7n7i7y0iPWc=" crossorigin="anonymous" />

<!-- Scoped CSS -->
<style>
.payment-history {
    background-color: #f4f4f4 !important;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
}

.payment-history .btn-primary {
    background-color: #3498db !important;
    border: none !important;
    min-width: 100px !important;
    padding: 8px 16px !important;
    border-radius: 6px !important;
}

.payment-history .btn-primary:hover {
    background-color: #2980b9 !important;
}

.payment-history .btn-danger {
    background-color: #e74c3c !important;
    border: none !important;
    min-width: 100px !important;
    padding: 8px 16px !important;
    border-radius: 6px !important;
}

.payment-history .btn-danger:hover {
    background-color: #c0392b !important;
}

.payment-history .btn-success {
    background-color: #28a745 !important;
    border: none !important;
    min-width: 100px !important;
    padding: 8px 16px !important;
    border-radius: 6px !important;
}

.payment-history .btn-success:hover {
    background-color: #218838 !important;
}

.payment-history .modal-title {
    font-weight: bold !important;
    color: #2c3e50 !important;
}

.payment-history .modal-content {
    border-radius: 12px !important;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1) !important;
}

.payment-history .form-control,
.payment-history .select2-container--default .select2-selection--single {
    border-radius: 8px !important;
    font-size: 15px !important;
}

.payment-history table.dataTable {
    width: 100% !important;
    margin: 0 auto !important;
    background-color: #ffffff !important;
    border-radius: 12px !important;
    overflow: hidden !important;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
}

.payment-history table.dataTable thead th {
    background-color: #3498db !important;
    color: white !important;
    font-size: 16px !important;
    padding: 12px !important;
    text-transform: uppercase !important;
    text-align: center !important;
}

.payment-history table.dataTable tbody td {
    font-size: 15px !important;
    color: #2c3e50 !important;
    text-align: center !important;
    padding: 10px !important;
}

.payment-history table.dataTable tbody tr:hover {
    background-color: #ecf0f1 !important;
}

.payment-history .dataTables_wrapper .dataTables_filter input {
    border-radius: 6px !important;
    padding: 6px !important;
    border: 1px solid #ccc !important;
    font-size: 14px !important;
}

.payment-history .alert {
    border-radius: 10px !important;
    padding: 12px !important;
    font-size: 15px !important;
}

.payment-history .modal-footer button {
    min-width: 100px !important;
}

.payment-history .btn-close {
    outline: none !important;
}

.payment-history .select2-container {
    width: 100% !important;
}

.payment-history .price-display {
    margin: 10px 0 !important;
    color: #2c3e50 !important;
    font-weight: bold !important;
    font-size: 15px !important;
}

.payment-history .empty-message {
    text-align: center !important;
    padding: 20px !important;
    color: #666 !important;
    font-style: italic !important;
}
</style>

<div class="payment-history p-2 row mb-3">
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
        <table id="paymentTable" class="display table table-bordered">
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
<div class="modal fade payment-history" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addPaymentModalLabel">Add Payment</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addPaymentForm" action="<?= base_url('/payment/add') ?>" method="POST" onsubmit="return false;">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
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
<div class="modal fade payment-history" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editPaymentModalLabel">Edit Payment</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPaymentForm" action="<?= base_url('/payment/update') ?>" method="POST" onsubmit="return false;">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js" integrity="sha256-XHDO7HHEcH6Ay3uQ7ZlcT6lK5xKx5xWORf3oQTP/r9o=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js" integrity="sha256-/F0e1tLh1z6tP2bW5qG3hIdM6b1f0qLqK0gG9N1j1E=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" integrity="sha256-8WKr1tB7hH9yG2bQN4yJ6R4dEzS2v4G7n7i7y0iPWc=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" integrity="sha256-2Zf3Zo9oQWVZ41s+/31X3uXXjG5pD5kB1cEHsT3j0do=" crossorigin="anonymous"></script>

<!-- Fallbacks -->
<script>
    if (typeof jQuery === 'undefined') {
        console.error('jQuery not loaded from CDN, loading fallback');
        document.write('<script src="<?= base_url('/js/jquery-3.7.1.min.js') ?>"><\/script>');
    } else {
        console.log('jQuery loaded successfully');
    }
</script>
<script>
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap not loaded from CDN, loading fallback');
        document.write('<script src="<?= base_url('/js/bootstrap.bundle.min.js') ?>"><\/script>');
    } else {
        console.log('Bootstrap loaded successfully');
    }
</script>
<script>
    if (typeof DataTable === 'undefined') {
        console.error('DataTables not loaded from CDN, loading fallback');
        document.write('<script src="<?= base_url('/js/dataTables.min.js') ?>"><\/script>');
        document.write('<script src="<?= base_url('/js/dataTables.bootstrap5.min.js') ?>"><\/script>');
    } else {
        console.log('DataTables loaded successfully');
    }
</script>
<script>
    if (typeof $.fn.select2 === 'undefined') {
        console.error('Select2 not loaded from CDN, loading fallback');
        document.write('<script src="<?= base_url('/js/select2.min.js') ?>"><\/script>');
    } else {
        console.log('Select2 loaded successfully');
    }
</script>
<script>
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 not loaded from CDN, loading fallback');
        document.write('<script src="<?= base_url('/js/sweetalert2.min.js') ?>"><\/script>');
    } else {
        console.log('SweetAlert2 loaded successfully');
    }
</script>

<script>
$(document).ready(function () {
    console.log('Document ready');

    // Initialize DataTable
    try {
        new DataTable('#paymentTable', {
            responsive: true,
            searching: true,
            ordering: true,
            paging: true
        });
        console.log('DataTable initialized');
    } catch (e) {
        console.error('DataTable initialization failed:', e.message);
    }

    // Initialize Select2
    try {
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
        console.log('Select2 initialized');
    } catch (e) {
        console.error('Select2 initialization failed:', e.message);
    }

    // Update price display on plan selection
    try {
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
        console.log('Price display handler attached');
    } catch (e) {
        console.error('Price display handler failed:', e.message);
    }

    // Handle Add Payment Form
    try {
        $('#addPaymentForm').on('submit', function (e) {
            e.preventDefault();
            console.log('Add payment form submitted');
            const formData = new FormData(this);

            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            $.ajax({
                url: '<?= base_url('/payment/add') ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    console.log('Sending AJAX request to:', '<?= base_url('/payment/add') ?>');
                },
                success: function (response) {
                    console.log('AJAX success:', response);
                    try {
                        if (response.status === 'success') {
                            Swal.fire('Success!', response.message, 'success').then(() => {
                                $('#addPaymentModal').modal('hide');
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error!', response.message || 'Failed to add payment.', 'error');
                        }
                    } catch (e) {
                        console.error('Success handler error:', e.message);
                        Swal.fire('Error!', 'Invalid server response.', 'error');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', xhr.status, xhr.responseText, status, error);
                    Swal.fire('Error!', 'Failed to add payment: ' + (xhr.responseText || error), 'error');
                }
            });
        });
        console.log('Add payment form handler attached');
    } catch (e) {
        console.error('Add payment form handler failed:', e.message);
    }

    // Handle Edit Payment Form
    try {
        $('#editPaymentForm').on('submit', function (e) {
            e.preventDefault();
            console.log('Edit payment form submitted');
            const formData = new FormData(this);

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
                            try {
                                if (response.status === 'success') {
                                    Swal.fire('Updated!', response.message, 'success').then(() => {
                                        $('#editPaymentModal').modal('hide');
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire('Error!', response.message || 'Failed to update payment.', 'error');
                                }
                            } catch (e) {
                                console.error('Update success handler error:', e.message);
                                Swal.fire('Error!', 'Invalid server response.', 'error');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Update error:', xhr.status, xhr.responseText, status, error);
                            Swal.fire('Error!', 'Could not update payment: ' + (xhr.responseText || error), 'error');
                        }
                    });
                }
            });
        });
        console.log('Edit payment form handler attached');
    } catch (e) {
        console.error('Edit payment form handler failed:', e.message);
    }

    // Reset forms on modal close
    try {
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
        console.log('Modal reset handlers attached');
    } catch (e) {
        console.error('Modal reset handlers failed:', e.message);
    }
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
        console.error('Edit error:', error.message);
        Swal.fire('Error!', 'Failed to fetch payment details.', 'error');
    }
}

// Delete payment
async function deletePayment(id) {
    try {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        });

        if (result.isConfirmed) {
            console.log('Deleting payment:', id);
            await $.ajax({
                url: '<?= base_url('/payment/delete/') ?>' + id,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    [<?= json_encode(csrf_token()) ?>]: <?= json_encode(csrf_hash()) ?>
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
                    console.error('Delete error:', xhr.status, xhr.responseText, status, error);
                    Swal.fire('Error!', 'Failed to delete payment.', 'error');
                }
            });
        }
    } catch (error) {
        console.error('Delete error:', error.message);
        Swal.fire('Error!', 'Something went wrong.', 'error');
    }
}
</script>

<?php $this->endSection(); ?>