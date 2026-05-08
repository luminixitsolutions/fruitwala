<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customers";
$Page = "Today Pending Orders";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Expired Packages Report</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once 'header_script.php'; ?>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">

<style>
.badge {
  font-size: 13px;
  padding: 6px 10px;
  border-radius: 8px;
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
<h4 class="font-weight-bold py-3 mb-0">2 Days Before Expired Packages Customers</h4>

<div class="card" style="padding-right:10px;padding-left:10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
  <thead>
    <tr>
      <th>Name</th>
      <th>Phone</th>
      <th>Expired On</th>
      <th>Status</th>
      <th>Renew</th>
      <th>Address</th>
    </tr>
  </thead>
  <tbody>
    <?php
   $today         = date('Y-m-d');
$twoDaysBefore = date('Y-m-d', strtotime('-2 days'));
    $sql = "
      SELECT * FROM tbl_users
      WHERE Roll = 5 AND Status = 1 AND Validity >= '$today'
    AND Validity <= '$twoDaysAfter'
    ";
    $res = $conn->query($sql);

    if ($res && $res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        $id = htmlspecialchars($row['id']);
        $fname = htmlspecialchars($row['Fname']);
        $lname = htmlspecialchars($row['Lname']);
        $phone = htmlspecialchars($row['Phone']);
        $address = htmlspecialchars($row['Address']);
        $validity = date('d-M-Y', strtotime($row['Validity']));

        echo "
        <tr>
          <td>{$fname} {$lname}</td>
          <td>{$phone}</td>
          <td>{$validity}</td>
          <td><span class='badge bg-danger'>Expired</span></td>
          <td><a href='renew-customer.php?id={$id}' class='badge bg-success' style='color:white;'>Renew</a></td>
          <td>{$address}</td>
        </tr>";
      }
    } else {
      echo "<tr><td colspan='6' class='text-center text-muted'>No expired customers found.</td></tr>";
    }
    ?>
  </tbody>
</table>

<script>
$(document).ready(function() {
  $('#example').DataTable({
    responsive: true,
    order: [[2, 'desc']], // Sort by Expired On (column index 2)
    pageLength: 25
  });
});
</script>

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
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>

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
        title: 'Expire_Package_List',
        text: '<i class="fa fa-file-excel"></i> Excel',
        className: 'btn btn-success btn-sm'
      }
    ]
  });
});
</script>


</body>
</html>
