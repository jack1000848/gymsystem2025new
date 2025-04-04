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

<style>
    /* Global Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f9;
        color: #333;
        padding: 20px;
    }

    h2 {
        font-size: 28px;
        color: #333;
        margin-bottom: 20px;
    }

    /* Alert Box */
    .alert {
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
    }

    .alert-success {
        background-color: #4CAF50;
        color: white;
    }

    .alert-danger {
        background-color: #f44336;
        color: white;
    }

    /* Form Styles */
    form {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 400px;
        margin: 0 auto;
    }

    label {
        display: block;
        margin: 10px 0 5px;
        font-weight: bold;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    button {
        background-color: #6c5ce7;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #4e38c4;
    }

    button:focus {
        outline: none;
    }
</style>

<?= $this->endSection() ?>
