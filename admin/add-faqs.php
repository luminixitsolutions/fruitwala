<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Faq";
$Page = "Faq";

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Faq</title>
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
$sql7 = "SELECT * FROM tbl_faqs WHERE id='$id'";
$row7 = getRecord($sql7);

if(isset($_POST['submit'])){
$Question = addslashes(trim($_POST["Question"]));
$Answer = addslashes(trim($_POST["Answer"]));
$Status = $_POST["Status"];

  if($_GET['id'] == ''){
    $sql = "INSERT INTO tbl_faqs SET Question = '$Question',Answer = '$Answer',Status='$Status'";
    $conn->query($sql);
    echo "<script>alert('Faqs Created Successfully!');window.location.href='faqs.php';</script>";
  }
  else{
     $sql = "UPDATE tbl_faqs SET Question = '$Question',Answer = '$Answer',Status='$Status' WHERE id='$id'";
    $conn->query($sql);
    echo "<script>alert('Faqs Updated Successfully!');window.location.href='faqs.php';</script>";
  }

}
?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Faqs</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">
<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
<input type="hidden" name="action" value="Save" id="action">

<div class="form-group col-lg-12">
<label class="form-label">Question <span class="text-danger">*</span></label>
<input type="text" name="Question" class="form-control" id="Question" placeholder="" value="<?php echo $row7["Question"]; ?>" required>
<div class="clearfix"></div>
</div>



 <div class="form-group col-md-12">
  <label class="form-label">Answer </label>
  <textarea class="form-control" name="Answer" id="editor1"><?php echo $row7["Answer"]; ?></textarea>
</div> 



<div class="form-group col-md-12">
<label class="form-label">Status <span class="text-danger">*</span></label>
  <select class="form-control" id="Status" name="Status" required="">
<option selected="" disabled="" value="">Select Status</option>
<option value="1" <?php if($row7["Status"]=='1') {?> selected <?php } ?>>Active</option>
<option value="0" <?php if($row7["Status"]=='0') {?> selected <?php } ?>>Inctive</option>
</select>
<div class="clearfix"></div>
</div>
</div>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
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
function isNumberKey(evt){ 
    var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
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
    url:"ajax_files/ajax_website_notification.php",
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


$(document).on("click", "#delete_photo2", function(event){
event.preventDefault();  
if(confirm("Are you sure you want to delete Document?"))  
           {  
             var action = "deleteDoc";
             var id = $('#userid').val();
             var Photo = $('#OldFile').val();
             $.ajax({
    url:"ajax_files/ajax_website_notification.php",
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
   var action = "getCourse";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#CourseId').html(data);
    }
    });

 });

$(document).on("change", "#CourseId", function(event){
  var val = this.value;
  var action = "getBatch";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#BatchId').html(data);
    }
    });

 });
  
});
</script>
</body>
</html>