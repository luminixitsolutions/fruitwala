<?php  
session_start();
include_once '../config.php';
$user_id = $_SESSION['User']['id'];
$sql110 = "SELECT Roll,Lattitude,Longitude FROM tbl_users WHERE id='$user_id'";
$row110 = getRecord($sql110);
$Roll = $row110['Roll'];
$Latitude = $row110['Lattitude'];
$Longitude = $row110['Longitude'];
if($_POST['action'] == 'saveRequest'){
$AttRoll = addslashes(trim($_POST["AttRoll"]));
$FromDate = addslashes(trim($_POST["FromDate"]));
$FromTime = addslashes(trim($_POST["FromTime"]));
$ToDate = addslashes(trim($_POST["ToDate"]));
$ToTime = addslashes(trim($_POST["ToTime"]));
$Narration = addslashes(trim($_POST["Narration"]));
$TotDays = addslashes(trim($_POST["TotDays"]));
$CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');

$sql = "SELECT * FROM tbl_leave_request WHERE UserId = '$user_id' AND ReqDate='$CreatedDate'";
$rncnt = getRow($sql);
if($rncnt > 0){
    echo 0;
}
else{
$qx = "INSERT INTO tbl_leave_request SET Latitude='$Latitude',Longitude='$Longitude',TotDays='$TotDays',FromDate='$FromDate',FromTime='$FromTime',ToDate='$ToDate',ToTime='$ToTime',UserId = '$user_id',Status='0',Narration = '$Narration',ReqDate='$CreatedDate',NoticePeriod='$NoticePeriod',CreatedDate='$CreatedDate',CreatedBy='$user_id'";
  $conn->query($qx); 
  echo 1;
}
}
?>