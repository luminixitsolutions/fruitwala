<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Leads";
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

<?php
// Handle delete action
if(isset($_GET["action"]) && $_GET["action"]=="delete"){
  $id = $_GET["id"];
  $sql_delete = "DELETE FROM tbl_leads WHERE id='$id'";
  $conn->query($sql_delete);
  echo "<script>alert('Lead Deleted Successfully!');window.location.href='view-leads.php';</script>";
  exit;
}
?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">
  View Leads
  <span style="float: right;">
    <a href="add-lead.php" class="btn btn-secondary btn-round">
      <i class="ion ion-md-add mr-2"></i> Add Lead
    </a>
  </span>
</h4>

<div class="card" style="padding-right:10px;padding-left:10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered " style="width:100%">
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
                <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $sql = "SELECT * FROM tbl_leads 
            ORDER BY id DESC";

    $res = $conn->query($sql);
    $total = 0;
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

      <td>
        <a href="add-lead.php?id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit">
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
        title: 'Lead_List',
        text: '<i class="fa fa-file-excel"></i> Excel',
        className: 'btn btn-success btn-sm'
      }
    ]
  });
});
</script>


</body>
</html>
