
<?= $this->extend('layout/mainclient') ?>
<?= $this->section('body') ?>

<div class="container mt-5">
    <h2>Update Body Information</h2>
    <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session('errors') as $error): ?>
                <p><?= esc($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success">
            <p><?= esc(session('success')) ?></p>
        </div>
    <?php endif; ?>
    
    <form id="bodyForm">
        <div class="mb-3">
            <label for="height" class="form-label">Height (cm)</label>
            <input type="number" step="0.01" class="form-control" id="height" name="height" value="<?= esc($customer['Height'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="weight" class="form-label">Weight (kg)</label>
            <input type="number" step="0.01" class="form-control" id="weight" name="weight" value="<?= esc($customer['Weight'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="weight_goal" class="form-label">Weight Goal (kg)</label>
            <input type="number" step="0.01" class="form-control" id="weight_goal" name="weight_goal" value="<?= esc($customer['Weight_Goal'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="height_goal" class="form-label">Height Goal (cm, optional)</label>
            <input type="number" step="0.01" class="form-control" id="height_goal" name="height_goal" value="<?= esc($customer['Height_Goal'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes (optional)</label>
            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
        </div>
        <button type="button" class="btn btn-primary" onclick="validateAndRedirect()">Save</button>
        <a href="<?= base_url('/customer/body/history') ?>" class="btn btn-secondary">View History</a>
    </form>
</div>

<script>
    function validateAndRedirect() {
        const form = document.getElementById('bodyForm');

        if (form.checkValidity()) {
            // Only redirect after validation success
            window.location.href = "<?= base_url('/customer/body/history') ?>";
        } else {
            // Show validation errors
            form.reportValidity();
        }
    }
</script>


<?= $this->endSection() ?>