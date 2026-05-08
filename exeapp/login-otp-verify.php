<?php session_start();
require_once 'config.php';
$PageName = "OTP Verify";
$UserId = $_SESSION['User']['id'];
if(!isset($_SESSION['otp'])){
    echo "<script>window.location.href='dashboard.php';</script>";exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, minimal-ui, viewport-fit=cover">
    <meta name="theme-color" content="#2196f3">
    <meta name="author" content="DexignZone" /> 
    <meta name="keywords" content="" /> 
    <meta name="robots" content="" /> 
    <meta name="description" content="Soziety - Social Network Mobile App Template ( Bootstrap 5 + PWA )"/>
    <meta property="og:title" content="Soziety - Social Network Mobile App Template ( Bootstrap 5 + PWA )" />
    <meta property="og:description" content="Soziety - Social Network Mobile App Template ( Bootstrap 5 + PWA )" />
    <meta property="og:image" content="social-image.png"/>
    <meta name="format-detection" content="telephone=no">
    
    <!-- Favicons Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="login/assets/images/favicon.png" />
    
    <!-- Title -->

    
    <!-- Stylesheets -->
    <link href="login/assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login/assets/vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="login/assets/css/style.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;300;400;600;700;800;900&amp;family=Poppins:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">

</head>   
<body class="gradiant-bg">
<div class="page-wraper">
    

<?php if($_REQUEST['phone'] == '8378970080' || $_REQUEST['phone'] == '6371167472' || $_REQUEST['phone'] == '9623515426' || $_REQUEST['phone'] == '7498754440' ||  $_REQUEST['phone'] == '9689515426' || $_REQUEST['phone'] == '7218503563' || $_REQUEST['phone'] == '9307388110'){
                                $fillotp = $_SESSION['otp'];
                            }
                            else{
                                $fillotp = "";
                            }
                            
                            $fillotp = $_SESSION['otp'];
                            ?>

    <!-- Welcome Start -->
    <div class="content-body">
        <div class="container vh-100">
            <div class="welcome-area">
                <div class="bg-image bg-image-overlay" style="background-image: url(pic222.jpg);height:100vh;"></div>
                <div class="join-area h-50">
                    <div class="started">
                       <h1 class="title" onclick="showOtp()" id="hidetitle">OTP Verification</h1>
                         <!--<h1 class="title" style="display:none;" id="showotp"><?php echo $_SESSION['otp'];?></h1>-->
                        <p>Enter the OTP send to +91 <?php echo $_REQUEST['phone'];?></p>
                    </div>
                    <form>
                         <div class="alert alert-danger" role="alert" id="danger_message" style="display: none;"></div>
                           <div class="alert alert-success" role="alert" id="success_message" style="display: none;"></div>
                        <div class="input-group form-item input-select">
                                
                            <input type="number" name="YourOtp" id="YourOtp" class="form-control" placeholder="Enter OTP" maxlength="4" value="<?php echo $fillotp;?>" required>
                            <!--<input type="number" name="YourOtp" id="YourOtp" class="form-control" placeholder="Enter OTP" maxlength="4" value="<?php echo $_SESSION['otp'];?>" required>-->
                        </div>
                        
                         <input class="form-control" type="hidden" autocomplete="off" name="GetOtp" placeholder="Phone No" id="GetOtp" value="<?php echo $_SESSION['otp'];?>">
                        
                    <input class="form-control" type="hidden" autocomplete="off" name="Phone" placeholder="Phone No" id="Phone" value="<?php echo $_REQUEST['phone'];?>">
                    
                     <input type="hidden" id="uid" value="<?php echo $_GET['uid']; ?>">
                    </form> 
                    
                    <div class="seprate-box mb-3">
                        <a href="javascript:void(0);" class="back-btn">
                            <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.40366 8L9.91646 2.58333L7.83313 0.499999L0.333132 8L7.83313 15.5L9.91644 13.4167L4.40366 8Z" fill="white"/>
                            </svg>
                        </a>
                        <button type="button" id="login" class="btn btn-primary btn-block">Verify</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Welcome End -->
    
</div>
<!--**********************************
    Scripts
***********************************-->
<script src="login/assets/js/jquery.js"></script>
<script src="login/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="login/assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="login/assets/vendor/swiper/swiper-bundle.min.js"></script><!-- Swiper -->
<script src="login/assets/js/dz.carousel.js"></script><!-- Swiper -->
<script src="login/assets/js/settings.js"></script>
<script src="login/assets/js/custom.js"></script>
 <script type="text/javascript">
 function showOtp(){
     $('#showotp').show();
     $('#hidetitle').hide();
 }
         $(document).ready(function(){
           
         $(document).on("click", "#login", function(event){
            var action = "OtpVerify";
            var YourOtp = $('#YourOtp').val();
            var GetOtp = $('#GetOtp').val();
            var Phone = $('#Phone').val();
            var page = $('#page').val();
            var uid = $('#uid').val();
            if(YourOtp.trim() == ''){
                    $('#danger_message').css('display','block').html("Please Enter OTP");
                    $('#YourOtp').focus();
                      setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                }
          /*  else if(Password.trim() == ''){
                     $('#danger_message').css('display','block').html("Please Enter Password");
                    $('#Password').focus();
                      setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                }*/
                else{
                    $.ajax({  
                url :"ajax_files/ajax_customers.php",  
                method:"POST",  
                data:{action:action,Phone:Phone,YourOtp:YourOtp,GetOtp:GetOtp,uid:uid},
                 beforeSend:function(){
     $('#login').attr('disabled','disabled');
     $('#login').text('Please Wait...');
    },
                success:function(data){ 
                    //alert(data);
                    console.log(data);
                     var res = JSON.parse(data);
                    var status = res.status;
                    var roll = res.roll;
                    var Username = res.Username;
                    var KycStatus = res.KycStatus;
                    var uid = res.uid;
                      //alert(Username);  alert(uid);
                     if(status == 0){
                         $('#danger_message').css('display','block').html("Invalid OTP");
                      setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                     }
                     else{
                         Android.loginUser(Username,uid,0);
                         Android.startDriverTracking(Username,uid);
                        $('#success_message').css('display','block').html("OTP Verified!");
                      setTimeout(function(){
                        $('#success_message').fadeOut("Slow"); 
                        window.location.href="home.php";
                    }, 2000); 
                }
                $('#login').attr('disabled',false);
                    $('#login').text('Login');
                }
                });
                }
            }); 
         }); 
</script>   
</body>
</html>