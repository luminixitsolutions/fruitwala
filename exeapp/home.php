<?php session_start();
$sessionid = session_id();
require_once 'config.php';
//require_once 'auth.php';
$PageName = "Home";


$user_id = $_SESSION['User']['id'];
$uid = $_REQUEST['uid']; 
if($_REQUEST['uid'] == ''){
$sql11 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}   
else{
$sql11 = "SELECT * FROM tbl_users WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}

?>
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
    <link rel="manifest" href="manifest.json" />

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js">
</script>

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
    <link rel="stylesheet" href="dist/css/styles.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
   
</head>

<style>

        /* Popup Overlay */
        #popupOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        /* Popup Container */
        #popupContainer {
            position: relative;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            text-align: center;
            width: 400px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.6);
            animation: popupIn 0.5s ease-in-out;
        }

        @keyframes popupIn {
            from { transform: scale(0.7); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        /* Popup Image */
        #popupContainer img {
            width: 100%;
            display: block;
        }

        /* Loading Text & Countdown */
        #loadingText {
            font-size: 20px;
            font-weight: bold;
            color: #444;
            margin: 15px 0;
        }

        /* Close Button */
        #closePopup {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ff3b3b;
            border: none;
            color: white;
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 6px;
            cursor: not-allowed;
            opacity: 0.6;
        }

        #closePopup.enabled {
            cursor: pointer;
            opacity: 1;
            background: #28a745;
        }
    </style>
     <style>
    body { font-family: 'Roboto', sans-serif; background: #f8f9fa; }
    .hero-banner {
      background: linear-gradient(135deg,#667eea,#764ba2);
      color: #fff; border-radius: 15px; padding: 25px; margin: 20px 0; text-align: center;
    }
    .stat-box {
      background: #fff; border-radius: 12px; padding: 15px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: transform .2s;
    }
    .stat-box:hover { transform: translateY(-5px); }
    .dashboard-card .icon-circle {
      width: 70px; height: 70px; border-radius: 50%; display:flex; justify-content:center; align-items:center;
      margin:auto; margin-bottom:10px;
    }
    .bg-gradient-primary { background: linear-gradient(45deg,#4facfe,#00f2fe); }
    .bg-gradient-success { background: linear-gradient(45deg,#43e97b,#38f9d7); }
    .bg-gradient-warning { background: linear-gradient(45deg,#f7971e,#ffd200); }
    .bg-gradient-danger { background: linear-gradient(45deg,#f857a6,#ff5858); }
    .hover-effect { transition: all .2s; }
    .hover-effect:hover { transform: translateY(-8px); box-shadow:0 6px 18px rgba(0,0,0,0.15); }
    .section-title { font-weight: 600; margin: 25px 0 15px; color: #444; }
  </style>
    
<div class="container-fluid h-100 loader-display">
        <div class="row h-100">
            <div class="align-self-center col">
                <div class="logo-loading">
                    <div class="icon  ">
                        <img src="logo.png" alt="">
                    </div><br>
                    <div class="loader-ellipsis">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="shop" style="line-height: 15px;">

    
    <?php include_once 'sidebar.php'; ?>

    <!-- Begin page content -->
   <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
      <?php include_once 'top_header.php'; ?>


 
        <!-- page content start -->
<!-- page content start -->
   

        <div class="main-container  text-center" style="background-color:#fff;">

          
            
          
           
          <div class="container mb-4" >
    <div class="text-center">
        
         <!-- Hero Section -->
  <div class="hero-banner" data-aos="fade-down">
    <h4>Welcome, <?php echo $_SESSION['User']['Fname']." ".$_SESSION['User']['Lname']; ?>!</h4>
    <p>Have a productive day at <?php echo $Proj_Title; ?></p>
  </div>
  
        <div class="row justify-content-equal no-gutters mt-2">
            <?php if($MainBrEmp == 2){?>
             <div class="col-4 col-md-2 mb-3">
                <a href="today-orders.php">
                    <div class="avatar avatar-70 mb-1 rounded">
                        <div class="background">
                            <img src="icons/shiporder.png" alt="">
                        </div>
                    </div>
                    <p class="text-secondary">
                        <small>Today Orders</small>
                    </p>
                </a>
            </div>
            <?php } ?>
            
            <div class="col-4 col-md-2 mb-3">
                <a href="attendance.php">
                    <div class="avatar avatar-70 mb-1 rounded">
                        <div class="background">
                            <img src="icons/dayattendance.jpg" alt="">
                        </div>
                    </div>
                    <p class="text-secondary">
                        <small>Mark Attendance</small>
                    </p>
                </a>
            </div>

           
       

            <div class="col-4 col-md-2 mb-3">
                <a href="my-attendance.php">
                    <div class="avatar avatar-70 mb-1 rounded">
                        <div class="background">
                            <img src="icons/attendance.jpg" alt="">
                        </div>
                    </div>
                    <p class="text-secondary">
                        <small>My Attendance</small>
                    </p>
                </a>
            </div>

            

           

            <div class="col-4 col-md-2 mb-3">
                <a href="add-advance-payment-request.php">
                    <div class="avatar avatar-70 mb-1 rounded">
                        <div class="background">
                            <img src="icons/advance.jpg" alt="">
                        </div>
                    </div>
                    <p class="text-secondary">
                        <small>Advance Request</small>
                    </p>
                </a>
            </div>

          <!--  <div class="col-4 col-md-2 mb-3">
                <a href="view-resign-request.php">
                    <div class="avatar avatar-70 mb-1 rounded">
                        <div class="background">
                            <img src="icons/resign.jpg" alt="">
                        </div>
                    </div>
                    <p class="text-secondary">
                        <small>Resign Request</small>
                    </p>
                </a>
            </div>
-->
            <div class="col-4 col-md-2 mb-3">
                <a href="view-leave-request.php">
                    <div class="avatar avatar-70 mb-1 rounded">
                        <div class="background">
                            <img src="icons/leave_request.jpg" alt="">
                        </div>
                    </div>
                    <p class="text-secondary">
                        <small>Leave Request</small>
                    </p>
                </a>
            </div>
            
          <!--  <div class="col-4 col-md-2 mb-3">
                <a href="view-week-off.php">
                    <div class="avatar avatar-70 mb-1 rounded">
                        <div class="background">
                            <img src="icons/leave_request.jpg" alt="">
                        </div>
                    </div>
                    <p class="text-secondary">
                        <small>Week Off</small>
                    </p>
                </a>
            </div>
            
            
-->
          
          
            
          <!--  <div class="col-4 col-md-2 mb-3">
                <a href="add-ticket.php">
                    <div class="avatar avatar-70 mb-1 rounded">
                        <div class="background">
                            <img src="icons/create_task.jpg" alt="">
                        </div>
                    </div>
                    <p class="text-secondary">
                        <small>Create Tickets</small>
                    </p>
                </a>
            </div>
            
            <div class="col-4 col-md-2 mb-3">
                <a href="view-tickets.php">
                    <div class="avatar avatar-70 mb-1 rounded">
                        <div class="background">
                            <img src="icons/create_task.jpg" alt="">
                        </div>
                    </div>
                    <p class="text-secondary">
                        <small>View Tickets</small>
                    </p>
                </a>
            </div>-->

            <div class="col-4 col-md-2 mb-3">
                <a href="create-task.php">
                    <div class="avatar avatar-70 mb-1 rounded">
                        <div class="background">
                            <img src="icons/create_task.jpg" alt="">
                        </div>
                    </div>
                    <p class="text-secondary">
                        <small>Create Task</small>
                    </p>
                </a>
            </div>

            <div class="col-4 col-md-2 mb-3">
                <a href="view-task.php">
                    <div class="avatar avatar-70 mb-1 rounded">
                        <div class="background">
                            <img src="icons/today_task.jpg" alt="">
                        </div>
                    </div>
                    <p class="text-secondary">
                        <small>Today Task</small>
                    </p>
                </a>
            </div>
           
         
            
             
            <div class="col-4 col-md-2 mb-3">
                <a href="javascript:void(0)" onclick="logout()">
                    <div class="avatar avatar-70 mb-1 rounded">
                        <div class="background">
                            <img src="icons/logout.jpg" alt="">
                        </div>
                    </div>
                    <p class="text-secondary">
                        <small>Logout</small>
                    </p>
                </a>
            </div>

        </div>
    </div>
</div>

            
            <br><br>
              
 
    </main>

    <!-- footer-->
  <?php include_once 'footer.php'; ?>


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

    <!-- PWA app service registration and works -->
    <script src="js/pwa-services.js"></script>

    <!-- page level custom script -->
    <script src="js/app.js"></script>

   
</body>

</html>
