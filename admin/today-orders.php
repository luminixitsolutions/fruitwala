<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customer";
$Page = "View-Customers";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Customer List</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once 'header_script.php'; ?>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">

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
<h4 class="font-weight-bold py-3 mb-0">Today Orders
 
</h4>

<div class="card">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
  <thead>
    <tr>
      <th>Customer Name</th>
      <th>Contact No</th>
      <th>Address</th>
      <th>Executive</th>
      <th>Package Expiry</th>
      <th>Date</th>
      <th>Delivered Status</th>
    
    </tr>
  </thead>
  <tbody>
  <?php 
  date_default_timezone_set('Asia/Kolkata');
  $today = date('Y-m-d');

  $sql = "SELECT tu.* FROM tbl_users tu WHERE tu.Roll=5 AND (
          tu.Validity IS NULL OR tu.Validity >= '$today'
      ) ORDER BY tu.CreatedDate DESC";
  $res = $conn->query($sql);
  while($row = $res->fetch_assoc()){
    $userId = $row['id'];

    // ✅ Check today's Delivered status
    $sqlDelivered = "SELECT COUNT(*) AS cnt 
                     FROM tbl_order_status_log 
                     WHERE UserId='$userId' 
                       AND Status='Delivered' 
                       AND DATE(CreatedDate)='$today'";
    $resDelivered = $conn->query($sqlDelivered);
    $deliveredCount = $resDelivered->fetch_assoc()['cnt'] ?? 0;

    // ✅ Check today's Pending status
    $sqlPending = "SELECT COUNT(*) AS cnt 
                   FROM tbl_order_status_log 
                   WHERE UserId='$userId' 
                     AND Status='Pending' 
                     AND DATE(CreatedDate)='$today'";
    $resPending = $conn->query($sqlPending);
    $pendingCount = $resPending->fetch_assoc()['cnt'] ?? 0;

    // ✅ Determine single status
    if ($deliveredCount > 0) {
        $todayStatus = "<span class='badge bg-success'>Delivered</span>";
    }  else {
        $todayStatus = "<span class='badge bg-secondary'>Pending Order</span>";
    }
    
    $AreaId = $row['AreaId'];
    $sql2 = "SELECT Fname,Lname FROM tbl_users WHERE Roll=3 AND AreaId IN($AreaId)";
    $row2 = getRecord($sql2);
  ?>
  <tr>
    <!-- 👤 Basic Info -->
    <td><?php echo $row['Fname']." ".$row['Lname']; ?></td>
    <td><?php echo $row['Phone']; ?></td>
    <td><?php echo $row['Address']; ?></td>

    <!-- ✅ Customer Account Status -->
   <td><?php echo $row2['Fname']." ".$row2['Lname'];?></td>
<td><?php echo date("d/m/Y", strtotime($row['Validity'])); ?></td>
    <!-- 📅 Registered -->
    <td><?php echo date("d/m/Y", strtotime(date('Y-m-d'))); ?></td>

    <!-- 📦 Today’s Status -->
    <td><?php echo $todayStatus; ?></td>

   
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
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

<script>
$(document).ready(function() {
  $('#example').DataTable({
    order: [[4, 'desc']] // Sort by Register Date descending
  });
});
</script>

</body>
</html>
