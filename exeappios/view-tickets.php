<?php 
session_start();
require_once 'config.php';
require_once 'auth.php';

$PageName = "Tickets";
$UserId   = $_SESSION['User']['id'];
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?php echo $Proj_Title; ?> - Tickets</title>
  <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
  <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
  <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">
  <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet" id="style">
  <link href="css/toastr.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

  <style>
    body { font-family: 'Roboto', sans-serif; background: #f8f9fb; }

    /* Tabs */
    .tab-pill {
      background: #fff;
      border-radius: 12px;
      padding: 8px 12px;
      font-weight: 600;
      font-size: 13px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      color: #555;
      transition: 0.3s;
    }
    .tab-pill.active {
      background: linear-gradient(135deg,#007bff,#00bfff);
      color: #fff !important;
      box-shadow: 0 4px 12px rgba(0,123,255,0.3);
    }
    .nav-pills { display:flex; gap:8px; }
    .nav-pills .nav-link i { font-size: 16px; display:block; }
    
    .row {
            margin-left: 8px;
    }
  </style>
</head>
<body class="body-scroll d-flex flex-column h-100 menu-overlay">
<main class="flex-shrink-0 main">
  <?php include_once 'back-header.php'; ?> 
  <div class="main-container">

    <!-- Tabs -->
    <div class="card p-2 mb-3 shadow-sm">
      <ul class="nav nav-pills justify-content-between flex-nowrap w-100" id="ticketTabs" role="tablist">
        <li class="nav-item flex-fill text-center">
          <a class="nav-link active tab-pill" id="pending-tab" data-toggle="tab" href="#pending" role="tab">
            <i class="fas fa-clock"></i> Pending
          </a>
        </li>
        <li class="nav-item flex-fill text-center">
          <a class="nav-link tab-pill" id="inprogress-tab" data-toggle="tab" href="#inprogress" role="tab">
            <i class="fas fa-spinner"></i> In Progress
          </a>
        </li>
        <li class="nav-item flex-fill text-center">
          <a class="nav-link tab-pill" id="resolved-tab" data-toggle="tab" href="#resolved" role="tab">
            <i class="fas fa-check-circle"></i> Resolved
          </a>
        </li>
        <li class="nav-item flex-fill text-center">
          <a class="nav-link tab-pill" id="closed-tab" data-toggle="tab" href="#closed" role="tab">
            <i class="fas fa-lock"></i> Closed
          </a>
        </li>
      </ul>
    </div>

    <!-- Tabs Content -->
    <div class="tab-content" id="ticketTabsContent">

      <!-- Pending -->
      <div class="tab-pane fade show active" id="pending" role="tabpanel">
        <div class="row">
        <?php
        
        $sqlPending = "SELECT t.*, d.Name AS DeptName 
                       FROM tbl_tickets t
                       LEFT JOIN tbl_departments d ON d.id=t.department_id
                       WHERE t.created_by='$UserId' AND t.status='open'
                       ORDER BY t.created_at DESC";
        $rows = getList($sqlPending);
        if($rows){ foreach($rows as $ticket){ include 'ticket-card.php'; } } 
        else { echo "<div class='col-12 text-center mt-4'><h6 class='text-muted'>No Pending tickets found.</h6></div>"; }
        ?>
        </div>
      </div>

      <!-- In Progress -->
      <div class="tab-pane fade" id="inprogress" role="tabpanel">
        <div class="row">
        <?php
        $sqlProgress = "SELECT t.*, d.Name AS DeptName 
                        FROM tbl_tickets t
                        LEFT JOIN tbl_departments d ON d.id=t.department_id
                        WHERE t.created_by='$UserId' AND t.status='in_progress'
                        ORDER BY t.created_at DESC";
        $rows = getList($sqlProgress);
        if($rows){ foreach($rows as $ticket){ include 'ticket-card.php'; } } 
        else { echo "<div class='col-12 text-center mt-4'><h6 class='text-muted'>No In Progress tickets found.</h6></div>"; }
        ?>
        </div>
      </div>

      <!-- Resolved -->
      <div class="tab-pane fade" id="resolved" role="tabpanel">
        <div class="row">
        <?php
        $sqlResolved = "SELECT t.*, d.Name AS DeptName 
                        FROM tbl_tickets t
                        LEFT JOIN tbl_departments d ON d.id=t.department_id
                        WHERE t.created_by='$UserId' AND t.status='resolved'
                        ORDER BY t.created_at DESC";
        $rows = getList($sqlResolved);
        if($rows){ foreach($rows as $ticket){ include 'ticket-card.php'; } } 
        else { echo "<div class='col-12 text-center mt-4'><h6 class='text-muted'>No Resolved tickets found.</h6></div>"; }
        ?>
        </div>
      </div>

      <!-- Closed -->
      <div class="tab-pane fade" id="closed" role="tabpanel">
        <div class="row">
        <?php
        $sqlClosed = "SELECT t.*, d.Name AS DeptName 
                      FROM tbl_tickets t
                      LEFT JOIN tbl_departments d ON d.id=t.department_id
                      WHERE t.created_by='$UserId' AND t.status='closed'
                      ORDER BY t.created_at DESC";
        $rows = getList($sqlClosed);
        if($rows){ foreach($rows as $ticket){ include 'ticket-card.php'; } } 
        else { echo "<div class='col-12 text-center mt-4'><h6 class='text-muted'>No Closed tickets found.</h6></div>"; }
        ?>
        </div>
      </div>

    </div>
  </div>
</main>

<?php include_once 'footer.php'; ?>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
