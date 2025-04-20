<?= $this->extend('layout/maincoach') ?> <!-- Change if using a different layout -->
<?= $this->section('body') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            
        }

        h2 {
            color: #2f3640;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            max-width: 500px;
            margin: auto;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #353b48;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #dcdde1;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            margin-top: 20px;
            padding: 12px 20px;
            background-color: rgb(5, 146, 240);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        button[type="submit"]:hover {
            background-color:rgb(5, 146, 240);
        }

        .alert {
            max-width: 500px;
            margin: 10px auto;
            padding: 15px;
            border-radius: 5px;
            font-weight: bold;
        }

        .alert-success {
            background-color: #dff9fb;
            color: #22a6b3;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #c23616;
        }
    </style>
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
resend-verification

<?= $this->endSection() ?>


Marvin123!