<?php
$this->extend('layout/main'); // Extend the main layout
$this->section('body'); // Start the body section
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
        <center> <h2>Customer Schedules</h2> </center>
        <table id="customerTable" class="display">
            <thead>
                <tr>
                <th scope="col">ID</th>
                  <th>Schedule Date</th>
                 <th>Start Time</th>
                 <th>End Time</th>
                  <th>Customer Name</th>
                 <th>Coach Name</th>               
                </tr>
            </thead>
            <tbody>
                <?php foreach ($coach1 as $coachSched): ?>
                    <tr>
                     <th scope="row"><?= $coachSched['ID']; ?></th>
                        <td><?= $coachSched['ScheduleDate']; ?></td>
                        <td><?= $coachSched['Start']; ?></td>
                        <td><?= $coachSched['End']; ?></td>
                        <td><?= isset($coachSched['CustomerName']) ? $coachSched['CustomerName'] : 'N/A'; ?></td>
                        <td><?= isset($coachSched['CoachName']) ? $coachSched['CoachName'] : 'N/A'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </body>
    </html>
    
        <script>
            $(document).ready(function() {
               $('#customerTable').DataTable();
                });
           </script>


        </body>
        </html>
<?php $this->endSection(); ?>
