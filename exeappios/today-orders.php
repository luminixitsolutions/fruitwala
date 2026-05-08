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
            background: #f7f9fc;
            font-family: 'Inter', sans-serif;
        }
        .order-tabs {
            display: flex;
            justify-content: space-around;
            background: #fff;
            border-radius: 15px;
            padding: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 15px;
        }
        .tab-btn {
            flex: 1;
            border: none;
            background: #fff;
            color: #444;
            font-weight: 600;
            padding: 8px 0;
            border-radius: 12px;
            transition: 0.3s;
        }
        .tab-btn.active {
            background: #007bff;
            color: #fff;
            box-shadow: 0 3px 10px rgba(0,123,255,0.3);
        }
        .order-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            margin-bottom: 12px;
            padding: 12px 15px;
            border-left: 4px solid #007bff;
            transition: 0.3s;
        }
        .order-card:hover {
            transform: scale(1.01);
        }
        .order-avatar {
            width: 45px;
            height: 45px;
            background: #007bff;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .order-info h6 {
            font-weight: 600;
            color: #222;
            margin-bottom: 3px;
        }
        .order-info small {
            color: #777;
        }
        .action-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }
        .btn-track {
            background: #00bcd4;
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 5px 12px;
            font-size: 13px;
        }
        .btn-delivered {
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 5px 12px;
            font-size: 13px;
        }
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
        <!-- Tabs -->
        <div class="order-tabs">
            <button class="tab-btn active" id="tab-pending" onclick="showTab('pending')">
                <i class="fa fa-clock-o"></i> Pending
            </button>
            <button class="tab-btn" id="tab-delivered" onclick="showTab('delivered')">
                <i class="fa fa-check-circle"></i> Delivered
            </button>
        </div>

      <?php
date_default_timezone_set('Asia/Kolkata');
$today = date('Y-m-d');

$UserId = $_SESSION['User']['id'] ?? 0;

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

// Step 3: Fetch customers only in those areas
$sql = "
    SELECT id, Fname, Lname, Phone, Address, PackageId, PkgAmt, PkgDate, Validity 
    FROM tbl_users 
    WHERE Roll=5 
      AND Status=1 
      AND AreaId IN ($areaList)
      AND (
          Validity IS NULL OR Validity >= '$today'
      )
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
?>


<!-- Pending Orders -->
<div id="pending-orders">
    <?php if (count($pending) > 0) { 
        foreach ($pending as $result) { ?>
    <div class="order-card">
        <div class="d-flex align-items-center">
            <div class="order-avatar me-3">
                <i class="fa fa-user"></i>
            </div>
            <div class="order-info flex-grow-1" style="padding-left: 10px;">
                <h6><?php echo ucfirst($result['Fname'] . " " . $result['Lname']); ?></h6>
                <small><i class="fa fa-phone"></i> <?php echo $result['Phone']; ?></small><br>
                <small><i class="fa fa-map-marker-alt"></i> <?php echo $result['Address']; ?></small>
            </div>
            <span class="badge-status badge-pending">Pending</span>
        </div>
        <div class="action-buttons justify-content-end">
            <a href="https://www.google.com/maps/dir//<?php echo $result['Address']; ?>/@21.1388559,79.0438716,12z/data=!3m1!4b1!4m8!4m7!1m0!1m5!1m1!1s0x3bd4c7447dd3468f:0x99c6fa88cbfd789b!2m2!1d79.1262733!2d21.1388758?entry=ttu&g_ep=EgoyMDI1MTAyNi4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="btn-track">
                <i class="fa fa-location-arrow"></i>
            </a>
            <button class="btn-delivered" onclick="markDelivered(<?php echo $result['id']; ?>)">Delivered</button>
        </div>
    </div>
    <?php } } else { ?>
        <div class="no-data">No Pending Orders Found</div>
    <?php } ?>
</div>

<!-- Delivered Orders -->
<div id="delivered-orders" style="display:none;">
    <?php if (count($delivered) > 0) {
        foreach ($delivered as $result2) { ?>
    <div class="order-card border-left-success">
        <div class="d-flex align-items-center">
            <div class="order-avatar me-3" style="background:#28a745;">
                <i class="fa fa-user"></i>
            </div>
            <div class="order-info flex-grow-1" style="padding-left: 10px;">
                <h6><?php echo ucfirst($result2['Fname'] . " " . $result2['Lname']); ?></h6>
                <small><i class="fa fa-phone"></i> <?php echo $result2['Phone']; ?></small><br>
                <small><i class="fa fa-map-marker-alt"></i> <?php echo $result2['Address']; ?></small>
            </div>
            <span class="badge-status badge-delivered">Delivered</span>
        </div>
    </div>
    <?php } } else { ?>
        <div class="no-data">No Delivered Orders Found</div>
    <?php } ?>
</div>

    </div>
</main>

<?php include_once 'footer.php'; ?>

<!-- JS -->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>

<script>
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

function markDelivered(id) {
    Swal.fire({
        title: 'Mark as Delivered?',
        text: "This order will be marked as delivered.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Deliver it'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'update-order-status.php',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
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
        }
    });
}

</script>
</body>
</html>
