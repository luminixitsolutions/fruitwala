<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Account";
$Page = "Add-Receive-Amount";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> 
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <?php include_once 'header_script.php'; ?>
      <link rel="stylesheet" href="example/css/slim.min.css">
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
$recid = $_GET['recid'];
$sql7 = "SELECT * FROM tbl_general_ledger WHERE id='$id'";
$row7 = getRecord($sql7);
if($_GET['id'] == ''){
    $PayDate = date('Y-m-d');
}
else{
    $PayDate = $row7['PaymentDate'];
    $CustId = $row7["CustId"];
    $CustName = $row7['AccountName'];
    $CellNo = $row7['CellNo'];
    $Address = $row7['Address'];
    $RecId = $row7["RecId"];
}
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Create
                            <?php } ?> Payment Receive</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="alert_message"></div>
                                <div class="row">
                                    <div class="col-lg-7">
                                <form id="validation-form" method="post" autocomplete="off">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="SavePaymentReceive" id="action">
                                    <div class="form-row">

                                        <div class="form-group col-md-12">
<label class="form-label"> Customer<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="CustId" id="CustId" required>
<option selected="" value="">Select Customer</option>
 <?php 
  $sql12 = "SELECT id,Fname,Lname FROM tbl_users WHERE Status='1' AND Roll=5";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7['UserId'] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." ".$result['Lname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

                                        
                                        <div class="form-group col-md-12">
   <label class="form-label">Customer Name </label>
     <input type="text" name="CustName" id="CustName" class="form-control"
                                                placeholder="" value="<?php echo $CustName; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>
 

 <input type="hidden" name="InvNo" id="InvNo" value="<?php echo $row7['InvNo'];?>">

<?php if($_GET['id']==''){?>
<div class="form-group col-md-4">
   <label class="form-label">Total Amount <span class="text-danger">*</span></label>
     <input type="text" name="TotalInvAmt" id="TotalInvAmt" class="form-control"
                                                placeholder="" value=""
                                                autocomplete="off" readonly>
    <div class="clearfix"></div>
 </div>


 <div class="form-group col-md-4">
   <label class="form-label">Total Paid Amount <span class="text-danger">*</span></label>
     <input type="text" name="PaidAmount" id="PaidAmount" class="form-control"
                                                placeholder="" value=""
                                                autocomplete="off" readonly>
    <div class="clearfix"></div>
 </div>

 
 <div class="form-group col-md-4">
   <label class="form-label">Balance Amount <span class="text-danger">*</span></label>
     <input type="text" name="BalanceAmt" id="BalanceAmt" class="form-control"
                                                placeholder="" value=""
                                                autocomplete="off" readonly>
    <div class="clearfix"></div>
 </div> 
<?php } ?>
 <div class="form-group col-md-4">
   <label class="form-label">Paid Amount <span class="text-danger">*</span></label>
     <input type="text" name="Amount" id="Amount" class="form-control"
                                                placeholder="" value="<?php echo $row7["Amount"]; ?>"
                                                autocomplete="off" required oninput="getTotal(document.getElementById('Amount').value,document.getElementById('Interest').value,document.getElementById('MonthPeriod').value)">
    <div class="clearfix"></div>
 </div>



 <div class="form-group col-md-4">
   <label class="form-label">Payment Date <span class="text-danger">*</span></label>
     <input type="date" name="PayDate" id="PayDate" class="form-control"
                                                placeholder="" value="<?php echo $PayDate; ?>"
                                                autocomplete="off" required onchange="getCollectionsMonth(document.getElementById('RecId').value)">
    <div class="clearfix"></div>
 </div>


 <div class="form-group col-md-4">
<label class="form-label">Payment Type <span class="text-danger">*</span></label>
  <select class="form-control" id="PayType" name="PayType" required="" onchange="getPayType(this.value);">
<option selected="" disabled="" value="">Select Payment Type</option>
<option <?php if($row7['PayMode'] == 'Cash') {?> selected <?php } ?> value="Cash">Cash</option>
<option <?php if($row7['PayMode'] == 'Cheque') {?> selected <?php } ?> value="Cheque">Cheque/Bank Transfer</option>
<option <?php if($row7['PayMode'] == 'UPI') {?> selected <?php } ?> value="UPI">UPI</option>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4 chequeoption" style="display: none;">
<label class="form-label">Cheque No <span class="text-danger">*</span></label>
<input type="text" name="ChequeNo" class="form-control" id="ChequeNo" placeholder="Cheque No" value="<?php echo $row7['ChequeNo']; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4 chequeoption" style="display: none;">
<label class="form-label">Cheque Date <span class="text-danger">*</span></label>
<input type="date" name="ChqDate" class="form-control" id="ChqDate" placeholder="Cheque Date" value="<?php echo $row7['ChqDate']; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4 chequeoption" style="display: none;">
<label class="form-label">Bank Name <span class="text-danger">*</span></label>
<input type="text" name="BankName" class="form-control" id="BankName" placeholder="Bank Name" value="<?php echo $row7['BankName']; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12 upioption" style="display: none;">
<label class="form-label">UPI No/ Transaction Id <span class="text-danger">*</span></label>
<input type="text" name="UpiNo" class="form-control" id="UpiNo" placeholder="UPI No/ Transaction Id" value="<?php echo $row7['UpiNo']; ?>">
<div class="clearfix"></div>
</div>
<div class="form-group col-md-12">
   <label class="form-label">Narration</label>
     <input type="text" name="Narration" id="Narration" class="form-control" value="<?php echo $row7['Narration']; ?>">
    <div class="clearfix"></div>
 </div>  


                                    </div> 
                                    <!-- <button id="growl-default" class="btn btn-default">Default</button> -->
                                    <button type="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
                                </form>
                            </div>

                            <div class="col-lg-5" id="custresult">

                            </div>
                        </div>
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
    
     function getPayType(val){
    if(val == 'Cheque'){
      $('.chequeoption').show();
      $('.upioption').hide();
    }
    else if(val == 'UPI'){
      $('.chequeoption').hide();
      $('.upioption').show();
    }
    else{
      $('.chequeoption').hide();
      $('.upioption').hide();
    }
  }
  
          function addVendor(){
        setTimeout(function() {
        window.open(
            'add-customer2.php', 'stickerPrint',
            'toolbar=1, scrollbars=1, location=1,statusbar=0, menubar=1, resizable=1, width=800, height=600,left=350,top=40,right=200'
        );
    }, 1);
    }

    function getTotal(amount,interest,month){
         var IntrestAmount = Number(amount)*(Number(month)*(Number(interest)/100));
         var TotAmount = Number(IntrestAmount) + Number(amount)
         $('#TotAmount').val(parseFloat(TotAmount).toFixed(2))
    }
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

    function error_toast() {
        var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
        $.growl.error({
            title: 'Error',
            message: 'Phone No Already Exists',
            location: isRtl ? 'tl' : 'tr'
        });
    }

    function success_toast() {
        var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
        $.growl.notice({
            title: 'Success',
            message: 'Saved Successfully...',
            location: isRtl ? 'tl' : 'tr'
        });
    }

 
    function getCollections(recid){
         var action = "getCollections";
            $.ajax({
                url: "ajax_files/ajax_general_ledger.php",
                method: "POST",
                data: {
                    action: action,
                    id: recid
                }, 
                success: function(data) {
                    $('#custresult').html(data);
                    
                    
                }
            });
    }

    function getRecordDetails(uid){
        var action = "getRecordDetails";
            $.ajax({
                url: "ajax_files/ajax_general_ledger.php",
                method: "POST",
                data: {
                    action: action,
                    uid: uid
                }, 
                success: function(data) {
                    console.log(data);
                    var res = JSON.parse(data);
                    $('#TotalInvAmt').val(res.TotAmt);
                    $('#PaidAmount').val(res.PaidAmt);
                    $('#BalanceAmt').val(res.BalAmt);
                    getCollections(uid);
                }
            });
    }
    $(document).ready(function() {
        //$(document).on("click", ".btn-finish", function(event){
        var CustId = $('#CustId').val();
        getCollections(CustId);
        var PayType = $('#PayType').val();
        getPayType(PayType);
        $('#validation-form').on('submit', function(e) {
            e.preventDefault();
            if ($('#validation-form').valid()) {

                $.ajax({
                    url: "ajax_files/ajax_general_ledger.php",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#submit').attr('disabled', 'disabled');
                        $('#submit').text('Please Wait...');
                    },
                    success: function(data) {
//console.log(data);exit();
                        if (data == 0) {
                            error_toast();

                        } else {
                            success_toast();
                            setTimeout(function() {
                                window.location.href = 'receive-amount.php';
                            }, 2000);
                        }
                        $('#submit').attr('disabled', false);
                        $('#submit').text('Save');
                    }
                })



            } else {
                //$('#Fname').focus();
                return false;
            }
        });

  

  $(document).on("change", "#CustId", function(event) {
            var val = this.value;
            getRecordDetails(val);
            var action = "getUserDetails";
            $.ajax({
                url: "ajax_files/ajax_customers.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                dataType:"json",  
                success: function(data) {
                    $('#Address').val(data.Address);
                    $('#CustName').val((data.Fname || '') + ' ' + (data.Lname || ''));
                    $('#CellNo').val(data.Phone);
                    
                }
            });

        });

    $(document).on("change", "#InvId", function(event) {
            var val = this.value;
            var action = "getRecordDetails";
            $.ajax({
                url: "ajax_files/ajax_general_ledger.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    //alert(data);
                    var res = JSON.parse(data);
                    $('#InvNo').val(res.InvNo); 
                    $('#PaidAmount').val(res.SumCredit);
                    $('#TotalInvAmt').val(res.SumDebit);
                    $('#BalanceAmt').val(res.BalAmt);
                    getCollections(val);
                   
                }
            });

        });
       
       
        
    });
    </script>
    
      
      

</body>

</html>