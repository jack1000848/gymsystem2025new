<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Codes</title>
</head>
<body>
    <h1>Saved QR Codes</h1>
    <?php if (session()->getFlashdata('success')): ?>
        <p style="color: green;"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>QR Code</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($qrcodes as $qr): ?>
                <tr>
                    <td><?= $qr['id'] ?></td>
                    <td><?= $qr['data'] ?></td>
                    <td><img src="<?= base_url('/writable/qrcodes/6751c83521a81.png') ?>" alt="QR Code" style="width: 100px;"></td>
                    <td><?= $qr['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
