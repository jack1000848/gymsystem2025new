<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resend Verification Email</title>
</head>
<body>
    <?php if(session()->getFlashdata('error')): ?>
        <div style="color: red;">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    
    <?php if(session()->getFlashdata('success')): ?>
        <div style="color: green;">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('resend-verification') ?>" method="post">
        <?= csrf_field() ?>
        <label for="clients1Emailaddress">Enter your email to resend verification:</label>
        <input type="email" name="clients1Emailaddress" required>
        <button type="submit">Resend Verification Email</button>
    </form>
</body>
</html>