<?php 
session_start();
include_once '../config.php';
if(isset($_POST)){
$username = trim($_POST['Username']);
$password = $_POST['Password'];
$Roll = $_POST['Roll'];
$query = "SELECT * FROM tbl_admin WHERE Username = '$username' AND Password = '$password'";
    $result = $conn->query($query);
     $rncnt = mysqli_num_rows($result);
    $row = $result->fetch_assoc();
    if($rncnt > 0){
        $_SESSION['Admin'] = $row;
        $_SESSION['Roll'] = $row['Roll'];
        echo json_encode(array('Status'=>1,'Roll'=>$row['Roll']));
    }
    else{
        unset($_SESSION['Admin']);
        unset($_SESSION['Roll']);
        echo json_encode(array('Status'=>0));
    } 
}
?>