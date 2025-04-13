<?php
    $this ->extend('layout/maincoach');
    $this ->section('body');

    ?>
    <title>Create Task</title>
    
<form action="<?= base_url('coach/markAbsence') ?>" method="post">
    <textarea name="message" placeholder="Optional message (e.g. sick, vacation)" rows="2"></textarea><br>
    <button type="submit">I’m Absent Today</button>
</form>

    </div>
<?php $this->endSection(); ?> 