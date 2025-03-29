<?php
    $this ->extend('layout/mainclient');
    $this ->section('body');

    ?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Attendance Records</h2>
        
        <?php if (!empty($attendance)) : ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Attendance ID</th>
                        <th>Customer ID</th>
                        <th>Check-in Time</th>
                        <th>Check-out Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendance as $row) : ?>
                        <tr>
                            <td><?= esc($row['AttendanceID']) ?></td>
                            <td><?= esc($row['CustomerID']) ?></td>
                            <td><?= esc($row['InDate']) ?></td>
                            <td><?= !empty($row['CheckOut']) ? esc($row['CheckOut']) : 'Not checked out' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <div class="alert alert-warning">No attendance records found.</div>
        <?php endif; ?>
    </div>
</body>
</html>




<?php $this->endSection(); ?> 