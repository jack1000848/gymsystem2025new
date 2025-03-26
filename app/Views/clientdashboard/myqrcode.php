<?php
    $this ->extend('layout/mainclient');
    $this ->section('body');
?>

<div class="card">
    <h3>My QR Code</h3>
    <img id="qrCodeImage" src="" alt="QR Code" style="width: 200px;">
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

    document.getElementById('qrCodeImage').src = qr.toDataURL();
}

    // Fetch Coach ID from session (make sure session data is available in your controller)
    window.onload = function () {
        const customerID = <?= json_encode(session()->get('CustomerID')); ?>; // Get CoachID from session
        generateQRCode(customerID);
    };
</script>
<?php $this->endSection(); ?> 