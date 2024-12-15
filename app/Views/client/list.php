<?php
    $this ->extend('layout/bootstrapyt');
    $this ->section('body');

    ?>

    <?php if (session()->getFlashdata('success')) :?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
        <?php endif;?>

    <h1>Client List</h1>
    <a href="/client" class="btn btn-primary">Add Client</a>
    <table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Client FirstName</th>
            <th scope="col">Client LastName</th>
            <th scope="col">Client Age</th>
            <th scope="col">Client Adress</th>
            <th scope="col">Client Email</th>
            <th scope="col">Client Profile</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody> 

    </tbody>
</table>


<?php $this->endSection(); ?> 