<?php session_start();
require_once 'config.php';
//require_once 'auth.php';
$PageName = "Attendance";
$UserId = $_SESSION['User']['id'];
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname']." ".$row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId']; 
$Latitude = $row11['Lattitude']; 
$Longitude = $row11['Longitude']; 

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
<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php';?> 
        
<?php 
$id = $_GET['id'];
$CurrDate = date('Y-m-d');

$sql8 = "SELECT ta.*,tu.Fname FROM tbl_attendance ta INNER JOIN tbl_users tu ON ta.UserId=tu.id WHERE ta.UserId='$UserId' AND ta.CreatedDate='$CurrDate' AND ta.Type=1";
$rncnt8 = getRow($sql8);
$row8 = getRecord($sql8);

$sql9 = "SELECT ta.*,tu.Fname FROM tbl_attendance ta INNER JOIN tbl_users tu ON ta.UserId=tu.id WHERE ta.UserId='$UserId' AND ta.CreatedDate='$CurrDate' AND ta.Type=2";
$rncnt9 = getRow($sql9);
$row9 = getRecord($sql9);
     
$sql7 = "SELECT ta.*,tu.Fname FROM tbl_attendance ta INNER JOIN tbl_users tu ON ta.UserId=tu.id WHERE ta.UserId='$UserId' AND ta.CreatedDate='$CurrDate'  GROUP BY ta.CreatedDate ORDER BY ta.CreatedDate DESC LIMIT 1";
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
<input type="hidden" id="workedHours" value="<?php echo $workedHours; ?>">
<input type="hidden" id="requiredHours" value="<?php echo $WorkingHrs; ?>">

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
                <?php if($rncnt8 > 0){} else{?>
                <!-- <div style="float:right;">
                                                                   
                 <a href="javascript:void(0)" class="btn btn-sm btn-default rounded" data-toggle="modal" data-target="#myModal">Take Today Attendance</a>
            
                                
                                                                </div><br><br> -->
                                                            <?php } ?>

             <?php include 'start-attendance.php';include 'end-attendance.php';?>

<form id="validation-form" method="post" autocomplete="off">
               <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">Today Attendance</h6>
                    </div>
                    
                    

                        <div style="float:right;padding-left: 10px;">
                                      <div class="row">
    <div class="col-lg-6 col-6">                             
        <button type="button" 
            class="btn btn-sm btn-default rounded" 
            data-toggle="modal" 
            data-target="#myModal"
            <?php 
                $currentTime = date("H:i"); 

                if($HoEmp == 1){
                    // Full conditions for HO Employee
                    if($rncnt8 > 0 || $currentTime > "10:31"){ 
                        echo "disabled"; 
                    }
                } else {
                    // Normal Employee → check only start mark
                    if($rncnt8 > 0){ 
                        echo "disabled"; 
                    }
                }
            ?>>
            Start Attendance
        </button>
    </div>
    
    <div class="col-lg-6 col-6">
        <button type="button" 
            id="endAttendanceBtn"
            class="btn btn-sm btn-default rounded" 
            data-toggle="modal" 
            data-target="#myModal2"
            <?php 
                $currentTime = date("H:i"); 

                if($HoEmp == 1){
                    // Full conditions for HO Employee
                    if($rncnt9 > 0 || $rncnt8 == 0 || $currentTime < "19:30"){ 
                        echo "disabled"; 
                    }
                } else {
                    // Normal Employee → End only visible if start marked
                    if($rncnt8 == 0){ 
                        echo "disabled"; 
                    }
                }
            ?>>
            End Attendance
        </button>
    </div>
</div>



            
                                
                                                                </div><br><br>
                                                         
                   
                </div>

              
               <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">Today Attendance</h6>
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
                        <!-- Thumbnail Avatar -->
<!-- Thumbnail Avatar -->
<div class="avatar avatar-80 rounded" style="cursor: pointer;" data-toggle="modal" data-target="#imageModal<?php echo $row33['id']; ?>">
    <div class="background">
        <img src="../attendanceimages/<?php echo $row33['Photo']; ?>" alt="" style="width: 100%; height: auto;">
    </div>
</div>

<!-- Modal with Highlighted Close Button -->
<div class="modal fade" id="imageModal<?php echo $row33['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body p-0 position-relative text-center">
        <!-- Clearly visible Close button -->
        <button type="button" class="close position-absolute" style="
          top: 10px; 
          right: 15px; 
          z-index: 10;
          background: rgba(0, 0, 0, 0.7); 
          color: #fff; 
          font-size: 2rem; 
          border: none; 
          border-radius: 50%; 
          width: 40px; 
          height: 40px;
          line-height: 36px;
          text-align: center;
          box-shadow: 0 0 5px #000;
        " data-dismiss="modal" aria-label="Close">
          &times;
        </button>
        <!-- Full Image -->
        <img src="../attendanceimages/<?php echo $row33['Photo']; ?>" alt="Full Image" class="img-fluid rounded">
      </div>
    </div>
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
                       <!-- Avatar Thumbnail -->
<div class="avatar avatar-80 rounded" style="cursor: pointer;" data-toggle="modal" data-target="#imageModal<?php echo $row34['id']; ?>">
    <div class="background">
        <img src="../attendanceimages/<?php echo $row34['Photo']; ?>" alt="" style="width: 100%; height: auto;">
    </div>
</div>

<!-- Modal for Full Image Preview -->
<div class="modal fade" id="imageModal<?php echo $row34['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body p-0 position-relative text-center">
        <!-- Highlighted Close Button -->
        <button type="button" class="close position-absolute" style="
          top: 10px;
          right: 15px;
          z-index: 10;
          background: rgba(0, 0, 0, 0.7);
          color: #fff;
          font-size: 2rem;
          border: none;
          border-radius: 50%;
          width: 40px;
          height: 40px;
          line-height: 36px;
          text-align: center;
          box-shadow: 0 0 5px #000;
        " data-dismiss="modal" aria-label="Close">
          &times;
        </button>

        <!-- Full Image Display -->
        <img src="../attendanceimages/<?php echo $row34['Photo']; ?>" alt="Full Image" class="img-fluid rounded">
      </div>
    </div>
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
    
     <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADZAncocVsQMiK8ebIDhli29nk5GWWydk&amp;callback=initMap"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADZAncocVsQMiK8ebIDhli29nk5GWWydk&callback=init&libraries=places&v=weekly&channel=2"></script> 

<script type="text/javascript">
    var markers = [
        
        {
            "title": 'Your Location',
            "latitude": '<?php echo $Latitude;?>',
            "longitude": '<?php echo $Longitude;?>',
            "description": 'Your Location'
        },
        
        {
            "title": 'Franchise',
            "latitude": '<?php echo $FrLattitude;?>',
            "longitude": '<?php echo $FrLongitude;?>',
            "description": 'Franchise'
        },

        
    ];
    window.onload = function () {
        var mapOptions = {
            center: new google.maps.LatLng(markers[0].latitude, markers[0].longitude),
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
        var infoWindow = new google.maps.InfoWindow();
        var lat_lng = new Array();
        var latlngbounds = new google.maps.LatLngBounds();
        for (i = 0; i < markers.length; i++) {
            var data = markers[i]
            var myLatlng = new google.maps.LatLng(data.latitude, data.longitude);
            lat_lng.push(myLatlng);
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title: data.title
            });

            latlngbounds.extend(marker.position);
            (function (marker, data) {
                google.maps.event.addListener(marker, "click", function (e) {
                    infoWindow.setContent(data.title);
                    infoWindow.open(map, marker);
                });
            })(marker, data);
        }
        map.setCenter(latlngbounds.getCenter());
        map.fitBounds(latlngbounds);

        var service = new google.maps.DirectionsService();

        for (var i = 0; i < lat_lng.length; i++) {
            if ((i + 1) < lat_lng.length) {
                var src = lat_lng[i];
                var des = lat_lng[i + 1];

                service.route({
                    origin: src,
                    destination: des,
                    travelMode: google.maps.DirectionsTravelMode.WALKING
                }, function (result, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        var path = new google.maps.MVCArray();
                        var poly = new google.maps.Polyline({
                            map: map,
                            strokeColor: '#4986E7'
                        });
                        poly.setPath(path);
                        for (var i = 0, len = result.routes[0].overview_path.length; i < len; i++) {
                            path.push(result.routes[0].overview_path[i]);
                        }
                    }
                });
            }
        }
    }
    
    
    
</script>



<script>
function checkLocKm(){
    var totdist = $('#totdist').val();
    if (Number(totdist) > 0.5) {   // 0.1 km = 100 meters
        $('#submit').attr('disabled',false);
        $('#errmsg').show();
    }
    else{
        $('#submit').attr('disabled',false);
        $('#errmsg').hide();
    }
}

/*function checkHoEmp(){
    var action = 'checkHoEmp';
    var  userid = $('#userid').val();
     $.ajax({
                url: "ajax_files/ajax_attendance.php",
                method: "POST",
                data: {
                    action: action,
                    userid:userid
                },
                success: function(data) {
                    //alert(data);
                    if(data == 1){
                     $('#submit').attr('disabled',false);
                     $('#errmsg').hide();
                    }
                    else{
                        if(userid==10503 || userid==10029 || userid==7941 || userid == 9952 || userid == 9180 || userid == 9615 || userid == 8671 || userid == 5967 || userid == 7458 || userid == 3287 || userid == 6017 || userid == 4954 || userid == 2941 || userid == 7869){
                            $('#submit').attr('disabled',false);
                            $('#errmsg').hide();
                        }
                        else{
                       checkLocKm(); 
                        }
                    }
                }  
           })
}*/

function checkHoEmp() {
    const userid = $('#userid').val();
    const $submit = $('#submit');
    const $errmsg = $('#errmsg');
    //const hoEmpIds = [2562,2748,2711,4088,10582,10663,1520,10503, 10029, 7941, 9952, 9180, 9615, 8671, 5967, 7458, 3287, 6017, 4954, 2941, 7869];

    $.post("ajax_files/ajax_attendance.php", { action: 'checkHoEmp', userid }, function (data) {
        // if (data == 1 || hoEmpIds.includes(parseInt(userid))) {
        if (data == 1) {
           /* $submit.prop('disabled', false);
            $errmsg.hide();*/
            checkLocKm();
        } 
        else if(data == 2){
            $submit.prop('disabled', false);
            $errmsg.hide();
        }
        else {
            checkLocKm();
        }
    });
}
function uploadImageSingle(prdid){
    //alert(prdid);
    var action = "save";
    var pageval = "attendance";
    Android.uploadImageSingle(''+prdid+'',''+action+'',''+pageval+'');
}

 $(document).ready(function() {
     
     $("#endAttendanceBtn").on("click", function(e){
        var worked = parseFloat($("#workedHours").val());
        var required = parseFloat($("#requiredHours").val());

        if(worked < required){
            e.stopImmediatePropagation(); // stop opening modal
            toastr.error("Your working hours are not completed yet! (" + worked + " / " + required + " hrs)", "Error", {timeOut: 5000});
            return false;
        }
    });
    
     checkHoEmp();
     $('#validation-form').on('submit', function(e){
      e.preventDefault();  
      var userid = $('#userid').val();
      /*if(userid == 475){
          toastr.error('You Cant Mark Attendance. Contact HR Department...', 'Error', {timeOut: 5000});  
      }
      else{*/
     $.ajax({  
                url :"ajax_files/ajax_attendance.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                 beforeSend:function(){
     $('#submit').attr('disabled','disabled');
     $('#submit').text('Please Wait...');
    },
                success:function(data){ 
                  //console.log(data);exit();
                 // alert(data);exit();
                     if(data == 0){
                         toastr.error('Please Fill Manager Checkpoint First', 'Error', {timeOut: 5000});  
                     }
                     else if(data == 2){
                         toastr.error('Please Fill BDM Checkpoint First', 'Error', {timeOut: 5000}); 
                          setTimeout(function(){ 
                           
                                        window.location.href="add-bdm-checklist.php";
                                        }, 3000);
                     }
                     else if(data == 1){
                         // Android.saveProductClick();
                    toastr.success('Attendance Successfully!', 'Success', {timeOut: 5000}); 
                        setTimeout(function(){ 
                           
                                        window.location.href="attendance.php";
                                        }, 3000);
                     }
                     else{
                        toastr.error('Please Upload Selfi/Photo', 'Error', {timeOut: 5000});  
                     }
                     
                  
                     
                      $('#submit').attr('disabled',false);
                    $('#submit').text('Update');
                }  
           })
     /* }*/
      });

     $('#validation-form2').on('submit', function(e){
      e.preventDefault();    
     $.ajax({  
                url :"ajax_files/ajax_attendance.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                 beforeSend:function(){
     $('#submit2').attr('disabled','disabled');
     $('#submit2').text('Please Wait...');
    },
                success:function(data){ 
                  //console.log(data);exit();
                     
                    if(data == 1){
                        // Android.saveProductClick();
                    toastr.success('Attendance Successfully!', 'Success', {timeOut: 5000}); 
                        setTimeout(function(){ 
                                        window.location.href="attendance.php";
                                        }, 3000);
                     }
                     else{
                        toastr.error('Please Upload Selfi/Photo', 'Error', {timeOut: 5000});  
                     }
                    
                     
                      $('#submit2').attr('disabled',false);
                    $('#submit2').text('Update');
                }  
           })
      });
 });
function checkToday(){
    var CurrDate = $('#CurrDate').val();
    var srno = $('#srno').val();
    var userid = $('#userid').val();
    var action = "checkToday";
            $.ajax({
                url: "ajax_files/ajax_attendance.php",
                method: "POST",
                data: {
                    action: action,
                    date: CurrDate,
                    userid:userid
                },
                success: function(data) {
                    //alert(data);
                    if(data == 1){
                    $('#Attendance'+srno).prop("checked",true);
                    $('#Attendance'+srno).attr("disabled",true);
                    }
                    else{
                     $('#Attendance'+srno).prop("checked",false);   
                     $('#Attendance'+srno).attr("disabled",false);
                    }
                    
                }
            });
}
    function takeAttendance(date,userid,id){
         if($('#Attendance'+id).prop('checked') == true) {
            $('#Attendance'+id).val(1);
        }
        else{
           $('#Attendance'+id).val(0);
            }
            var status = $('#Attendance'+id).val();
            var SourceLat = $('#SourceLat').val();
            var SourceLong = $('#SourceLong').val();
            var SourceAddress = $('#SourceAddress').val();
        var action = "takeAttendance";
            $.ajax({
                url: "ajax_files/ajax_attendance.php",
                method: "POST",
                data: {
                    action: action,
                    date: date,
                    userid:userid,
                    status:status,
                    SourceLat:SourceLat,
                    SourceLong:SourceLong,
                    SourceAddress:SourceAddress
                },
                success: function(data) {
                    //console.log(data);exit();
                    if(status == 1){
                    $('#Attendance'+id).prop("checked",true);
                    $('#Attendance'+id).attr("disabled",true);
                    }
                    else{
                     $('#Attendance'+id).prop("checked",false);   
                     $('#Attendance'+id).attr("disabled",false);
                    }
                    
                }
            });

       
    }
    
    
    
    var map;
var directionsDisplay;
var geocoder = new google.maps.Geocoder();
var infowindow = new google.maps.InfoWindow();
var marker;
var marker2;
function initMap() {
currentLocation();
}
    
    function init() {
initMap();
}
    
    function currentLocation(){
     var SourceLat = $('#SourceLat').val();
    var SourceLong = $('#SourceLong').val();

    var latlng = new google.maps.LatLng(SourceLat, SourceLong);
    // This is making the Geocode request
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'latLng': latlng },  (results, status) =>{
        if (status !== google.maps.GeocoderStatus.OK) {
           // alert(status);
        }
        // This is checking to see if the Geoeode Status is OK before proceeding
        if (status == google.maps.GeocoderStatus.OK) {
            console.log(results);
            var address = (results[0].formatted_address);
            $('#SourceAddress').val(address);
        }
    });

     if (marker)
        marker.setMap(null);
var myLatlng = new google.maps.LatLng(SourceLat,SourceLong);
var mapOptions = {
zoom: 18,
center: myLatlng,
mapTypeId: google.maps.MapTypeId.ROADMAP,
disableDefaultUI: true,
};

 map = new google.maps.Map(document.getElementById("map"), mapOptions);

var iconBase = 'icons/Webp.net-gifmaker(14).gif';
marker = new google.maps.Marker({
map: map,
 icon: {
   url: iconBase,
   size: new google.maps.Size(20, 80),
   scaledSize: new google.maps.Size(20, 80),
   anchor: new google.maps.Point(0, 50)
  },
position: myLatlng,
animation: google.maps.Animation.DROP,
draggable: true 
}); 


google.maps.event.addListener(marker, 'dragend', function (event) {
    var lat = this.getPosition().lat();
     var lang = this.getPosition().lng();

     var latlng = new google.maps.LatLng(lat, lang);
    // This is making the Geocode request
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'latLng': latlng },  (results, status) =>{
        if (status !== google.maps.GeocoderStatus.OK) {
            //alert(status);
        }
        // This is checking to see if the Geoeode Status is OK before proceeding
        if (status == google.maps.GeocoderStatus.OK) {
            //console.log(results);
            var address = (results[0].formatted_address);
             //alert(address);
             $('#origin-input2').val(address);
        }
    });
    
     $('#SourceLat').val(parseFloat(lat).toFixed(7));
        $('#SourceLong').val(parseFloat(lang).toFixed(7));
});
 marker.addListener("click", toggleBounce);
}
</script>

<script>
    // load this code when the document has loaded

    document.addEventListener('DOMContentLoaded', function() {

        // get a reference to the remove button

        var button = document.querySelector('#remove-button');

        // listen to clicks on the remove button

        button.addEventListener('click', function() {

            // get the element with id 'my-cropper'

            var element = document.querySelector('#my-cropper');

            // find the cropper attached to the element

            var cropper = Slim.find(element);

            // call the remove method on the cropper

            cropper.remove();

        });

    });

    </script>

  <script>

    function handleImageRemoval(data) {

        // can't continue without server file name

        if (!data.server) { return; }

        // setup request and send

        var name = data.server.file;

        var url = 'example/async-remove.php';

        var xhr = new XMLHttpRequest();

        xhr.open('GET', url + (url.indexOf('?')===-1?'?':':') + 'name=' + name, true);

        xhr.send();

    }

    </script>
<script src="example/js/slim.kickstart.min.js"></script>
</body>

</html>
