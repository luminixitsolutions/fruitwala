<?php 
include_once 'config.php';
$uid = $_REQUEST['uid'];
$latitude = $_REQUEST['lat'];
$longitude = $_REQUEST['lang'];
$sql = "UPDATE tbl_users SET Lattitude='$latitude',Longitude='$longitude' WHERE id='$uid'";
$conn->query($sql);
echo 1;
?>