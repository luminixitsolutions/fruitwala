<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Advance Request";
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
   <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: 'Segoe UI', sans-serif;
      background: #f4f4f4;
    }

    body.popup-open {
      overflow: hidden;
    }

    /* Floating Video Button */
    .floating-btn {
      position: fixed;
      bottom: 100px;
      right: 20px;
      background-color: #e2982d;
      color: white;
      border: none;
      padding: 15px 15px;
      border-radius: 50%;
      font-size: 24px;
      cursor: pointer;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
      z-index: 999;
      transition: transform 0.2s ease;
    }

    .floating-btn:hover {
      background-color: #e60000;
      transform: scale(1.1);
    }

    /* Popup Overlay */
    .popup-overlay {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100vw;
      height: 100vh;
      backdrop-filter: blur(8px);
      background: rgba(0, 0, 0, 0.6);
      justify-content: center;
      align-items: center;
      z-index: 9999;
      animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }

    /* Video Popup Box */
    .popup-content {
      position: relative;
      width: 90%;
      max-width: 500px;
      aspect-ratio: 9 / 16;
      background: #000;
      border-radius: 12px;
      overflow: hidden;
     
      animation: fadeIn 0.5s ease;
    }

    .popup-content iframe {
      width: 100%;
      height: 100%;
      border: none;
    }

    /* Close Button */
    .close-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 22px;
      color: #fff;
      background: red;
      border-radius: 50%;
      width: 35px;
      height: 35px;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      z-index: 10000;
      box-shadow: 0 0 10px rgba(255, 255, 255, 0.6);
    }

    .close-btn:hover {
      background: #ff3333;
    }

    /* Open in YouTube button */
    .open-youtube {
      position: absolute;
      bottom: 12px;
      left: 12px;
      background: white;
      color: black;
      border-radius: 6px;
      font-size: 12px;
      padding: 4px 10px;
      z-index: 9999;
      font-weight: bold;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
      text-decoration: none;
    }

    .open-youtube:hover {
      background: #f0f0f0;
    }

    /* Dark Mode */
    @media (prefers-color-scheme: dark) {
      body {
        background: #1e1e1e;
      }

      .popup-content {
        background: rgba(0, 0, 0, 0.85);
      }

      .floating-btn {
        background-color: #bb0000;
      }
    }
  </style>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   <!-- Floating Button -->


<!-- Video Popup -->
<div class="popup-overlay" id="videoPopup">
  <div class="popup-content">
    <span class="close-btn" onclick="closePopup()">&times;</span>
    <a class="open-youtube" href="#" id="youtubeLink" target="_blank"></a>
    <iframe id="youtubeIframe" src="" allowfullscreen allow="autoplay"></iframe>
  </div>
</div>

<script>
  const videoId = "-2jWyGDA4Lc"; // Replace with your Shorts video ID

  function openPopup() {
    document.body.classList.add('popup-open');
    const popup = document.getElementById('videoPopup');
    const iframe = document.getElementById('youtubeIframe');
    const link = document.getElementById('youtubeLink');

    iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
    link.href = `https://www.youtube.com/watch?v=${videoId}`;
    popup.style.display = 'flex';
  }

  function closePopup() {
    document.body.classList.remove('popup-open');
    const popup = document.getElementById('videoPopup');
    const iframe = document.getElementById('youtubeIframe');
    iframe.src = "";
    popup.style.display = 'none';
  }
</script>
    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        
     

<?php 
  if(isset($_POST['submit'])){
$AdvanceDate = $_POST['AdvanceDate'];
    $TotalDays = $_POST['TotalDays'];
    $PresentDay = $_POST['PresentDay'];
    $AbsentDay = $_POST['AbsentDay'];
    $AdvanceSalary = $_POST['AdvanceSalary'];
    $TotalSalary = $_POST['TotalSalary'];
    $Narration = addslashes(trim($_POST['Narration']));
    $EmpCode = addslashes(trim($_POST['EmpCode']));
    $OutletName = addslashes(trim($_POST['OutletName']));
    $BankAccNo = addslashes(trim($_POST['BankAccNo']));
    $IfscCode = addslashes(trim($_POST['IfscCode']));
    $CreatedDate = date('Y-m-d H:i:s');
 $currentMonth = date('n'); 
if($_GET['id']==''){
    $sql = "SELECT * 
    FROM tbl_advance_salary 
    WHERE UserId='$UserId' 
      AND MONTH(AdvanceDate) = '$currentMonth'
      AND NOT (ManagerStatus=2 OR HrStatus=2)";
    $rncnt = getRow($sql);
    if($rncnt > 0){?>
    <script type="text/javascript">
    setTimeout(function () { 
        swal({
            title: "Error",
            text: "You already sent advance request this month so you can’t raise request again.",
            type: "error",
            confirmButtonText: "OK"
        }, function(isConfirm){
            if (isConfirm) {
                // Do nothing, no redirect
            }
        });
    });
</script>
   <?php }
    else{
     $qx = "INSERT INTO tbl_advance_salary SET EmpCode='$EmpCode',OutletName='$OutletName',BankAccNo='$BankAccNo',IfscCode='$IfscCode',UserId='$UserId',AdvanceDate='$AdvanceDate',TotalDays='$TotalDays',PresentDay='$PresentDay',AbsentDay='$AbsentDay',AdvanceSalary='$AdvanceSalary',TotalSalary='$TotalSalary',Narration='$Narration',CreatedDate='$CreatedDate',CreatedBy='$user_id'";
  $conn->query($qx);
  $to = $row55['ExpMail'];
  $allmail = $row55['AllMail'];
  //include("incenquirymail.php");
  //include("sendmailsmtp.php");?>
  <script type="text/javascript">
        setTimeout(function () { 
swal({
  title: "Thank you",
  text: "Your advance request successfully submitted.",
  type: "success",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="view-advance-payment-request.php";
  }
}); });</script>
<?php 
}
}
else{
 
    $query2 = "UPDATE tbl_advance_request SET EmpCode='$EmpCode',OutletName='$OutletName',BankAccNo='$BankAccNo',IfscCode='$IfscCode',Narration = '$Narration',Amount='$Amount',ExpenseDate='$ExpenseDate',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id' WHERE id = '$id'";
  $conn->query($query2);
  ?>
  <script type="text/javascript">
        setTimeout(function () { 
swal({
  title: "Thank you",
  text: "Your advance request successfully updated.",
  type: "success",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="view-advance-payment-request.php";
  }
}); });</script>
<?php 

}
    //header('Location:courses.php'); 

  }


  $CurrDate = date('Y-m-d');
    $month = date('m');
    $year = date('Y');
    $totdays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $sql2 = "SELECT tu.SalaryType,tu.PerDaySalary,tu.CustomerId,tu.AccountNo,tu.IfscCode FROM tbl_users tu WHERE tu.id='$UserId'";
    $row2 = getRecord($sql2);
    $SalaryType = $row2['SalaryType'];
    $CustomerId = $row2['CustomerId'];
    $ShopName = $row2['ShopName'];
    $AccountNo = $row2['AccountNo'];
    $IfscCode = $row2['IfscCode'];
    
    if($SalaryType == 1){
        $PerDaySalary = $row2['PerDaySalary'];
    }
    else{
        $MonthSalary = $row2['PerDaySalary'];
        $PerDaySalary = $MonthSalary/$totdays;
    }

    $StartDate = date('Y-m')."-01";
    $EndDate = date('Y-m-d');
    $start_date = strtotime($StartDate);
    $end_date = strtotime($EndDate);
    $interval = ($end_date - $start_date)/60/60/24;

    $sql = "SELECT * FROM `tbl_attendance` WHERE UserId='$UserId' AND Type=2 AND MONTH(CreatedDate) = MONTH(CURRENT_DATE()) AND YEAR(CreatedDate) = YEAR(CURRENT_DATE())";
    $presentday = getRow($sql);
    $absentday = $interval - $presentday;
    $totalsalary = $PerDaySalary*$presentday;
    $advancesalary = $totalsalary*(40/100);
 ?>
 
        <div class="main-container">
            <div class="container">
               
                <div class="card">
                  <form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
                   <div class="card-body">
                       <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']; ?>">
                       
                     
                   <div class="form-group float-label active">
                            <input type="date" name="AdvanceDate" id="AdvanceDate" class="form-control"
                                                placeholder="" value="<?php echo date('Y-m-d'); ?>"
                                                autocomplete="off" readonly>
                            <label class="form-control-label">Date</label>
                        </div>
                        
                        <div class="form-group float-label active">
                            <input type="text" name="TotalDays" id="TotalDays" class="form-control" placeholder="" value="<?php echo $interval; ?>" autocomplete="off" readonly>
                            <label class="form-control-label">Total Days</label>
                        </div>

                        <div class="form-group float-label active">
                            <input type="text" name="PresentDay" id="PresentDay" class="form-control" placeholder="" value="<?php echo $presentday; ?>" autocomplete="off" readonly>
                            <label class="form-control-label">Present Days</label>
                        </div>

                        <div class="form-group float-label active">
                            <input type="text" name="AbsentDay" id="AbsentDay" class="form-control" placeholder="" value="<?php echo $absentday; ?>" autocomplete="off" readonly>
                            <label class="form-control-label">Absent Days</label>
                        </div>

                        <div class="form-group float-label active">
                            <input type="text" name="TotalSalary" id="TotalSalary" class="form-control" placeholder="" value="<?php echo round($totalsalary); ?>" autocomplete="off" readonly>
                            <label class="form-control-label">Total Earn Salary</label>
                        </div>

                        <div class="form-group float-label active">
                            <input type="text" name="AdvanceSalary" id="AdvanceSalary" class="form-control" placeholder="" value="<?php echo round($advancesalary); ?>" autocomplete="off" readonly>
                            <label class="form-control-label">Advance Salary</label>
                        </div>
                        
                        <div class="form-group float-label active">
                            <input type="text" name="EmpCode" id="EmpCode" class="form-control" placeholder="" value="<?php echo $CustomerId; ?>" autocomplete="off" readonly>
                            <label class="form-control-label">Employee Code</label>
                        </div>
                       
                        <div class="form-group float-label active">
                            <input type="text" name="BankAccNo" id="BankAccNo" class="form-control" placeholder="" value="<?php echo $AccountNo; ?>" autocomplete="off" readonly>
                            <label class="form-control-label">Bank Account No</label>
                        </div>
                        <div class="form-group float-label active">
                            <input type="text" name="IfscCode" id="IfscCode" class="form-control" placeholder="" value="<?php echo $IfscCode; ?>" autocomplete="off" readonly>
                            <label class="form-control-label">IFSC Code</label>
                        </div>


                        
                         

                   

                        <div class="form-group float-label active">
                            <input type="text" class="form-control" id="Narration" name="Narration" value="<?php echo $row7['Narration']; ?>">
                            <label class="form-control-label">Narration</label>                            
                        </div>
                        
                         
                            
                
                    </div>
                        
                          <input type="hidden" name="CreatedBy" value="<?php echo $_SESSION['User']['id']; ?>" id="CreatedBy">  
                      <input type="hidden" name="action" value="NewReg" id="action">  
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" name="submit" id="submit">Submit</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </main>
<br><br><br><br><br>
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
    
   
</body>

</html>
