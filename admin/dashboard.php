<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$_SESSION['pagevalue'] = $_REQUEST['pagevalue'] ?? '';

if(isset($_GET['uid'])){
  $_SESSION['Admin']['id'] = $_GET['uid'];
  $user_id = $_SESSION['Admin']['id'];
} else {
  $user_id = $_SESSION['Admin']['id'];
}

$MainPage = "Dashboard";
$Page = "Dashboard";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Dashboard</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php include_once 'header_script.php'; ?>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
.dashboard-card {
  border-radius: 15px;
  transition: 0.3s;
  box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}
.dashboard-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}
.card-icon {
  font-size: 45px;
  opacity: 0.8;
}
.bg-gradient-info {
  background: linear-gradient(45deg, #00c6ff, #0072ff);
}
.bg-gradient-success {
  background: linear-gradient(45deg, #38ef7d, #11998e);
}
.bg-gradient-warning {
  background: linear-gradient(45deg, #f7971e, #ffd200);
}
.bg-gradient-danger {
  background: linear-gradient(45deg, #f85032, #e73827);
}
.bg-gradient-primary {
  background: linear-gradient(45deg, #4776E6, #8E54E9);
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

<h4 class="font-weight-bold mb-4" style="padding-top: 25px;">👋 Welcome back, 
  <?php echo $_SESSION['Admin']['name'] ?? 'Admin'; ?>!
</h4>

<div class="row g-4">
<?php 
$today = date('Y-m-d');
// ✅ Count of today's delivered orders (Admin View)
$sqlTodayOrders = "
    SELECT COUNT(*) AS TotalOrders FROM tbl_users tu WHERE tu.Roll=5 AND (
          tu.Validity IS NULL OR tu.Validity >= '$today'
      ) ORDER BY tu.CreatedDate DESC
";
$resToday = $conn->query($sqlTodayOrders);
$rowToday = $resToday->fetch_assoc();
$todayOrdersCount = $rowToday['TotalOrders'] ?? 0;


// ✅ 2. Total Customers
$sqlCustomers = "
    SELECT COUNT(*) AS TotalCustomers
    FROM tbl_users
    WHERE Roll = 5
";
$resCustomers = $conn->query($sqlCustomers);
$rowCustomers = $resCustomers->fetch_assoc();
$totalCustomers = $rowCustomers['TotalCustomers'] ?? 0;


// ✅ 3. Total Executives
$sqlExecs = "
    SELECT COUNT(*) AS TotalExecs
    FROM tbl_users
    WHERE Roll = 3
";
$resExecs = $conn->query($sqlExecs);
$rowExecs = $resExecs->fetch_assoc();
$totalExecs = $rowExecs['TotalExecs'] ?? 0;


// ✅ 4. Pending Deliveries
//    - Active customers (Roll=5, Status=1)
//    - Valid package (Validity >= today)
//    - NOT marked as “Delivered” today in tbl_order_status_log

$sqlPending = "
    SELECT COUNT(*) AS PendingCount
    FROM tbl_users u
    WHERE u.Roll = 5
      AND u.Status = 1
      AND (u.Validity IS NULL OR u.Validity >= '$today')
      AND u.id NOT IN (
          SELECT UserId 
          FROM tbl_order_status_log 
          WHERE Status = 'Delivered' 
          AND DATE(CreatedDate) = '$today'
      )
";
$resPending = $conn->query($sqlPending);
$rowPending = $resPending->fetch_assoc();
$pendingOrders = $rowPending['PendingCount'] ?? 0;


// --------------------
// 📅 Get last 7 days data for delivered orders
// --------------------
$today = date('Y-m-d');
$dates = [];
$deliveredCounts = [];

for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dates[] = date('D', strtotime($date)); // e.g. Mon, Tue

    $sql = "
        SELECT COUNT(DISTINCT UserId) AS cnt
        FROM tbl_order_status_log
        WHERE Status = 'Delivered'
          AND DATE(CreatedDate) = '$date'
    ";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $deliveredCounts[] = (int)($row['cnt'] ?? 0);
}

// Convert to JSON for Chart.js
$chartLabels = json_encode($dates);
$chartData = json_encode($deliveredCounts);
?>
  <div class="col-md-6 col-xl-3">
    <a href="today-orders.php" class="text-decoration-none">
      <div class="card dashboard-card bg-gradient-info text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h6 class="text-white-50">Today Orders</h6>
            <h3 class="fw-bold" id="todayOrders"><?php echo $todayOrdersCount; ?></h3>
          </div>
          <i class="fas fa-shopping-cart card-icon"></i>
        </div>
      </div>
    </a>
  </div>

<!-- Total Customers -->
  <div class="col-md-6 col-xl-3">
    <a href="view-customers.php" class="text-decoration-none">
      <div class="card dashboard-card bg-gradient-primary text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h6 class="text-white-50">Total Customers</h6>
            <h3 class="fw-bold" id="totalCustomers"><?php echo $totalCustomers; ?></h3>
          </div>
          <i class="fas fa-users card-icon"></i>
        </div>
      </div>
    </a>
  </div>

  <!-- Total Executives -->
  <div class="col-md-6 col-xl-3">
    <a href="view-executive.php" class="text-decoration-none">
      <div class="card dashboard-card bg-gradient-success text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h6 class="text-white-50">Total Executives</h6>
            <h3 class="fw-bold" id="totalExecs"><?php echo $totalExecs; ?></h3>
          </div>
          <i class="fas fa-user-tie card-icon"></i>
        </div>
      </div>
    </a>
  </div>

  <!-- Pending Deliveries -->
  <div class="col-md-6 col-xl-3">
    <a href="today-pending-orders.php" class="text-decoration-none">
      <div class="card dashboard-card bg-gradient-warning text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h6 class="text-white-50">Pending Deliveries</h6>
            <h3 class="fw-bold" id="pendingOrders"><?php echo $pendingOrders; ?></h3>
          </div>
          <i class="fas fa-truck card-icon"></i>
        </div>
      </div>
    </a>
  </div>
</div>

<!-- Charts -->
<div class="row mt-5">
  <div class="col-lg-8">
    <div class="card dashboard-card">
      <div class="card-header fw-bold">📊 Sales Overview (Last 7 Days)</div>
      <div class="card-body">
        <canvas id="salesChart" height="120"></canvas>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
  <div class="card dashboard-card">
    <div class="card-header fw-bold">🕒 Recent Activity</div>
    <div class="card-body">
      <ul class="list-group list-group-flush">
        <?php
        // Fetch the 10 most recent order events (Delivered / Pending etc.)
        $sqlActivity = "
            SELECT osl.*, u.Fname, u.Lname
            FROM tbl_order_status_log osl
            INNER JOIN tbl_users u ON osl.UserId = u.id
            WHERE u.Roll = 5
            ORDER BY osl.CreatedDate DESC
            LIMIT 5
        ";
        $resActivity = $conn->query($sqlActivity);

        if ($resActivity && $resActivity->num_rows > 0) {
          while ($row = $resActivity->fetch_assoc()) {
              $userName = ucfirst($row['Fname'] . ' ' . $row['Lname']);
              $status = ucfirst($row['Status']);
              $date = date('d M, h:i A', strtotime($row['CreatedDate']));
              $icon = '<i class="fa-solid fa-circle text-muted small me-2"></i>';

              if ($row['Status'] === 'Delivered') {
                  $icon = '<i class="fa-solid fa-circle-check text-success me-2"></i>';
                  $msg = "Order delivered for <b>$userName</b>";
              } elseif ($row['Status'] === 'Pending') {
                  $icon = '<i class="fa-solid fa-clock text-warning me-2"></i>';
                  $msg = "Order pending for <b>$userName</b>";
              } elseif ($row['Status'] === 'Cancelled') {
                  $icon = '<i class="fa-solid fa-times-circle text-danger me-2"></i>';
                  $msg = "Order cancelled by <b>$userName</b>";
              } else {
                  $msg = "Status updated (<b>$status</b>) for <b>$userName</b>";
              }

              echo "
              <li class='list-group-item d-flex justify-content-between align-items-center'>
                <span>$icon $msg</span>
                <small class='text-muted'>$date</small>
              </li>
              ";
          }
        } else {
          echo "<li class='list-group-item text-muted text-center'>No recent activity found</li>";
        }
        ?>
      </ul>
    </div>
  </div>
</div>

</div>

<?php 
/* ============================================================
   📦 1. DELIVERY GRAPH DATA (Delivered vs Pending)
   ============================================================ */
$deliveryMonths = [];
$deliveredData = [];
$pendingData = [];

$currentYear = date('Y');
for ($m = 1; $m <= 12; $m++) {
    $monthName = date('M', mktime(0, 0, 0, $m, 1));
    $deliveryMonths[] = $monthName;

    $startDate = date('Y-m-01', mktime(0, 0, 0, $m, 1, $currentYear));
    $endDate = date('Y-m-t', mktime(0, 0, 0, $m, 1, $currentYear));

    // Delivered count from tbl_order_status_log
    $sqlDelivered = "
        SELECT COUNT(DISTINCT UserId) AS cnt
        FROM tbl_order_status_log
        WHERE Status = 'Delivered'
          AND DATE(CreatedDate) BETWEEN '$startDate' AND '$endDate'
    ";
    $resDelivered = $conn->query($sqlDelivered);
    $deliveredRow = $resDelivered->fetch_assoc();
    $deliveredCount = (int)($deliveredRow['cnt'] ?? 0);

    // Pending logic (Active users NOT in Delivered list)
    $sqlPending = "
        SELECT COUNT(*) AS cnt
        FROM tbl_users u
        WHERE u.Roll = 5
          AND u.Status = 1
          AND (u.Validity IS NULL OR u.Validity >= '$endDate')
          AND u.id NOT IN (
              SELECT DISTINCT UserId
              FROM tbl_order_status_log
              WHERE Status = 'Delivered'
                AND DATE(CreatedDate) BETWEEN '$startDate' AND '$endDate'
          )
    ";
    $resPending = $conn->query($sqlPending);
    $pendingRow = $resPending->fetch_assoc();
    $pendingCount = (int)($pendingRow['cnt'] ?? 0);

    $deliveredData[] = $deliveredCount;
    $pendingData[] = $pendingCount;
}

/* ============================================================
   💰 2. CUSTOMER PAYMENTS GRAPH DATA (Earnings vs Expenses)
   ============================================================ */
$months = [];
$earnings = [];
$expenses = [];
$profit = [];

for ($m = 1; $m <= 12; $m++) {
    $monthName = date('M', mktime(0, 0, 0, $m, 1));
    $months[] = $monthName;

    $startDate = date('Y-m-01', mktime(0, 0, 0, $m, 1, $currentYear));
    $endDate = date('Y-m-t', mktime(0, 0, 0, $m, 1, $currentYear));

    // Earnings (Credit Records)
    $sqlCredit = "
        SELECT SUM(Amount) AS total
        FROM tbl_general_ledger
        WHERE CrDr='cr'
          AND DATE(PaymentDate) BETWEEN '$startDate' AND '$endDate'
    ";
    $resCredit = $conn->query($sqlCredit);
    $creditRow = $resCredit->fetch_assoc();
    $earning = (float)($creditRow['total'] ?? 0);
    $earnings[] = $earning;

    // Expenses (Debit from tbl_company_expenses)
    $sqlExpense = "
        SELECT SUM(Amount) AS total
        FROM tbl_company_expenses
        WHERE DATE(ExpenseDate) BETWEEN '$startDate' AND '$endDate'
    ";
    $resExpense = $conn->query($sqlExpense);
    $expenseRow = $resExpense->fetch_assoc();
    $expense = (float)($expenseRow['total'] ?? 0);
    $expenses[] = $expense;

    // Profit = Earnings - Expenses
    $profit[] = $earning - $expense;
}

?>
<!-- ======================================================= -->
<!-- 📦 Additional Graph Reports: Deliveries & Payments -->
<!-- ======================================================= -->
<div class="row mt-5">
  <!-- 🟢 Delivery Graph -->
  <div class="col-lg-6 mb-4">
    <div class="card dashboard-card">
      <div class="card-header fw-bold">🚚 Delivery Performance (Last 3 Months)</div>
      <div class="card-body">
        <canvas id="deliveryChart" height="320"></canvas>
      </div>
    </div>
  </div>

  <!-- 💰 Customer Payments Graph -->
  <div class="col-lg-6 mb-4">
    <div class="card dashboard-card">
      <div class="card-header fw-bold">💸 Income vs Expense Report (Current Year)</div>
      <div class="card-body">
        <canvas id="paymentChart" height="320"></canvas>
      </div>
    </div>
  </div>
</div>

</div>
</div>

<?php include_once 'footer.php'; ?>
</div>
</div>
</div>

<?php include_once 'footer_script.php'; ?>

<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

<script>
const ctx = document.getElementById('salesChart');

const chartLabels = <?php echo $chartLabels; ?>;
const chartData = <?php echo $chartData; ?>;

new Chart(ctx, {
  type: 'line',
  data: {
    labels: chartLabels,
    datasets: [{
      label: 'Delivered Orders',
      data: chartData,
      borderWidth: 3,
      borderColor: '#007bff',
      backgroundColor: 'rgba(0,123,255,0.1)',
      fill: true,
      tension: 0.4,
      pointBackgroundColor: '#007bff',
      pointRadius: 5,
      pointHoverRadius: 7,
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: '#007bff',
        titleColor: '#fff',
        bodyColor: '#fff',
        displayColors: false
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          precision: 0,
          stepSize: 1
        }
      }
    }
  }
});


// =======================================================
// 1️⃣ DELIVERY GRAPH - Delivered vs Pending (from delivery-graph-report.php)
// =======================================================
const deliveryCtx = document.getElementById('deliveryChart').getContext('2d');
new Chart(deliveryCtx, {
  type: 'bar',
  data: {
    labels: <?= json_encode($deliveryMonths ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']); ?>,
    datasets: [
      {
        label: 'Delivered Orders',
        data: <?= json_encode($deliveredData ?? [0,0,0,0,0,0,0,0,0,0,0,0]); ?>,
        backgroundColor: 'rgba(40,167,69,0.9)',
        borderColor: 'rgba(40,167,69,1)',
        borderWidth: 1.5,
        borderRadius: 6,
        maxBarThickness: 35
      },
      {
        label: 'Pending Orders',
        data: <?= json_encode($pendingData ?? [0,0,0,0,0,0,0,0,0,0,0,0]); ?>,
        backgroundColor: 'rgba(255,193,7,0.9)',
        borderColor: 'rgba(255,193,7,1)',
        borderWidth: 1.5,
        borderRadius: 6,
        maxBarThickness: 35
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      title: {
        display: true,
        text: 'Monthly Delivered vs Pending Orders',
        font: { size: 16, weight: 'bold' },
        color: '#333'
      },
      legend: { position: 'bottom' }
    },
    scales: {
      x: { grid: { display: false }, title: { display: true, text: 'Months' } },
      y: {
        beginAtZero: true,
        title: { display: true, text: 'No. of Orders' },
        ticks: { precision: 0 }
      }
    }
  }
});

// =======================================================
// 2️⃣ CUSTOMER PAYMENTS GRAPH - Earnings vs Expenses vs Profit (from customer-payments-report.php)
// =======================================================
const paymentCtx = document.getElementById('paymentChart').getContext('2d');
new Chart(paymentCtx, {
  type: 'bar',
  data: {
    labels: <?= json_encode($months ?? ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']); ?>,
    datasets: [
      {
        label: 'Earnings (Credit)',
        data: <?= json_encode($earnings ?? [0,0,0,0,0,0,0,0,0,0,0,0]); ?>,
        backgroundColor: 'rgba(40,167,69,0.9)',
        borderColor: 'rgba(40,167,69,1)',
        borderWidth: 1.5,
        borderRadius: 6,
        maxBarThickness: 40
      },
      {
        label: 'Expenses (Debit)',
        data: <?= json_encode($expenses ?? [0,0,0,0,0,0,0,0,0,0,0,0]); ?>,
        backgroundColor: 'rgba(220,53,69,0.9)',
        borderColor: 'rgba(220,53,69,1)',
        borderWidth: 1.5,
        borderRadius: 6,
        maxBarThickness: 40
      },
      {
        label: 'Profit (Earnings - Expenses)',
        data: <?= json_encode($profit ?? [0,0,0,0,0,0,0,0,0,0,0,0]); ?>,
        backgroundColor: function(ctx) {
          const val = ctx.raw;
          return val >= 0 ? 'rgba(0,123,255,0.9)' : 'rgba(255,99,71,0.9)';
        },
        borderColor: 'rgba(0,90,200,1)',
        borderWidth: 1.5,
        borderRadius: 6,
        maxBarThickness: 40
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      title: {
        display: true,
        text: 'Income vs Expense Comparison',
        font: { size: 16, weight: 'bold' },
        color: '#333'
      },
      legend: { position: 'bottom' }
    },
    scales: {
      x: { grid: { display: false }, title: { display: true, text: 'Months' } },
      y: {
        title: { display: true, text: 'Amount (₹)' },
        ticks: {
          callback: function(value) { return '₹' + value; },
          color: '#333'
        },
        grid: { color: 'rgba(200,200,200,0.2)' }
      }
    }
  }
});
</script>
</body>
</html>
