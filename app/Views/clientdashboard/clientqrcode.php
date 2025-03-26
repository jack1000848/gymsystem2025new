<?= $this->extend('layout/main'); ?>
<?= $this->section('body'); 
?>

<td><img id="qrCodeImage<?= $client['CustomerID']; ?>" src="" alt="QR Code" style="width: 100px;"></td>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script>
    // Function to generate QR Code
    function generateQRCode(clientId) {
        if (!clientId) return; // Avoid errors if clientId is undefined

        const qr = new QRious({
            value: String(clientId), // Convert to string for safety
            size: 100, // Adjust the size if needed
            background: 'white',
            foreground: 'black'
        });

        const qrImageElement = document.getElementById('qrCodeImage' + clientId);
        if (qrImageElement) {
            qrImageElement.src = qr.toDataURL(); // Set QR code as image source
            console.log("QR Code generated for:", clientId);
        } else {
            console.error("QR Image element not found for:", clientId);
        }
    }

    // Generate QR Codes for all clients on page load
    window.onload = function () {
        <?php foreach ($clients1 as $client) : ?>
            generateQRCode(<?= json_encode($client['CustomerID']); ?>);
        <?php endforeach; ?>
    };
</script>


<?= $this->endSection(); ?>