<?php  
session_start();
include_once '../config.php';
$user_id = $_SESSION['User']['id'];
$sql110 = "SELECT Roll FROM tbl_users WHERE id='$user_id'";
$row110 = getRecord($sql110);
$Roll = $row110['Roll'];
if($_POST['action'] == 'saveResignRequest'){
$Narration = addslashes(trim($_POST["Narration"]));
$NoticePeriod = addslashes(trim($_POST["NoticePeriod"]));
$LastWorkingDay = addslashes(trim($_POST["LastWorkingDay"]));
$CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');

$sql = "SELECT * FROM tbl_resign_request WHERE UserId = '$user_id' AND ReqDate='$CreatedDate'";
$rncnt = getRow($sql);
if($rncnt > 0){
    echo 0;
}
else{
$qx = "INSERT INTO tbl_resign_request SET UserId = '$user_id',Status='0',Narration = '$Narration',ReqDate='$CreatedDate',NoticePeriod='$NoticePeriod',CreatedDate='$CreatedDate',CreatedBy='$user_id',LastWorkingDay='$LastWorkingDay'";
  $conn->query($qx); 
  echo 1;
}
}

if($_POST['action'] == 'updateExpenses'){
    $id = $_POST['id']; 
    $Narration = addslashes(trim($_POST["Narration"]));
$Amount = addslashes(trim($_POST["Amount"]));
$PaymentMode = addslashes(trim($_POST["PaymentMode"]));
$ExpenseDate = addslashes(trim($_POST["ExpenseDate"]));
$Gst = addslashes(trim($_POST["Gst"]));
$VedPhone = addslashes(trim($_POST["VedPhone"]));
$TempPrdId = $_POST['TempPrdId'];
$TempPrdId2 = $_POST['TempPrdId2'];
$FrId = addslashes(trim($_POST["FrId"]));
$ExpCatId = addslashes(trim($_POST["ExpCatId"]));
$Locations = addslashes(trim($_POST["Locations"]));
$CreatedDate = date('Y-m-d');
$ModifiedDate = date('Y-m-d');

if (isset($_FILES['Photo'])) {
$FileName1 = $_FILES["Photo"]["name"];
$FileSize1 = $_FILES["Photo"]["size"];
$TempFile1 = $_FILES["Photo"]["tmp_name"];
$Photo = uploadImage($FileName1,$FileSize1,$TempFile1);
}
else{
   $Photo = $_POST['OldPhoto']; 
}

if (isset($_FILES['Photo2'])) {
$FileName2 = $_FILES["Photo2"]["name"];
$FileSize2 = $_FILES["Photo2"]["size"];
$TempFile2 = $_FILES["Photo2"]["tmp_name"];
$Photo2 = uploadImage($FileName2,$FileSize2,$TempFile2);
}
else{
   $Photo2 = $_POST['OldPhoto2']; 
}

$date1=date_create($ExpenseDate);
$date2=date_create($CreatedDate);
$diff=date_diff($date1,$date2);
if($ExpenseDate<$CreatedDate){
$TotDays = $diff->format("%a");
}
else{
    $TotDays = 0;
}

 $qx = "UPDATE tbl_expense_request SET UserId = '$user_id',Status='0',Narration = '$Narration',Amount='$Amount',PaymentMode = '$PaymentMode',ExpenseDate='$ExpenseDate',CreatedDate='$CreatedDate',CreatedBy='$user_id',Gst='$Gst',TempPrdId='$TempPrdId',TempPrdId2='$TempPrdId2',VedPhone='$VedPhone',FrId='$FrId',Photo='$Photo',Photo2='$Photo2',ExpCatId='$ExpCatId',ManagerStatus=0,AdminStatus=0,TotDays='$TotDays',Locations='$Locations' WHERE id='$id'";

  $conn->query($qx); 
  
  echo 1;
    
}
?>