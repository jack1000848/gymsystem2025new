<?= $this->extend('layout/maincoach') ?> <!-- Change if using a different layout -->
<?= $this->section('body') ?>

<form action="<?= base_url('coach/markAbsent') ?>" method="post">
    <textarea name="message" placeholder="Optional message to clients (e.g., Sick today)" class="form-control mb-2"></textarea>
    <button type="submit" class="btn btn-warning">🚫 I'm Absent Today</button>
</form>
<?= $this->endSection() ?>