<?= $this->extend('layout/maincoach') ?>
<?= $this->section('body') ?>

<!-- Include Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .list-group-item.active,
    .list-group-item:hover {
        background-color: #0CA6F7;
        color: #fff;
    }

    .card {
        border: none;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .btn-save {
        background-color: #0CA6F7;
        color: white;
        border: none;
    }

    .btn-save:hover {
        background-color: #0990d6;
    }

    .alert {
        font-weight: 500;
        padding: 12px 20px;
        border-radius: 6px;
    }

    .alert-success {
        background-color: #d1f2eb;
        color: #148f77;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #c0392b;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <!-- Sidebar -->
        <div class="col-md-4 mb-4">
            <div class="list-group shadow-sm rounded">
                <a href="#" class="list-group-item list-group-item-action active">
                    <i class="bi bi-person"></i> My Profile
                </a>
                <a href="<?= base_url('change-password') ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-lock"></i> Change Password
                </a>
            </div>
        </div>

        <!-- Profile Info Form -->
        <div class="col-md-8">
            <div class="card">
                <h4>My Profile</h4>
                <p class="text-muted">Manage your account information</p>
                <hr>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('update-account1') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="firstname" class="form-label"><strong>First Name</strong></label>
                        <input type="text" class="form-control" name="firstname" value="<?= esc($user['Firstname']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="lastname" class="form-label"><strong>Last Name</strong></label>
                        <input type="text" class="form-control" name="lastname" value="<?= esc($user['Lastname']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label"><strong>Email</strong></label>
                        <input type="email" class="form-control" name="email" value="<?= esc($user['Email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label"><strong>New Password</strong> <small class="text-muted">(Leave blank if unchanged)</small></label>
                        <input type="password" class="form-control" name="password">
                    </div>

                    <button type="submit" class="btn btn-save">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
