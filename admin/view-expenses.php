<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Expenses";
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

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>

<div class="layout-container">
<?php include_once 'top_header.php'; ?>

<?php
// Handle delete action
if(isset($_GET["action"]) && $_GET["action"]=="delete"){
  $id = $_GET["id"];
  
  // Delete attached file if exists
  $sql_get = "SELECT Attachment FROM tbl_company_expenses WHERE id='$id'";
  $row_get = getRecord($sql_get);
  if(!empty($row_get['Attachment']) && file_exists('uploads/expenses/'.$row_get['Attachment'])){
      unlink('uploads/expenses/'.$row_get['Attachment']);
  }

  $sql_delete = "DELETE FROM tbl_company_expenses WHERE id='$id'";
  $conn->query($sql_delete);
  echo "<script>alert('Expense Deleted Successfully!');window.location.href='view-expenses.php';</script>";
  exit;
}
?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">
  View Company Expenses
  <span style="float: right;">
    <a href="add-expense.php" class="btn btn-secondary btn-round">
      <i class="ion ion-md-add mr-2"></i> Add Expense
    </a>
  </span>
</h4>

<div class="card">
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
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $sql = "SELECT e.*, c.Name AS ExpenseTypeName, u.Name AS CreatedByName
            FROM tbl_company_expenses e 
            LEFT JOIN tbl_expense_category c ON e.ExpenseType = c.id 
            LEFT JOIN tbl_admin u ON e.CreatedBy = u.id
            ORDER BY e.ExpenseDate DESC";

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
      <td>
        <a href="add-expenses.php?id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit">
          <i class="lnr lnr-pencil mr-2"></i>
        </a>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete"
           onClick="return confirm('Are you sure you want to delete this expense?');"
           data-toggle="tooltip" data-placement="top" title="Delete">
           <i class="lnr lnr-trash text-danger"></i>
        </a>
      </td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <tr>
      <th colspan="4" class="text-right">Total Expense:</th>
      <th colspan="6">₹ <?php echo number_format($total, 2); ?></th>
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
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

<!-- DataTables Buttons Extension -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

<!-- JSZip (required for Excel export) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- Excel export -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>


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
        title: 'Company_Expenses_List',
        text: '<i class="fa fa-file-excel"></i> Export Excel',
        className: 'btn btn-success btn-sm'
      }
    ]
  });
});
</script>


</body>
</html>
