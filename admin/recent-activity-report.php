<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Reports";
$Page = "Recent Activity Report";

$filterActive = isset($_POST['apply_filter']);

$where = ['u.Roll = 5'];

if ($filterActive) {
    $fromDate = trim($_POST['FromDate'] ?? '');
    $toDate = trim($_POST['ToDate'] ?? '');
    if ($fromDate !== '') {
        $fromDate = $conn->real_escape_string($fromDate);
        $where[] = "DATE(osl.CreatedDate) >= '$fromDate'";
    }
    if ($toDate !== '') {
        $toDate = $conn->real_escape_string($toDate);
        $where[] = "DATE(osl.CreatedDate) <= '$toDate'";
    }

    $fromTime = trim($_POST['FromTime'] ?? '');
    $toTime = trim($_POST['ToTime'] ?? '');
    $normTime = function ($t) {
        if ($t === '') {
            return '';
        }
        return strlen($t) === 5 ? $t . ':00' : $t;
    };
    $fromTimeSql = $normTime($fromTime);
    $toTimeSql = $normTime($toTime);
    if ($fromTimeSql !== '' && $toTimeSql !== '') {
        $fromTimeSql = $conn->real_escape_string($fromTimeSql);
        $toTimeSql = $conn->real_escape_string($toTimeSql);
        $where[] = "TIME(osl.CreatedDate) BETWEEN '$fromTimeSql' AND '$toTimeSql'";
    } elseif ($fromTimeSql !== '') {
        $fromTimeSql = $conn->real_escape_string($fromTimeSql);
        $where[] = "TIME(osl.CreatedDate) >= '$fromTimeSql'";
    } elseif ($toTimeSql !== '') {
        $toTimeSql = $conn->real_escape_string($toTimeSql);
        $where[] = "TIME(osl.CreatedDate) <= '$toTimeSql'";
    }

    $custId = $_POST['CustId'] ?? 'all';
    if ($custId !== 'all' && $custId !== '') {
        $cid = (int) $custId;
        if ($cid > 0) {
            $where[] = "osl.UserId = $cid";
        }
    }
}

$whereSql = implode(' AND ', $where);
$sqlActivity = "
    SELECT osl.*, u.Fname, u.Lname
    FROM tbl_order_status_log osl
    INNER JOIN tbl_users u ON osl.UserId = u.id
    WHERE $whereSql
    ORDER BY osl.CreatedDate DESC
";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Recent Activity Report</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once 'header_script.php'; ?>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">

<style>
div.dt-buttons {
  margin-bottom: 10px;
}
.badge-status {
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
<h4 class="font-weight-bold py-3 mb-0">Recent Activity Report</h4>
<p class="text-muted mb-3">Order status events from the dashboard &ldquo;Recent Activity&rdquo; feed. Leave filters empty to list all records (newest first).</p>

<div class="card mb-3">
  <div class="card-body py-3">
    <form method="post" action="" class="form-row align-items-end">
      <div class="form-group col-md-3 col-lg-3">
        <label class="form-label">Customer name</label>
        <select class="select2-demo form-control" name="CustId" id="CustId">
          <option value="all">All customers</option>
          <?php
          $custRes = $conn->query("SELECT id, Fname, Lname FROM tbl_users WHERE Roll=5 ORDER BY Fname ASC, Lname ASC");
          if ($custRes) {
              $postCust = $_POST['CustId'] ?? 'all';
              while ($c = $custRes->fetch_assoc()) {
                  $sel = ($postCust == $c['id']) ? ' selected' : '';
                  $label = htmlspecialchars(trim($c['Fname'] . ' ' . $c['Lname']), ENT_QUOTES, 'UTF-8');
                  echo '<option value="' . (int) $c['id'] . '"' . $sel . '>' . $label . '</option>';
              }
          }
          ?>
        </select>
      </div>
      <div class="form-group col-md-2 col-lg-2">
        <label class="form-label">From date</label>
        <input type="date" name="FromDate" class="form-control" value="<?php echo htmlspecialchars($_POST['FromDate'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off">
      </div>
      <div class="form-group col-md-2 col-lg-2">
        <label class="form-label">To date</label>
        <input type="date" name="ToDate" class="form-control" value="<?php echo htmlspecialchars($_POST['ToDate'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off">
      </div>
      <div class="form-group col-md-2 col-lg-1">
        <label class="form-label">From time</label>
        <input type="time" name="FromTime" class="form-control" value="<?php echo htmlspecialchars($_POST['FromTime'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" step="60">
      </div>
      <div class="form-group col-md-2 col-lg-1">
        <label class="form-label">To time</label>
        <input type="time" name="ToTime" class="form-control" value="<?php echo htmlspecialchars($_POST['ToTime'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" step="60">
      </div>
      <div class="form-group col-md-6 col-lg-3 mt-3 mt-lg-0">
        <button type="submit" name="apply_filter" value="1" class="btn btn-primary mr-2">Apply filters</button>
        <?php if ($filterActive) { ?>
        <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline-secondary">Clear</a>
        <?php } ?>
      </div>
    </form>
    <small class="text-muted d-block mt-2">Time filters apply to the clock time of each event (optional). Combine with dates to narrow a specific day and window.</small>
  </div>
</div>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="recentActivityTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
  <thead>
    <tr>
      <th>Status</th>
      <th>Activity</th>
      <th>Customer name</th>
      <th>Date</th>
      <th>Time</th>
    </tr>
  </thead>
  <tbody>
<?php
$resActivity = $conn->query($sqlActivity);

if ($resActivity && $resActivity->num_rows > 0) {
    while ($row = $resActivity->fetch_assoc()) {
        $userName = ucfirst(trim($row['Fname'] . ' ' . $row['Lname']));
        $userNameEsc = htmlspecialchars($userName, ENT_QUOTES, 'UTF-8');
        $status = $row['Status'];
        $statusLabel = htmlspecialchars(ucfirst($status), ENT_QUOTES, 'UTF-8');

        if ($status === 'Delivered') {
            $badgeClass = 'badge-success';
            $activity = 'Order delivered for';
        } elseif ($status === 'Pending') {
            $badgeClass = 'badge-warning';
            $activity = 'Order pending for';
        } elseif ($status === 'Cancelled') {
            $badgeClass = 'badge-danger';
            $activity = 'Order cancelled by';
        } else {
            $badgeClass = 'badge-secondary';
            $activity = 'Status updated (' . ucfirst($status) . ') for';
        }

        $datePart = date('d M', strtotime($row['CreatedDate']));
        $timePart = date('h:i A', strtotime($row['CreatedDate']));

        echo '<tr>';
        echo '<td data-order="' . htmlspecialchars($status, ENT_QUOTES, 'UTF-8') . '"><span class="badge ' . $badgeClass . ' badge-status">' . $statusLabel . '</span></td>';
        echo '<td>' . htmlspecialchars($activity, ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . $userNameEsc . '</td>';
        echo '<td data-order="' . htmlspecialchars($row['CreatedDate'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($datePart, ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($timePart, ENT_QUOTES, 'UTF-8') . '</td>';
        echo '</tr>';
    }
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
<script>
$(document).ready(function() {
  $('#recentActivityTable').DataTable({
    order: [[3, 'desc']],
    scrollX: true,
    pageLength: 25,
    dom: 'Bfrtip',
    buttons: [
      'excelHtml5',
      'csvHtml5'
    ]
  });
});
</script>

</body>
</html>
