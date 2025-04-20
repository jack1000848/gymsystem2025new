<?= $this->extend('layout/maincoach') ?>
<?= $this->section('body') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
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

    hr.bg-secondary {
        border-top: 1px solid #dee2e6;
    }

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

    .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d;
    }

    .password-field {
        padding-right: 40px;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }
</style>

<div class="container mt-5 d-flex justify-content-center">
    <div class="row w-100 justify-content-center" style="max-width: 900px;">
        <!-- Sidebar -->
        <div class="col-md-4 mb-4">
            <div class="list-group bg-white rounded shadow">
                <a href="#" class="list-group-item list-group-item-action active" data-tab="profile">
                    <i class="bi bi-person"></i> My Profile
                </a>
                <a href="#" class="list-group-item list-group-item-action" data-tab="change-password">
                    <i class="bi bi-lock"></i> Change Password
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <!-- Content Area -->
        <div class="col-md-8">
            <!-- Profile Tab -->
            <div id="profile" class="card tab-content active">
                <h4>My Profile</h4>
                <p class="text-muted">Manage your account information</p>
                <hr class="bg-secondary">

                <form id="profileForm" action="<?= base_url('update-account1') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <strong>Account ID</strong>
                        <input type="text" class="form-control" value="<?= esc($user['id'] ?? '123456789') ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <strong>Firstname</strong>
                        <input type="text" id="firstname" name="firstname" class="form-control" value="<?= esc($user['Firstname'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <strong>Lastname</strong>
                        <input type="text" id="lastname" name="lastname" class="form-control" value="<?= esc($user['Lastname'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <strong>Email</strong>
                        <input type="email" id="email" name="email" class="form-control" value="<?= esc($user['Email'] ?? '') ?>" required>
                    </div>
                    <button type="submit" class="btn btn-save">Save Changes</button>
                </form>
            </div>

            <!-- Change Password Tab -->
            <div id="change-password" class="card tab-content">
                <h4>Change Password</h4>
                <p class="text-muted">Update your account password</p>
                <hr class="bg-secondary">

                <form id="changePasswordForm" action="<?= base_url('update-password') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3 position-relative">
                        <strong>New Password</strong>
                        <input type="password" id="password" name="password" class="form-control password-field" required>
                        <span class="password-toggle" onclick="togglePassword('password', 'eye1')">
                            <i id="eye1" class="fas fa-eye"></i>
                        </span>
                    </div>
                    <div class="mb-3 position-relative">
                        <strong>Confirm Password</strong>
                        <input type="password" id="confirmPassword" name="confirmPassword" class="form-control password-field" required>
                        <span class="password-toggle" onclick="togglePassword('confirmPassword', 'eye2')">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<script>
    // Tab Switching
    document.querySelectorAll('.list-group-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const tab = this.getAttribute('data-tab');

            // Update active tab
            document.querySelectorAll('.list-group-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            // Show/hide content
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            document.getElementById(tab).classList.add('active');
        });
    });

    // Profile Form Validation
    document.getElementById('profileForm').addEventListener('submit', function(event) {
        const firstname = document.getElementById('firstname').value.trim();
        const lastname = document.getElementById('lastname').value.trim();
        const email = document.getElementById('email').value.trim();

        if (!firstname || !lastname || !email) {
            event.preventDefault();
            alert('Please fill in all required fields.');
            return;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            event.preventDefault();
            alert('Please enter a valid email address.');
            return;
        }
    });

    // Change Password Form Validation
    document.getElementById('changePasswordForm').addEventListener('submit', function(event) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const errorMessage = document.getElementById('errorMessage');

        if (password !== confirmPassword) {
            event.preventDefault();
            errorMessage.classList.remove('hidden');
            return;
        } else {
            errorMessage.classList.add('hidden');
        }
    });

    // Password Toggle
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
</script>

<?= $this->endSection() ?>