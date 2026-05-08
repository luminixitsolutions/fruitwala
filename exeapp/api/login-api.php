<?php
include "../config.php";
$mobile = $_REQUEST['Phone']; // ✅ Changed from $_POST to $_REQUEST
$password = $_REQUEST['password'];
$sql = "SELECT id,Phone,Fname,Roll FROM tbl_users WHERE Phone='$mobile' AND Status=1 AND Roll=3";
$res = mysqli_query($conn, $sql);

if(mysqli_num_rows($res) > 0){
    $data = mysqli_fetch_assoc($res);
    
    $Roll = $data['Roll'];


    echo json_encode(['status' => true, 'user' => $data,'Roll'=>$Roll]);
} else {
    echo json_encode(['status' => false, 'message' => 'Invalid Mobile','Roll'=>0]);
}
?>