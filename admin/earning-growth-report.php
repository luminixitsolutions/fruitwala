<?php
include_once 'config.php';

// 🗓️ Date Filter Inputs
$from = $_POST['FromDate'] ?? date('Y-01-01');
$to   = $_POST['ToDate'] ?? date('Y-12-31');
$currentYear = date('Y', strtotime($to));

// 📊 Get EARNINGS (Credit)
$sqlEarning = "
  SELECT DATE_FORMAT(PaymentDate, '%Y-%m') AS MonthKey, 
         SUM(Amount) AS TotalEarning
  FROM tbl_general_ledger
  WHERE CrDr='cr' AND PaymentDate BETWEEN '$from' AND '$to'
  GROUP BY MonthKey
";
$resE = $conn->query($sqlEarning);
$earningData = [];
while ($row = $resE->fetch_assoc()) {
  $earningData[$row['MonthKey']] = (float)$row['TotalEarning'];
}

// 💸 Get EXPENSES (Company Spending)
$sqlExpense = "
  SELECT DATE_FORMAT(ExpenseDate, '%Y-%m') AS MonthKey,
         SUM(Amount) AS TotalExpense
  FROM tbl_company_expenses
  WHERE ExpenseDate BETWEEN '$from' AND '$to'
  GROUP BY MonthKey
";
$resX = $conn->query($sqlExpense);
$expenseData = [];
while ($row = $resX->fetch_assoc()) {
  $expenseData[$row['MonthKey']] = (float)$row['TotalExpense'];
}

// 🧮 Prepare Final 12-Month Data
$months = [];
$earnings = [];
$expenses = [];
$profit = [];

for ($m = 1; $m <= 12; $m++) {
  $monthKey = sprintf("%s-%02d", $currentYear, $m);
  $monthName = date('M Y', strtotime($monthKey . '-01'));

  $earn = $earningData[$monthKey] ?? 0;
  $exp = $expenseData[$monthKey] ?? 0;
  $prof = $earn - $exp;

  $months[] = $monthName;
  $earnings[] = $earn;
  $expenses[] = $exp;
  $profit[] = $prof;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Income vs Expense Report</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f4f6fa;
      font-family: "Poppins", sans-serif;
    }
    .card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 6px 25px rgba(0,0,0,0.1);
      margin-top: 40px;
      padding: 30px;
    }
    .chart-title {
      font-size: 22px;
      font-weight: 700;
      text-align: center;
      margin-bottom: 20px;
      color: #222;
    }
  </style>
</head>
<body class="container py-4">

  <form method="POST" class="row gy-2">
      <div class="col-md-2 d-flex align-items-end">
      <a href="dashboard.php" class="btn btn-primary w-100">Home</a>
    </div>
    <div class="col-md-4">
      <label>From Date</label>
      <input type="date" name="FromDate" class="form-control" value="<?= $from ?>">
    </div>
    <div class="col-md-4">
      <label>To Date</label>
      <input type="date" name="ToDate" class="form-control" value="<?= $to ?>">
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <button class="btn btn-primary w-100">Filter</button>
    </div>
  </form>

<div class="card" style="padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-radius: 15px;">
  <h5 class="text-center mb-4">📊 Monthly Income vs Expense Report (<?= $currentYear ?>)</h5>

  <div style="position: relative; height: 450px; width: 100%;">
    <canvas id="incomeExpenseChart"></canvas>
  </div>
</div>

<script>
const ctx = document.getElementById('incomeExpenseChart').getContext('2d');

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: <?= json_encode($months) ?>,
    datasets: [
      {
        label: 'Earnings (Credit)',
        data: <?= json_encode($earnings) ?>,
        backgroundColor: 'rgba(40,167,69,0.9)',
        borderColor: 'rgba(40,167,69,1)',
        borderWidth: 1.5,
        borderRadius: 6,
        hoverBackgroundColor: 'rgba(33,136,56,1)',
        maxBarThickness: 45,
        categoryPercentage: 0.9,
        barPercentage: 1.0
      },
      {
        label: 'Expenses (Debit)',
        data: <?= json_encode($expenses) ?>,
        backgroundColor: 'rgba(220,53,69,0.9)',
        borderColor: 'rgba(200,35,51,1)',
        borderWidth: 1.5,
        borderRadius: 6,
        hoverBackgroundColor: 'rgba(220,53,69,1)',
        maxBarThickness: 45,
        categoryPercentage: 0.9,
        barPercentage: 1.0
      },
      {
        label: 'Profit (Earnings - Expenses)',
        data: <?= json_encode($profit) ?>, // ✅ Can include negative values (e.g. -1500)
        backgroundColor: function(ctx) {
          const val = ctx.raw;
          if (val > 0) return 'rgba(0,123,255,0.9)'; // Positive → Blue
          if (val < 0) return 'rgba(255,99,71,0.9)'; // Negative → Red
          return 'rgba(128,128,128,0.7)'; // Zero → Grey
        },
        borderColor: function(ctx) {
          const val = ctx.raw;
          return val >= 0 ? 'rgba(0,90,200,1)' : 'rgba(200,60,60,1)';
        },
        borderWidth: 1.5,
        borderRadius: 6,
        hoverBackgroundColor: function(ctx) {
          const val = ctx.raw;
          return val >= 0 ? 'rgba(0,123,255,1)' : 'rgba(255,80,80,1)';
        },
        maxBarThickness: 45,
        categoryPercentage: 0.9,
        barPercentage: 1.0
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    interaction: { mode: 'index', intersect: false },
    animation: { duration: 1000, easing: 'easeOutCubic' },
    layout: { padding: { top: 10, bottom: 10, left: 10, right: 10 } },
    plugins: {
      title: {
        display: true,
        text: 'Income, Expense & Profit Overview',
        font: { size: 20, weight: 'bold' },
        color: '#333',
        padding: { bottom: 15 }
      },
      legend: {
        position: 'bottom',
        labels: { font: { size: 14, weight: '600' }, color: '#333' }
      },
      tooltip: {
        backgroundColor: '#2c2c2c',
        titleColor: '#fff',
        bodyColor: '#fff',
        padding: 10,
        callbacks: {
          title: (items) => '📅 ' + items[0].label,
          label: (item) => `${item.dataset.label}: ₹${item.formattedValue}`
        }
      }
    },
    scales: {
      x: {
        grid: { display: false },
        stacked: false,
        title: {
          display: true,
          text: 'Months',
          font: { weight: 'bold', size: 14 },
          color: '#333'
        },
        ticks: { color: '#333', font: { size: 13 } }
      },
      y: {
        stacked: false,
        title: {
          display: true,
          text: 'Amount (₹)',
          font: { weight: 'bold', size: 14 },
          color: '#333'
        },
        ticks: {
          color: '#333',
          font: { size: 13, weight: '500' },
          callback: function(value) {
            return '₹' + value;
          }
        },
        grid: { color: 'rgba(200,200,200,0.2)' }
      }
    }
  }
});
</script>




</body>
</html>
