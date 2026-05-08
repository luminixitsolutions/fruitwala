<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Reports";
$Page = "View-Leads";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> </title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />

<?php include_once 'header_script.php'; ?>

</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>

<div class="layout-container">
<?php include_once 'top_header.php'; ?>



<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">
   Leads Report

</h4>

<div class="card" style="padding-right:10px;padding-left:10px;">
  <div id="accordion2">
    <div class="card mb-2">
      <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
        <div style="padding:5px;">
          <form id="validation-form" method="post" enctype="multipart/form-data" action="">
            <div class="form-row">
                
                <!-- Customer -->
             <div class="form-group col-md-3">
                <label class="form-label">Customers </label>
                <select class="select2-demo form-control" id="UserId" name="UserId">
                  <option selected value="all">All</option>
                  <?php 
                    $sql = "SELECT * FROM tbl_users WHERE Roll=5";
                    $row = getList($sql);
                    foreach($row as $result){ ?>
                      <option value="<?php echo $result['id']; ?>" <?php if($_POST["UserId"]==$result['id']) echo 'selected'; ?>>
                        <?php echo $result['Fname']." ".$result['Lname']; ?>
                      </option>
                  <?php } ?>
                </select>
                <div class="clearfix"></div>
              </div>

              <!-- From Date -->
              <div class="form-group col-md-2">
                <label class="form-label">From Date</label>
                <input type="date" name="FromDate" id="FromDate" class="form-control"
                       value="<?php echo $_POST['FromDate'] ?? ''; ?>" autocomplete="off">
              </div>

              <!-- To Date -->
              <div class="form-group col-md-2">
                <label class="form-label">To Date</label>
                <input type="date" name="ToDate" id="ToDate" class="form-control"
                       value="<?php echo $_POST['ToDate'] ?? ''; ?>" autocomplete="off">
              </div>

              <input type="hidden" name="Search" value="Search">

              <!-- Search Button -->
              <div class="form-group col-md-1">
                <label class="form-label">&nbsp;</label>
                <button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
              </div>

              <!-- Clear Button -->
              <?php if(isset($_POST['Search'])) { ?>
              <div class="form-group col-md-1">
                <label class="form-label">&nbsp;</label>
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" title="Clear Filter">X</a>
              </div>
              <?php } ?>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="card-datatable table-responsive">
    <table id="example" class="table table-striped table-bordered" style="width:100%">
      <thead>
        <tr>
          <th>Sr No</th>
          <th>Date</th>
          <th>Type</th>
          <th>Invoice / Code</th>
          <th>Payment Mode</th>
          <th>Narration</th>
          <th class="text-right">Debit (₹)</th>
          <th class="text-right">Credit (₹)</th>
          <th class="text-right">Balance (₹)</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $sql = "SELECT * FROM tbl_general_ledger WHERE 1 ";

        // Apply filters
        if(isset($_POST['Search'])) {

          if(!empty($_POST['UserId']) && $_POST['UserId'] != 'all') {
            $userId = intval($_POST['UserId']);
            $sql .= " AND UserId = '$userId' ";
          }

          if(!empty($_POST['FromDate']) && !empty($_POST['ToDate'])) {
            $from = $_POST['FromDate'];
            $to = $_POST['ToDate'];
            $sql .= " AND DATE(PaymentDate) BETWEEN '$from' AND '$to' ";
          } elseif(!empty($_POST['FromDate'])) {
            $from = $_POST['FromDate'];
            $sql .= " AND DATE(PaymentDate) >= '$from' ";
          } elseif(!empty($_POST['ToDate'])) {
            $to = $_POST['ToDate'];
            $sql .= " AND DATE(PaymentDate) <= '$to' ";
          }
        }

        // ✅ Order by date ascending, debit before credit
        $sql .= " ORDER BY PaymentDate ASC, 
                         CASE WHEN CrDr='dr' THEN 1 ELSE 2 END, 
                         id ASC";

        $res = $conn->query($sql);
        $sr = 0;
        $balance = 0;
        $totalDebit = 0;
        $totalCredit = 0;

        while($row = $res->fetch_assoc()) {
          $sr++;
          $date = date("d/m/Y", strtotime($row['PaymentDate']));
          $type = $row['Type'];
          $code = $row['Code'] ?: '-';
          $mode = $row['PayMode'] ?: '-';
          $narration = $row['Narration'] ?: '-';
          $crdr = strtolower($row['CrDr']);

          if($crdr == 'dr') {
            $debit = $row['Amount'];
            $credit = 0;
            $balance += $debit;
            $totalDebit += $debit;
          } else {
            $credit = $row['Amount'];
            $debit = 0;
            $balance -= $credit;
            $totalCredit += $credit;
          }

          echo "<tr>
            <td>{$sr}</td>
            <td>{$date}</td>
            <td>{$type}</td>
            <td>{$code}</td>
            <td>{$mode}</td>
            <td>{$narration}</td>
            <td class='text-right'>".($debit > 0 ? number_format($debit,2) : '-')."</td>
            <td class='text-right'>".($credit > 0 ? number_format($credit,2) : '-')."</td>
            <td class='text-right'><b>".number_format($balance,2)."</b></td>
          </tr>";
        }
        ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="6" class="text-right">Total :</th>
          <th class="text-right text-primary">₹ <?php echo number_format($totalDebit, 2); ?></th>
          <th class="text-right text-success">₹ <?php echo number_format($totalCredit, 2); ?></th>
          <th class="text-right text-danger"><b>₹ <?php echo number_format($balance, 2); ?></b></th>
        </tr>
      </tfoot>
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



<script type="text/javascript">
$(document).ready(function() {
  $('#example').DataTable({
     scrollX: true,
    order: [[1, "asc"]],
    pageLength: 25,
    dom: 'Bfrtip', // Add this to show buttons
    buttons: [
      {
        extend: 'excelHtml5',
        title: 'Lead_Report',
        text: '<i class="fa fa-file-excel"></i> Excel',
        className: 'btn btn-success btn-sm'
      }
    ]
  });
});
</script>


</body>
</html>
