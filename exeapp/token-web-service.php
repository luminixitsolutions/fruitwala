<?php 
session_start();
include_once 'config.php';
$Username = $_REQUEST['username'];
$Tokens = $_REQUEST['token'];
$sql = "UPDATE tbl_users SET Tokens='$Tokens' WHERE Phone='$Username'";
$conn->query($sql);
echo 1;
?>