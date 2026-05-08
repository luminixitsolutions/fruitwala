<?php
include '../config.php';

if($_POST['action'] == 'deleteAttachment'){
    $id = $_POST['id'];
    $Attachment = $_POST['Attachment'];

    $filePath = "../uploads/expenses/" . $Attachment;
    if(file_exists($filePath)) unlink($filePath);

    $sql = "UPDATE tbl_company_expenses SET Attachment='' WHERE id='$id'";
    $conn->query($sql);
    echo "Attachment deleted successfully.";
}
?>
