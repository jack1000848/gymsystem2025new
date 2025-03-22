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
                    <h2>Please Tap Your QR Code</h2>
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

        // Stop the scanner
        html5QrCode.stop().then(() => {
            console.log("QR Scanner stopped.");
        }).catch(err => {
            console.error("Failed to stop scanner:", err);
        });

        // Send data to backend
        $.ajax({
             url: "<?= base_url('/scan-qr/save/') ?>" + customerId,
                 method: "POST",
                 dataType: "json",
                 success: function (response) {
                    console.log(response);

                
                // Hide loader & show scanned user info
                $("#loadingSpinner").hide();
                $("#showInfo").show();

                // Populate scanned info (assuming response has user data)
                $("#userId").text(customer.CustomerID || "N/A");
                $("#fullName").text(customer.CustomerName || "N/A");
                $("#expirationDate").text(customer.ExpirationDate || "N/A");
                setTimeout(() => {
                    reset();

        }, 10000);
            },
            error: function(error) {
                console.error("Error saving QR Code:", error);
                $("#loadingSpinner").hide();
                alert("Failed to process QR Code.");
                reset();
            }
        });
    }

    function onScanFailure(error) {
       
    }

    function reset(){
        $("#showInfo").hide();

            // Restart QR Scanner
            html5QrCode.start(
                { facingMode: "environment" },  // Use back camera
                { fps: 10, qrbox: 300 },
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                console.error("Failed to restart scanner:", err);
            });
    }

    // Start QR Scanner
    html5QrCode.start(
        { facingMode: "environment" }, // Use back camera
        { fps: 10, qrbox: 300 },
        onScanSuccess,
        onScanFailure
    );
</script>

</body>
</html>
<?php $this->endSection(); ?>

