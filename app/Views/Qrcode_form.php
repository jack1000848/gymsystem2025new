<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate QR Code</title>
</head>
<body>
    <h1>Generate QR Code</h1>
    <?php if (session()->getFlashdata('error')): ?>
        <p style="color: red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>
    <form action="/qrcode/generate" method="post">
        <?= csrf_field() ?>
        <label for="data">Enter Data:</label>
        <input type="text" id="data" name="data" required>
        <label for="duration">Duration (in days):</label>
        <input type="number" id="duration" name="duration" required>
        <button type="submit">Generate</button>
    </form>
    <script>
    function makeReservation() {
     // const name = document.getElementById('name').value.trim();
      //const date = document.getElementById('date').value;
      const duration = parseInt(document.getElementById('duration').value);

      if (!name || !date || isNaN(duration)) {
        alert("Please fill in all fields with valid data.");
        return;
      }

      // Simulate reservation process (replace with your actual reservation logic)
      console.log(`Reservation made for ${name} on ${date} for ${duration} days.`);

      // Generate QR code with reservation details
      const reservationDetails = `${name}-${date}-${duration}`;
      const qr = new QRious({
        element: document.getElementById('qrCodeCanvas'),
        value: reservationDetails,
        size: 200,
        background: 'white',
        foreground: 'black',
      });

      alert(`Your reservation is confirmed! Show this QR code upon arrival.`);
    }
  </script>
</body>
</html>
