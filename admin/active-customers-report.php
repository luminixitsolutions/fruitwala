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
<title><?php echo $Proj_Title; ?> | Active Customers Report</title>
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
<h4 class="font-weight-bold py-3 mb-0">Active Customers Report</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
  <thead>
    <tr>
          <th>Name</th>
          <th>Phone</th>
          <th>Address</th>
          <th>Package Expiry</th>
          <th>Registered</th>
        </tr>
  </thead>
 <tbody>
       <?php
      $sql = "
        SELECT * FROM tbl_users
        WHERE Roll=5 AND Status=1
          AND (Validity IS NULL OR Validity >= '$today')
      ";
      $res = $conn->query($sql);
      while($row = $res->fetch_assoc()){
        echo "<tr>
          <td>{$row['Fname']} {$row['Lname']}</td>
          <td>{$row['Phone']}</td>
          <td>{$row['Address']}</td>
          <td>".($row['Validity'] ? date('d-M-Y', strtotime($row['Validity'])) : 'N/A')."</td>
          <td>".date('d-M-Y', strtotime($row['CreatedDate']))."</td>
        </tr>";
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
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>

<script>
$(document).ready(function() {
  $('#example').DataTable({
    order: [[0, 'asc']],
    "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
  });
});
</script>

</body>
</html>
