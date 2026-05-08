<?php
session_start();
include_once '../config.php';
if($_POST['action']=='Login'){
$Username = addslashes(trim($_POST['Username']));
$Password = addslashes(trim($_POST['Password']));
$query = "SELECT * FROM tbl_users WHERE Phone = '$Username' AND Password='$Password' AND Status=1 AND Roll=3";
 $rncnt = getRow($query);
 if($rncnt > 0){
    $row = getRecord($query);
    $Name = addslashes(trim($row['Fname']));
    $Phone = $row['Phone'];
    $uid = $row['id'];
    $_SESSION['User'] = $row;
    echo json_encode(array('status'=>1,'Username'=>$Phone,'uid'=>$uid));
 }
 else{
  unset($_SESSION['User']);   
  echo json_encode(array('status'=>0));
 }
}
?>