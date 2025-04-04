<?= $this->extend('layout/mainclient') ?> <!-- Change if using a different layout -->
<?= $this->section('body') ?>

<h2>Account Settings</h2>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<form action="<?= base_url('update-account') ?>" method="post">
    <?= csrf_field() ?>
    
    <label for="name">First Name:</label>
    <input type="text" name="Firstname" value="<?= esc($user['Firstname']) ?>" required>

    <label for="name">Last Name:</label>
    <input type="text" name="Lastname" value="<?= esc($user['Lastname']) ?>" required>

    <label for="email">Email:</label>
    <input type="email" name="Email" value="<?= esc($user['Email']) ?>" required>

    <label for="password">New Password (Leave blank if not changing):</label>
    <input type="password" name="password">

    <button type="submit">Save Changes</button>
</form>

<?= $this->endSection() ?>
