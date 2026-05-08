<?php
include_once '../config.php';
$id = $_POST['id'];
$Sname = addslashes(trim($_POST['Sname']));
$Address = addslashes(trim(nl2br($_POST['Address'])));
$GoogleMap = addslashes(trim($_POST['GoogleMap']));
$Phone1 = $_POST['Phone1'];
$EmailId = $_POST['EmailId'];
$Phone2 = $_POST['Phone2'];
$EmailId2 = $_POST['EmailId2'];
$Facebook = addslashes($_POST['Facebook']);
$Twitter = addslashes($_POST['Twitter']);
$Linkedin = addslashes($_POST['Linkedin']);
$Google = addslashes($_POST['Google']);
$Instagram = addslashes($_POST['Instagram']);
$Pinterest = addslashes($_POST['Pinterest']);

$sql = "UPDATE tbl_company_profile SET Sname='$Sname',Address='$Address',GoogleMap='$GoogleMap',Phone1='$Phone1',EmailId='$EmailId',EmailId2='$EmailId2',Phone2='$Phone2',Facebook='$Facebook',Twitter='$Twitter',Linkedin='$Linkedin',Google='$Google',Instagram='$Instagram',Pinterest='$Pinterest' WHERE id='1'";
$conn->query($sql);
echo 1;
?>