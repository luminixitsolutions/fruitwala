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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>
<div class="layout-container">
<?php include_once 'top_header.php'; ?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">

<h4 class="font-weight-bold mb-4" style="padding-top: 25px;"> Delivery Growth Dashboard
</h4>


<?php 
// Get filter range
$from = $_POST['FromDate'] ?? date('Y-01-01');
$to = $_POST['ToDate'] ?? date('Y-12-31');
$today = date('Y-m-d');

// Fixed month labels
$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$delivered = array_fill(1, 12, 0);
$pending = array_fill(1, 12, 0);

// ✅ STEP 1: FETCH DELIVERED COUNTS FROM DB
$sqlDelivered = "
  SELECT 
    MONTH(CreatedDate) AS month_num, 
    COUNT(*) AS delivered_count
  FROM tbl_order_status_log 
  WHERE Status='Delivered' 
    AND DATE(CreatedDate) BETWEEN '$from' AND '$to'
  GROUP BY MONTH(CreatedDate)
";
$resDelivered = $conn->query($sqlDelivered);
while($r = $resDelivered->fetch_assoc()){
  $delivered[(int)$r['month_num']] = (int)$r['delivered_count'];
}

// ✅ STEP 2: FETCH ACTIVE CUSTOMERS FOR PENDING LOGIC
$custSql = "
  SELECT u.id, u.Fname, u.Lname, u.Validity
  FROM tbl_users u
  WHERE u.Roll=5 AND u.Status=1
";
$custRes = $conn->query($custSql);

// Loop through all active customers
while($cust = $custRes->fetch_assoc()) {
  // Get package validity ranges for this customer
  $pkgSql = "
    SELECT PkgDate, Validity 
    FROM tbl_cust_package_history
    WHERE CustId='{$cust['id']}'
      AND (
        (PkgDate BETWEEN '$from' AND '$to')
        OR (Validity BETWEEN '$from' AND '$to')
        OR ('$from' BETWEEN PkgDate AND Validity)
      )
  ";
  $pkgRes = $conn->query($pkgSql);
  while($pkg = $pkgRes->fetch_assoc()){
    $pkgStart = max($from, $pkg['PkgDate']);
    $pkgEnd = min($to, $pkg['Validity'] ?? $to);

    $datePeriod = new DatePeriod(
      new DateTime($pkgStart),
      new DateInterval('P1D'),
      (new DateTime($pkgEnd))->modify('+1 day')
    );

    foreach($datePeriod as $date){
      $d = $date->format('Y-m-d');
      $m = (int)$date->format('m');

      // Check if delivered on this date
      $delCheck = $conn->query("
        SELECT 1 FROM tbl_order_status_log 
        WHERE UserId='{$cust['id']}' 
          AND Status='Delivered' 
          AND DATE(CreatedDate)='$d'
      ");

      if($delCheck->num_rows == 0){
        $pending[$m] += 1;
      }
    }
  }
}

$totalDelivered = array_sum($delivered);
$totalPending = array_sum($pending);
$totalOrders = $totalDelivered + $totalPending;
?>
<style>
    body {
      font-family: 'Poppins', sans-serif;
      background: white;
      color: #333;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 1100px;
      margin: 40px auto;
      background: white;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      padding: 35px 45px;
    }
    h2 {
      text-align: center;
      font-size: 26px;
      margin-bottom: 8px;
      color: #222;
    }
    p.subtitle {
      text-align: center;
      color: #666;
      margin-top: 0;
      margin-bottom: 25px;
    }
    .filters {
      display: flex;
      justify-content: center;
      gap: 15px;
      flex-wrap: wrap;
      margin-bottom: 25px;
    }
    input, button {
      padding: 8px 12px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 14px;
      outline: none;
    }
    button {
      background: #007bff;
      color: white;
      border: none;
      cursor: pointer;
      transition: 0.3s;
    }
    button:hover { background: #0056b3; }
    canvas {
      width: 100% !important;
      height: 420px !important;
      margin-top: 20px;
    }
    .summary {
      display: flex;
      justify-content: space-evenly;
      margin-top: 35px;
      text-align: center;
    }
    .summary-card {
      background: linear-gradient(135deg, #e6f7ff, #e6ffe6);
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.08);
      padding: 15px 25px;
      flex: 1;
      margin: 0 10px;
      transition: 0.3s;
    }
    .summary-card:hover { transform: translateY(-4px); }
    .summary-card h3 {
      margin: 0;
      color: #333;
      font-size: 17px;
    }
    .summary-card span {
      display: block;
      font-size: 24px;
      font-weight: bold;
      color: #007bff;
      margin-top: 5px;
    }
  </style>
  <div class="row g-4">
   <form method="post" class="filters">
    <div>
      <label>From:</label>
      <input type="date" name="FromDate" value="<?= $from ?>" required>
    </div>
    <div>
      <label>To:</label>
      <input type="date" name="ToDate" value="<?= $to ?>" required>
    </div>
    <button type="submit">Show Report</button>
  </form>

  <canvas id="growthChart"></canvas>

  <div class="summary">
      <div class="summary-card">
      <h3>Total Orders</h3>
      <span><?= $totalOrders ?></span>
    </div>
    <div class="summary-card">
      <h3>Total Delivered</h3>
      <span><?= $totalDelivered ?></span>
    </div>
    <div class="summary-card">
      <h3>Total Pending</h3>
      <span><?= $totalPending ?></span>
    </div>
    
  </div>
</div>


</div>
</div>

<?php include_once 'footer.php'; ?>
</div>
</div>


<?php include_once 'footer_script.php'; ?>



<script>
const ctx = document.getElementById('growthChart').getContext('2d');

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: <?= json_encode($months) ?>,
    datasets: [
      {
        label: 'Delivered',
        data: <?= json_encode(array_values($delivered)) ?>,
        backgroundColor: 'rgba(40, 167, 69, 0.8)',
        borderColor: 'rgba(33, 136, 56, 1)',
        borderWidth: 1,
        borderRadius: 8,
        hoverBackgroundColor: 'rgba(40, 167, 69, 1)',
      },
      {
        label: 'Pending',
        data: <?= json_encode(array_values($pending)) ?>,
        backgroundColor: 'rgba(255, 193, 7, 0.8)',
        borderColor: 'rgba(230, 160, 0, 1)',
        borderWidth: 1,
        borderRadius: 8,
        hoverBackgroundColor: 'rgba(255, 193, 7, 1)',
      }
    ]
  },
  options: {
    responsive: true,
    interaction: {
      mode: 'index',
      intersect: false
    },
    plugins: {
      title: {
        display: true,
        text: 'Monthly Delivery Performance',
        font: { size: 18, weight: 'bold' },
        color: '#333'
      },
      legend: {
        position: 'bottom',
        labels: {
          font: { size: 13 },
          color: '#222'
        }
      },
      tooltip: {
        enabled: true,
        backgroundColor: 'rgba(30, 30, 30, 0.9)',
        borderColor: '#aaa',
        borderWidth: 1,
        titleColor: '#fff',
        bodyColor: '#fff',
        bodySpacing: 6,
        padding: 10,
        usePointStyle: true,
        displayColors: true,
        callbacks: {
          // Tooltip title: show only the month
          title: function (tooltipItems) {
            const month = tooltipItems[0].label;
            return '📅 ' + month;
          },
          // Tooltip label: clean numeric value
          label: function (tooltipItem) {
            return tooltipItem.dataset.label + ': ' + tooltipItem.formattedValue + ' Orders';
          },
          footer: function () { return ''; }
        }
      }
    },
    scales: {
      x: {
        grid: { display: false },
        ticks: {
          color: '#333',
          font: { size: 12 }
        },
        title: {
          display: true,
          text: 'Months',
          color: '#555',
          font: { size: 14, weight: 'bold' }
        }
      },
      y: {
        beginAtZero: true,
        grid: { color: 'rgba(200,200,200,0.2)' },
        ticks: {
          color: '#333',
          font: { size: 12 },
          stepSize: 5
        },
        title: {
          display: true,
          text: 'Number of Orders',
          color: '#555',
          font: { size: 14, weight: 'bold' }
        }
      }
    }
  }
});
</script>
</body>
</html>
