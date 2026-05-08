<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Reports";
$Page = "Today Pending Orders";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Pending Orders Report</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once 'header_script.php'; ?>

<!-- ✅ DataTables Core + Buttons CSS -->


<style>
.badge {
  font-size: 13px;
  padding: 6px 10px;
  border-radius: 8px;
}
div.dt-buttons {
  margin-bottom: 10px;
}
</style>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>

<div class="layout-container">
<?php include_once 'top_header.php'; ?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Pending Orders Report</h4>

<div class="card" style="padding-right:10px;padding-left:10px;">
  <div id="accordion2">
    <div class="card mb-2">
      <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
        <div class="" style="padding:5px;">
          <form id="validation-form" method="post" enctype="multipart/form-data" action="">
            <div class="form-row">

              <div class="form-group col-md-4">
                <label class="form-label">Customers </label>
                <select class="select2-demo form-control" id="CustId" name="CustId">
                  <option selected value="all">All</option>
                  <?php 
                    $sql = "SELECT * FROM tbl_users WHERE Roll=5";
                    $row = getList($sql);
                    foreach($row as $result){ ?>
                      <option value="<?php echo $result['id']; ?>" <?php if(@$_POST["CustId"]==$result['id']) echo 'selected'; ?>>
                        <?php echo $result['Fname']." ".$result['Lname']; ?>
                      </option>
                  <?php } ?>
                </select>
              </div>
              
              <div class="col-md-3">
                <label class="form-label fw-semibold">Executive</label>
                <select class="select2-demo form-control" name="ExecId" id="ExecId">
                  <option value="all">All</option>
                  <?php 
                    $sql = "SELECT id, Fname FROM tbl_users WHERE Roll=3 ORDER BY Fname ASC";
                    $res = $conn->query($sql);
                    while($e = $res->fetch_assoc()){ ?>
                      <option value="<?php echo $e['id']; ?>" 
                        <?php if(isset($_POST['ExecId']) && $_POST['ExecId']==$e['id']) echo 'selected'; ?>>
                        <?php echo $e['Fname']; ?>
                      </option>
                  <?php } ?>
                </select>
              </div>

              <div class="form-group col-md-2">
                <label class="form-label">From Date</label>
                <input type="date" name="FromDate" id="FromDate" class="form-control" 
                       value="<?php echo $_POST['FromDate'] ?? ''; ?>" autocomplete="off" required>
              </div>

              <div class="form-group col-md-2">
                <label class="form-label">To Date</label>
                <input type="date" name="ToDate" id="ToDate" class="form-control" 
                       value="<?php echo $_POST['ToDate'] ?? ''; ?>" autocomplete="off" required>
              </div>

              <input type="hidden" name="Search" value="Search">

              <div class="form-group col-md-1">
                <label class="form-label">&nbsp;</label>
                <button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
              </div>

              <?php if(isset($_POST['Search'])) { ?>
              <div class="form-group col-md-1">
                <label class="form-label">&nbsp;</label>
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" title="Clear Filter">X</a>
              </div>
              <?php } ?>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<div class="card-datatable table-responsive">
<?php
if(isset($_POST['Search'])) {

  $from = $_POST['FromDate'];
  $to = $_POST['ToDate'];
  $custId = $_POST['CustId'];
  $execId = $_POST['ExecId'];
  $today = date('Y-m-d');

  $where = "WHERE u.Roll=5 AND u.Status=1 AND (u.Validity IS NULL OR u.Validity >= '$today')";
  if($custId != 'all') $where .= " AND u.id='$custId'";
  if($execId != 'all') $where .= " AND u.ExecId='$execId'";

  $custSql = "
    SELECT u.id, u.Fname, u.Lname, u.Phone, u.Address, u.Validity, u.AreaId
    FROM tbl_users u
    $where
    ORDER BY u.Fname ASC
  ";
  $custRes = $conn->query($custSql);
?>

  <table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
      <tr>
        <th>Sr No</th>
        <th>Customer</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Date / Message</th>
        <th>Status</th>
        <th>Package Expiry</th>
        <th>Executive</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $i = 1;
      while($cust = $custRes->fetch_assoc()){

        $pkgExpiry = $cust['Validity'] ? date('d-M-Y', strtotime($cust['Validity'])) : 'N/A';

        // Executive Name
        $exec = 'Unassigned';
        $execRow = $conn->query("
          SELECT Fname FROM tbl_users 
          WHERE Roll=3 AND FIND_IN_SET('{$cust['AreaId']}', AreaId)
        ")->fetch_assoc();
        if($execRow) $exec = $execRow['Fname'];

        // Get package history for selected date range
        $pkgSql = "
          SELECT PkgDate, Validity 
          FROM tbl_cust_package_history
          WHERE CustId='{$cust['id']}'
            AND (
              (PkgDate BETWEEN '$from' AND '$to')
              OR (Validity BETWEEN '$from' AND '$to')
              OR ('$from' BETWEEN PkgDate AND Validity)
            )
          ORDER BY PkgDate ASC
        ";
        $pkgRes = $conn->query($pkgSql);

        $lastPkgEnd = null;
        $packagesFound = false;

        while($pkg = $pkgRes->fetch_assoc()){
          $packagesFound = true;
          $pkgStart = $pkg['PkgDate'];
          $pkgEnd = $pkg['Validity'] ?: $to;

          // Check for inactive period
if ($lastPkgEnd && strtotime($pkgStart) > strtotime($lastPkgEnd . ' +1 day')) {
  $gapStart = date('Y-m-d', strtotime($lastPkgEnd . ' +1 day'));
  $gapEnd = date('Y-m-d', strtotime($pkgStart . ' -1 day'));

  echo "<tr class='no-export' style='background:#ffe6e6; color:red; font-weight:bold;'>
    <td>{$i}</td>
    <td>{$cust['Fname']} {$cust['Lname']}</td>
    <td>❌ No Active Package</td>
    <td>Between " . date('d-M-Y', strtotime($gapStart)) . " to " . date('d-M-Y', strtotime($gapEnd)) . "</td>
    <td></td>
    
    
    <td></td>
    <td></td>
    <td></td>
  </tr>";
  $i++;
}



          // Adjust range within filter dates
          $loopStart = (strtotime($pkgStart) < strtotime($from)) ? $from : $pkgStart;
          $loopEnd = (strtotime($pkgEnd) > strtotime($to)) ? $to : $pkgEnd;

          $datePeriod = new DatePeriod(
            new DateTime($loopStart),
            new DateInterval('P1D'),
            (new DateTime($loopEnd))->modify('+1 day')
          );

          foreach($datePeriod as $date){
            $checkDate = $date->format('Y-m-d');

            // Skip delivered
            $delivered = $conn->query("
              SELECT 1 FROM tbl_order_status_log 
              WHERE UserId='{$cust['id']}' 
                AND Status='Delivered' 
                AND DATE(CreatedDate)='$checkDate'
            ");

            if($delivered->num_rows == 0){
              echo "<tr>
                <td>{$i}</td>
                <td>{$cust['Fname']} {$cust['Lname']}</td>
                <td>{$cust['Phone']}</td>
                <td>{$cust['Address']}</td>
                <td>".date('d-M-Y', strtotime($checkDate))."</td>
                <td><span class='badge badge-warning'>Pending</span></td>
                <td>".date('d-M-Y', strtotime($pkgEnd))."</td>
                <td>$exec</td>
              </tr>";
              $i++;
            }
          }

          $lastPkgEnd = $pkgEnd;
        }

        // No packages found
if (!$packagesFound) {
  echo "<tr class='no-export' style='background:#ffe6e6; color:red; font-weight:bold;'>
    <td>{$i}</td>
    <td>❌ No Active Package Found</td>
    <td>In selected date range</td>
    <td></td>
    <td></td>
    
    <td></td>
    <td></td>
    <td></td>
  </tr>";
  $i++;
}

      }
      ?>
    </tbody>
  </table>
<?php } ?>

</div>
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
    order: [[0, "asc"]],
    pageLength: 25,
    dom: 'Bfrtip', // Add this to show buttons
    buttons: [
      {
        extend: 'excelHtml5',
        title: 'pending_report',
        text: '<i class="fa fa-file-excel"></i> Excel',
        className: 'btn btn-success btn-sm'
      }
    ]
  });
});
</script>


</body>
</html>
