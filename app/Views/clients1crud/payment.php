<?php
$this->extend('layout/main');
$this->section('body');
?>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" integrity="sha384-J+U5u7zYZzM5x8z0pMw5x5l5e5v5e5z5z5y5z5z5z5z5z5z5z5z5z5z5z5z5z5z" crossorigin="anonymous" />

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" integrity="sha384-H+U5u7zYZzM5x8z0pMw5x5l5e5v5e5z5z5y5z5z5z5z5z5z5z5z5z5z5z5z5z5z" crossorigin="anonymous">

<!-- Integrated CSS -->
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

    /* Print-specific styles */
    @media print {
        body * {
            visibility: hidden;
        }
        .printable-report, .printable-report * {
            visibility: visible;
        }
        .printable-report {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>

<div class="p-2 row mb-3">
    <div class="col-12 mb-2">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentModal">Add Payment</button>
        <!-- Month Selector and Print Report Button -->
        <input type="month" id="monthSelector" class="form-control d-inline-block" style="width: 200px; margin-left: 10px;" value="<?= date('Y-m') ?>">
        <button class="btn btn-success" onclick="printMonthlyReport()" style="margin-left: 10px;">Print Monthly Report</button>
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
                        <td colspan="6" class="text-center">No payments found.</td>
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
                                    <button type="button" class="btn btn-primary" onclick="editPayment(<?= $payment['PaymentHistoryID'] ?>)">Edit</button>
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
                <form id="addPaymentForm">
                    <div class="mb-3">
                        <label for="CustomerID" class="form-label">Customer</label>
                        <select class="form-select" id="CustomerID" name="CustomerID" required>
                            <option value="">Select Customer</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= esc($customer['CustomerID']) ?>"><?= esc($customer['CustomerName']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="PaidAmount" class="form-label">Paid Amount</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="PaidAmount" name="PaidAmount" required>
                    </div>
                    <div class="mb-3">
                        <label for="PaidDate" class="form-label">Paid Date</label>
                        <input type="date" class="form-control" id="PaidDate" name="PaidDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="PlanID" class="form-label">Plan</label>
                        <select class="form-select" id="PlanID" name="PlanID" required>
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
                <form id="editPaymentForm">
                    <input type="hidden" name="PaymentHistoryID" id="editPaymentId">
                    <div class="mb-3">
                        <label for="editCustomerID" class="form-label">Customer</label>
                        <select class="form-select" id="editCustomerID" name="CustomerID" required>
                            <option value="">Select Customer</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= esc($customer['CustomerID']) ?>"><?= esc($customer['CustomerName']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editPaidAmount" class="form-label">Paid Amount</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="editPaidAmount" name="PaidAmount" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPaidDate" class="form-label">Paid Date</label>
                        <input type="date" class="form-control" id="editPaidDate" name="PaidDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPlanID" class="form-label">Plan</label>
                        <select class="form-select" id="editPlanID" name="PlanID" required>
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
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js" integrity="sha384-H+U5u7zYZzM5x8z0pMw5x5l5e5v5e5z5z5y5z5z5z5z5z5z5z5z5z5z5z5z5z5z" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" integrity="sha384-H+U5u7zYZzM5x8z0pMw5x5l5e5v5e5z5z5y5z5z5z5z5z5z5z5z5z5z5z5z5z5z" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js" integrity="sha384-H+U5u7zYZzM5x8z0pMw5x5l5e5v5e5z5z5y5z5z5z5z5z5z5z5z5z5z5z5z5z5z" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script>
$(document).ready(function () {
    // Initialize DataTable
    new DataTable('#paymentTable', {
        responsive: true
    });

    // Initialize Select2 for modals
    $('#CustomerID, #PlanID').select2({
        dropdownParent: $('#addPaymentModal'),
        placeholder: "Select an option",
        width: '100%',
        allowClear: true
    });

    $('#editCustomerID, #editPlanID').select2({
        dropdownParent: $('#editPaymentModal'),
        placeholder: "Select an option",
        width: '100%',
        allowClear: true
    });

    // Update price display when a plan is selected
    function updatePriceDisplay(selectElement, priceDisplayElement, amountInputElement) {
        $(selectElement).on('change', function () {
            const price = this.options[this.selectedIndex]?.getAttribute('data-price');
            if (price) {
                $(priceDisplayElement).text(`Plan Price: ₱${parseFloat(price).toFixed(2)}`);
                $(amountInputElement).val(parseFloat(price).toFixed(2));
            } else {
                $(priceDisplayElement).text('Plan Price: Select a plan to see the price.');
                $(amountInputElement).val('');
            }
        });
    }

    updatePriceDisplay('#PlanID', '#priceDisplay', '#PaidAmount');
    updatePriceDisplay('#editPlanID', '#editPriceDisplay', '#editPaidAmount');

    // Handle form submissions
    async function handleFormSubmit(formId, url, successMessage, modalId) {
        $(formId).on('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            const confirm = formId === '#editPaymentForm' ? await Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to update this payment?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, update it!'
            }) : { isConfirmed: true };

            if (confirm.isConfirmed) {
                try {
                    const response = await $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false
                    });

                    if (response.status === 'success') {
                        Swal.fire(successMessage, response.message, 'success').then(() => {
                            $(modalId).modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                } catch (error) {
                    Swal.fire('Error!', `Failed to ${successMessage.toLowerCase()} payment.`, 'error');
                }
            }
        });
    }

    handleFormSubmit('#addPaymentForm', '<?= base_url('/payment/add') ?>', 'Success!', '#addPaymentModal');
    handleFormSubmit('#editPaymentForm', '<?= base_url('/payment/update/') ?>' + $('#editPaymentId').val(), 'Updated!', '#editPaymentModal');

    // Reset forms when modals are closed
    $('.modal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        const selects = $(this).find('select');
        const priceDisplay = $(this).find('.price-display');
        selects.val(null).trigger('change');
        priceDisplay.text('Plan Price: Select a plan to see the price.');
    });
});

// Load payment into edit modal
async function editPayment(id) {
    try {
        const response = await $.get('<?= base_url('/payment/edit/') ?>' + id);
        if (response.status === 'success') {
            const payment = response.data;
            $('#editPaymentId').val(payment.PaymentHistoryID);
            $('#editCustomerID').val(payment.CustomerID).trigger('change');
            $('#editPaidAmount').val(payment.PaidAmount);
            $('#editPaidDate').val(payment.PaidDate);
            $('#editPlanID').val(payment.PlanID).trigger('change');
            $('#editPriceDisplay').text(`Plan Price: ₱${parseFloat(payment.PlanPrice).toFixed(2)}`);
            $('#editPaymentModal').modal('show');
        } else {
            Swal.fire('Error!', response.message, 'error');
        }
    } catch (error) {
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
            const response = await $.ajax({
                url: '<?= base_url('/payment/delete/') ?>' + id,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                }
            });

            if (response.status === 'success') {
                Swal.fire('Deleted!', response.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error!', response.message, 'error');
            }
        } catch (error) {
            Swal.fire('Error!', 'Failed to delete payment.', 'error');
        }
    }
}

// Print monthly payment report
async function printMonthlyReport() {
    const monthSelector = document.getElementById('monthSelector').value;
    if (!monthSelector) {
        Swal.fire('Error!', 'Please select a month to generate the report.', 'error');
        return;
    }

    const [year, month] = monthSelector.split('-');
    const monthYear = new Date(year, month - 1).toLocaleString('default', { month: 'long', year: 'numeric' });

    try {
        const response = await $.ajax({
            url: '<?= base_url('/payment/monthly/') ?>' + monthSelector,
            type: 'GET',
            data: { <?= csrf_token() ?>: '<?= csrf_hash() ?>' }
        });

        if (response.status !== 'success') {
            Swal.fire('Error!', response.message, 'error');
            return;
        }

        const payments = response.data;
        if (payments.length === 0) {
            Swal.fire('Info', `No payments found for ${monthYear}.`, 'info');
            return;
        }

        const totalAmount = payments.reduce((sum, payment) => sum + parseFloat(payment.PaidAmount), 0);
        const tableRows = payments.map(payment => `
            <tr>
                <td style="padding: 8px; border: 1px solid #ccc;">${payment.PaymentHistoryID}</td>
                <td style="padding: 8px; border: 1px solid #ccc;">${payment.CustomerName}</td>
                <td style="padding: 8px; border: 1px solid #ccc;">₱${parseFloat(payment.PaidAmount).toFixed(2)}</td>
                <td style="padding: 8px; border: 1px solid #ccc;">${payment.PaidDate}</td>
                <td style="padding: 8px; border: 1px solid #ccc;">${payment.PlanName}</td>
            </tr>
        `).join('');

        const reportContent = `
            <div class="printable-report" style="font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; border: 1px solid #ccc; border-radius: 8px;">
                <h2 style="text-align: center; color: #2c3e50;">Payment Report</h2>
                <h4 style="text-align: center; color: #3498db;">Payment Report for ${monthYear}</h4>
                <hr style="border: 0; border-top: 1px solid #ccc; margin: 20px 0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="padding: 8px; border: 1px solid #ccc; background-color: #f0f0f0;">Payment ID</th>
                            <th style="padding: 8px; border: 1px solid #ccc; background-color: #f0f0f0;">Customer</th>
                            <th style="padding: 8px; border: 1px solid #ccc; background-color: #f0f0f0;">Paid Amount</th>
                            <th style="padding: 8px; border: 1px solid #ccc; background-color: #f0f0f0;">Paid Date</th>
                            <th style="padding: 8px; border: 1px solid #ccc; background-color: #f0f0f0;">Plan</th>
                        </tr>
                    </thead>
                    <tbody>${tableRows}</tbody>
                </table>
                <hr style="border: 0; border-top: 1px solid #ccc; margin: 20px 0;">
                <p style="text-align: right; font-weight: bold; color: #2c3e50;">Total Amount: ₱${totalAmount.toFixed(2)}</p>
                <p style="text-align: center; font-size: 14px; color: #666;">Generated on: ${new Date().toLocaleString()}</p>
            </div>
        `;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
            <head>
                <title>Monthly Payment Report</title>
                <style>
                    @media print {
                        body { margin: 0; }
                        .printable-report { width: 100%; }
                    }
                </style>
            </head>
            <body>
                ${reportContent}
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(() => window.close(), 100);
                    };
                </script>
            </body>
            </html>
        `);
        printWindow.document.close();
    } catch (error) {
        Swal.fire('Error!', 'Failed to fetch monthly payments.', 'error');
    }
}
</script>

<?php $this->endSection(); ?>