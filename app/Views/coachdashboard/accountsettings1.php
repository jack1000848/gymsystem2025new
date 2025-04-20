<?= $this->extend('layout/maincoach') ?> <!-- Change if using a different layout -->
<?= $this->section('body') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #212529;
            margin: 0;
            padding: 0;
        }

        /* Sidebar styles */
        .list-group {
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .list-group-item {
            background-color: transparent;
            border: none;
            color: #212529;
            padding: 15px 20px;
            transition: background 0.3s ease;
        }

        .list-group-item.active,
        .list-group-item:hover {
            background-color: #0CA6F7;
            color: #fff;
        }

        /* Card styling for profile */
        .card {
            background-color: #ffffff;
            color: #212529;
            border: none;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .card h4 {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card p {
            margin-bottom: 0;
            font-size: 16px;
        }

        .card i {
            margin-right: 8px;
            color: #0CA6F7;
        }

        /* Horizontal divider */
        hr.bg-secondary {
            border-top: 1px solid #dee2e6;
        }

        /* Responsive fix */
        @media (max-width: 768px) {
            .list-group-item {
                padding: 12px 16px;
                font-size: 15px;
            }

            .card {
                padding: 20px;
            }
        }

        .container {
            display: flex;
            justify-content: center;
        }

        .row {
            width: 100%;
            max-width: 900px;
        }

        .form-control[readonly] {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

        .btn-save {
            background-color: #0CA6F7;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
        }

        .btn-save:hover {
            background-color: #0990d6;
        }
    </style>
</head>
<body>
    <div class="container mt-5 d-flex justify-content-center">
        <div class="row w-100 justify-content-center" style="max-width: 900px;">
            <!-- Sidebar -->
            <div class="col-md-4 mb-4">
                <div class="list-group bg-white rounded shadow">
                    <a href="#" class="list-group-item list-group-item-action active">
                        <i class="bi bi-person"></i> My Profile
                    </a>
                    <a href="change Password.html" class="list-group-item list-group-item-action">
                        <i class="bi bi-lock"></i> Change Password
                    </a>
                </div>
            </div>
                            <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
            <!-- Profile Info -->
            <div class="col-md-8">
                <div class="card">
                    <h4>My Profile</h4>
                    <p class="text-muted">Manage your account information</p>
                    <hr class="bg-secondary">

                    <form id="profileForm" action="<?= base_url('update-account1') ?>" method="post">
                    <?= csrf_field() ?>>
                        <div class="mb-3">
                            <strong>Account ID</strong>
                            <input type="text" class="form-control" value="123456789" readonly>
                        </div>

                        <div class="mb-3">
                            <strong>Firstname</strong>
                            <br><input type="text" name="firstname" value="<?= esc($user['Firstname']) ?>" required></br>
                        </div>
                        <div class="mb-3">
                            <strong>Lastname</strong>
                            <input type="text" name="lastname" value="<?= esc($user['Lastname']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <strong>Email</strong>
                            <input type="email" name="email" value="<?= esc($user['Email']) ?>" required>
                        </div>

                        <button type="button" class="btn btn-save" onclick="saveProfile()">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function saveProfile() {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;

            if (!name || !email) {
                alert('Please fill in all required fields.');
                return;
            }

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                alert('Please enter a valid email address.');
                return;
            }

            // Simulate saving data (replace with actual API call)
            console.log('Saving profile:', { name, email });
            alert('Profile saved successfully!');
        }
    </script>
</body>
</html>


<?= $this->endSection() ?>
