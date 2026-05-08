<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];

$MainPage = "Customers";
$Page = "Add Expenses";

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="">
<meta name="author" content="">

<?php include_once 'header_script.php'; ?>
</head>
<body>
<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>

<div class="layout-container">

<?php include_once 'top_header.php'; ?>

<?php 
$id = $_GET['id'] ?? '';
if($id != ''){
  $sql7 = "SELECT * FROM tbl_users WHERE id='$id'";
  $row7 = getRecord($sql7);
  $FullName = $row7['Fname']." ".$row7['Lname'];
}

if(isset($_POST['submit'])){
  $RenewDate = $_POST['RenewDate'];
  $PackageId = addslashes(trim($_POST['PackageId']));
  $PkgDate = addslashes(trim($_POST['PkgDate']));
$Validity = addslashes(trim($_POST['Validity']));
$PkgAmount = addslashes(trim($_POST['PkgAmount']));
$PaidAmount = addslashes(trim($_POST['PaidAmount']));
$BalAmount = addslashes(trim($_POST['BalAmount']));
$PayMode = addslashes(trim($_POST['PayMode']));
$CreatedDate = date('Y-m-d');

    $sql = "INSERT INTO tbl_cust_package_history SET PayMode='$PayMode',RenewDate='$RenewDate',CustId='$id',PackageId='$PackageId',PkgDate='$PkgDate',Validity='$Validity',PkgAmount='$PkgAmount',UpdateStatus=0";
     $conn->query($sql);
     
     $sql = "UPDATE tbl_users SET PaidAmount='$PaidAmount',PackageId='$PackageId',PkgDate='$PkgDate',Validity='$Validity',PkgAmount='$PkgAmount' WHERE id='$id'";
     $conn->query($sql);
     
     $sql = "INSERT INTO tbl_general_ledger SET PayMode='$PayMode',UserId='$id',AccountName='$FullName',Amount='$PkgAmount',PaymentDate='$PkgDate',CrDr='dr',Roll=5,Type='CINV',CreatedBy='$user_id',CreatedDate='$CreatedDate',SellType='CustInv'";
    $conn->query($sql);
    if($PaidAmount > 0){
        $sql2 = "SELECT MAX(SrNo) as maxid FROM tbl_general_ledger WHERE Type='PR'";
    $row2 = getRecord($sql2);
    if($row2['maxid'] == ''){
        $SrNo = 1;
        $Code = "PR".$SrNo;
    }
    else{
        $SrNo = $row2['maxid']+1;
        $Code = "PR".$SrNo;
    }
    
    $sql4 = "INSERT INTO tbl_general_ledger SET PaymentDate='$PkgDate',PayMode='$PayMode',SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$FullName',InvoiceNo='$InvoiceNo',Amount='$PaidAmount',CrDr='cr',Roll=5,Type='PR',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
    $conn->query($sql4);
    }
    echo "<script>alert('Package Renewed Successfully!');window.location.href='view-expired-customers.php';</script>";
}
?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Renew Package</h4>

<div class="card mb-4">
<div class="card-body">

<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

<input type="hidden" name="id" value="<?php echo $id; ?>">

<div class="form-group col-md-4">
  <label class="form-label">Renew Date <span class="text-danger">*</span></label>
  <input type="date" name="RenewDate" class="form-control" required value="<?php echo $row7['RenewDate'] ?? date('Y-m-d'); ?>" readonly>
</div>

<div class="form-group col-md-4">
  <label class="form-label">Customer Name </label>
  <input type="text" class="form-control" required value="<?php echo $row7['Fname']." ".$row7['Lname']; ?>" disabled>
</div>

<div class="form-group col-md-4">
  <label class="form-label">Phone No </label>
  <input type="text" class="form-control" required value="<?php echo $row7['Phone']; ?>" disabled>
</div>

<div class="form-group col-md-12">
  <label class="form-label">Address </label>
  <input type="text"class="form-control" required value="<?php echo $row7['Address']; ?>" disabled>
</div>



<div class="form-group col-md-3">
  <label class="form-label">Select Package <span class="text-danger">*</span></label>
  <select class="form-control" name="PackageId" id="PackageId" required onchange="getPackageDetails(this.value)">
    <option selected disabled>Select Package</option>
    <?php 
      $q = "SELECT * FROM tbl_packages WHERE Status=1 ORDER BY Name ASC";
      $r = $conn->query($q);
      while($rw = $r->fetch_assoc()) {
    ?>
      <option 
        value="<?php echo $rw['id']; ?>" 
        data-duration="<?php echo $rw['Duration']; ?>" 
        data-period="<?php echo $rw['Period']; ?>" 
        data-amount="<?php echo $rw['Amount']; ?>" 
        >
        <?php echo $rw['Name']." (₹".$rw['Amount'].")"; ?>
      </option>
    <?php } ?>
  </select>
</div>


<div class="form-group col-md-3">
  <label class="form-label">Start From <span class="text-danger">*</span></label>
  <input type="date" name="PkgDate" id="PkgDate" class="form-control"
         value="<?php echo date('Y-m-d'); ?>"
         onchange="autoCalculateValidity()" required>
</div> 

<div class="form-group col-md-3">
  <label class="form-label">Validity <span class="text-danger">*</span></label>
  <input type="date" name="Validity" id="Validity" class="form-control"
         value="" required>
</div>

<div class="form-group col-md-3">
  <label class="form-label">Amount <span class="text-danger">*</span></label>
  <input type="text" name="PkgAmount" id="PkgAmount" class="form-control"
         value="<?php echo $row7['PkgAmount'];?>" readonly>
</div>

<div class="form-group col-md-3">
  <label class="form-label">Paid Amount <span class="text-danger">*</span></label>
  <input type="text" name="PaidAmount" id="PaidAmount" class="form-control"
         value="<?php echo $row7['PaidAmount'];?>" required >
</div>

<div class="form-group col-md-3">
  <label class="form-label">Balance Amount <span class="text-danger">*</span></label>
  <input type="text" name="BalAmount" id="BalAmount" class="form-control"
         value="<?php echo $row7['BalAmount'];?>" readonly>
</div>

<div class="form-group col-md-3">
<label class="form-label">Payment Mode <span class="text-danger">*</span></label>
  <select class="form-control" id="PayMode" name="PayMode" required="">
<option selected="" disabled="" value="">Select Pay Mode</option>
<option value="Cash" <?php if($row7["PayMode"]=='Cash') {?> selected <?php } ?>>Cash</option>
<option value="UPI" <?php if($row7["PayMode"]=='UPI') {?> selected <?php } ?>>UPI</option>
</select>
<div class="clearfix"></div>
</div>

</div>

<button type="submit" name="submit" class="btn btn-primary btn-finish mt-3">Submit</button>

</form>
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
// ✅ Auto-calculate validity date when package or start date changes
function getPackageDetails(packageId) {
  const select = document.getElementById('PackageId');
  const option = select.options[select.selectedIndex];
  const PkgAmount = option.getAttribute('data-amount'); 
  const duration = option.getAttribute('data-duration'); // Month or Year
  const period = parseInt(option.getAttribute('data-period')) || 0;

  const startInput = document.getElementById('PkgDate');
  const validityInput = document.getElementById('Validity');
    $('#PkgAmount').val(PkgAmount);
    // Recalculate balance automatically
  updateBalance();
  // Ensure start date defaults to today
  if (!startInput.value) {
    const today = new Date();
    startInput.value = today.toISOString().split('T')[0];
  }

  const startDate = new Date(startInput.value);

  if (duration === 'Day') {
  startDate.setDate(startDate.getDate() + period);
} else if (duration === 'Month') {
  startDate.setMonth(startDate.getMonth() + period);
} else if (duration === 'Week') {
  startDate.setDate(startDate.getDate() + (period * 7));
} else if (duration === 'Year') {
  startDate.setFullYear(startDate.getFullYear() + period);
}


  // Set validity date
  validityInput.value = startDate.toISOString().split('T')[0];
}

function autoCalculateValidity() {
  // recalc when user changes "Start From" manually
  const select = document.getElementById('PackageId');
  if (select.value) {
    getPackageDetails(select.value);
  }
}

// ✅ Update balance when typing Paid Amount
function updateBalance() {
  const pkg = parseFloat($('#PkgAmount').val()) || 0;
  const paid = parseFloat($('#PaidAmount').val()) || 0;
  const balance = pkg - paid;
  $('#BalAmount').val(balance.toFixed(2));

  // Optional: show warning if overpaid
  if (balance < 0) {
    $('#BalAmount').css('color', 'red');
  } else {
    $('#BalAmount').css('color', 'black');
  }
}

// ✅ Automatically calculate validity if editing an existing record
document.addEventListener('DOMContentLoaded', () => {
  const select = document.getElementById('PackageId');
  if (select.value) {
    getPackageDetails(select.value);
  } else {
    // Default start date = today
    document.getElementById('PkgDate').value = new Date().toISOString().split('T')[0];
  }
  
   // ✅ Auto calculate balance when typing PaidAmount
  const pkgInput = document.getElementById('PkgAmount');
  const paidInput = document.getElementById('PaidAmount');
  const balInput = document.getElementById('BalAmount');



   $('#PaidAmount').on('input', updateBalance);
});
</script>

</body>
</html>
