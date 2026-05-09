<?php
error_reporting(0);
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fruitwala";


/// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
// check connection 
if($conn->connect_error) {
    die("connection failed : " . $conn->connect_error);
} else {
    // echo "Successfully Connected";
}
$Proj_Title = "Fruit Wala Break Fast";
$SiteUrl = "https://easylearnalone.com/";
date_default_timezone_set("Asia/Kolkata");
/*$sms_username = "giradkatotp";
$sms_password = "ind123";
$sms_sender = "SAIAPP";
$site_url = "http://localhost/education";*/

function getList($sql){
  global $conn;  
    $res2 = $conn->query($sql);
    while($row2 = $res2->fetch_assoc()){
        $row3[] = $row2;
    }
    return $row3;
}

function getRecord($sql){
  global $conn;  
    $res2 = $conn->query($sql);
	$row2 = $res2->fetch_assoc();
    return $row2;
}

function getRow($sql){
  global $conn;  
    $res2 = $conn->query($sql);
	$row2 = mysqli_num_rows($res2);
    return $row2;
}
?>