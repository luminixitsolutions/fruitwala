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
$Narration = addslashes(trim($_POST["Comment"]));
$CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');

// Check if user has week-off punch record for the requested date
$weekoff_check_sql = "SELECT id FROM tbl_week_off_punch WHERE user_id = '$user_id' AND punch_date = '$FromDate' AND status = 'active'";
$weekoff_result = $conn->query($weekoff_check_sql);

if($weekoff_result && $weekoff_result->num_rows > 0){
    echo 2; // Week-off punch found, cannot add attendance request
}
else{
    $sql = "SELECT * FROM tbl_attendance_request WHERE UserId = '$user_id' AND MONTH(FromDate) = MONTH(CURDATE()) AND YEAR(FromDate) = YEAR(CURDATE())";
    $rncnt = getRow($sql);
    if($rncnt >= 5){
        echo 0; // Monthly limit exceeded
    }
    else{
        $qx = "INSERT INTO tbl_attendance_request SET Latitude='$Latitude',Longitude='$Longitude',AttRoll='$AttRoll',FromDate='$FromDate',FromTime='$FromTime',ToDate='$ToDate',ToTime='$ToTime',UserId = '$user_id',Status='0',Narration = '$Narration',ReqDate='$CreatedDate',NoticePeriod='$NoticePeriod',CreatedDate='$CreatedDate',CreatedBy='$user_id'";
        $conn->query($qx); 
        echo 1; // Success
    }
}
}
?>