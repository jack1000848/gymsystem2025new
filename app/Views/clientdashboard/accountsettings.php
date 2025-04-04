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
        font-family: 'Roboto', sans-serif;
        background: linear-gradient(135deg, #1e2a47, #4c6cb3);
        color: #e0e0e0;
        padding: 40px 20px;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    h2 {
        font-size: 36px;
        font-weight: 600;
        color: #a1c4e8;
        margin-bottom: 30px;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4);
    }

    /* Alert Box */
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
        font-weight: bold;
        text-align: center;
    }

    .alert-success {
        background-color: #0066cc;
        color: #ffffff;
        border: 2px solid #004d99;
    }

    .alert-danger {
        background-color: #e60000;
        color: #ffffff;
        border: 2px solid #990000;
    }

    /* Form Styles */
    form {
        background: rgba(0, 0, 0, 0.7);
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5);
        width: 400px;
        text-align: center;
        transition: all 0.3s ease;
    }

    form:hover {
        transform: scale(1.05);
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.6);
    }

    label {
        font-size: 14px;
        margin-bottom: 8px;
        color: #b4c7e7;
        display: block;
        text-align: left;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #333;
        border-radius: 8px;
        font-size: 16px;
        background: #222;
        color: #fff;
        transition: background-color 0.3s;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
        background: #333;
        border-color: #4c6cb3;
        outline: none;
    }

    button {
        background-color: #4c6cb3;
        color: #fff;
        padding: 15px 30px;
        font-size: 18px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #374a99;
    }

    button:active {
        background-color: #003f7d;
    }

    button:focus {
        outline: none;
    }
</style>

<?= $this->endSection() ?>
