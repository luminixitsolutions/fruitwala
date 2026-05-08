<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Masters";
$Page = "Courses";

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Course</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="">
<meta name="author" content="" />

<?php include_once 'header_script.php'; ?>
<script src="../ckeditor/ckeditor.js"></script>
</head>
<body>
<style type="text/css">
  .password-tog-info {
  display: inline-block;
cursor: pointer;
font-size: 12px;
font-weight: 600;
position: absolute;
right: 50px;
top: 30px;
text-transform: uppercase;
z-index: 2;
}


</style>
<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>
<?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_packages WHERE id='$id'";
$row7 = getRecord($sql7);

if (isset($_POST['submit'])) {

    // Sanitize inputs
    $Name = mysqli_real_escape_string($conn, trim($_POST['Name']));
    $Amount = floatval($_POST['Amount']);
    $Period = intval($_POST['Period']);
    $Duration = mysqli_real_escape_string($conn, $_POST['Duration']);
    $Status = intval($_POST['Status']);
    $CreatedDate = date('Y-m-d H:i:s');

    // Validation
    if ($Name == '' || $Period <= 0 || $Period > 12) {
        echo "<script>alert('Please fill all required fields correctly.');</script>";
    } else {

        // Check duplicate
        $checkSql = "SELECT COUNT(*) as cnt FROM tbl_packages WHERE Name='$Name' " . ($id > 0 ? "AND id!='$id'" : "");
        $checkRes = $conn->query($checkSql);
        $exists = $checkRes->fetch_assoc()['cnt'] ?? 0;

        if ($exists > 0) {
            echo "<script>alert('Package name already exists!');</script>";
        } else {
            if ($id == 0) {
                // Insert new package
                $sql = "INSERT INTO tbl_packages (Name, Amount, Period, Duration, Status, CreatedBy, CreatedDate)
                        VALUES ('$Name', '$Amount', '$Period', '$Duration', '$Status', '$user_id', '$CreatedDate')";
                if ($conn->query($sql)) {
                    echo "<script>alert('Package Created Successfully!');window.location.href='view-packages.php';</script>";
                } else {
                    echo "<script>alert('Error creating package: " . $conn->error . "');</script>";
                }
            } else {
                // Update existing package
                $sql = "UPDATE tbl_packages SET 
                        Name='$Name',
                        Amount='$Amount',
                        Period='$Period',
                        Duration='$Duration',
                        Status='$Status',
                        ModifiedBy='$user_id',
                        ModifiedDate='$CreatedDate'
                        WHERE id='$id'";
                if ($conn->query($sql)) {
                    echo "<script>alert('Package Updated Successfully!');window.location.href='view-packages.php';</script>";
                } else {
                    echo "<script>alert('Error updating package: " . $conn->error . "');</script>";
                }
            }
        }
    }
}
?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Create Course</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data" class="row g-3">

  <input type="hidden" name="id" value="<?php echo $id; ?>">

  <div class="col-12">
    <label class="form-label">Package Name <span class="text-danger">*</span></label>
    <input type="text" name="Name" class="form-control" value="<?php echo htmlspecialchars($row7['Name']); ?>" required>
  </div>

  <div class="col-md-6">
    <label class="form-label">Amount (₹) <span class="text-danger">*</span></label>
    <input type="number" step="0.01" min="0" name="Amount" class="form-control" value="<?php echo htmlspecialchars($row7['Amount']); ?>" required>
  </div>

 <div class="col-md-6">
    <label class="form-label">Period <span class="text-danger">*</span></label>
    <select name="Period" class="form-control" required>
      <option value="">Select Period</option>
      <?php
      for ($i = 1; $i <= 12; $i++) {
          $sel = ($row7['Period'] == $i) ? 'selected' : '';
          echo "<option value='$i' $sel>$i</option>";
      }
      ?>
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">Duration Unit <span class="text-danger">*</span></label>
    <select name="Duration" class="form-control" required>
        <option value="Day" <?php if(isset($row7['Duration']) && $row7['Duration']=='Day') echo 'selected'; ?>>One Day</option>
        <option value="Week" <?php if($row7['Duration']=='Week') echo 'selected'; ?>>Week</option>
      <option value="Month" <?php if($row7['Duration']=='Month') echo 'selected'; ?>>Month</option>
      <option value="Year" <?php if($row7['Duration']=='Year') echo 'selected'; ?>>Year</option>
    </select>
  </div>

 

  <div class="col-md-6">
    <label class="form-label">Status <span class="text-danger">*</span></label>
    <select name="Status" class="form-control" required>
      <option value="1" <?php if($row7['Status']==1) echo 'selected'; ?>>Active</option>
      <option value="0" <?php if($row7['Status']==0) echo 'selected'; ?>>Inactive</option>
    </select>
  </div>

  <div class="col-12 mt-3">
    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    <a href="view-packages.php" class="btn btn-secondary">Cancel</a>
  </div>

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
 <script>
        CKEDITOR.replace( 'editor1');
</script>
<script type="text/javascript">

  function error_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.error({
      title:    'Error',
      message:  'Email Id / Phone No Already Exists',
      location: isRtl ? 'tl' : 'tr'
    });
  }
    function success_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'Saved Successfully...',
      location: isRtl ? 'tl' : 'tr'
    });
  }
   $(document).ready(function(){
   

$(document).on("click", "#delete_photo", function(event){
event.preventDefault();  
if(confirm("Are you sure you want to delete Course Photo?"))  
           {  
             var action = "deletePhoto";
             var id = $('#userid').val();
             var Photo = $('#OldPhoto').val();
             $.ajax({
    url:"ajax_files/ajax_courses.php",
    method:"POST",
    data : {action:action,id:id,Photo:Photo},
    success:function(data)
    {

      $('#show_photo').hide();
      var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  data,
      location: isRtl ? 'tl' : 'tr'
    });

    }
    });
           }

   });

$(document).on("click", "#delete_photo3", function(event){
event.preventDefault();  
if(confirm("Are you sure you want to delete Course Photo?"))  
           {  
             var action = "deletePhoto2";
             var id = $('#userid').val();
             var Photo = $('#OldPhoto2').val();
             $.ajax({
    url:"ajax_files/ajax_courses.php",
    method:"POST",
    data : {action:action,id:id,Photo:Photo},
    success:function(data)
    {

      $('#show_photo3').hide();
      var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  data,
      location: isRtl ? 'tl' : 'tr'
    });

    }
    });
           }

   });

$(document).on("click", "#delete_photo2", function(event){
event.preventDefault();  
if(confirm("Are you sure you want to delete Document?"))  
           {  
             var action = "deleteDoc";
             var id = $('#userid').val();
             var Photo = $('#OldFile').val();
             $.ajax({
    url:"ajax_files/ajax_courses.php",
    method:"POST",
    data : {action:action,id:id,Photo:Photo},
    success:function(data)
    {

      $('#show_photo2').hide();
      var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  data,
      location: isRtl ? 'tl' : 'tr'
    });

    }
    });
           }

   });

$(document).on("change", "#DeptId", function(event){
  var val = this.value;
   var action = "getMulSubjects";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      //alert(data);
      $('#showmaterials').html(data);
    }
    });

 });
  
});
</script>
</body>
</html>