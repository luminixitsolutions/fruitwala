<?php session_start();
require_once 'config.php';
$id = $_GET['id'];
$PageName = "Referral List";
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
    <link href="vendor/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="vendor/daterangepicker-master/daterangepicker.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 

        <!-- page content start -->

       <style>
.delete-icon-btn {
    width: 28px;
    height: 28px;
    background: #ff4d4d; /* Red background */
    color: white;
    border-radius: 50%;
    font-size: 14px;
    transition: 0.3s ease;
    box-shadow: 0 2px 5px rgba(255,0,0,0.4);
}
.delete-icon-btn:hover {
    background: #e60000; /* Darker red on hover */
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(255,0,0,0.6);
    text-decoration: none;
}
</style>

<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_referral_details WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      //alert("Deleted Successfully!");
      window.location.href="view-referal-list.php";
    </script>
<?php } ?>

            <div class="container mb-4" style="padding-right: 1px;
padding-left: 1px;">

                   <div style="float:right;">
                                                                   
                 <a href="add-referal.php" class="btn btn-sm btn-default rounded">Add New</a>
             
                                
                                                                </div><br><br>
                <div class="card">
                   
                
           
                    <div class="card-body px-0 pt-0">
                        <ul class="list-group list-group-flush" id="show_prod">
<?php 
$sql = "SELECT * FROM tbl_referral_details WHERE UserId='$user_id' ORDER BY CreatedDate DESC";
$rncnt = getRow($sql);

if ($rncnt > 0) {
    $rows = getList($sql);
    foreach ($rows as $result) {
?>
   <a href="javascript:void(0)" class="d-block text-decoration-none text-dark position-relative">
    <li class="list-group-item position-relative">
        
        <!-- Delete icon with highlight -->
        <a href="view-referal-list.php?id=<?php echo $result['id']; ?>&action=delete" 
           onclick="return confirm('Are you sure you want to delete this referral?');" 
           class="delete-icon-btn position-absolute d-flex align-items-center justify-content-center" 
           style="top: 8px; right: 8px; z-index: 5;">
            <i class="fa fa-trash"></i>
        </a>

        <div class="row align-items-center">
            <!-- Avatar -->
            <div class="col-auto pr-0">
                <div class="avatar avatar-40 rounded-circle overflow-hidden border bg-light text-center d-flex align-items-center justify-content-center">
                    <i class="fa fa-user text-secondary"></i>
                </div>
            </div>

            <!-- Candidate Name & Phone -->
            <div class="col pl-2">
                <h6 class="mb-0 font-weight-bold text-dark">
                    <?php echo ucfirst(strtolower($result['CandName'])); ?>
                </h6>
                <small class="text-muted">
                    📞 <?php echo $result['CandPhone']; ?>
                </small>
            </div>
        </div>

        <!-- Extra Info -->
        <div class="mt-2 ml-2 pl-2 border-left">
            <p class="mb-1"><strong>Email:</strong> <?php echo $result['CandEmail'] ?: '<span class="text-muted">—</span>'; ?></p>
            <p class="mb-1"><strong>Referral Date:</strong> <?php echo date("d/m/Y", strtotime($result['ReqDate'])); ?></p>
            <p class="mb-1"><strong>Notes:</strong> <?php echo nl2br(htmlspecialchars($result['Notes'])); ?></p>
            <p class="mb-0 text-muted"><small>Created On: <?php echo date("d/m/Y H:i", strtotime($result['CreatedDate'])); ?></small></p>
        </div>
    </li>
</a>

<?php 
    } 
} else { 
?>
    <li class="list-group-item text-danger text-center">
        Oops! No Record Found..
    </li>
<?php 
} 
?>

</ul>

                    </div>
                </div>
             
                
            
        </div>
    </main><br><br><br><br>
 <?php include_once 'footer.php';?>

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

  <script src="vendor/daterangepicker-master/moment.min.js"></script>
    <script src="vendor/daterangepicker-master/daterangepicker.js"></script>
    <!-- page level custom script -->
    <script src="js/app.js"></script>

    </body>

</html>
