<?php
    $this ->extend('layout/mainclient');
    $this ->section('body');

    ?>

<?php if (isset($coachAbsence) && $coachAbsence): ?>
    <div style="background-color: #ffe0e0; padding: 10px; border-left: 5px solid red; margin-bottom: 20px;">
        <strong>Your coach is absent today.</strong><br>
        <?php if ($coachAbsence->message): ?>
            Reason: <?= esc($coachAbsence->message) ?>
        <?php else: ?>
            No reason provided.
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php $this->endSection(); ?> 