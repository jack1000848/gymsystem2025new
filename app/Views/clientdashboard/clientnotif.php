<?= $this->extend('layout/mainclient') ?>
<?= $this->section('body') ?>

<h2>Notifications</h2>

<?php if (!empty($notifications)) : ?>
    <ul>
        <?php foreach ($notifications as $note) : ?>
            <li style="margin-bottom: 10px;<?= $note->is_read ? '' : ' font-weight: bold;' ?>">
                <?= esc($note->message) ?> <br>
                <small><?= date('F j, Y g:i A', strtotime($note->created_at)) ?></small>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>No notifications yet.</p>
<?php endif; ?>

<?= $this->endSection() ?>