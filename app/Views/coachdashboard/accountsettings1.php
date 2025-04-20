<?= $this->extend('layout/maincoach') ?>
<?= $this->section('body') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        /* Card styling */
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

        /* Alert styling */
        .alert {
            border-radius: 5px;
            font-weight: bold;
        }

        /* Form control styling */
        .form-control {
            border-radius: 8px;
            padding: 10px;
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

        /* Password input styling */
        .password-container {
            position: relative;
        }

        .password-container .form-control {
            padding-right: 40px;
        }

        .password-container .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
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
    </style>
</head>
<body>
    <div class="container mt-5 d-flex justify-content-center">
        <div class="row w-100 justify-content-center" style="max-width: 900px;">
            <!-- Sidebar -->
            <div class="col-md-4 mb-4">
                <div class="list-group bg-white rounded shadow">
                    <a href="#" class="list-group-item list-group-item-action active" onclick="toggleSection('profile', 'password')">
                        <i class="bi bi-person"></i> My Profile
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" onclick="toggleSection('password', 'profile')">
                        <i class="bi bi-lock"></i> Change Password
                    </a>
                </div>
            </div>

            <!-- Profile and Password Sections -->
            <div class="col-md-8">
                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <!-- Profile Form -->
                <div class="card" id="profile-section">
                    <h4>My Profile</h4>
                    <p class="text-muted">Manage your account information</p>
                    <hr class="bg-secondary">

                    <form action="<?= base_url('update-account1') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <strong>First Name</strong>
                            <input type="text" class="form-control" name="firstname" value="<?= esc($user['Firstname']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <strong>Last Name</strong>
                            <input type="text" class="form-control" name="lastname" value="<?= esc($user['Lastname']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <strong>Email</strong>
                            <input type="email" class="form-control" name="email" value="<?= esc($user['Email']) ?>" required>
                        </div>
                        <button type="submit" class="btn btn-save">Save Changes</button>
                    </form>
                </div>

                <!-- Password Change Form -->
                <div class="card" id="password-section" style="display: none;">
                    <h4>Change Password</h4>
                    <p class="text-muted">Update your account password</p>
                    <hr class="bg-secondary">

                    <form action="<?= base_url('update-password') ?>" method="post" onsubmit="return validatePassword()">
                        <?= csrf_field() ?>
                        <div class="mb-3 password-container">
                            <strong>New Password</strong>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <span class="toggle-password" onclick="togglePassword('password', 'eye1')">
                                <i id="eye1" class="fas fa-eye"></i>
                            </span>
                        </div>
                        <div class="mb-3 password-container">
                            <strong>Confirm Password</strong>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                            <span class="toggle-password" onclick="togglePassword('confirmPassword', 'eye2')">
                                <i id="eye2" class="fas fa-eye"></i>
                            </span>
                        </div>
                        <p id="errorMessage" class="text-danger text-sm mt-2 hidden">Passwords do not match!</p>
                        <button type="submit" class="btn btn-save">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, eyeId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }

        function toggleSection(showSection, hideSection) {
            document.getElementById(`${showSection}-section`).style.display = 'block';
            document.getElementById(`${hideSection}-section`).style.display = 'none';
            document.querySelectorAll('.list-group-item').forEach(item => item.classList.remove('active'));
            document.querySelector(`a[onclick*="toggleSection('${showSection}')"]`).classList.add('active');
        }

        function validatePassword() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const errorMessage = document.getElementById('errorMessage');

            if (password !== confirmPassword) {
                errorMessage.classList.remove('hidden');
                return false;
            }
            errorMessage.classList.add('hidden');
            return true;
        }
    </script>
</body>
</html>

<?= $this->endSection() ?>