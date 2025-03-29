<?php
    $this ->extend('layout/mainclient');
    $this ->section('body');

    ?>
   

   <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>

        $(document).ready(function() {
            $('#customerTable').DataTable();
        });
    </script>
    <style>
       
        body {
           
            background-color: #f4f4f4;
            text-align: center;
        }

        h2 {
            font-size: 28px;
            font-weight: 600;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 20px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #ffffff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #3498db;
            color: white;
            font-size: 18px;
            text-transform: uppercase;
        }

        td {
            font-size: 16px;
            color: #2c3e50;
        }

        tr:hover {
            background: #ecf0f1;
        }
    </style>
</head>
<body>
<div class="container">
        <center> <h2>MY LOGS</h2> </center>
        <?php if (!empty($attendance)) : ?>
        <table id="customerTable" class="display">
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
    </div>
    <?php else : ?>
        <div class="container">
            <h2>No attendance records found.</h2>
        </div>
    <?php endif; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
        <script>
            $(document).ready(function() {
               $('#customerTable').DataTable();
                });
           </script>


        </body>
        </html>
<?php $this->endSection(); ?> 