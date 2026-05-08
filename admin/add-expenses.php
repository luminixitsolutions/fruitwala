<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];

$MainPage = "Expenses";
$Page = "Add Expenses";

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Company Expenses</title>
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
  $sql7 = "SELECT * FROM tbl_company_expenses WHERE id='$id'";
  $row7 = getRecord($sql7);
}

if(isset($_POST['submit'])){
  $ExpenseDate = $_POST['ExpenseDate'];
  $ExpenseType = addslashes(trim($_POST['ExpenseType']));
  $Description = addslashes(trim($_POST['Description']));
  $Amount = floatval($_POST['Amount']);
  $PaymentMode = $_POST['PaymentMode'];
  $ReferenceNo = addslashes(trim($_POST['ReferenceNo']));
  $Attachment = "";

  // Upload file if provided
  if(!empty($_FILES['Attachment']['name'])){
      $target_dir = "uploads/expenses/";
      if(!file_exists($target_dir)) mkdir($target_dir,0777,true);
      $filename = time() . "_" . basename($_FILES["Attachment"]["name"]);
      $target_file = $target_dir . $filename;
      move_uploaded_file($_FILES["Attachment"]["tmp_name"], $target_file);
      $Attachment = $filename;
  } else {
      $Attachment = $row7['Attachment'] ?? '';
  }

  if($id == ''){
    $sql = "INSERT INTO tbl_company_expenses 
            SET ExpenseDate='$ExpenseDate', ExpenseType='$ExpenseType', Description='$Description',
            Amount='$Amount', PaymentMode='$PaymentMode', ReferenceNo='$ReferenceNo', 
            Attachment='$Attachment', CreatedBy='$user_id'";
    $conn->query($sql);
    echo "<script>alert('Expense Added Successfully!');window.location.href='view-expenses.php';</script>";
  } else {
    $sql = "UPDATE tbl_company_expenses 
            SET ExpenseDate='$ExpenseDate', ExpenseType='$ExpenseType', Description='$Description',
            Amount='$Amount', PaymentMode='$PaymentMode', ReferenceNo='$ReferenceNo', 
            Attachment='$Attachment', UpdatedBy='$user_id'
            WHERE id='$id'";
    $conn->query($sql);
    echo "<script>alert('Expense Updated Successfully!');window.location.href='view-expenses.php';</script>";
  }
}
?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Company Expenses</h4>

<div class="card mb-4">
<div class="card-body">

<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

<input type="hidden" name="id" value="<?php echo $id; ?>">

<div class="form-group col-md-4">
  <label class="form-label">Expense Date <span class="text-danger">*</span></label>
  <input type="date" name="ExpenseDate" class="form-control" required value="<?php echo $row7['ExpenseDate'] ?? date('Y-m-d'); ?>">
</div>

<div class="form-group col-md-4">
  <label class="form-label">Expense Type <span class="text-danger">*</span></label>
  <select class="form-control" id="ExpenseType" name="ExpenseType" required="">
<option selected="" disabled="">...</option>
  <?php 
        $q = "select * from tbl_expense_category WHERE Status='1' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['ExpenseType']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
              </select>
</div>

<div class="form-group col-md-4">
  <label class="form-label">Amount (₹) <span class="text-danger">*</span></label>
  <input type="number" name="Amount" step="0.01" class="form-control" placeholder="Enter amount" required value="<?php echo $row7['Amount'] ?? ''; ?>">
</div>

<div class="form-group col-md-6">
  <label class="form-label">Payment Mode <span class="text-danger">*</span></label>
  <select name="PaymentMode" class="form-control" required>
    <option value="">Select Mode</option>
    <option value="Cash" <?php if(($row7['PaymentMode'] ?? '')=='Cash') echo 'selected'; ?>>Cash</option>
    <option value="UPI" <?php if(($row7['PaymentMode'] ?? '')=='UPI') echo 'selected'; ?>>UPI</option>
    <option value="Bank Transfer" <?php if(($row7['PaymentMode'] ?? '')=='Bank Transfer') echo 'selected'; ?>>Bank Transfer</option>
    <option value="Card" <?php if(($row7['PaymentMode'] ?? '')=='Card') echo 'selected'; ?>>Card</option>
    <option value="Cheque" <?php if(($row7['PaymentMode'] ?? '')=='Cheque') echo 'selected'; ?>>Cheque</option>
  </select>
</div>

<div class="form-group col-md-6">
  <label class="form-label">Reference / Transaction No.</label>
  <input type="text" name="ReferenceNo" class="form-control" placeholder="Enter Reference No" value="<?php echo $row7['ReferenceNo'] ?? ''; ?>">
</div>

<div class="form-group col-md-12">
  <label class="form-label">Description</label>
  <textarea name="Description" class="form-control" rows="3" placeholder="Enter details of expense"><?php echo $row7['Description'] ?? ''; ?></textarea>
</div>

<div class="form-group col-md-6">
  <label class="form-label">Attachment (Bill / Invoice)</label>
  <input type="file" name="Attachment" class="form-control">
  <?php if(!empty($row7['Attachment'])) { ?>
    <div id="show_photo" class="mt-2">
      <a href="uploads/expenses/<?php echo $row7['Attachment']; ?>" target="_blank" class="btn btn-sm btn-info">View File</a>
      <input type="hidden" id="OldAttachment" value="<?php echo $row7['Attachment']; ?>">
      <a href="#" id="delete_attachment" class="btn btn-sm btn-danger">Delete</a>
    </div>
  <?php } ?>
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
$(document).ready(function(){
  $(document).on("click", "#delete_attachment", function(e){
      e.preventDefault();
      if(confirm("Are you sure you want to delete this attachment?")){
          var id = "<?php echo $id; ?>";
          var Attachment = $('#OldAttachment').val();
          $.ajax({
              url:"ajax_files/ajax_expenses.php",
              method:"POST",
              data:{action:"deleteAttachment", id:id, Attachment:Attachment},
              success:function(data){
                  $('#show_photo').hide();
                  alert(data);
              }
          });
      }
  });
});
</script>

</body>
</html>
