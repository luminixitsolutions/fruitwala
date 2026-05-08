<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Reports";
$Page = "View-Expenses";
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
   Company Expenses Report
  
</h4>

<div class="card" style="padding-right:10px;padding-left:10px;">
  <div id="accordion2">
    <div class="card mb-2">
      <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
        <div style="padding:5px;">
          <form id="validation-form" method="post" enctype="multipart/form-data" action="">
            <div class="form-row">

              <!-- Expense Type -->
              <div class="col-md-3">
                <label class="form-label fw-semibold">Expense Type</label>
                <select class="select2-demo form-control" id="ExpenseType" name="ExpenseType">
                  <option selected value="all">All</option>
                  <?php 
                    $q = "SELECT * FROM tbl_expense_category WHERE Status='1' ORDER BY Name ASC";
                    $r = $conn->query($q);
                    while($rw = $r->fetch_assoc()) { ?>
                      <option value="<?php echo $rw['id']; ?>" <?php if(isset($_POST['ExpenseType']) && $_POST['ExpenseType']==$rw['id']) echo 'selected'; ?>>
                        <?php echo $rw['Name']; ?>
                      </option>
                  <?php } ?>
                </select>
              </div>

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
    <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
      <thead>
        <tr>
          <th>#</th>
          <th>Date</th>
          <th>Expense Type</th>
          <th>Description</th>
          <th>Amount (₹)</th>
          <th>Payment Mode</th>
          <th>Reference No</th>
          <th>Attachment</th>
          <th>Created By</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        // Base query
        $sql = "SELECT e.*, c.Name AS ExpenseTypeName, u.Name AS CreatedByName
                FROM tbl_company_expenses e 
                LEFT JOIN tbl_expense_category c ON e.ExpenseType = c.id 
                LEFT JOIN tbl_admin u ON e.CreatedBy = u.id
                WHERE 1 ";

        // Apply filters only when Search is clicked
        if(isset($_POST['Search'])) {

          // Filter by Expense Type
          if(!empty($_POST['ExpenseType']) && $_POST['ExpenseType'] != 'all') {
            $expType = intval($_POST['ExpenseType']);
            $sql .= " AND e.ExpenseType = '$expType' ";
          }

          // Filter by From and To Dates
          if(!empty($_POST['FromDate']) && !empty($_POST['ToDate'])) {
            $from = $_POST['FromDate'];
            $to = $_POST['ToDate'];
            $sql .= " AND DATE(e.ExpenseDate) BETWEEN '$from' AND '$to' ";
          } elseif(!empty($_POST['FromDate'])) {
            $from = $_POST['FromDate'];
            $sql .= " AND DATE(e.ExpenseDate) >= '$from' ";
          } elseif(!empty($_POST['ToDate'])) {
            $to = $_POST['ToDate'];
            $sql .= " AND DATE(e.ExpenseDate) <= '$to' ";
          }
        }

        $sql .= " ORDER BY e.ExpenseDate DESC";
        $res = $conn->query($sql);
        $total = 0;
        $sr = 0;

        while($row = $res->fetch_assoc()) {
          $sr++;
          $total += $row['Amount'];
        ?>
        <tr>
          <td><?php echo $sr; ?></td>
          <td><?php echo date("d/m/Y", strtotime($row['ExpenseDate'])); ?></td>
          <td><?php echo htmlspecialchars($row['ExpenseTypeName'] ?? 'Unknown'); ?></td>
          <td><?php echo nl2br(htmlspecialchars($row['Description'])); ?></td>
          <td><b><?php echo number_format($row['Amount'], 2); ?></b></td>
          <td><?php echo htmlspecialchars($row['PaymentMode']); ?></td>
          <td><?php echo htmlspecialchars($row['ReferenceNo']); ?></td>
          <td>
            <?php if(!empty($row['Attachment']) && file_exists('uploads/expenses/'.$row['Attachment'])) { ?>
              <a href="uploads/expenses/<?php echo $row['Attachment']; ?>" target="_blank" class="btn btn-sm btn-info">View</a>
            <?php } else { echo '<span class="text-muted">No File</span>'; } ?>
          </td>
          <td><?php echo htmlspecialchars($row['CreatedByName'] ?? ''); ?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="4" class="text-right">Total Expense:</th>
          <th colspan="5">₹ <?php echo number_format($total, 2); ?></th>
        </tr>
      </tfoot>
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
    responsive: true,
    order: [[1, "desc"]],
    pageLength: 25,
    dom: 'Bfrtip', // Add this to show buttons
    buttons: [
      {
        extend: 'excelHtml5',
        title: 'Company_Expenses_report',
        text: '<i class="fa fa-file-excel"></i> Export Excel',
        className: 'btn btn-success btn-sm'
      }
    ]
  });
});
</script>


</body>
</html>
