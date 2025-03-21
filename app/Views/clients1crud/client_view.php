<!-- app/Views/clients1crud/client_view.php -->
<?= $this->extend('layout/main'); ?>
<?= $this->section('body'); ?>

<div class="container">
    <h1>Client Information</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Client ID: <?= $client['CustomerID']; ?></h5>
            <p class="card-text">First Name: <?= $client['Firstname']; ?></p>
            <p class="card-text">Last Name: <?= $client['Lastname']; ?></p>
            <p class="card-text">Address: <?= $client['Address']; ?></p>
            <p class="card-text">Gender: <?= $client['Gender']; ?></p>
            <p class="card-text">Email: <?= $client['Email']; ?></p>
            <p class="card-text">Registered Date: <?= $client['RegisteredDate']; ?></p>
            <p class="card-text">Types of Workout: <?= $client['types_of_workout']; ?></p>
            <p class="card-text">Gym Time Slot: <?= $client['GymTimeSlot']; ?></p>
            <p class="card-text">Membership Plan: <?= $client['Membesrship_plan']; ?></p>
            <h5>QR Code:</h5>
            <img id="qrCodeImage" src="" alt="QR Code" style="width: 200px; ">
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script>
    // Function to generate QR Code
    function generateQRCode(clientId) {
        const qr = new QRious({
            element: document.getElementById('qrCodeImage'),
            value: `${clientId}`,
            size: 200,
            background: 'white',
            foreground: 'black',
        });
    }

    // Generate QR Code for the client
    window.onload = function () {
        generateQRCode(<?= $client['CustomerID']; ?>);
    };
</script>

<?= $this->endSection(); ?>