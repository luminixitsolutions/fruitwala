<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'deletePhoto') {
   	$id = $_POST['id'];
   	$Photo = $_POST['Photo'];
    $query = "UPDATE tbl_upsc_mains_test_papers SET File='' WHERE id=$id";
    $conn->query($query);
    $src = "../../uploads/$Photo";
    unlink($src);
    echo "Photo Delete Successfully";

  }

if($_POST['action'] == 'deleteFile2') {
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
    $query = "UPDATE tbl_student_test_papers SET File2='' WHERE id=$id";
    $conn->query($query);
    $src = "../../uploads/$Photo";
    unlink($src);
    echo "Document Delete Successfully";

  }
?>