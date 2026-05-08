<?php 
include_once 'config.php';
$uid = $_REQUEST['uid'];
//$Phone = "9595454907";
$sql = "SELECT Lattitude,Longitude FROM tbl_users WHERE id='$uid'";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
//echo $row['latitude'];
echo json_encode(array('lat'=>$row['Lattitude'],'lang'=>$row['Longitude']));
?>