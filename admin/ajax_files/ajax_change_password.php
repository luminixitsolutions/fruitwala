<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION["Admin"]['id'];
$Username = addslashes(trim($_POST['Username']));
$OldPassword = addslashes($_POST['OldPassword']);
$Password = addslashes($_POST['AdminPassword']);
$ConfirmPassword = addslashes($_POST['ConfirmPassword']);

$sql = "SELECT * FROM tbl_admin WHERE id='$user_id'";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$MyPass = $row['Password'];
if($OldPassword == $MyPass){
	$sql2 = "UPDATE tbl_admin SET Password='$Password' WHERE id='$user_id'";
	$conn->query($sql2);
	echo 1;
}
else{
	echo 0;
}
?>