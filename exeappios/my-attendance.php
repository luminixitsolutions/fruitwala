<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Attendance";
$UserId = $_SESSION['User']['id'];
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname']." ".$row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId']; 
$Latitude = $row11['latitude']; 
$Longitude = $row11['longitude']; 

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
      <link rel="stylesheet" href="example/css/slim.min.css">
</head>

<style>
    .custom-control {
  line-height: 24px;
  padding-top: 5px;
}
</style>
<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        
<?php 
$id = $_GET['id'];
$CurrDate = date('Y-m-d');

$sql8 = "SELECT ta.*,tu.Fname FROM tbl_attendance ta INNER JOIN tbl_users tu ON ta.UserId=tu.id WHERE ta.UserId='$UserId' AND Type=1";
$rncnt8 = getRow($sql8);
$row8 = getRecord($sql8);

$sql9 = "SELECT ta.*,tu.Fname FROM tbl_attendance ta INNER JOIN tbl_users tu ON ta.UserId=tu.id WHERE ta.UserId='$UserId' AND Type=2";
$rncnt9 = getRow($sql9);
$row9 = getRecord($sql9);
     
$sql7 = "SELECT ta.*,tu.Fname FROM tbl_attendance ta INNER JOIN tbl_users tu ON ta.UserId=tu.id WHERE ta.UserId='$UserId'  GROUP BY ta.CreatedDate ORDER BY ta.CreatedDate DESC";
$rncnt7 = getRow($sql7);
$row7 = getRecord($sql7);

function distance($lat1, $lon1, $lat2, $lon2, $unit) {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}

$tot_dist = round(distance($Latitude, $Longitude, $FrLattitude, $FrLongitude, "K"),1); 
?>

<style>
    #map {height: 100%;}
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }
    #floating-panel {
      position: absolute;
      top: 10px;
      right: 1%;
      z-index: 5;
      background-color: #fff;
      border: 1px solid #999;
      text-align: center;
    }
    </style>
        <div class="main-container">
            <div class="container">
    

<form id="validation-form" method="post" autocomplete="off">
               

              
               <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">My Attendances</h6>
                    </div>
                 
                    <div class="card-body">



<div class="row text-center mt-3">

    <div class="col-4 col-md-3" style="padding-right: 5px;padding-left: 5px;">
                       
                       

                    </div>
                    <div class="col-4 col-md-3">
                       
                        Start Time
                        
                    </div>
                    <div class="col-4 col-md-3">
                      
                      End Time
                       
                    </div>

   <?php 
            $i=1;
            $row77 = getList($sql7);
            foreach($row77 as $result){

                    if($result['Type'] == 1){
                        $Name = "Start";
                    }
                    else{
                        $Name = "End";
                    }

                     $sql33 = "SELECT * FROM tbl_attendance WHERE CreatedDate='".$result['CreatedDate']."' AND UserId='$UserId' AND Type=1";
                    $rncnt33 = getRow($sql33);
                    $row33 = getRecord($sql33);

                    $sql34 = "SELECT * FROM tbl_attendance WHERE CreatedDate='".$result['CreatedDate']."' AND UserId='$UserId' AND Type=2";
                    $rncnt34 = getRow($sql34);
                    $row34 = getRecord($sql34);

                    if($rncnt33 > 0){
                        $bgcolor = "background-color: #acf3ac;";
                        $st_time = date("h:i a", strtotime(str_replace('-', '/',$row33['CreatedTime'])));
                    }
                    else{
                        $bgcolor = "background-color: #f55d5d;";
                        $st_time = "";
                    }

                    if($rncnt34 > 0){
                        $bgcolor2 = "background-color: #acf3ac;";
                        $ed_time = date("h:i a", strtotime(str_replace('-', '/',$row34['CreatedTime'])));
                    }
                    else{
                        $bgcolor2 = "background-color: #f55d5d;";
                        $ed_time = "";
                    }

            ?>
                        <div class="col-4 col-md-3" style="padding-right: 5px;padding-left: 5px;">
                       
                        <div class="card border-0 mb-4">
                            <div class="card-body" style="padding-top: 1px;">
                                
                                <h3 class="mt-3 mb-0 font-weight-normal" style="font-size: 14px;"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$result['CreatedDate']))); ?></h3>
                              
                            </div>
                        </div>

                    </div>
                    <div class="col-4 col-md-3">
                       
                        <div class="card border-0 mb-4" style="<?php echo $bgcolor;?>">
                            <div class="card-body" style="padding-top: 1px;">
                                
                                <h3 class="mt-3 mb-0 font-weight-normal" style="font-size: 14px;"><?php echo $st_time;?></h3>
                              
                            </div>
                        </div>
                        <?php if($row33['Photo']!=''){?>
                        <div class="avatar avatar-80 rounded">
                                            <div class="background">
                                                <img src="../uploads/<?php echo $row33['Photo'];?>" alt="">
                                            </div>
                                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-4 col-md-3">
                      
                        <div class="card border-0 mb-4" style="<?php echo $bgcolor2;?>">
                            <div class="card-body" style="padding-top: 1px;">
                                
                                <h3 class="mt-3 mb-0 font-weight-normal" style="font-size: 14px;"><?php echo $ed_time;?></h3>
                              
                            </div>
                        </div>
                         <?php if($row34['Photo']!=''){?>
                        <div class="avatar avatar-80 rounded">
                                            <div class="background">
                                                <img src="../uploads/<?php echo $row34['Photo'];?>" alt="">
                                            </div>
                                        </div>
                       <?php } ?>
                    </div>
                     <?php $i++;} ?>
                    </div>


                        

                       
                      
                       
                    </div>
                </div>

              <input type="hidden" id="srno" value="<?php echo $i;?>">
<input type="hidden" id="CurrDate" value="<?php echo date('Y-m-d');?>">
<input type="hidden" id="userid" value="<?php echo $UserId;?>">

<input type="hidden" id="SourceLat" name="SourceLat" value="<?php echo $Latitude;?>">
        <input type="hidden" id="SourceLong" name="SourceLong" value="<?php echo $Longitude;?>">
        <input type="hidden" id="SourceAddress" name="SourceAddress" value="">

               
             </form>
        </div>
    </main>

    <!-- footer-->
    


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
