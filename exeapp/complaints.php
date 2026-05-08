<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Complaints";
$UserId = $_SESSION['User']['id'];
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
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include 'back-header.php'; ?>
       
        <div class="main-container">
            <div class="container">
                <div class="card mb-4">
                  
                   
                    <div class="card-header">
                        <h6 class="subtitle mb-0">
                             <div class="avatar avatar-40  rounded-circle text-default" style="background:linear-gradient(90deg, rgba(232,37,37,1) 36%, rgba(245,182,51,1) 100%);">
                                    <i class="material-icons vm md-36 text-template" style="color: #fff; font-size:22px;">live_help</i>
                                </div>
                            
                            Send Your Complaints
                        </h6>
                    </div>

                       <form id="validation-form" method="post" autocomplete="off">
                    <div class="card-body">
                         <div id="alert_message"></div>
                         <div class="form-group float-label">
                            <input type="text" name="Name" id="Name" class="form-control" autofocus required value="<?php echo $row110['Fname']." ".$row110['Lname']; ?>">
                            <label class="form-control-label">Your Name</label>
                        </div>
                        <div class="form-group float-label active">
                            <input type="text" name="Phone" id="Phone" class="form-control" required value="<?php echo $row110['Phone'];?>">
                            <label class="form-control-label">Phone No</label>
                        </div>
                        <div class="form-group float-label active">
                            <input type="text" name="EmailId" id="EmailId" class="form-control" required value="<?php echo $row110['EmailId'];?>">
                            <label class="form-control-label">Email Id</label>
                        </div>
                        <div class="form-group float-label">
                            <textarea name="Message" id="Message" class="form-control" required></textarea>
                            <label class="form-control-label">Your Message</label>
                        </div>
                        
                    </div>
                    
                      <input type="hidden" name="id" value="<?php echo $_SESSION['User']['id']; ?>" id="UserId">  
                      <input type="hidden" name="action" value="SaveHelpSupport" id="action">  
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" id="submit">Submit</button>
                    </div>
                </form>
                </div>
                
            </div>

            <div class="container mb-4">
                
                <div class="row">
                    <?php  
                        $sql = "SELECT * FROM tbl_helps_enquiry WHERE UserId='$user_id'";
                        $row = getList($sql);
                        foreach($row as $result){

                            if($result['Status'] == 4){
                                            $OrderStatus = "<span style='color:green;'>Complete</span>";
                                        }
                                        else if($result['Status'] == 1){
                                            $OrderStatus = "<span style='color:orange;'>Pending</span>";
                                        }
                                        else if($result['Status'] == 3){
                                            $OrderStatus = "<span style='color:red;'>Reject</span>";
                                        }
                                        else if($result['Status'] == 2){
                                            $OrderStatus = "<span style='color:orange;'>In Process</span>";
                                        }
                                        
                                        else{
                                            $OrderStatus = "";
                                        }
                    ?>
                    <div class="col-12 col-md-6">
                        <div class="card border-0 mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                  
                                    <div class="col-6 align-self-center">
                                        <a href="token-details.php?id=<?php echo $result['id'];?>"><h6 class="mb-1"><?php echo $result['TokenNo'];?></h6></a>
                                       <!--  <a href="my-booking-details.php?oid=<?php echo $row['id'];?>" style="padding-top: 10px;"><button class="btn btn-sm btn-default rounded" style="width: 105px;">View Details</button></a> -->  
                                    </div>
                                    <div class="col-auto align-self-center border-left">
                                        <h6 class="mb-1"><?php echo $OrderStatus; ?></h6>
                                        <p class="small text-secondary">Due: <?php echo date("d-m-Y", strtotime(str_replace('-', '/',$result['CreatedDate'])));?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
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
    <script type="text/javascript">
    $(document).ready(function() {
            $('#validation-form').on('submit', function(e){
            e.preventDefault();    
       $.ajax({  
                url :"ajax_files/ajax_customers.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                success:function(data){ 
                    
                        $('#alert_message').removeClass('alert alert-danger');
                        $('#alert_message').fadeIn().addClass('alert alert-success').html("Your Enquiry Sent Successfully...");
        setTimeout(function(){  
            $('#alert_message').fadeOut("Slow"); 
             window.location.href = 'help-and-support.php';
        }, 2000);
                     
                }  
           })  

     });

  });

</script>
</body>

</html>
