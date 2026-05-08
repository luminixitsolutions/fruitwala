<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Orders";
$Page = "Today Delivered Orders";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Today Delivered Orders</title>
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
<h4 class="font-weight-bold py-3 mb-0">Today Delivered Orders</h4>

<?php 
date_default_timezone_set('Asia/Kolkata');
$today = date('Y-m-d');
$isSunday = (date('N') == 7);

if ($isSunday) { ?>
<div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
    <div class="card-body text-center py-5" style="background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);">
        <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);">
            <i class="fa fa-calendar-times-o" style="font-size: 45px; color: #fff;"></i>
        </div>
        <h2 style="color: #c82333; font-weight: 700; margin-bottom: 10px;">Sunday - Delivery Off</h2>
        <p style="color: #666; font-size: 16px; margin-bottom: 5px;">Today is <strong><?php echo date('l, d F Y'); ?></strong></p>
        <p style="color: #888; font-size: 14px; margin-bottom: 25px;">No deliveries are scheduled on Sundays. Enjoy your day off!</p>
        <div style="display: inline-flex; align-items: center; gap: 8px; background: #fff; padding: 12px 24px; border-radius: 30px; box-shadow: 0 3px 15px rgba(0,0,0,0.08);">
            <i class="fa fa-info-circle" style="color: #17a2b8;"></i>
            <span style="color: #555; font-size: 13px;">Deliveries will resume on <strong><?php echo date('l, d F Y', strtotime('+1 day')); ?></strong></span>
        </div>
    </div>
</div>
<?php } else { ?>

<div class="card" style="padding-right:10px;padding-left:10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered " style="width:100%">
  <thead>
    <tr>
      <th>Customer Name</th>
      <th>Contact No</th>
      <th>Address</th>
      <th>Executive</th>
        <th>Box Collect</th>
      <th>Date</th>
      <th>Package Expiry</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
  <?php

  // ✅ Fetch all customers with valid packages
  $sql = "
    SELECT tu.* 
    FROM tbl_users tu 
    WHERE tu.Roll = 5 
      AND tu.Status = 1
      AND (tu.Validity IS NULL OR tu.Validity >= '$today')
    ORDER BY tu.Fname ASC
  ";
  $res = $conn->query($sql);

  while($row = $res->fetch_assoc()){
    $userId = $row['id'];

    // ✅ Check if this customer has a delivered record for today
    $checkDelivered = "
        SELECT COUNT(*) AS cnt,BoxQty 
        FROM tbl_order_status_log 
        WHERE UserId = '$userId' 
          AND Status = 'Delivered' 
          AND DATE(CreatedDate) = '$today'
    ";
   $resDelivered = $conn->query($checkDelivered);
$deliveredRow = $resDelivered->fetch_assoc();

$deliveredCount = $deliveredRow['cnt'] ?? 0;
$deliveredBoxCount = $deliveredRow['BoxQty'] ?? 0;

    // ❌ Skip customers not delivered today
    if ($deliveredCount == 0) continue;

    // ✅ Get assigned executive based on AreaId
    $AreaId = $row['AreaId'];
    $sqlExec = "SELECT Fname, Lname FROM tbl_users WHERE Roll = 3 AND FIND_IN_SET('$AreaId', AreaId)";
    $execRow = $conn->query($sqlExec)->fetch_assoc();
    $executiveName = $execRow ? $execRow['Fname']." ".$execRow['Lname'] : "<span class='text-muted'>Not Assigned</span>";

    // ✅ Package expiry info
    if (empty($row['Validity'])) {
        $pkgStatus = "<span class='text-muted'>No Package</span>";
    } elseif ($row['Validity'] >= $today) {
        $pkgStatus = "<span class='text-success'>" . date("d-M-Y", strtotime($row['Validity'])) . "</span>";
    } else {
        $pkgStatus = "<span class='text-danger'>" . date("d-M-Y", strtotime($row['Validity'])) . " (Expired)</span>";
    }

    // ✅ Status (Delivered)
    $todayStatus = "<span class='badge bg-success'>Delivered</span>";
  ?>
  <tr>
    <td><?php echo $row['Fname']." ".$row['Lname']; ?></td>
    <td><?php echo $row['Phone']; ?></td>
    <td><?php echo $row['Address']; ?></td>
    <td><?php echo $executiveName; ?></td>
    <td><?php echo $deliveredBoxCount; ?></td>
    <td><?php echo date("d/m/Y", strtotime($today)); ?></td>
    <td><?php echo $pkgStatus; ?></td>
    <td><?php echo $todayStatus; ?></td>
  </tr>
  <?php } ?>
  </tbody>
</table>
</div>
</div>
<?php } ?>
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
        title: 'today_delievred_orders',
        text: '<i class="fa fa-file-excel"></i> Excel',
        className: 'btn btn-success btn-sm'
      }
    ]
  });
});
</script>

</body>
</html>
