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
    <select id="userType" class="form-select" onchange="redirectToPage()">
        <option value="client">Client</option>
        <option value="coach">Coach</option>
    </select>
</div>
                <div class="card-body text-center">
                    <!-- Scanned User Info (Initially Hidden) -->
                    <div id="showInfo" class="alert alert-success">
                        
                        <p><strong>Status:</strong> <span id="status">-</span></p>
                        <p><strong>User ID:</strong> <span id="userId">-</span></p>
                        <p><strong>Full Name:</strong> <span id="fullName">-</span></p>
                        <p><strong>Expiration Date:</strong> <span id="expirationDate">-</span></p>
                        <p><strong>Your Plan:</strong> <span id="planId">-</span></p>
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

        $.ajax({
            url: "<?= base_url('/scan-qr/save/'); ?>" + decodedText,
            type: "POST",
            success: function(response) {
                console.log("Response from server:", response);
                const customer = response.customer;
                $("#status").text(response.status || "N/A");
                $("#loadingSpinner").hide();
                $("#showInfo").show();

                $("#status").text(response.status || "N/A");
                $("#userId").text(customer.CustomerID || "N/A");
                $("#fullName").text(customer.CustomerName || "N/A");
                $("#expirationDate").text(customer.ExpirationDate || "N/A");
                $("#planId").text(customer.Membership_plan || "N/A");

                // Show a simple message in the console
                if (response.status === 'check-in') {
                    console.log('Checked In Successfully!');
                } else if (response.status === 'check-out') {
                    console.log('Checked Out Successfully!');
                }

                setTimeout(() => {
                    reset();
                }, 10000); // Reset scanner after 10 seconds
            },
            error: function(xhr) {
                $("#loadingSpinner").hide();

                let errorMsg = "Failed to process QR Code.";
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                }

                // Show the error inside the showInfo div
                $("#showInfo").removeClass('alert-success').addClass('alert-danger').show().html(`
                    <p><strong>Error:</strong> ${errorMsg}</p>
                `);

                console.error(errorMsg);

                setTimeout(() => {
                    reset();
                }, 5000); // Reset scanner after 5 seconds on error
            }
        });
    }

    function onScanFailure(error) {
        // Optional: You can log scan failures silently
    }

    function reset() {
        $("#showInfo").hide().removeClass('alert-danger').addClass('alert-success');

        // Restart the scanner
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

    function redirectToPage() {
        let userType = document.getElementById("userType").value;

        if (userType === "client") {
            window.location.href = "scan-qr"; // Change to your actual client view
        } else if (userType === "coach") {
            window.location.href = "coachattendanceqr"; // Change to your actual coach view
        }
    }
</script>


</body>
</html>
<?php $this->endSection(); ?>
