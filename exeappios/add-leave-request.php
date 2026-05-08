<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Leave/Week Off Request";
$UserId = $_SESSION['User']['id']; ?>
<!doctype html>
<html lang="en" class="h-100">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $Proj_Title; ?></title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
    <link href="css/toastr.min.css" rel="stylesheet">
      <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        
      <?php 
        
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_leave_request WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();


$currentyear = date('Y')."-06-01";
//echo $JoinDate;
if($JoinDate >= $currentyear){
    $startdate = $JoinDate;
}
else{
    $startdate = $currentyear;
}
//echo $startdate;
$sql = "WITH RECURSIVE all_dates AS ( SELECT DATE('$startdate') AS dt UNION ALL SELECT dt + INTERVAL 1 DAY FROM all_dates WHERE dt + INTERVAL 1 DAY <= CURDATE() ) 
SELECT dt AS MissingDate FROM all_dates WHERE dt NOT IN ( SELECT DISTINCT CreatedDate FROM tbl_attendance WHERE Type = 2 AND UserId='$UserId' )";
$rncnt = getRow($sql);

$totalcurrleave = ($MonthlyWeekOff*date('m'))-25;
$sql88 = "SELECT SUM(TotDays) AS TotDays FROM tbl_leave_request WHERE UserId = '$UserId' AND HrStatus = 1 AND ReqDate>='2025-06-01'";
$row88 = getRecord($sql88);
$pendingleave = $totalcurrleave - $row88['TotDays'] - $rncnt;
/*if($pendingleave2 > 0){
    $pendingleave = $pendingleave2;
}
else{
    $pendingleave = 0;
}*/

?>


        <div class="main-container">
            <div class="container">
               
                <div class="card">
                  <form id="validation-form" method="post" enctype="multipart/form-data">
   <div class="card-body">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="saveRequest" id="action">
                                 
                                        <div class="form-group col-md-2">
                                            <label class="form-label">Request Date <span class="text-danger">*</span> </label>
                                            <input type="date" name="ReqDate" id="ReqDate" class="form-control"
                                                placeholder="" value="<?php echo date('Y-m-d'); ?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-2">
                                            <label class="form-label">Available Leave <span class="text-danger">*</span> </label>
                                            <input type="number" name="AvailLeave" id="AvailLeave" class="form-control"
                                                placeholder="" value="<?php echo $pendingleave;?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                       
                            
                                        <div class="form-group col-md-2">
                                            <label class="form-label">From Date <span class="text-danger">*</span> </label>
                                            <input type="date" name="FromDate" id="FromDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["CreatedDate"]; ?>"
                                                autocomplete="off" required onchange="daysBetween(document.getElementById('FromDate').value,document.getElementById('ToDate').value)">
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        
                                       
                                   
                                                
                                       <div class="form-group col-md-2">
                                            <label class="form-label">To Date <span class="text-danger">*</span> </label>
                                            <input type="date" name="ToDate" id="ToDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["CreatedDate"]; ?>"
                                                autocomplete="off" required onchange="daysBetween(document.getElementById('FromDate').value,document.getElementById('ToDate').value)">
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                         <div class="form-group col-md-2">
                                            <label class="form-label">Leave Days <span class="text-danger">*</span> </label>
                                            <input type="number" name="TotDays" id="TotDays" class="form-control"
                                                placeholder="" value="<?php echo $row7["TotDays"]; ?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                             <label class="form-label">Leave Reason <span class="text-danger">*</span></label>
                                             <textarea class="form-control" name="Narration" id="Narration" value="" required></textarea>
                                            <div class="clearfix"></div>
                                        </div>
                                            
                        


                   

                       
                        
                        
                                    
                                    <input type="hidden" id="Status" name="Status" value="0">
                                  

<div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" name="submit" id="submit">Submit</button>
                        <span style="color:red;display:none;" id="errormsg">You Can't Apply Leave Request.</span>
                    </div>
  </div>
                                   
                                </form>
                </div>
            </div>
        </div>
    </main>
<br><br><br><br>
    <!-- footer-->
    
<?php include 'footer.php';?>

    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="js/app.js"></script>
    <script>
    function checkLeaves(){
        var pendingleave = $('#AvailLeave').val();
        var TotDays = $('#TotDays').val();
        if(Number(TotDays) >= Number(pendingleave)){
            // $('#errormsg').show();
            // $('#submit').attr("disabled",true);
        }
        else{
        //   $('#errormsg').hide();
        //     $('#submit').attr("disabled",false); 
        }
    }
    function daysBetween(date1, date2) {
    const oneDay = 1000 * 60 * 60 * 24;
    const diffDays = Math.floor((new Date(date2) - new Date(date1)) / oneDay) + 1;
    $('#TotDays').val(diffDays);
    checkLeaves();
}

   
      $(document).ready(function() {
          
     $('#validation-form').on('submit', function(e){
      e.preventDefault();    
     
     $.ajax({  
                url :"ajax_files/ajax_leave_request.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                 beforeSend:function(){
     $('#submit').attr('disabled','disabled');
     $('#submit').text('Please Wait...');
    },
                success:function(data){ 
                    //alert(data);exit();
                // console.log(data);exit();
                     
                    if(data == 1){
                    setTimeout(function () { 
swal({
  title: "Thank you",
  text: "Your leave request successfully submitted.",
  type: "success",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="view-leave-request.php";
  }
}); });
                        
                     }
                    
                    
                     
                      $('#submit').attr('disabled',false);
                    $('#submit').text('Update');
                }  
           })
     });

            
        });
    </script>
   
</body>

</html>
