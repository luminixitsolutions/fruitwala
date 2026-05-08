<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Reports";
$Page = "View-Leads";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Attendance Report</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />

<?php include_once 'header_script.php'; ?>

</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>

<div class="layout-container">
<?php include_once 'top_header.php'; ?>



<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">
   Attendance Report

</h4>

<div class="card" style="padding-right:10px;padding-left:10px;">
  <div id="accordion2">
    <div class="card mb-2">
      <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
        <div style="padding:5px;">
          <form id="validation-form" method="post" enctype="multipart/form-data" action="">
            <div class="form-row">

             <div class="form-group col-md-4">
                                            <label class="form-label">Employee</label>
                                            <select class="select2-demo form-control" name="ExeId" id="ExeId">
                                                <option selected="" value="all">All</option>
                                                <?php 
                                                
                                                    $sql12 = "SELECT id,Fname,Lname FROM tbl_users WHERE Status='1' AND Roll=3 ORDER BY Fname";
                                               
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
                                                <option <?php if($_REQUEST['ExeId']==$result['id']){ ?> selected <?php } ?>
                                                    value="<?php echo $result['id']; ?>"><?php echo $result['Fname']." ".$result['Lname']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>


 <div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:30px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
</div>

</form>
        </div>
      </div>
    </div>
  </div>

  </div>
  <?php if(isset($_POST['Search'])) {

    // Build date range array
    $fromDate = $_POST['FromDate'];
    $toDate = $_POST['ToDate'];

    $dates = [];
    $start = strtotime($fromDate);
    $end = strtotime($toDate);
    while ($start <= $end) {
        $dates[] = date('Y-m-d', $start);
        $start = strtotime("+1 day", $start);
    }

    // Get all employees (executives)
    $emp_sql = "SELECT tu.id, tu.Fname, tu.CustomerId, tu.Lname
                FROM tbl_users tu 
                WHERE tu.Status=1 AND tu.Roll=3 ";
    if($_POST['ExeId'] && $_POST['ExeId'] != 'all'){
        $emp_sql .= " AND tu.id='".$_POST['ExeId']."'";
    }
   
    
    $emp_sql .= " ORDER BY tu.Fname"; 
    $emp_res = $conn->query($emp_sql);
?>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
<thead>
<tr>
    <th>Emp ID</th>
    <th>Employee Name</th>
<?php foreach($dates as $d){ ?>
    <th><?php echo date('d/m', strtotime($d)); ?></th>
<?php } ?>
    <th>Total Days</th>
    <th>Present</th>
    <th>Week Off</th>
    <th>Absent</th>
</tr>
</thead>
<tbody>
<?php
while($emp = $emp_res->fetch_assoc()){
    $UnderFrId = $emp['UnderFrId'];
    $UnderByUser = $emp['UnderByUser'];
    $ZoneId = $emp['ZoneId'];
    $SubZoneId = $emp['SubZoneId'];

    // Get supporting info
   
    $mgr = getRecord("SELECT Fname FROM tbl_users WHERE id='$UnderByUser'");
   

    // Main Branch Employee flag
    $MainBrEmp = $emp['MainBrEmp']; // assuming 1 = main branch employee

    $totalDays = count($dates);
    $presentCount = 0;
    $absentCount = 0;
    $weekoffCount = 0;

    echo "<tr>";
    echo "<td>".$emp['Fname']."</td>"; 
    echo "<td>".$emp['Lname']."</td>";
   
    

    // Loop through all dates in range
    foreach($dates as $dt){
    $dayName = date('D', strtotime($dt)); // Sun, Mon...

    // === NORMAL ATTENDANCE CHECK ===
    $att_sql = "SELECT COUNT(*) AS cnt FROM tbl_attendance 
                WHERE UserId='".$emp['id']."' 
                AND CreatedDate='$dt' 
                AND Type=2";
    $att_row = getRecord($att_sql);
    $hasNormalAttendance = $att_row['cnt'] > 0;

    // === WEEK OFF PUNCH CHECK ===
    $weekoff_sql = "SELECT COUNT(*) AS cnt FROM tbl_week_off_punch 
                    WHERE user_id='".$emp['id']."' 
                    AND punch_date='$dt' 
                    AND status='active'";
    $weekoff_row = getRecord($weekoff_sql);
    $hasWeekOffPunch = $weekoff_row['cnt'] > 0;


    // =========================
    // RULE A: NORMAL ATTENDANCE
    // =========================
    if ($hasNormalAttendance) {
        echo "<td style='background:#b0ffb0;text-align:center;'>P</td>";
        $presentCount++;
        continue;
    }


    // ===========================
    // RULE B: WEEK OFF PUNCH → W
    // Applies to MAIN + NON-MAIN
    // ===========================
    if ($hasWeekOffPunch) {
        echo "<td style='background:#cce5ff;text-align:center;'>W</td>";
        $weekoffCount++;
        continue;
    }


    // ===========================
    // RULE C: SUNDAY (NO ATTENDANCE + NO W-PUNCH)
    // ===========================
    if ($dayName == 'Sun') {

        if ($MainBrEmp == 1) {
            // Main Branch Employee → Week Off
            echo "<td style='background:#cce5ff;text-align:center;'>W</td>";
            $weekoffCount++;
        } else {
            // Normal Employee → Absent
            echo "<td style='background:#ffb0b0;text-align:center;' 
          id='cell_".$emp['id']."_".$dt."'>
          <a href='javascript:void(0)' onclick=\"openAttendanceModal('".$emp['id']."','".$emp['Fname']."','".$dt."')\">A</a>
          </td>";
            $absentCount++;
        }

        continue;
    }


    // ===========================
    // RULE D: NORMAL DAY WITH NO ATTENDANCE
    // ===========================
    echo "<td style='background:#ffb0b0;text-align:center;' 
          id='cell_".$emp['id']."_".$dt."'>
          <a href='javascript:void(0)' onclick=\"openAttendanceModal('".$emp['id']."','".$emp['Fname']."','".$dt."')\">A</a>
          </td>";
    $absentCount++;
}



    echo "<td style='background:#e3e3e3;text-align:center;font-weight:bold;'>".$totalDays."</td>";
    echo "<td style='background:#d4edda;text-align:center;font-weight:bold;'>".$presentCount."</td>";
    echo "<td style='background:#cce5ff;text-align:center;font-weight:bold;'>".$weekoffCount."</td>";
    echo "<td style='background:#f8d7da;text-align:center;font-weight:bold;'>".$absentCount."</td>";
    echo "</tr>";
}
?>
</tbody>

</table>
</div>
<?php } ?>
</div>

</div>

<?php include_once 'footer.php'; ?>
</div>

</div>
</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>

<?php include_once 'footer_script.php'; ?>



<script type="text/javascript">
$(document).ready(function() {
  $('#example').DataTable({
     scrollX: true,
    order: [[1, "desc"]],
    pageLength: 25,
    dom: 'Bfrtip', // Add this to show buttons
    buttons: [
      {
        extend: 'excelHtml5',
        title: 'Attendance_Report',
        text: '<i class="fa fa-file-excel"></i> Excel',
        className: 'btn btn-success btn-sm'
      }
    ]
  });
});
</script>


</body>
</html>
