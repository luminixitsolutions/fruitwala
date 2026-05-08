<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Delivery Status";
$Page = "Customer Delivery Status";
date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Customer Delivery Status</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once 'header_script.php'; ?>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
<style>
.badge { font-size: 13px; padding: 6px 10px; border-radius: 8px; }
.btn-view-boxes { background: linear-gradient(135deg, #17a2b8, #138496); color: #fff; border: none; padding: 6px 14px; border-radius: 20px; font-size: 12px; }
.btn-view-boxes:hover { background: linear-gradient(135deg, #138496, #117a8b); color: #fff; }
.btn-manage-hold { background: linear-gradient(135deg, #fd7e14, #e96b00); color: #fff; border: none; padding: 6px 14px; border-radius: 20px; font-size: 12px; }
.btn-manage-hold:hover { background: linear-gradient(135deg, #e96b00, #d35400); color: #fff; }
#boxesContainer { display: flex; flex-wrap: wrap; gap: 6px; }
.delivery-box { width: 38px; height: 38px; min-width: 38px; border: 2px solid #ccc; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: bold; border-radius: 6px; }
.delivery-box.pending { background-color: #fff; color: #555; }
.delivery-box.delivered { background-color: #28a745; color: #fff; border-color: #28a745; }
.delivery-box.sunday { background-color: #dc3545; color: #fff; border-color: #dc3545; }
.delivery-box.hold { background-color: #fd7e14; color: #fff; border-color: #fd7e14; }
.boxes-legend { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 15px; font-size: 13px; }
.boxes-legend span { display: inline-flex; align-items: center; gap: 5px; }
.legend-box { width: 18px; height: 18px; border-radius: 4px; display: inline-block; }
.legend-box.delivered { background: #28a745; }
.legend-box.pending { background: #fff; border: 2px solid #ccc; }
.legend-box.sunday { background: #dc3545; }
.legend-box.hold { background: #fd7e14; }
.hold-date-item { display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background: #fff3e0; border-radius: 8px; margin-bottom: 8px; }
.hold-date-item .date-text { font-weight: 600; color: #e65100; }
.hold-date-item .btn-delete { background: #dc3545; color: #fff; border: none; padding: 4px 10px; border-radius: 15px; font-size: 11px; cursor: pointer; }
.hold-date-item .btn-delete:hover { background: #c82333; }
.hold-date-item.past-hold { background: #f5f5f5; }
.hold-date-item.past-hold .date-text { color: #777; }
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
<h4 class="font-weight-bold py-3 mb-0">Customer Delivery Status</h4>
<p class="text-muted mb-3">View delivery boxes, manage hold dates, and track customer delivery progress</p>

<div class="card" style="padding: 15px;">
<div class="card-datatable table-responsive">
<table id="customerTable" class="table table-striped table-bordered" style="width:100%">
  <thead>
    <tr>
      <th>Customer Name</th>
      <th>Phone</th>
      <th>Package</th>
      <th>Start Date</th>
      <th>End Date</th>
      <th>Extended To</th>
      <th>Hold Days</th>
      <th>Total Delivered</th>
      <th>Today Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php 
  $today = date('Y-m-d');
  $sql = "SELECT u.id, u.Fname, u.Lname, u.Phone, u.PkgDate, u.Validity, p.Name as PkgName 
          FROM tbl_users u 
          LEFT JOIN tbl_packages p ON u.PackageId = p.id 
          WHERE u.Roll = 5 AND u.Status = 1 AND (u.Validity IS NULL OR u.Validity >= '$today')
          ORDER BY u.Fname ASC";
  $res = $conn->query($sql);
  
  while($row = $res->fetch_assoc()) {
    $custId = $row['id'];
    $holdCount = 0;
    $deliveredCount = 0;
    $extendedDate = '';
    
    if (!empty($row['PkgDate'])) {
      $originalEnd = !empty($row['Validity']) ? $row['Validity'] : $row['PkgDate'];
      
      $holdRes = $conn->query("SELECT COUNT(*) as cnt FROM tbl_order_hold_dates WHERE order_id = '$custId' AND hold_date >= '{$row['PkgDate']}' AND hold_date <= '$originalEnd'");
      $holdCount = $holdRes->fetch_assoc()['cnt'] ?? 0;
      
      $delRes = $conn->query("SELECT COUNT(*) as cnt FROM tbl_order_delivery_boxes WHERE order_id = '$custId' AND status = 'delivered'");
      $deliveredCount = $delRes->fetch_assoc()['cnt'] ?? 0;
      
      // Check today's delivery status
      $todayStatusRes = $conn->query("SELECT COUNT(*) as cnt FROM tbl_order_status_log WHERE UserId = '$custId' AND Status = 'Delivered' AND DATE(CreatedDate) = '$today'");
      $isTodayDelivered = ($todayStatusRes->fetch_assoc()['cnt'] ?? 0) > 0;
      
      if ($holdCount > 0) {
        $endDt = new DateTime($originalEnd);
        $daysToAdd = $holdCount;
        while ($daysToAdd > 0) {
          $endDt->modify('+1 day');
          if ($endDt->format('N') != 7) $daysToAdd--;
        }
        $extendedDate = $endDt->format('Y-m-d');
      }
    }
  ?>
  <tr>
    <td><strong><?php echo $row['Fname'] . " " . $row['Lname']; ?></strong></td>
    <td><?php echo $row['Phone']; ?></td>
    <td><?php echo $row['PkgName'] ?: '<span class="text-muted">N/A</span>'; ?></td>
    <td><?php echo $row['PkgDate'] ? date('d-m-Y', strtotime($row['PkgDate'])) : '-'; ?></td>
    <td><?php echo $row['Validity'] ? date('d-m-Y', strtotime($row['Validity'])) : '-'; ?></td>
    <td>
      <?php if (!empty($extendedDate)) { ?>
        <span class="text-success font-weight-bold"><?php echo date('d-m-Y', strtotime($extendedDate)); ?></span>
      <?php } else { ?>
        <span class="text-muted">-</span>
      <?php } ?>
    </td>
    <td>
      <?php if ($holdCount > 0) { ?>
        <span class="badge bg-warning text-dark"><?php echo $holdCount; ?> days</span>
      <?php } else { ?>
        <span class="text-muted">0</span>
      <?php } ?>
    </td>
    <td><span class="badge bg-success"><?php echo $deliveredCount; ?></span></td>
    <td>
      <?php if (isset($isTodayDelivered) && $isTodayDelivered) { ?>
        <span class="badge" style="background: linear-gradient(135deg, #28a745, #1e7e34); color: #fff; padding: 6px 12px; border-radius: 20px; font-size: 11px;"><i class="fa fa-check-circle"></i> Delivered</span>
      <?php } else { ?>
        <span class="badge" style="background: linear-gradient(135deg, #ffc107, #e0a800); color: #000; padding: 6px 12px; border-radius: 20px; font-size: 11px;"><i class="fa fa-clock"></i> Pending</span>
      <?php } ?>
    </td>
    <td>
      <button class="btn-view-boxes" onclick="viewBoxes(<?php echo $custId; ?>, '<?php echo addslashes($row['Fname'] . ' ' . $row['Lname']); ?>')">
        <i class="fa fa-th"></i> Boxes
      </button>
      <button class="btn-manage-hold" onclick="manageHold(<?php echo $custId; ?>, '<?php echo addslashes($row['Fname'] . ' ' . $row['Lname']); ?>')">
        <i class="fa fa-pause"></i> Hold
      </button>
    </td>
  </tr>
  <?php } ?>
  </tbody>
</table>
</div>
</div>
</div>

<!-- Boxes Modal -->
<div class="modal fade" id="boxesModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title"><i class="fa fa-th"></i> Delivery Boxes - <span id="boxesCustomerName"></span></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div id="boxesLoading" class="text-center py-4"><div class="spinner-border text-info"></div><p class="mt-2">Loading...</p></div>
        <div id="boxesContent" style="display:none;">
          <div class="row mb-3">
            <div class="col-4"><label class="text-muted small">Start Date</label><div id="boxesPkgStart" class="font-weight-bold"></div></div>
            <div class="col-4"><label class="text-muted small">Original End</label><div id="boxesPkgEnd" class="font-weight-bold"></div></div>
            <div class="col-4"><label class="text-muted small">Extended To</label><div id="boxesExtendedEnd" class="font-weight-bold text-success"></div></div>
          </div>
          <p id="boxesSummary" class="font-weight-bold mb-2"></p>
          <div class="boxes-legend">
            <span><i class="legend-box delivered"></i> Delivered</span>
            <span><i class="legend-box pending"></i> Pending</span>
            <span><i class="legend-box hold"></i> Hold (H)</span>
            <span><i class="legend-box sunday"></i> Sunday (S)</span>
          </div>
          <div id="boxesContainer"></div>
        </div>
        <div id="boxesError" class="alert alert-warning" style="display:none;"></div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button></div>
    </div>
  </div>
</div>

<!-- Hold Dates Modal -->
<div class="modal fade" id="holdModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title"><i class="fa fa-pause"></i> Manage Hold Dates - <span id="holdCustomerName"></span></h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="holdCustomerId">
        <div id="holdLoading" class="text-center py-3"><div class="spinner-border text-warning"></div></div>
        <div id="holdContent" style="display:none;">
          <h6 class="mb-3"><i class="fa fa-calendar-check text-warning"></i> Upcoming Hold Dates</h6>
          <div id="holdFutureDatesList"></div>
          <hr>
          <h6 class="mb-3"><i class="fa fa-history text-secondary"></i> Past Hold Dates</h6>
          <div id="holdPastDatesList"></div>
          <hr>
          <h6 class="mb-3"><i class="fa fa-plus-circle text-success"></i> Add New Hold Date</h6>
          <div class="input-group mb-3">
            <input type="date" class="form-control" id="newHoldDate" min="<?php echo date('Y-m-d'); ?>">
            <button class="btn btn-warning" type="button" onclick="addHoldDate()"><i class="fa fa-plus"></i> Add</button>
          </div>
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button></div>
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
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script>
$(document).ready(function() {
  $('#customerTable').DataTable({
    order: [[0, 'asc']],
    pageLength: 25,
    scrollX: true,
    dom: 'Bfrtip',
    buttons: [
      {
        extend: 'excelHtml5',
        text: '<i class="fa fa-file-excel"></i> Export Excel',
        className: 'btn btn-success btn-sm',
        title: 'Customer Delivery Status',
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
        }
      }
    ]
  });
});

function formatDate(ymd) {
  if (!ymd) return '-';
  var p = ymd.split('-');
  return p[2] + '-' + p[1] + '-' + p[0];
}

var currentCustomerId = null;

function viewBoxes(custId, custName) {
  currentCustomerId = custId;
  $('#boxesCustomerName').text(custName);
  $('#boxesLoading').show();
  $('#boxesContent, #boxesError').hide();
  $('#boxesModal').modal('show');
  
  $.ajax({
    url: 'customer_boxes_action.php',
    type: 'GET',
    dataType: 'json',
    data: { action: 'get_boxes', customer_id: custId },
    success: function(res) {
      $('#boxesLoading').hide();
      if (res.status === 'success' && res.data) {
        $('#boxesPkgStart').text(formatDate(res.data.pkg_start_date));
        $('#boxesPkgEnd').text(formatDate(res.data.pkg_end_date));
        $('#boxesExtendedEnd').text(res.data.extended_end_date !== res.data.pkg_end_date ? formatDate(res.data.extended_end_date) : '-');
        var summary = 'Delivered: ' + res.data.delivered_count + ' / ' + res.data.total_days;
        if (res.data.hold_count > 0) summary += ' | Hold: ' + res.data.hold_count + ' (Extended)';
        if (res.data.sunday_count > 0) summary += ' | Sundays: ' + res.data.sunday_count;
        $('#boxesSummary').text(summary);
        var html = '';
        (res.data.boxes || []).forEach(function(b) {
          html += '<div class="delivery-box ' + b.type + '" title="' + formatDate(b.date) + '">' + b.label + '</div>';
        });
        $('#boxesContainer').html(html);
        $('#boxesContent').show();
      } else {
        $('#boxesError').text(res.message || 'Could not load boxes.').show();
      }
    },
    error: function() { $('#boxesLoading').hide(); $('#boxesError').text('Server error.').show(); }
  });
}

function manageHold(custId, custName) {
  currentCustomerId = custId;
  $('#holdCustomerName').text(custName);
  $('#holdCustomerId').val(custId);
  $('#holdLoading').show();
  $('#holdContent').hide();
  $('#holdModal').modal('show');
  loadHoldDates(custId);
}

function loadHoldDates(custId) {
  $.ajax({
    url: 'customer_boxes_action.php',
    type: 'GET',
    dataType: 'json',
    data: { action: 'get_hold_dates', customer_id: custId },
    success: function(res) {
      $('#holdLoading').hide();
      if (res.status === 'success') {
        var futureHtml = '';
        var pastHtml = '';
        var futureDates = res.dates && res.dates.future ? res.dates.future : [];
        var pastDates = res.dates && res.dates.past ? res.dates.past : [];
        
        if (futureDates.length > 0) {
          futureDates.forEach(function(d) {
            futureHtml += '<div class="hold-date-item"><span class="date-text">' + formatDate(d) + '</span><button class="btn-delete" onclick="deleteHoldDate(' + custId + ', \'' + d + '\')"><i class="fa fa-trash"></i></button></div>';
          });
        } else {
          futureHtml = '<p class="text-muted">No upcoming hold dates.</p>';
        }
        
        if (pastDates.length > 0) {
          pastDates.forEach(function(d) {
            pastHtml += '<div class="hold-date-item past-hold"><span class="date-text">' + formatDate(d) + '</span><span class="badge bg-secondary">Past</span></div>';
          });
        } else {
          pastHtml = '<p class="text-muted">No past hold dates.</p>';
        }
        
        $('#holdFutureDatesList').html(futureHtml);
        $('#holdPastDatesList').html(pastHtml);
        $('#holdContent').show();
      }
    }
  });
}

function addHoldDate() {
  var custId = $('#holdCustomerId').val();
  var holdDate = $('#newHoldDate').val();
  if (!holdDate) { alert('Please select a date.'); return; }
  
  var dateObj = new Date(holdDate);
  if (dateObj.getDay() === 0) {
    Swal.fire({
      icon: 'info',
      title: 'Sunday - No Delivery Day',
      html: '<div style="text-align:center;"><i class="fa fa-calendar-times" style="font-size:48px;color:#6c757d;margin-bottom:15px;"></i><p style="color:#555;margin:0;">Sundays are already <strong>delivery off</strong> days.</p><p style="color:#888;font-size:13px;margin-top:8px;">Hold dates are only needed for working days.<br>Please select a weekday (Mon-Sat).</p></div>',
      confirmButtonText: 'Got it',
      confirmButtonColor: '#17a2b8'
    });
    return;
  }
  
  $.ajax({
    url: 'customer_boxes_action.php',
    type: 'POST',
    dataType: 'json',
    data: { action: 'add_hold_date', customer_id: custId, hold_date: holdDate },
    success: function(res) {
      if (res.status === 'success') {
        $('#newHoldDate').val('');
        loadHoldDates(custId);
        Swal.fire({ icon: 'success', title: 'Added', text: res.message, toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
      } else {
        Swal.fire({ icon: 'error', title: 'Error', text: res.message, toast: true, position: 'top-end', timer: 2500, showConfirmButton: false });
      }
    }
  });
}

function deleteHoldDate(custId, holdDate) {
  if (!confirm('Delete this hold date?')) return;
  
  $.ajax({
    url: 'customer_boxes_action.php',
    type: 'POST',
    dataType: 'json',
    data: { action: 'delete_hold_date', customer_id: custId, hold_date: holdDate },
    success: function(res) {
      if (res.status === 'success') {
        loadHoldDates(custId);
        Swal.fire({ icon: 'success', title: 'Deleted', text: res.message, toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
      } else {
        Swal.fire({ icon: 'error', title: 'Error', text: res.message });
      }
    }
  });
}
</script>
</body>
</html>
