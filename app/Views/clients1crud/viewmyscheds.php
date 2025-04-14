<?php
    $this ->extend('layout/mainclient');
    $this ->section('body');

    ?>
    <div class="p-2 row mb-3">

    <div class="col-12 mb-2">
    
    
    </div>

    
    <?php if (session()->getFlashdata('success')) :?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
        <?php endif;?>

    
    <div class="col-12">
    <table id="myTable" class="display">
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
<?php foreach ($coach2 as $coachSched): ?>
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


    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
    
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
    </div>
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Equipment Picture</label>
              <input type="file" class="form-control" name="equipmentpic"required>
    </div> 
    <div class="mb-3">
         <label for="exampleFormControlTextarea1" class="form-label">Amount</label>
             <input type="text" class="form-control" name="Eamount"required>
</div>
    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Quantity</label>
              <input type="text" class="form-control" name="Equantity"required>
    </div> 

    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">description</label>
              <input type="text" class="form-control" name="Ediscription"required>
    </div> 

    <div class="mb-3">
         <label for="exampleFormControlInput1" class="form-label">Purchase Date</label>
              <input type="date" class="form-control" name="Epurchasedate"required>
    </div> 

    </div>
   
    
    </div>
  </div>
</div>
<!DOCTYPE html>
<html>
<head>
    <title>My Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>My Tasks</h2>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Coach</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Subtasks</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tasks)): ?>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?= esc($task['Firstname']) ?></td>
                            <td><?= esc($task['TaskTitle']) ?></td>
                            <td><?= esc($task['TaskDescription']) ?></td>
                            <td><?= esc($task['DueDate']) ?></td>
                            <td><?= esc($task['Status']) ?></td>
                            <td><?= esc($task['Progress']) ?>%</td>
                            <td>
                                <?php if ($task['Status'] === 'pending'): ?>
                                    <form action="<?= base_url('tasks/updateSubtasks/' . $task['TaskID']) ?>" method="post">
                                        <?php
                                        $subtaskModel = new \App\Models\SubtaskModel();
                                        $subtasks = $subtaskModel->where('TaskID', $task['TaskID'])->findAll();
                                        foreach ($subtasks as $subtask):
                                        ?>
                                            <div class="form-check">
                                                <input type="checkbox" name="subtasks[<?= $subtask['SubtaskID'] ?>]" value="1" class="form-check-input" <?= $subtask['IsCompleted'] ? 'checked' : '' ?> onchange="this.form.submit()">
                                                <label class="form-check-label"><?= esc($subtask['SubtaskName']) ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted"><?= $task['Status'] === 'completed' ? 'Completed' : 'Incomplete' ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No tasks assigned.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>
    $(document).ready(function(){

        let table = new DataTable('#myTable', {
            responsive: true
        });

        $("#btn-save").on('click', function(){

            alert('Client Added Successfully!')

        });

    });
   
</script>




<?php $this->endSection(); ?> 