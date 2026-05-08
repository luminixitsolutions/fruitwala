<?php 
session_start();
include_once 'config.php';
$Phone=$_REQUEST['mobile'];
$latitude=$_REQUEST['lat'];
$longitude=$_REQUEST['lang'];


$sql = "UPDATE tbl_users SET Lattitude='$latitude',Longitude='$longitude' WHERE Phone='$Phone'";
$conn->query($sql);
echo "location updated";

?>