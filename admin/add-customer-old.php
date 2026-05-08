<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Customers";
$Page = "Add-Agent";

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Partner</title>
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
$sql7 = "SELECT * FROM tbl_users WHERE id='$id'";
$row7 = getRecord($sql7);

?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Customers</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">
<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
<input type="hidden" name="action" value="Save" id="action">

<div class="form-group col-md-4">
<label class="form-label">First Name <span class="text-danger">*</span></label>
<input type="text" name="Fname" id="Fname" class="form-control" placeholder="First Name" value="<?php echo $row7["Fname"]; ?>" autocomplete="off" required>
</div>
<div class="form-group col-md-4">
  <label class="form-label">Middle Name </label>
<input type="text" name="Mname" id="Mname" class="form-control" placeholder="Middle Name" value="<?php echo $row7["Mname"]; ?>" autocomplete="off">
</div>
<div class="form-group col-md-4">
  <label class="form-label">Last Name <span class="text-danger">*</span></label>
<input type="text" name="Lname" id="Lname" class="form-control" placeholder="Last Name" value="<?php echo $row7["Lname"]; ?>" autocomplete="off" required>
</div>
<div class="form-group col-md-6">
<label class="form-label">Email Id </label>
<input type="email" name="EmailId" id="EmailId" class="form-control" placeholder="Email Id" value="<?php echo $row7["EmailId"]; ?>" autocomplete="off" >
<div class="clearfix"></div>
</div>
<div class="form-group col-md-6">
<label class="form-label">Mobile No <span class="text-danger">*</span></label>
<input type="text" name="Phone" id="Phone" class="form-control" placeholder="Mobile No" value="<?php echo $row7["Phone"]; ?>" required>
<div class="clearfix"></div>
</div>
<div class="form-group col-md-6">
<label class="form-label">Another Mobile No</label>
<input type="text" name="Phone2" class="form-control" placeholder="Another Mobile No" value="<?php echo $row7["Phone2"]; ?>">
<div class="clearfix"></div>
</div>
<div class="form-group col-md-6">
<label class="form-label">Password</label>
<input type="password" name="Password" id="Password" class="form-control" placeholder="Password" value="<?php echo $row7["Password"]; ?>">
<span class="password-tog-info show2" onclick="myFunction2()"><i class="fa fa-eye" aria-hidden="true"></i></span>
<div class="clearfix"></div>
</div>


<!--<div class="form-group col-md-5">
<label class="form-label">Marital Status </label>
<select class="form-control" id="MaritalStatus" name="MaritalStatus" >
<option selected="" disabled="" value="">Select Status</option>
<option value="Married" <?php if($row7["MaritalStatus"]=='Married') {?> selected <?php } ?>>Married</option>
<option value="Unmarried" <?php if($row7["MaritalStatus"]=='Unmarried') {?> selected <?php } ?>>Unmarried</option>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-5">
    <label class="form-label">Gender <span class="text-danger">*</span></label>
<div class="custom-controls-stacked">

  <div class="row">
    <div class="col-md-4">
<label class="custom-control custom-radio">
<input name="Gender" type="radio" class="custom-control-input" <?php if($row7['Gender']=='Male') {?> checked="" <?php } ?> value="Male" required>
<span class="custom-control-label">Male</span>
</label>
</div>
<div class="col-md-4">
<label class="custom-control custom-radio">
<input name="Gender" type="radio" class="custom-control-input" <?php if($row7['Gender']=='Female') {?> checked="" <?php } ?> value="Female">
<span class="custom-control-label">Female</span>
</label>
</div>
</div>
</div>
</div>-->


<div class="form-group col-md-3">
<label class="form-label">Lattitude</label>
<input type="text" name="latitude" id="latitude" class="form-control" placeholder="" value="<?php echo $row7["Lattitude"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Longitude</label>
<input type="text" name="longitude" id="longitude" class="form-control" placeholder="" value="<?php echo $row7["Longitude"]; ?>">
<div class="clearfix"></div>
</div>


  <div class="form-group col-md-3">
    <label class="form-label">Country</label>
    <select class="select2-demo form-control" name="CountryId" id="CountryId" >
<option selected="" disabled="">Select Country</option>
    <?php 
      $q = "select * from tbl_country";
      $r = $conn->query($q);
      while($rw = $r->fetch_assoc())
      {
    ?>
      <option <?php if(1==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
    <?php } ?>
</select>
  </div>
  <div class="form-group col-md-3">
    <label class="form-label">State </label>
<select class="select2-demo form-control" id="StateId" name="StateId" >
<option selected="" disabled="">Select State</option>
 <?php 
        $CountryId = $row7['CountryId'];
        $q = "select * from tbl_state WHERE CountryId='1'  ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if(1==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
  </div>
  <div class="form-group col-md-3">
      <label class="form-label">City/District </label>
<select class="select2-demo form-control" id="CityId" name="CityId" >
<option selected="" disabled="">Select City/District</option>
  <?php 
  if($_GET['id'] == ''){
       $StateId = 1;
  }
  else{
 $StateId = $row7['StateId'];
  }
        $q = "select * from tbl_city WHERE StateId='$StateId' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['CityId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
  </div>
    <div class="form-group col-md-3">
      <label class="form-label">Zone </label>
<select class="select2-demo form-control" id="ZoneId" name="ZoneId" >
<option selected="" disabled="">Select Zone</option>
  <?php 
 $CityId = $row7['CityId'];
        $q = "select * from tbl_zone WHERE CityId='$CityId' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['ZoneId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
  </div>
  
    <div class="form-group col-md-2">
      <label class="form-label">Ward No </label>
<select class="select2-demo form-control" id="WardId" name="WardId" >
<option selected="" disabled="">Select Ward No</option>
  <?php 
 $ZoneId = $row7['ZoneId'];
        $q = "select * from tbl_ward WHERE ZoneId='$ZoneId' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['WardId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
  </div>
  <div class="form-group col-md-2">
      <label class="form-label">Area </label>
<select class="select2-demo form-control" id="AreaId" name="AreaId" >
<option selected="" disabled="">Select Area</option>
  <?php 
 $WardId = $row7['WardId'];
        $q = "select * from tbl_area WHERE WardId='$WardId' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['AreaId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
  </div>

  <div class="form-group col-md-2">
<label class="form-label">Pincode No </label>
<input type="text" name="Pincode" class="form-control" placeholder="Pincode No" value="<?php echo $row7["Pincode"]; ?>" autocomplete="off" >
<div class="clearfix"></div>
</div> 

<div class="form-group col-lg-12">
<label class="form-label">Address <span class="text-danger">*</span></label>
<textarea name="Address" class="form-control" placeholder="" required><?php echo $row7["Address"]; ?></textarea>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-12">
<label class="form-label">Google Location Link </label>
<textarea name="LocationLink" class="form-control" placeholder=""><?php echo $row7["LocationLink"]; ?></textarea>
<div class="clearfix"></div>
</div>


<div class="form-group col-md-12">
  <label class="form-label"> Profile Photo </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="Photo" style="opacity: 1;">
<input type="hidden" name="OldPhoto" value="<?php echo $row7['Photo'];?>" id="OldPhoto">
<span class="custom-file-label"></span>
</label>
<?php if($row7['Photo']=='') {} else{?>
  <span id="show_photo">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo"></a><img src="../uploads/<?php echo $row7['Photo'];?>" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>
</span>
<?php } ?>
</div>


 <div class="form-group col-md-3">
  <label class="form-label">Select Package <span class="text-danger">*</span></label>
  <select class="form-control" name="PackageId" id="PackageId" required onchange="getPackageDetails(this.value)" <?php if($_GET['id']!=''){?> disabled <?php } ?>>
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
        <?php if($row7['PackageId']==$rw['id']) echo 'selected'; ?>>
        <?php echo $rw['Name']." (₹".$rw['Amount'].")"; ?>
      </option>
    <?php } ?>
  </select>
</div>

<div class="form-group col-md-3">
  <label class="form-label">Start From <span class="text-danger">*</span></label>
  <input type="date" name="PkgDate" id="PkgDate" class="form-control"
         value="<?php echo $row7['PkgDate'] ? $row7['PkgDate'] : date('Y-m-d'); ?>"
         onchange="autoCalculateValidity()" required >
</div> 
<?php if($_GET['id']==''){?>
<div class="form-group col-md-3">
  <label class="form-label">Validity <span class="text-danger">*</span></label>
  <input type="date" name="Validity" id="Validity" class="form-control"
         value="<?php echo $row7['Validity']; ?>" required >
</div>
<?php } else{?>
<div class="form-group col-md-3">
  <label class="form-label">Validity <span class="text-danger">*</span></label>
  <input type="date" name="Validity" class="form-control"
         value="<?php echo $row7['Validity']; ?>" required >
</div>
<?php } ?>
<div class="form-group col-md-3">
  <label class="form-label">Amount <span class="text-danger">*</span></label>
  <input type="text" name="PkgAmount" id="PkgAmount" class="form-control"
         value="<?php echo $row7['PkgAmount'];?>" readonly>
</div>

<div class="form-group col-md-2">
  <label class="form-label">Paid Amount <span class="text-danger">*</span></label>
  <input type="text" name="PaidAmount" id="PaidAmount" class="form-control"
         value="<?php echo $row7['PaidAmount'];?>" required <?php if($_GET['id']!=''){?> disabled <?php } ?>>
</div>

<div class="form-group col-md-3">
  <label class="form-label">Balance Amount <span class="text-danger">*</span></label>
  <input type="text" name="BalAmount" id="BalAmount" class="form-control"
         value="<?php echo $row7['BalAmount'];?>" readonly>
</div>

<div class="form-group col-md-3">
<label class="form-label">Payment Mode <span class="text-danger">*</span></label>
  <select class="form-control" id="PayMode" name="PayMode" >
<option selected="" disabled="" value="">Select Pay Mode</option>
<option value="Cash" <?php if($row7["PayMode"]=='Cash') {?> selected <?php } ?>>Cash</option>
<option value="UPI" <?php if($row7["PayMode"]=='UPI') {?> selected <?php } ?>>UPI</option>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Paid Status <span class="text-danger">*</span></label>
  <select class="form-control" id="PaidStatus" name="PaidStatus" required="">
<option selected="" disabled="" value="">Select Paid Status</option>
<option value="1" <?php if($row7["PaidStatus"]=='1') {?> selected <?php } ?>>Paid</option>
<option value="0" <?php if($row7["PaidStatus"]=='0') {?> selected <?php } ?>>Un-Paid</option>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Status <span class="text-danger">*</span></label>
  <select class="form-control" id="Status" name="Status" required="">
<option selected="" disabled="" value="">Select Status</option>
<option value="1" <?php if($row7["Status"]=='1') {?> selected <?php } ?>>Active</option>
<option value="0" <?php if($row7["Status"]=='0') {?> selected <?php } ?>>Inctive</option>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
      <label class="form-label">Assign Executive </label>
<select class="select2-demo form-control" id="ExeId" name="ExeId" >
<option selected="" disabled="">Select Executive</option>
  <?php 
        $q = "select id,Fname,Lname from tbl_users WHERE Roll=3 AND Status=1 ORDER BY Fname ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['ExeId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']." ".$rw['Lname']; ?></option>
              <?php } ?>
</select>
  </div>
  
  <div class="form-group col-lg-12">
<label class="form-label">Order Instruction/Details </label>
<textarea name="Details" class="form-control" placeholder=""><?php echo $row7["Details"]; ?></textarea>
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

  if (duration === 'Month') {
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

function myFunction2() {

  var x = document.getElementById("Password");
  if (x.type === "password") {
    x.type = "text";
      $('.show2').html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
  } else {
    x.type = "password";
      $('.show2').html('<i class="fa fa-eye" aria-hidden="true"></i>');
  }
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
    //$(document).on("click", ".btn-finish", function(event){
      $('#validation-form').on('submit', function(e){
      e.preventDefault();    
    if ($('#validation-form').valid()){ 
      
         $.ajax({  
                url :"ajax_files/ajax_customers.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                 beforeSend:function(){
     $('#submit').attr('disabled','disabled');
     $('#submit').text('Please Wait...');
    },
                success:function(data){ 
                 // console.log(data);exit();
                     if(data == 0){
                      error_toast();
                     
                     }
                     else{
                   success_toast();
                   setTimeout(function(){  
                   window.location.href = 'view-customers.php';
                    }, 2000);
                     }
                      $('#submit').attr('disabled',false);
     $('#submit').text('Save');
                }  
           })  



    }
else{
  //$('#Fname').focus();
    return false;
}
  });

$(document).on("click", "#delete_photo", function(event){
event.preventDefault();  
if(confirm("Are you sure you want to delete Profile Photo?"))  
           {  
             var action = "deletePhoto";
             var id = $('#userid').val();
             var Photo = $('#OldPhoto').val();
             $.ajax({
    url:"ajax_files/ajax_customers.php",
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
   $(document).on("change", "#CountryId", function(event){
  var val = this.value;
   var action = "getState";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#StateId').html(data);
    }
    });

 });

         $(document).on("change", "#StateId", function(event){
  var val = this.value;
   var action = "getCity";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#CityId').html(data);
    }
    });

 });
 
  $(document).on("change", "#CityId", function(event){
  var val = this.value;
   var action = "getZone";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#ZoneId').html(data);
    }
    });

 });
 
 $(document).on("change", "#ZoneId", function(event){
  var val = this.value;
   var action = "getWard";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#WardId').html(data);
    }
    });

 });
 
 $(document).on("change", "#WardId", function(event){
  var val = this.value;
   var action = "getArea";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#AreaId').html(data);
    }
    });

 });
});
</script>
</body>
</html>