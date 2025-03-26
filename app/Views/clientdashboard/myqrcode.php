<?php
    $this->extend('layout/mainclient');
    $this->section('body');
?>

<div class="card" style="text-align: center; padding: 20px;">
    <h3>My QR Code</h3>
    <img id="qrCodeImage" src="" alt="QR Code" style="width: 200px; display: block; margin: 0 auto;">
    <br>
    <a id="downloadQR" class="btn btn-primary" download="MyQRCode.png">Download QR Code</a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script>
    function generateQRCode(customerID) {
        if (!customerID) return;

        // Adjust size based on screen width
        let qrSize = window.innerWidth < 768 ? 300 : 200; // 300px for mobile, 200px for desktop

        const qr = new QRious({
            value: String(customerID), // Convert to string
            size: qrSize, // Adjust size dynamically
            background: 'white',
            foreground: 'black'
        });

        const qrImage = document.getElementById('qrCodeImage');
        qrImage.src = qr.toDataURL();

        // Enable QR Code download
        const downloadLink = document.getElementById('downloadQR');
        downloadLink.href = qr.toDataURL("image/png"); // Convert QR to PNG for download
    }

    // Fetch Customer ID from session (make sure session data is available in your controller)
    window.onload = function () {
        const customerID = <?= json_encode(session()->get('CustomerID')); ?>; // Get CustomerID from session
        generateQRCode(customerID);
    };
</script>

<?php $this->endSection(); ?>
