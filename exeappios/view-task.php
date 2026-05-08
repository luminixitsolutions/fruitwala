<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Tasks";
$UserId = $_SESSION['User']['id'];
//print_r($_SESSION["cart_item"]);

 ?>
<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $Proj_Title; ?></title>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" id="style">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <link href="css/toastr.min.css" rel="stylesheet" id="style">
     <!-- FontAwesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
    <main class="flex-shrink-0 main">
        <?php include_once 'back-header.php'; ?> 
        <div class="main-container">
            
            
           
            
            <div class="container mb-4">
              
      <!--<div class="row">
          <div class="col-lg-6 col-6">
      <div class="form-group">
                               
                                <input type="date" class="form-control is-valid" id="SearchText" placeholder="Search Your Doubts..." onchange="searchDoughts(this.value,document.getElementById('Status').value)" value="<?php echo date('Y-m-d');?>">
                               
                            </div>
                            </div>
                              <div class="col-lg-6 col-6">
                             <div class="form-group">
                               
                                <select class="form-control" id="Status" onchange="searchDoughts(document.getElementById('SearchText').value,this.value)">
                                    <option value="" selected>All</option>
                                    <option value="1">Completed</option>
                                    <option value="0">Pending</option>
                                </select>
                               
                            </div>
                             </div>
                            </div>-->
    <style>
.tab-pill {
  background-color: #f2f2f2;
  border-radius: 12px;
  padding: 10px 0;
  transition: 0.3s ease;
  font-weight: 600;
  color: #000;
}

.tab-pill.active {
  background-color: #083068 !important;
  color: #fff !important;
}

.nav-pills .nav-item {
  margin-right: 8px;
}

.nav-pills {
  display: flex;
  gap: 8px;
}
.task-heading {
  font-size: 1.2rem;
  font-weight: 700;
  background: linear-gradient(to right, #083068, #0056b3);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}



    </style>                        
                <input type="hidden" id="name" value="<?php echo $CustName; ?>">
                 <input type="hidden" id="phone" value="<?php echo $CustPhone; ?>">
                  <input type="hidden" id="email" value="<?php echo $CustEmail; ?>">
             <!-- Bootstrap Nav Tabs -->
<!-- Bootstrap Tabs -->
<div class="card p-2 mb-3 shadow-sm">
  <ul class="nav nav-pills justify-content-between flex-nowrap w-100 gap-2" id="taskTabs" role="tablist" style="overflow-x: auto;">
    <li class="nav-item flex-fill text-center" role="presentation">
      <a class="nav-link active tab-pill" id="pending-tab" data-toggle="tab" href="#pending" role="tab">
        <i class="fas fa-clock me-1"></i><br> Pending
      </a>
    </li>
    <li class="nav-item flex-fill text-center" role="presentation">
      <a class="nav-link tab-pill" id="inprogress-tab" data-toggle="tab" href="#inprogress" role="tab">
        <i class="fas fa-spinner me-1 text-warning"></i><br> In Progress
      </a>
    </li>
    <li class="nav-item flex-fill text-center" role="presentation">
      <a class="nav-link tab-pill" id="completed-tab" data-toggle="tab" href="#completed" role="tab">
        <i class="fas fa-check-circle me-1 text-success"></i> <br>Completed
      </a>
    </li>
  </ul>
</div>


<div class="tab-content" id="taskTabsContent">

  <!-- In Progress Tab -->
  <div class="tab-pane fade" id="inprogress" role="tabpanel">
    <div class="row">
      <?php
      $sql = "SELECT tp.*, tu.Fname,tu2.Fname AS CreatedByName FROM tbl_task_new tp 
              LEFT JOIN tbl_users tu ON tu.id = tp.UserId 
              LEFT JOIN tbl_users tu2 ON tu2.id=tp.CreatedBy 
              WHERE tp.UserId = '$UserId'
              ORDER BY tp.CreatedDate DESC";
      $rows = getList($sql);

      foreach ($rows as $result) {
          $sql3 = "SELECT tpf.*, tu.Fname, tu.Lname 
                   FROM tbl_task_details tpf 
                   LEFT JOIN tbl_users tu ON tpf.CreatedBy = tu.id 
                   WHERE tpf.CompId = '" . $result['id'] . "' 
                   ORDER BY tpf.id DESC LIMIT 1";
          $row3 = getRecord($sql3);
          $status = trim($row3['ClainStatus']);

          if ($status !== "In Progress") continue;
$hasTask = true; // Found one task
          $badgeClass = "warning";
          $cardBgColor = "#fff8e1";
      ?>
      <div class="col-md-6">
        <div class="card shadow-sm mb-4 border-0" style="background-color: <?php echo $cardBgColor; ?>;">
          <div class="card-body">
            <h5 class="task-heading mb-3" style="font-size:13px;text-align:justify;">
  <i class="fas fa-tasks me-2 text-primary"></i>
  <?php echo htmlspecialchars($result['TaskName']); ?>
</h5>
            <p class="mb-1"><strong>Task Date:</strong> <?php echo date("d/m/Y", strtotime($result['TaskDate'])); ?></p>
            <p class="mb-1"><strong>Created By:</strong> <?php echo $result['CreatedByName']; ?></p>
            <p class="mb-1"><strong>Last Action Emp Name:</strong> <?php echo $row3['Fname'] . " " . $row3['Lname']; ?></p>
            <p class="mb-1"><strong>Last Action Date:</strong> <?php echo $row3['CreatedDate'] ? date("d/m/Y", strtotime($row3['CreatedDate'])) : "-"; ?></p>
            <p class="mb-1"><strong>Last Action:</strong> <?php echo $row3['Message']; ?></p>
            <p class="mb-3"><strong>Last Status:</strong>
              <span class="badge badge-<?php echo $badgeClass; ?>"><?php echo $status; ?></span>
            </p>
            <a href="take-task-action.php?qid=<?php echo $result['id']; ?>" class="btn btn-sm btn-primary">Open</a>
            <?php if ($result['CreatedBy'] == $UserId) { ?>
              <a href="create-task.php?id=<?php echo $result['id']; ?>" class="btn btn-sm btn-success">Edit</a>
            <?php } ?>
          </div>
        </div>
      </div>
      <?php } ?>
      
      <?php if (!$hasTask) { ?>
      <div class="col-12 text-center mt-4">
        <h6 class="text-muted">No In Progress tasks found.</h6>
      </div>
    <?php } ?>
    </div>
  </div>

  <!-- Completed Tab -->
  <div class="tab-pane fade" id="completed" role="tabpanel">
    <div class="row">
      <?php
      $rows = getList($sql);
      foreach ($rows as $result) {
          $sql3 = "SELECT tpf.*, tu.Fname, tu.Lname 
                   FROM tbl_task_details tpf 
                   LEFT JOIN tbl_users tu ON tpf.CreatedBy = tu.id 
                   WHERE tpf.CompId = '" . $result['id'] . "' 
                   ORDER BY tpf.id DESC LIMIT 1";
          $row3 = getRecord($sql3);
          $status = trim($row3['ClainStatus']);

          if ($status !== "Completed") continue;
$hasTask = true; // Found one task
          $badgeClass = "success";
          $cardBgColor = "#e6f4ea";
      ?>
      <div class="col-md-6">
        <div class="card shadow-sm mb-4 border-0" style="background-color: <?php echo $cardBgColor; ?>;">
          <div class="card-body">
           <h5 class="task-heading mb-3" style="font-size:13px;text-align:justify;">
  <i class="fas fa-tasks me-2 text-primary"></i>
  <?php echo htmlspecialchars($result['TaskName']); ?>
</h5>
            <p class="mb-1"><strong>Task Date:</strong> <?php echo date("d/m/Y", strtotime($result['TaskDate'])); ?></p>
            <p class="mb-1"><strong>Created By:</strong> <?php echo $result['CreatedByName']; ?></p>
            <p class="mb-1"><strong>Last Action Emp Name:</strong> <?php echo $row3['Fname'] . " " . $row3['Lname']; ?></p>
            <p class="mb-1"><strong>Last Action Date:</strong> <?php echo $row3['CreatedDate'] ? date("d/m/Y", strtotime($row3['CreatedDate'])) : "-"; ?></p>
            <p class="mb-1"><strong>Last Action:</strong> <?php echo $row3['Message']; ?></p>
            <p class="mb-3"><strong>Last Status:</strong>
              <span class="badge badge-<?php echo $badgeClass; ?>"><?php echo $status; ?></span>
            </p>
            <a href="take-task-action.php?qid=<?php echo $result['id']; ?>" class="btn btn-sm btn-primary">Open</a>
          </div>
        </div>
      </div>
      <?php } ?>
      <?php if (!$hasTask) { ?>
      <div class="col-12 text-center mt-4">
        <h6 class="text-muted">No Completed tasks found.</h6>
      </div>
    <?php } ?>
    </div>
  </div>

  <!-- Pending Tab -->
  <div class="tab-pane fade show active" id="pending" role="tabpanel">
    <div class="row">
      <?php
      $rows = getList($sql);
      foreach ($rows as $result) {
          $sql3 = "SELECT tpf.*, tu.Fname, tu.Lname 
                   FROM tbl_task_details tpf 
                   LEFT JOIN tbl_users tu ON tpf.CreatedBy = tu.id 
                   WHERE tpf.CompId = '" . $result['id'] . "' 
                   ORDER BY tpf.id DESC LIMIT 1";
          $row3 = getRecord($sql3);
          $status = trim($row3['ClainStatus']);

          // Skip known statuses
          if (in_array($status, ["In Progress", "Completed", "Cancelled"])) continue;
$hasTask = true; // Found one task
          $badgeClass = "secondary";
          $cardBgColor = "#f5f5f5";
      ?>
      <div class="col-md-6">
        <div class="card shadow-sm mb-4 border-0" style="background-color: <?php echo $cardBgColor; ?>;">
          <div class="card-body">
           <h5 class="task-heading mb-3" style="font-size:13px;text-align:justify;">
  <i class="fas fa-tasks me-2 text-primary"></i>
  <?php echo htmlspecialchars($result['TaskName']); ?>
</h5>
            <p class="mb-1"><strong>Task Date:</strong> <?php echo date("d/m/Y", strtotime($result['TaskDate'])); ?></p>
            <p class="mb-1"><strong>Created By:</strong> <?php echo $result['CreatedByName']; ?></p>
            <p class="mb-1"><strong>Last Action Emp Name:</strong> <?php echo $row3['Fname'] . " " . $row3['Lname']; ?></p>
            <p class="mb-1"><strong>Last Action Date:</strong> <?php echo $row3['CreatedDate'] ? date("d/m/Y", strtotime($row3['CreatedDate'])) : "-"; ?></p>
            <p class="mb-1"><strong>Last Action:</strong> <?php echo $row3['Message']; ?></p>
            <p class="mb-3"><strong>Last Status:</strong>
              <span class="badge badge-<?php echo $badgeClass; ?>"><?php echo $status ?: "Pending"; ?></span>
            </p>
            <a href="take-task-action.php?qid=<?php echo $result['id']; ?>" class="btn btn-sm btn-primary">Open</a>
          </div>
        </div>
      </div>
      <?php } ?>
      <?php if (!$hasTask) { ?>
      <div class="col-12 text-center mt-4">
        <h6 class="text-muted">No Pending tasks found.</h6>
      </div>
    <?php } ?>
    </div>
  </div>

</div>



                
          
            </div>

        </div>
    </main><br><br><br>
 <?php include_once 'footer.php'; ?>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="js/jquery.cookie.js"></script>
<script src="vendor/swiper/js/swiper.min.js"></script>
<script src="js/main.js"></script>
<script src="js/color-scheme-demo.js"></script>
<script src="js/app.js"></script>
<script src="js/toastr.min.js"></script>
<script>
    function searchDoughts(date,status){
        //var SearchText = $('#SearchText').val();
         var action = "searchTask";
    $.ajax({
    url:"ajax_files/ajax_task.php",
    method:"POST",
    data : {action:action,SearchText:date,status:status},
    success:function(data)
    {
        console.log(data);
      $('#showResult').html(data);
    }
    });
    }
    
    function completeTask(id,Details){
         var action = "completeTask";
    $.ajax({
    url:"ajax_files/ajax_task.php",
    method:"POST",
    data : {action:action,id:id,Details:Details},
    beforeSend: function() {
                        $('#CompleteBtn'+id).attr('disabled', 'disabled');
                        $('#CompleteBtn'+id).text('Please Wait...');
                    },
    success:function(data)
    {
        console.log(data);
      $('#CompleteBtn'+id).attr('disabled', 'disabled');
      $('#CompleteBtn'+id).text('Task Completed Dt On '+data);
    }
    });
    }
</script>
</body>
</html>
