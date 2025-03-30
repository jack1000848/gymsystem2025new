<?php
$this->extend('layout/main'); // Extend the main layout
$this->section('body'); // Start the body section
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCAN YOUR QR CODE </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #showInfo { display: none; font-size: 1.5rem; } 
        #loadingSpinner { display: none; } 
        .card { max-width: 600px; margin: auto; }
        .card-header, .card-footer { font-size: 1.5rem; }
        .alert { font-size: 1.2rem; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg p-4">
                <div class="card-header bg-primary text-white text-center">
                    <h2>Tap Your Own QR Code!</h2>
                </div>

                 <!-- Role Selection -->
                 <div class="mb-3">
                        <label for="userType" class="form-label"><strong>Select Role:</strong></label>
                        <select id="userType" class="form-select">
                            <option value="client">Client</option>
                            <option value="coach">Coach</option>
                        </select>
                    </div>

                <div class="card-body text-center">
                    <!-- Scanned User Info (Initially Hidden) -->
                    <div id="showInfo" class="alert alert-success">
                        <p><strong>User ID:</strong> <span id="userId">-</span></p>
                        <p><strong>Full Name:</strong> <span id="fullName">-</span></p>
                        <p><strong>Expiration Date:</strong> <span id="expirationDate">-</span></p>
                    </div>

                    <!-- Loader (Hidden Initially) -->
                    <div id="loadingSpinner" class="text-center my-3">
                        <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status"></div>
                        <p class="mt-3" style="font-size: 1.2rem;">Processing...</p>
                    </div>

                    <!-- QR Scanner -->
                    <div id="reader" style="width: 100%; max-width: 400px; margin: auto;"></div>
                </div>
                <div class="card-footer text-center">
                    <small class="text-muted" style="font-size: 1.2rem;">Align the QR code properly for scanning.</small>
                </div>
            </div>
        </div>
    </div>
</div>
    

<script>
    let html5QrCode = new Html5Qrcode("reader");

function onScanSuccess(decodedText, decodedResult) {
    console.log("Scanned QR Code:", decodedText);

    $("#loadingSpinner").show();

    // Stop the scanner to prevent multiple scans
    html5QrCode.stop().then(() => {
        console.log("QR Scanner stopped.");
    }).catch(err => {
        console.error("Failed to stop scanner:", err);
    });

    let userType = $("#userType").val(); // Get selected user type (client/coach)

    $.ajax({
        url: "<?= base_url('/scan-qr/save/'); ?>" + decodedText,
        type: "POST",
        data: { userType: userType }, // Send user type to backend
        success: function(response) {
            console.log("Response from server:", response);
            const user = response.user;

            $("#loadingSpinner").hide();
            $("#showInfo").show();

            $("#userId").text(user.UserID || "N/A");
            $("#fullName").text(user.FullName || "N/A");
            $("#expirationDate").text(user.ExpirationDate || "N/A");

            if (response.status === 'check-in') {
                console.log(userType.charAt(0).toUpperCase() + userType.slice(1) + ' Checked In Successfully!');
            } else if (response.status === 'check-out') {
                console.log(userType.charAt(0).toUpperCase() + userType.slice(1) + ' Checked Out Successfully!');
            }

            setTimeout(() => {
                reset();
            }, 10000);
        },
        error: function(xhr) {
            $("#loadingSpinner").hide();

            let errorMsg = "Failed to process QR Code.";
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMsg = xhr.responseJSON.error;
            }

            $("#showInfo").removeClass('alert-success').addClass('alert-danger').show().html(`
                <p><strong>Error:</strong> ${errorMsg}</p>
            `);

            console.error(errorMsg);

            setTimeout(() => {
                reset();
            }, 5000);
        }
    });
}

function onScanFailure(error) {
    // Optional: Log scan failures silently
}

function reset() {
    $("#showInfo").hide().removeClass('alert-danger').addClass('alert-success');

    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: 300 },
        onScanSuccess,
        onScanFailure
    ).catch(err => {
        console.error("Failed to restart scanner:", err);
    });
}

// Start the scanner on page load
html5QrCode.start(
    { facingMode: "environment" },
    { fps: 10, qrbox: 300 },
    onScanSuccess,
    onScanFailure
);
</script>


</body>
</html>
<?php $this->endSection(); ?>
