<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYM Master - Login</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/Joinus.css') ?>">
</head>
<body>
    <div id="container">
        Make 
        <div id="flip">
            <div><div>wOrK</div></div>
            <div><div>lifeStyle</div></div>
            <div><div>Everything</div></div>
        </div>
        AweSoMe!
    </div>
    <div class="background">
        <div class="login-box">
            <div class="logo">
                <img src="" alt="">
                <h1>Welcome to Login</h1>

            </div>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="error"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
    
            <form action="<?= site_url('/login/authenticate') ?>" method="post">
                <label for="email"></label>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="password"></label>
                <input type="password" name="password" id="password" placeholder="Password" required><br><br>
                <button type="submit">Login</button>
                <a href="<?= base_url('join-now') ?>" class="register">Signup</a>
                <a href="<?= base_url('forgot-password') ?>" class="register">Forgot Password?</a>
            </form>
        </div>
    </div>
</body>
</html>