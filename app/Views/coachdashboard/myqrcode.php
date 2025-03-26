<div class="card">
    <h3>My QR Code</h3>
    <img id="qrCodeImage" src="" alt="QR Code" style="width: 200px;">
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script>
    function generateQRCode(coachID) {
        if (!coachID) return;

        const qr = new QRious({
            value: String(coachID), // Convert to string
            size: 200, // Adjust size for dashboard
            background: 'white',
            foreground: 'black'
        });

        document.getElementById('qrCodeImage').src = qr.toDataURL();
    }

    // Fetch Coach ID from session (make sure session data is available in your controller)
    window.onload = function () {
        const coachID = <?= json_encode(session()->get('CoachID')); ?>; // Get CoachID from session
        generateQRCode(coachID);
    };
</script>
