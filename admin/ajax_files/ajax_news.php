<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'deletePhoto') {
   	$id = $_POST['id'];
   	$Photo = $_POST['Photo'];
    $query = "UPDATE tbl_news SET Photo='' WHERE id=$id";
    $conn->query($query);
    $src = "../../uploads/$Photo";
    unlink($src);
    echo "Photo Delete Successfully";

  }

if($_POST['action'] == 'deleteDoc'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_news SET File='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Document Delete Successfully";
}
?>