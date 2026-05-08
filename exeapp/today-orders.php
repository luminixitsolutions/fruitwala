<?php 
session_start();
require_once 'config.php';
$PageName = "Today's Orders";
$UserId = $_SESSION['User']['id']; 
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $Proj_Title; ?> - <?php echo $PageName; ?></title>

    <!-- Google & Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap & Custom CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }
        .order-tabs {
            display: flex;
            justify-content: center;
            gap: 10px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 16px;
            padding: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .tab-btn {
            flex: 1;
            border: none;
            background: transparent;
            color: #666;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .tab-btn:hover {
            background: rgba(0,123,255,0.1);
            color: #007bff;
        }
        .tab-btn.active {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: #fff;
            box-shadow: 0 4px 15px rgba(0,123,255,0.35);
        }
        .tab-btn .tab-count {
            background: rgba(0,0,0,0.15);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            margin-left: 4px;
        }
        .tab-btn.active .tab-count {
            background: rgba(255,255,255,0.25);
        }
        .order-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            margin-bottom: 14px;
            padding: 16px;
            border-left: 5px solid;
            border-image: linear-gradient(180deg, #007bff, #00c6ff) 1;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .order-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(0,123,255,0.05) 0%, transparent 100%);
            border-radius: 0 16px 0 100%;
        }
        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .order-card.delivered-card {
            border-image: linear-gradient(180deg, #28a745, #20c997) 1;
        }
        .order-card.delivered-card::before {
            background: linear-gradient(135deg, rgba(40,167,69,0.05) 0%, transparent 100%);
        }
        .order-card.unpaid-card {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
            border-left-color: #dc3545;
            border-image: linear-gradient(180deg, #dc3545, #e96b6b) 1;
        }
        .order-card.unpaid-card::before {
            background: linear-gradient(135deg, rgba(220,53,69,0.08) 0%, transparent 100%);
        }
        @keyframes unpaid-blink {
            0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.5); }
            50% { opacity: 0.95; box-shadow: 0 0 12px 4px rgba(220, 53, 69, 0.35); }
        }
        .badge-unpaid {
            animation: unpaid-blink 1.8s ease-in-out infinite;
        }
        .customer-name {
            font-weight: 700;
            font-size: 16px;
            color: #1a1a2e;
            margin-bottom: 8px;
            letter-spacing: -0.3px;
        }
        .customer-details {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .detail-row {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 13px;
            color: #666;
            line-height: 1.4;
        }
        .detail-row i {
            color: #007bff;
            font-size: 12px;
            margin-top: 2px;
            width: 14px;
            text-align: center;
        }
        .status-badges {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }
        .action-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #f0f0f0;
        }
        .btn-action {
            border: none;
            border-radius: 25px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-direction {
            background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
            color: #fff;
        }
        .btn-boxes {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: #fff;
        }
        .btn-hold {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #000;
        }
        .btn-hold-view {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: #fff;
        }
        .btn-delivered {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: #fff;
        }
        .badge-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-pending {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
            color: #856404;
            border: 1px solid #ffc107;
        }
        .badge-delivered {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #28a745;
        }
        .badge-hold {
            background: linear-gradient(135deg, #ffe5d0 0%, #ffd4b8 100%);
            color: #c35a00;
            border: 1px solid #fd7e14;
            font-size: 10px;
            padding: 5px 10px;
            margin-left: 8px;
        }
        .badge-paid {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #28a745;
            font-size: 10px;
            padding: 5px 10px;
        }
        .badge-unpaid {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 1px solid #dc3545;
            font-size: 10px;
            padding: 5px 10px;
            font-weight: 700;
        }
        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #888;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .no-data i {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 15px;
            display: block;
        }
        #boxesContainer {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            gap: 6px;
        }
        .delivery-box {
            width: 36px;
            height: 36px;
            min-width: 36px;
            min-height: 36px;
            flex-shrink: 0;
            border: 2px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: bold;
            border-radius: 6px;
            box-sizing: border-box;
        }
        .delivery-box.pending {
            background-color: #ffffff;
            color: #555;
            border-color: #ccc;
        }
        .delivery-box.delivered {
            background-color: #28a745;
            color: #fff;
            border-color: #28a745;
        }
        .delivery-box.sunday {
            background-color: #dc3545;
            color: #fff;
            border-color: #dc3545;
        }
        .delivery-box.hold {
            background-color: #fd7e14;
            color: #fff;
            border-color: #fd7e14;
        }
        #boxesSummary { font-weight: 600; color: #333; }
        .boxes-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 12px;
        }
        .boxes-legend span {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .legend-box {
            width: 16px;
            height: 16px;
            border-radius: 3px;
            display: inline-block;
        }
        .legend-box.delivered { background: #28a745; }
        .legend-box.pending { background: #fff; border: 1px solid #ccc; }
        .legend-box.sunday { background: #dc3545; }
        .legend-box.hold { background: #fd7e14; }
        .badge-status {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-pending {
            background: #ffeeba;
            color: #856404;
        }
        .badge-delivered {
            background: #d4edda;
            color: #155724;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #888;
        }
    </style>
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">

<main class="flex-shrink-0 main">
    <?php include_once 'back-header.php'; ?>

    <div class="container mt-3 mb-5">
      <?php
date_default_timezone_set('Asia/Kolkata');
$today = date('Y-m-d');
$isSunday = (date('N') == 7);

if ($isSunday) {
    // Sunday - No Delivery Message
    ?>
    <div style="display: flex; justify-content: center; align-items: center; min-height: 60vh;">
        <div style="text-align: center; background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%); border-radius: 20px; padding: 40px 30px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); max-width: 380px;">
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i class="fa fa-calendar-times" style="font-size: 36px; color: #fff;"></i>
            </div>
            <h3 style="color: #333; font-weight: 700; margin-bottom: 10px;">Sunday - Delivery Off</h3>
            <p style="color: #666; font-size: 15px; margin-bottom: 15px;">Today is <strong style="color: #dc3545;"><?php echo date('l, d F Y'); ?></strong></p>
            <p style="color: #888; font-size: 14px; margin-bottom: 0;">No deliveries are scheduled for today.<br>Enjoy your day off!</p>
            <hr style="margin: 20px 0; border-color: #eee;">
            <p style="color: #28a745; font-size: 13px; margin: 0;"><i class="fa fa-clock"></i> Deliveries resume on <strong>Monday</strong></p>
        </div>
    </div>
    <?php
} else {

$UserId = $_SESSION['User']['id'] ?? 0;

// Ensure hold dates table exists (for exclusion query below)
$conn->query("CREATE TABLE IF NOT EXISTS tbl_order_hold_dates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    hold_date DATE NOT NULL,
    created_by INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_order_hold (order_id, hold_date)
)");

// Step 1: Get executive’s AreaId list (e.g. "1,2,3")
$sqlArea = "SELECT AreaId FROM tbl_users WHERE id='$UserId' LIMIT 1";
$resArea = $conn->query($sqlArea);
$rowArea = $resArea->fetch_assoc();
$AreaIds = $rowArea['AreaId'] ?? '';

if (empty($AreaIds)) {
    $AreaIds = '0'; // prevent SQL error if no area assigned
}

// Convert "1,2,3" → array [1,2,3]
$areaArray = array_map('trim', explode(',', $AreaIds));

// Step 2: Build dynamic SQL IN clause
$areaList = implode(',', array_map('intval', $areaArray));

// Step 3: Fetch customers only in those areas (exclude customers on hold for today)
$sql = "
    SELECT id, Fname, Lname, Phone, Address, PackageId, PkgAmt, PkgDate, Validity, Lattitude, Longitude, LocationLink, PaidStatus 
    FROM tbl_users 
    WHERE Roll=5 
      AND Status=1 
      AND AreaId IN ($areaList)
      AND (Validity IS NULL OR Validity >= '$today')
      AND id NOT IN (SELECT order_id FROM tbl_order_hold_dates WHERE hold_date = '$today')
";
$rows = getList($sql);

// Separate arrays for pending & delivered
$pending = [];
$delivered = [];

foreach ($rows as $result) {
    $userId = $result['id'];

    // Step 4: Check if delivered today
    $check = "SELECT COUNT(*) as cnt 
              FROM tbl_order_status_log 
              WHERE UserId='$userId' 
              AND Status='Delivered' 
              AND DATE(CreatedDate)='$today'";
    $chkRes = $conn->query($check);
    $cnt = $chkRes->fetch_assoc()['cnt'] ?? 0;

    if ($cnt > 0) {
        $delivered[] = $result;
    } else {
        $pending[] = $result;
    }
}

// Order IDs that have future hold dates (for "On Hold" badge)
$holdOrderIds = [];
$holdRes = $conn->query("SELECT DISTINCT order_id FROM tbl_order_hold_dates WHERE hold_date >= '$today'");
if ($holdRes) {
    while ($hr = $holdRes->fetch_assoc()) {
        $holdOrderIds[] = (int) $hr['order_id'];
    }
}
?>

<!-- Tabs -->
<div class="order-tabs">
    <button class="tab-btn active" id="tab-pending" onclick="showTab('pending')">
        <i class="fa fa-clock"></i> Pending
        <span class="tab-count"><?php echo count($pending); ?></span>
    </button>
    <button class="tab-btn" id="tab-delivered" onclick="showTab('delivered')">
        <i class="fa fa-check-circle"></i> Delivered
        <span class="tab-count"><?php echo count($delivered); ?></span>
    </button>
</div>

<!-- Pending Orders -->
<div id="pending-orders">
    <?php if (count($pending) > 0) { 
        foreach ($pending as $result) { 
            $isUnpaid = !isset($result['PaidStatus']) || $result['PaidStatus'] != 1;
        ?>
    <div class="order-card<?php echo $isUnpaid ? ' unpaid-card' : ''; ?>">
        <div class="status-badges">
            <span class="badge-status badge-pending">Pending</span>
            <?php if (in_array((int)$result['id'], $holdOrderIds)) { ?>
            <span class="badge-status badge-hold">On Hold</span>
            <?php } ?>
            <?php if (isset($result['PaidStatus']) && $result['PaidStatus'] == 1) { ?>
            <span class="badge-status badge-paid"><i class="fa fa-check-circle"></i> Paid</span>
            <?php } else { ?>
            <span class="badge-status badge-unpaid"><i class="fa fa-clock-o"></i> Unpaid</span>
            <?php } ?>
        </div>
        <div class="customer-name"><?php echo ucfirst($result['Fname'] . " " . $result['Lname']); ?></div>
        <div class="customer-details">
            <div class="detail-row">
                <i class="fa fa-phone"></i>
                <span><?php echo $result['Phone']; ?></span>
            </div>
            <div class="detail-row">
                <i class="fa fa-map-marker-alt"></i>
                <span><?php echo $result['Address']; ?></span>
            </div>
        </div>
        <div class="action-buttons">
            <?php $url = $result['LocationLink']; if($url!=''){?>
            <a href="javascript:void(0)" onclick="goToWebsite('<?php echo $url;?>')" class="btn-action btn-direction">
                <i class="fa fa-location-arrow"></i> Direction
            </a>
            <?php } ?>
            <button type="button" class="btn-action btn-boxes" onclick="openBoxesModal(<?php echo (int)$result['id']; ?>)" title="Delivery boxes status">
                <i class="fa fa-th"></i> Boxes
            </button>
            <button type="button" class="btn-action btn-hold" onclick="openHoldModal(<?php echo (int)$result['id']; ?>)" title="Add hold dates">
                <i class="fa fa-pause"></i> Hold
            </button>
            <button class="btn-action btn-delivered" onclick="markDelivered(<?php echo $result['id']; ?>)">
                <i class="fa fa-check"></i> Done
            </button>
        </div>
    </div>
    <?php } } else { ?>
        <div class="no-data">
            <i class="fa fa-inbox"></i>
            No Pending Orders Found
        </div>
    <?php } ?>
</div>

<!-- Delivered Orders -->
<div id="delivered-orders" style="display:none;">
    <?php if (count($delivered) > 0) {
        foreach ($delivered as $result2) { 
            $hasHoldDates2 = in_array((int)$result2['id'], $holdOrderIds);
            $isUnpaid2 = !isset($result2['PaidStatus']) || $result2['PaidStatus'] != 1;
        ?>
    <div class="order-card delivered-card<?php echo $isUnpaid2 ? ' unpaid-card' : ''; ?>">
        <div class="status-badges">
            <span class="badge-status badge-delivered">
                <i class="fa fa-check-circle"></i> Delivered
            </span>
            <?php if ($hasHoldDates2) { ?>
            <span class="badge-hold"><i class="fa fa-pause-circle"></i> On Hold</span>
            <?php } ?>
            <?php if (isset($result2['PaidStatus']) && $result2['PaidStatus'] == 1) { ?>
            <span class="badge-status badge-paid"><i class="fa fa-check-circle"></i> Paid</span>
            <?php } else { ?>
            <span class="badge-status badge-unpaid"><i class="fa fa-clock-o"></i> Unpaid</span>
            <?php } ?>
        </div>
        <div class="customer-name"><?php echo ucfirst($result2['Fname'] . " " . $result2['Lname']); ?></div>
        <div class="customer-details">
            <div class="detail-row">
                <i class="fa fa-phone"></i>
                <span><?php echo $result2['Phone']; ?></span>
            </div>
            <div class="detail-row">
                <i class="fa fa-map-marker-alt"></i>
                <span><?php echo $result2['Address']; ?></span>
            </div>
        </div>
        <div class="action-buttons">
            <button class="btn-action btn-boxes" onclick="openBoxesModal(<?php echo (int)$result2['id']; ?>)">
                <i class="fa fa-th"></i> Boxes
            </button>
            <button class="btn-action btn-hold-view" onclick="viewHoldDates(<?php echo (int)$result2['id']; ?>)">
                <i class="fa fa-eye"></i> Hold
            </button>
        </div>
    </div>
    <?php } } else { ?>
        <div class="no-data">
            <i class="fa fa-check-circle"></i>
            No Delivered Orders Found
        </div>
    <?php } ?>
</div>


<!-- Delivery Modal -->
<div class="modal fade" id="deliveryModal" tabindex="-1" aria-labelledby="deliveryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="deliveryModalLabel">Mark as Delivered</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="deliveryForm">
          <input type="hidden" id="deliveryUserId" name="id">
          <div class="mb-3">
            <label class="form-label">Delivered Date</label>
            <input type="date" class="form-control" id="deliveredDate" name="deliveredDate" required readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Box Quantity</label>
            <input type="number" class="form-control" id="boxQty" name="boxQty" value="1" min="1" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Remark (Optional)</label>
            <textarea class="form-control" id="remark" name="remark" rows="2" placeholder="Enter any remark"></textarea>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-success">Submit & Mark Delivered</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Hold Dates Modal -->
<div class="modal fade" id="holdDatesModal" tabindex="-1" aria-labelledby="holdDatesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="holdDatesModalLabel">Add Hold Dates</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="holdOrderId" name="order_id" value="">
        <p class="text-muted small mb-2">Select dates when this customer should not appear in your list. Date must be today or later.</p>
        <div id="holdFutureDatesSection" class="mb-3" style="display:none;">
          <label class="form-label small fw-bold" style="color:#e65100;"><i class="fa fa-calendar-check"></i> Upcoming Hold Dates:</label>
          <ul id="holdFutureDatesList" class="list-group list-group-flush small"></ul>
        </div>
        <div id="holdPastDatesSection" class="mb-3" style="display:none;">
          <label class="form-label small fw-bold" style="color:#6c757d;"><i class="fa fa-history"></i> Past Hold Dates:</label>
          <ul id="holdPastDatesList" class="list-group list-group-flush small" style="background:#f8f9fa;"></ul>
        </div>
        <div class="mb-3">
          <label class="form-label">Hold dates</label>
          <div id="holdDatesContainer">
            <div class="hold-date-row input-group mb-2">
              <input type="date" class="form-control hold-date-input" name="hold_dates[]" min="<?php echo date('Y-m-d'); ?>">
              <button type="button" class="btn btn-outline-secondary btn-remove-date" style="display:none;" title="Remove">&times;</button>
            </div>
          </div>
          <button type="button" class="btn btn-sm btn-outline-secondary" id="addMoreHoldDate">+ Add More Date</button>
        </div>
        <div class="text-end">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-warning" id="holdDatesSubmit">Save Hold Dates</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- View Hold Dates Modal (Read Only) -->
<div class="modal fade" id="viewHoldModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title"><i class="fa fa-eye"></i> Hold Dates (View Only)</h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div id="viewHoldLoading" class="text-center py-3"><div class="spinner-border text-secondary"></div></div>
        <div id="viewHoldContent" style="display:none;">
          <div id="viewHoldFutureDatesSection" class="mb-3" style="display:none;">
            <label class="form-label small fw-bold" style="color:#e65100;"><i class="fa fa-calendar-check"></i> Upcoming Hold Dates:</label>
            <ul id="viewHoldFutureDatesList" class="list-group list-group-flush small"></ul>
          </div>
          <div id="viewHoldPastDatesSection" class="mb-3" style="display:none;">
            <label class="form-label small fw-bold" style="color:#6c757d;"><i class="fa fa-history"></i> Past Hold Dates:</label>
            <ul id="viewHoldPastDatesList" class="list-group list-group-flush small" style="background:#f8f9fa;"></ul>
          </div>
          <div id="viewHoldNoData" class="text-center text-muted py-3" style="display:none;">
            <i class="fa fa-calendar-times" style="font-size:32px;color:#ccc;"></i>
            <p class="mt-2 mb-0">No hold dates found.</p>
          </div>
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button></div>
    </div>
  </div>
</div>

<!-- Delivery Boxes Modal -->
<div class="modal fade" id="boxesModal" tabindex="-1" aria-labelledby="boxesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="boxesModalLabel">Delivery Boxes Status</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="boxesOrderId" value="">
        <div id="boxesLoading" class="text-center py-4">
          <div class="spinner-border text-info" role="status"></div>
          <p class="mt-2 text-muted">Loading...</p>
        </div>
        <div id="boxesContent" style="display:none;">
          <div class="row mb-3">
            <div class="col-4">
              <label class="text-muted small mb-0">Start Date</label>
              <div id="boxesPkgStart" class="fw-bold"></div>
            </div>
            <div class="col-4">
              <label class="text-muted small mb-0">Original End</label>
              <div id="boxesPkgEnd" class="fw-bold"></div>
            </div>
            <div class="col-4">
              <label class="text-muted small mb-0">Extended To</label>
              <div id="boxesExtendedEnd" class="fw-bold text-success"></div>
            </div>
          </div>
          <div class="mb-3">
            <p id="boxesSummary" class="mb-2">Delivered: 0 / 0</p>
            <div class="boxes-legend">
              <span><i class="legend-box delivered"></i> Delivered</span>
              <span><i class="legend-box pending"></i> Pending</span>
              <span><i class="legend-box hold"></i> Hold (H)</span>
              <span><i class="legend-box sunday"></i> Sunday (S)</span>
            </div>
            <div id="boxesContainer"></div>
          </div>
        </div>
        <div id="boxesError" class="alert alert-warning" style="display:none;"></div>
        <div class="text-end mt-3">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

    </div>
<?php } // End of else (not Sunday) ?>
</main>

<?php include_once 'footer.php'; ?>

<!-- JS -->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>

<script>
function goToWebsite(url){
    //alert(url);
    Android.goToWebsite(''+url+'');
}
function showTab(tab) {
    if (tab === 'pending') {
        $('#pending-orders').show();
        $('#delivered-orders').hide();
        $('#tab-pending').addClass('active');
        $('#tab-delivered').removeClass('active');
    } else {
        $('#pending-orders').hide();
        $('#delivered-orders').show();
        $('#tab-pending').removeClass('active');
        $('#tab-delivered').addClass('active');
    }
}

let currentUserId = null;

function markDelivered(id) {
    currentUserId = id;
    const today = '<?php echo date('Y-m-d'); ?>';
    $('#deliveredDate').val(today);
    $('#boxQty').val(1);
    $('#remark').val('');
    $('#deliveryUserId').val(id);
    $('#deliveryModal').modal('show');
}

// Handle form submission
$('#deliveryForm').on('submit', function(e) {
    e.preventDefault();

    const id = $('#deliveryUserId').val();
    const deliveredDate = $('#deliveredDate').val();
    const boxQty = $('#boxQty').val();
    const remark = $('#remark').val();

    $.ajax({
        url: 'update-order-status.php',
        type: 'POST',
        dataType: 'json',
        data: {
            id: id,
            deliveredDate: deliveredDate,
            boxQty: boxQty,
            remark: remark
        },
        success: function(res) {
            if (res.status === 'success') {
                $('#deliveryModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Delivered!',
                    text: res.message,
                    showConfirmButton: false,
                    timer: 1800,
                    toast: true,
                    position: 'top-end',
                    background: '#d4edda',
                    color: '#155724',
                    timerProgressBar: true
                });
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: res.message,
                    showConfirmButton: false,
                    timer: 1800,
                    toast: true,
                    position: 'top-end',
                    background: '#f8d7da',
                    color: '#721c24',
                    timerProgressBar: true
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Server Error',
                text: 'Please try again later.',
                showConfirmButton: false,
                timer: 2000,
                toast: true,
                position: 'top-end',
                background: '#f8d7da',
                color: '#721c24',
                timerProgressBar: true
            });
        }
    });
});

// ----- Hold Dates -----
var todayStr = '<?php echo date('Y-m-d'); ?>';

function openHoldModal(orderId) {
    $('#holdOrderId').val(orderId);
    $('#holdDatesContainer .hold-date-row').remove();
    $('#holdDatesContainer').append(
        '<div class="hold-date-row input-group mb-2">' +
        '<input type="date" class="form-control hold-date-input" name="hold_dates[]" min="' + todayStr + '">' +
        '<button type="button" class="btn btn-outline-secondary btn-remove-date" style="display:none;" title="Remove">&times;</button>' +
        '</div>'
    );
    $('#holdFutureDatesSection, #holdPastDatesSection').hide();
    $('#holdFutureDatesList, #holdPastDatesList').empty();
    $('#holdDatesModal').modal('show');
    $.ajax({
        url: 'hold_dates_action.php',
        type: 'GET',
        dataType: 'json',
        data: { action: 'get_dates', order_id: orderId },
        success: function(res) {
            if (res.status === 'success' && res.dates) {
                var futureDates = res.dates.future || [];
                var pastDates = res.dates.past || [];
                
                if (futureDates.length > 0) {
                    $('#holdFutureDatesSection').show();
                    futureDates.forEach(function(d) {
                        $('#holdFutureDatesList').append('<li class="list-group-item py-1" style="background:#fff3e0;color:#e65100;">' + d + '</li>');
                    });
                }
                
                if (pastDates.length > 0) {
                    $('#holdPastDatesSection').show();
                    pastDates.forEach(function(d) {
                        $('#holdPastDatesList').append('<li class="list-group-item py-1" style="background:#f5f5f5;color:#777;">' + d + '</li>');
                    });
                }
            }
        }
    });
    bindHoldDateEvents();
}

function viewHoldDates(orderId) {
    $('#viewHoldLoading').show();
    $('#viewHoldContent').hide();
    $('#viewHoldFutureDatesSection, #viewHoldPastDatesSection, #viewHoldNoData').hide();
    $('#viewHoldFutureDatesList, #viewHoldPastDatesList').empty();
    $('#viewHoldModal').modal('show');
    
    $.ajax({
        url: 'hold_dates_action.php',
        type: 'GET',
        dataType: 'json',
        data: { action: 'get_dates', order_id: orderId },
        success: function(res) {
            $('#viewHoldLoading').hide();
            $('#viewHoldContent').show();
            
            if (res.status === 'success' && res.dates) {
                var futureDates = res.dates.future || [];
                var pastDates = res.dates.past || [];
                
                if (futureDates.length === 0 && pastDates.length === 0) {
                    $('#viewHoldNoData').show();
                    return;
                }
                
                if (futureDates.length > 0) {
                    $('#viewHoldFutureDatesSection').show();
                    futureDates.forEach(function(d) {
                        $('#viewHoldFutureDatesList').append('<li class="list-group-item py-1" style="background:#fff3e0;color:#e65100;">' + d + '</li>');
                    });
                }
                
                if (pastDates.length > 0) {
                    $('#viewHoldPastDatesSection').show();
                    pastDates.forEach(function(d) {
                        $('#viewHoldPastDatesList').append('<li class="list-group-item py-1" style="background:#f5f5f5;color:#777;">' + d + '</li>');
                    });
                }
            } else {
                $('#viewHoldNoData').show();
            }
        },
        error: function() {
            $('#viewHoldLoading').hide();
            $('#viewHoldContent').show();
            $('#viewHoldNoData').show();
        }
    });
}

function bindHoldDateEvents() {
    var $rows = $('#holdDatesContainer .hold-date-row');
    $rows.find('.btn-remove-date').toggle($rows.length > 1);
    $('.hold-date-input').off('input').on('input', function() {
        var $rows = $('#holdDatesContainer .hold-date-row');
        $rows.find('.btn-remove-date').toggle($rows.length > 1);
    });
    $('.btn-remove-date').off('click').on('click', function() {
        var $container = $('#holdDatesContainer');
        if ($container.find('.hold-date-row').length > 1) {
            $(this).closest('.hold-date-row').remove();
            bindHoldDateEvents();
        }
    });
}

$('#addMoreHoldDate').on('click', function() {
    var $row = $('<div class="hold-date-row input-group mb-2">' +
        '<input type="date" class="form-control hold-date-input" name="hold_dates[]" min="' + todayStr + '">' +
        '<button type="button" class="btn btn-outline-secondary btn-remove-date" title="Remove">&times;</button>' +
        '</div>');
    $('#holdDatesContainer').append($row);
    bindHoldDateEvents();
});

$('#holdDatesSubmit').on('click', function() {
    var orderId = $('#holdOrderId').val();
    var dates = [];
    $('.hold-date-input').each(function() {
        var v = $(this).val();
        if (v) dates.push(v);
    });
    if (dates.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No date selected',
            text: 'Please select at least one hold date.',
            toast: true,
            position: 'top-end',
            timer: 2000,
            showConfirmButton: false
        });
        return;
    }
    var dup = dates.filter(function(d, i) { return dates.indexOf(d) !== i; });
    if (dup.length) {
        Swal.fire({
            icon: 'warning',
            title: 'Duplicate dates',
            text: 'Please remove duplicate dates.',
            toast: true,
            position: 'top-end',
            timer: 2000,
            showConfirmButton: false
        });
        return;
    }
    for (var i = 0; i < dates.length; i++) {
        if (dates[i] < todayStr) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid date',
                text: 'Date cannot be in the past.',
                toast: true,
                position: 'top-end',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }
        var dateObj = new Date(dates[i]);
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
    }
    $(this).prop('disabled', true);
    $.ajax({
        url: 'hold_dates_action.php',
        type: 'POST',
        dataType: 'json',
        data: { action: 'save', order_id: orderId, hold_dates: dates },
        success: function(res) {
            $('#holdDatesSubmit').prop('disabled', false);
            if (res.status === 'success') {
                $('#holdDatesModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Saved',
                    text: res.message || 'Hold dates saved.',
                    showConfirmButton: false,
                    timer: 1800,
                    toast: true,
                    position: 'top-end',
                    background: '#d4edda',
                    color: '#155724',
                    timerProgressBar: true
                });
                setTimeout(function() { location.reload(); }, 1200);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: res.message || 'Could not save.',
                    toast: true,
                    position: 'top-end',
                    timer: 2500,
                    showConfirmButton: false
                });
            }
        },
        error: function() {
            $('#holdDatesSubmit').prop('disabled', false);
            Swal.fire({
                icon: 'error',
                title: 'Server Error',
                text: 'Please try again later.',
                toast: true,
                position: 'top-end',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
});

// ----- Delivery Boxes -----
var boxesData = null;

function formatDateDMY(ymd) {
    if (!ymd) return '';
    var p = ymd.split('-');
    return (p[2] || '') + '-' + (p[1] || '') + '-' + (p[0] || '');
}

function openBoxesModal(orderId) {
    $('#boxesOrderId').val(orderId);
    $('#boxesLoading').show();
    $('#boxesContent').hide();
    $('#boxesError').hide().text('');
    $('#boxesModal').modal('show');

    $.ajax({
        url: 'boxes_action.php',
        type: 'GET',
        dataType: 'json',
        data: { action: 'get', order_id: orderId },
        success: function(res) {
            $('#boxesLoading').hide();
            if (res.status === 'success' && res.data) {
                boxesData = res.data;
                $('#boxesPkgStart').text(formatDateDMY(res.data.pkg_start_date));
                $('#boxesPkgEnd').text(formatDateDMY(res.data.pkg_end_date));
                var extEnd = res.data.extended_end_date || res.data.pkg_end_date;
                if (extEnd !== res.data.pkg_end_date) {
                    $('#boxesExtendedEnd').text(formatDateDMY(extEnd)).show();
                } else {
                    $('#boxesExtendedEnd').text('-').show();
                }
                var summaryText = 'Delivered: ' + res.data.delivered_count + ' / ' + res.data.total_days;
                if (res.data.hold_count > 0) summaryText += ' | Hold: ' + res.data.hold_count + ' (Extended)';
                if (res.data.sunday_count > 0) summaryText += ' | Sundays: ' + res.data.sunday_count;
                $('#boxesSummary').text(summaryText);
                var html = '';
                (res.data.boxes || []).forEach(function(b) {
                    var cls = 'delivery-box ' + b.type;
                    var title = formatDateDMY(b.date);
                    var label = b.label || '';
                    html += '<div class="' + cls + '" data-date="' + b.date + '" title="' + title + '">' + label + '</div>';
                });
                $('#boxesContainer').html(html);
                $('#boxesContent').show();
            } else {
                $('#boxesError').text(res.message || 'Could not load boxes.').show();
            }
        },
        error: function() {
            $('#boxesLoading').hide();
            $('#boxesError').text('Server error. Please try again.').show();
        }
    });
}

</script>
</body>
</html>
