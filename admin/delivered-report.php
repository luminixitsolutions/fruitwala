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
<title><?php echo $Proj_Title; ?> | Delivered Orders Report</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once 'header_script.php'; ?>


<style>
.badge {
  font-size: 13px;
  padding: 6px 10px;
  border-radius: 8px;
}
/*#example td {
  white-space: normal !important;  

}*/
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
<h4 class="font-weight-bold py-3 mb-0">Delivered Orders Report</h4>

<div class="card" style="padding-right:10px;padding-left:10px;">
  <div id="accordion2">
    <div class="card mb-2">
      <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
        <div class="" style="padding:5px;">
          <form id="validation-form" method="post" enctype="multipart/form-data" action="">
            <div class="form-row">

              <div class="form-group col-md-3">
                <label class="form-label">Customers </label>
                <select class="select2-demo form-control" id="CustId" name="CustId">
                  <option selected value="all">All</option>
                  <?php 
                    $sql = "SELECT * FROM tbl_users WHERE Roll=5";
                    $row = getList($sql);
                    foreach($row as $result){ ?>
                      <option value="<?php echo $result['id']; ?>" <?php if($_POST["CustId"]==$result['id']) echo 'selected'; ?>>
                        <?php echo $result['Fname']." ".$result['Lname']; ?>
                      </option>
                  <?php } ?>
                </select>
                <div class="clearfix"></div>
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
                <label class="form-label">From Date </label>
                <input type="date" name="FromDate" id="FromDate" class="form-control" 
                       value="<?php echo $_POST['FromDate'] ?? ''; ?>" autocomplete="off">
              </div>

              <div class="form-group col-md-2">
                <label class="form-label">To Date</label>
                <input type="date" name="ToDate" id="ToDate" class="form-control" 
                       value="<?php echo $_POST['ToDate'] ?? ''; ?>" autocomplete="off">
              </div>

              <input type="hidden" name="Search" value="Search">

              <div class="form-group col-md-1">
                <label class="form-label">&nbsp;</label>
                <button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
              </div>

              <?php if(isset($_POST['Search'])) { ?>
              <div class="form-group col-md-1">
                <label class="form-label">&nbsp;</label>
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" title="Clear Filter">X</a>
              </div>
              <?php } ?>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="card-datatable table-responsive">
    <table id="example" class="table table-striped table-bordered" style="width:100%">
      <thead>
        <tr>
          <th nowrap>Customer</th>
          <th>Phone</th>
          <th>Executive</th>
          <th>Box Collect</th>
          <th>Delivered Date</th>
          <th>Delivered Time</th>
          <th>Address</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // ✅ Base query
        $sql = "
          SELECT osl.*, u.Fname, u.Lname, u.Phone, u.Address,
                 (SELECT Fname FROM tbl_users e WHERE e.Roll=3 AND FIND_IN_SET(u.AreaId, e.AreaId) LIMIT 1) AS ExecName
          FROM tbl_order_status_log osl
          INNER JOIN tbl_users u ON osl.UserId = u.id
          WHERE osl.Status='Delivered'
        ";

        // ✅ Apply filters if search pressed
        if(isset($_POST['Search'])) {
          $custId = $_POST['CustId'];
          $fromDate = $_POST['FromDate'];
          $toDate = $_POST['ToDate'];
          $execId = $_POST['ExecId'];

          if($custId != "all" && $custId != "") {
            $sql .= " AND osl.UserId = '$custId'";
          }
          if($execId != "all" && $execId != "") {
          $sql .= " AND osl.UpdatedBy = '$execId'";
        }
          if(!empty($fromDate) && !empty($toDate)) {
            $sql .= " AND DATE(osl.CreatedDate) BETWEEN '$fromDate' AND '$toDate'";
          } elseif(!empty($fromDate)) {
            $sql .= " AND DATE(osl.CreatedDate) >= '$fromDate'";
          } elseif(!empty($toDate)) {
            $sql .= " AND DATE(osl.CreatedDate) <= '$toDate'";
          }
        }

        // ✅ Final ordering
        $sql .= " ORDER BY osl.CreatedDate DESC";

        $res = $conn->query($sql);
        if($res && $res->num_rows > 0) {
          while($row = $res->fetch_assoc()) {
            echo "<tr>
              <td nowrap>{$row['Fname']} {$row['Lname']}</td>
              <td>{$row['Phone']}</td>
              <td>".($row['ExecName'] ?: '<span class=\"text-muted\">Unassigned</span>')."</td>
              <td>{$row['BoxQty']}</td>
              <td>".date('d/m/Y', strtotime($row['CreatedDate']))."</td>
              <td>".date('h:i A', strtotime($row['CreatedDate']))."</td>
              <td>{$row['Address']}</td>
            </tr>";
          }
        } else {
          echo "<tr><td colspan='7' class='text-center text-muted'>No records found</td></tr>";
        }
        ?>
      </tbody>
    </table>
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
    order: [[1, "desc"]],
    pageLength: 25,
    dom: 'Bfrtip', // Add this to show buttons
    buttons: [
      {
        extend: 'excelHtml5',
        title: 'delivered_report',
        text: '<i class="fa fa-file-excel"></i> Excel',
        className: 'btn btn-success btn-sm'
      }
    ]
  });
});
</script>

</body>
</html>
