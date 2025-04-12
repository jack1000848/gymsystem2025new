<?php
    $this ->extend('layout/mainclient');
    $this ->section('body');

    ?>

<?php if (isset($noCoach) && $noCoach): ?>
    <div style="background-color: #fff3cd; padding: 10px; border-left: 5px solid orange;">
        <strong>You have not been assigned a coach yet.</strong>
    </div>
<?php endif; ?>

<?php if (isset($coachAbsence) && $coachAbsence): ?>
    <div style="background-color: #f8d7da; padding: 10px; border-left: 5px solid red;">
        <strong>Your coach is absent today.</strong><br>
        <?php if ($coachAbsence->message): ?>
            Reason: <?= esc($coachAbsence->message) ?>
        <?php else: ?>
            No reason given.
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php $this->endSection(); ?> 