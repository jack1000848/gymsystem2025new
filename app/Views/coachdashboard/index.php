<?php
    $this ->extend('layout/maincoach');
    $this ->section('body');

    ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mission and Vision</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f7fa;
      color: #333;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 800px;
      margin: 50px auto;
      background: #fff;
      padding: 40px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      border-radius: 12px;
    }

    h1 {
      text-align: center;
      margin-bottom: 40px;
      color: #504A4A;
    }

    .section {
      margin-bottom: 30px;
    }

    .section h2 {
      color: #504A4A;
      margin-bottom: 10px;
    }

    .section p {
      font-size: 1.1em;
      line-height: 1.6;
    }

    @media (max-width: 600px) {
      .container {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <h1>Our Mission & Vision</h1>

    <div class="section">
      <h2>Mission</h2>
      <p>
        To revolutionize gym management by providing a smart, secure, and seamless platform that enhances member experience through QR code-based access, real-time performance tracking, and personalized fitness coaching integration.
      </p>
    </div>

    <div class="section">
      <h2>Vision</h2>
      <p>
        To become the leading digital fitness ecosystem that empowers gyms and fitness enthusiasts with cutting-edge technology, fostering healthier lifestyles through innovation, convenience, and data-driven results.
      </p>
    </div>
  </div>
  <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Dashboard - Gym Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Gym Management System</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="<?= base_url('coach/logout') ?>">Logout</a>
            </div>
        </div>
    </nav>

    <?= $this->renderSection('body') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
  
</body>
</html>

<?php $this->endSection(); ?> 