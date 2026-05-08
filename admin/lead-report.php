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
<title><?php echo $Proj_Title; ?> | View Company Expenses</title>
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
   Leads Report

</h4>

<div class="card" style="padding-right:10px;padding-left:10px;">
  <div id="accordion2">
    <div class="card mb-2">
      <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
        <div style="padding:5px;">
          <form id="validation-form" method="post" enctype="multipart/form-data" action="">
            <div class="form-row">

              <!-- From Date -->
              <div class="form-group col-md-2">
                <label class="form-label">From Date</label>
                <input type="date" name="FromDate" id="FromDate" class="form-control"
                       value="<?php echo $_POST['FromDate'] ?? ''; ?>" autocomplete="off">
              </div>

              <!-- To Date -->
              <div class="form-group col-md-2">
                <label class="form-label">To Date</label>
                <input type="date" name="ToDate" id="ToDate" class="form-control"
                       value="<?php echo $_POST['ToDate'] ?? ''; ?>" autocomplete="off">
              </div>

              <input type="hidden" name="Search" value="Search">

              <!-- Search Button -->
              <div class="form-group col-md-1">
                <label class="form-label">&nbsp;</label>
                <button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
              </div>

              <!-- Clear Button -->
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
    <table id="example" class="table table-striped table-bordered" style="width:100%">
      <thead>
        <tr>
          <th>Sr No</th>
          <th>Ticket No</th>
          <th>Source</th>
          <th>Customer Name</th>
          <th>Contact No</th>
          <th>Address</th>
          <th>Lead Status</th>
          <th>Lead Date</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        // Base query
        $sql = "SELECT * FROM tbl_leads WHERE 1";

        // Apply filters when search clicked
        if(isset($_POST['Search'])) {

          // Filter by date range
          if(!empty($_POST['FromDate']) && !empty($_POST['ToDate'])) {
            $from = $_POST['FromDate'];
            $to = $_POST['ToDate'];
            $sql .= " AND DATE(CreatedDate) BETWEEN '$from' AND '$to'";
          } elseif(!empty($_POST['FromDate'])) {
            $from = $_POST['FromDate'];
            $sql .= " AND DATE(CreatedDate) >= '$from'";
          } elseif(!empty($_POST['ToDate'])) {
            $to = $_POST['ToDate'];
            $sql .= " AND DATE(CreatedDate) <= '$to'";
          }
        }

        // Always order newest first
        $sql .= " ORDER BY id DESC";

        $res = $conn->query($sql);
        $sr = 0;

        while($row = $res->fetch_assoc()) {
          $sr++;
        ?>
        <tr>
          <td><?php echo $sr; ?></td>
          <td><?php echo htmlspecialchars($row['TicketNo']); ?></td>
          <td><?php echo htmlspecialchars($row['ClainReason']); ?></td>
          <td><?php echo htmlspecialchars($row['CustName']); ?></td>
          <td><?php echo htmlspecialchars($row['CellNo']); ?></td>
          <td><?php echo htmlspecialchars($row['Address']); ?></td>
          <td><?php echo htmlspecialchars($row['ClainStatus']); ?></td>
          <td><?php echo date("d/m/Y", strtotime($row['CreatedDate'])); ?></td>
        </tr>
        <?php } ?>
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
        title: 'Lead_Report',
        text: '<i class="fa fa-file-excel"></i> Excel',
        className: 'btn btn-success btn-sm'
      }
    ]
  });
});
</script>


</body>
</html>
