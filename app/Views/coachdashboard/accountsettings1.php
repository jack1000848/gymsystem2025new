<?= $this->extend('layout/maincoach') ?> <!-- Change if using a different layout -->
<?= $this->section('body') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h2>Account Settings</h2>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<form action="<?= base_url('update-account1') ?>" method="post">
    <?= csrf_field() ?>
    
    <label for="name">First Name:</label>
    <br><input type="text" name="firstname" value="<?= esc($user['Firstname']) ?>" required></br>

    <label for="name">Last Name:</label>
    <input type="text" name="lastname" value="<?= esc($user['Lastname']) ?>" required>

    <label for="email">Email:</label>
    <input type="email" name="email" value="<?= esc($user['Email']) ?>" required>

    <label for="password">New Password (Leave blank if not changing):</label>
    <input type="password" name="password">

    <button type="submit">Save Changes</button>
</form>

</body>
</html>


<?= $this->endSection() ?>
