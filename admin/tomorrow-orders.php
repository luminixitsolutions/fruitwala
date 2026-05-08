<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Orders";
$Page = "Today Pending Orders";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Tomorrow Orders</title>
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
<h4 class="font-weight-bold py-3 mb-0">Tomorrow Orders</h4>

<?php 
date_default_timezone_set('Asia/Kolkata');
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$tomorrowDayNum = date('N', strtotime('+1 day'));
$isTomorrowSunday = ($tomorrowDayNum == 7);

if ($isTomorrowSunday) { ?>
<div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
    <div class="card-body text-center py-5" style="background: linear-gradient(135deg, #f0f7ff 0%, #e3f0ff 100%);">
        <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #6c757d 0%, #495057 100%); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);">
            <i class="fa fa-moon-o" style="font-size: 45px; color: #fff;"></i>
        </div>
        <h2 style="color: #495057; font-weight: 700; margin-bottom: 10px;">Tomorrow is Sunday</h2>
        <p style="color: #666; font-size: 16px; margin-bottom: 5px;">Tomorrow is <strong><?php echo date('l, d F Y', strtotime('+1 day')); ?></strong></p>
        <p style="color: #888; font-size: 14px; margin-bottom: 25px;">No deliveries are scheduled for Sunday. The delivery team will have a day off.</p>
        <div style="display: inline-flex; align-items: center; gap: 8px; background: #fff; padding: 12px 24px; border-radius: 30px; box-shadow: 0 3px 15px rgba(0,0,0,0.08);">
            <i class="fa fa-truck" style="color: #28a745;"></i>
            <span style="color: #555; font-size: 13px;">Deliveries will resume on <strong><?php echo date('l, d F Y', strtotime('+2 days')); ?></strong></span>
        </div>
    </div>
</div>
<?php } else { 
$today = $tomorrow;
?>

<div class="card" style="padding-right:10px;padding-left:10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
  <thead>
    <tr>
      <th>Customer Name</th>
      <th>Contact No</th>
      <th>Alternate Contact No</th>
      <th>Order Instruction</th>
       <th>Payment Status</th>
      
      <th>Address</th>
      <th>Executive</th>
        <th>Package</th>
      <th>Package Expiry</th>
      <th>Date</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
  <?php


  // ✅ Fetch all active customers whose package is valid
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

    // ✅ Check if customer has delivered today
    $checkDelivered = "
        SELECT COUNT(*) AS cnt 
        FROM tbl_order_status_log 
        WHERE UserId = '$userId' 
          AND Status = 'Delivered' 
          AND DATE(CreatedDate) = '$today'
    ";
    $resDelivered = $conn->query($checkDelivered);
    $deliveredCount = $resDelivered->fetch_assoc()['cnt'] ?? 0;

    // ❌ If delivered today, skip (we only want pending)
    if ($deliveredCount > 0) continue;

    // ✅ Get assigned executive based on AreaId
    $AreaId = $row['AreaId'];
    $sqlExec = "SELECT Fname, Lname FROM tbl_users WHERE Roll = 3 AND FIND_IN_SET('$AreaId', AreaId)";
    $execRow = $conn->query($sqlExec)->fetch_assoc();
    $executiveName = $execRow ? $execRow['Fname']." ".$execRow['Lname'] : "<span class='text-muted'>Not Assigned</span>";

    // ✅ Determine package expiry
    if (empty($row['Validity'])) {
        $pkgStatus = "<span class='text-muted'>No Package</span>";
    } elseif ($row['Validity'] >= $today) {
        $pkgStatus = "<span class='text-success'>" . date("d-M-Y", strtotime($row['Validity'])) . "</span>";
    } else {
        $pkgStatus = "<span class='text-danger'>" . date("d-M-Y", strtotime($row['Validity'])) . " (Expired)</span>";
    }

    // ✅ Today status is always pending since we filtered out delivered ones
    $todayStatus = "<span class='badge bg-warning text-dark'>Pending</span>";
    
    $sql33 = "SELECT * FROM tbl_packages WHERE id='".$row['PackageId']."'";
$row33 = getRecord($sql33);

  ?>
  <tr>
    <td><?php echo $row['Fname']." ".$row['Lname']; ?></td>
    <td><?php echo $row['Phone']; ?></td>
    <td><?php echo $row['Phone2']; ?></td>
    <td><?php echo $row['Details']; ?></td>
    <td>
<?php 
if ($row['PaidStatus'] == 1) {
    echo "<span style='color: green; font-weight: 600;'>Paid</span>";
} else {
    echo "<span style='color: red; font-weight: 600;'>Unpaid</span>";
}


?>
</td>
    <td><?php echo $row['Address']; ?></td>
    <td><?php echo $executiveName; ?></td>
    <td><?php echo $row33['Name']." (".$row33['Amount'].")"; ?></td>
    <td><?php echo $pkgStatus; ?></td>
    <td><?php echo date("d/m/Y", strtotime($today)); ?></td>
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
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

<script>
$(document).ready(function() {
  $('#example').DataTable({
    order: [[0, 'asc']],
     scrollX: true,
     dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
  });
});
</script>

</body>
</html>
