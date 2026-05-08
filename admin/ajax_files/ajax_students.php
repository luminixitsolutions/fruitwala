<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'Save'){
$id = $_POST['id'];
$Fname = addslashes(trim($_POST['Fname']));
$Mname = addslashes(trim($_POST['Mname']));
$Lname = addslashes(trim($_POST['Lname']));
$Phone = $_POST['Phone'];
$EmailId = $_POST['EmailId'];
$Phone2 = $_POST['Phone2'];
$Password = $_POST['Password'];
$Gender = addslashes($_POST['Gender']);
$Day = addslashes($_POST['Day']);
$Month = addslashes($_POST['Month']);
$Year = addslashes($_POST['Year']);
$CountryId = addslashes($_POST['CountryId']);
$StateId = addslashes($_POST['StateId']);
$CityId = addslashes($_POST['CityId']);
$Address = addslashes(trim($_POST['Address']));
$Pincode = trim($_POST['Pincode']);
$Education = addslashes(trim($_POST['Education']));
$College = addslashes(trim($_POST['College']));
$Occupation = addslashes(trim($_POST['Occupation']));
$MaritalStatus = addslashes(trim($_POST['MaritalStatus']));
$DeptId = addslashes(trim($_POST['DeptId']));
$CourseId = addslashes(trim($_POST['CourseId']));
$BatchId = addslashes(trim($_POST['BatchId']));
$StartDate = addslashes(trim($_POST['StartDate']));
$BatchTime = addslashes(trim($_POST['BatchTime']));
$Duration = addslashes(trim($_POST['Duration']));
$CourseType = addslashes(trim($_POST['CourseType']));
$CourseFees = addslashes(trim($_POST['CourseFees']));
$Seats = addslashes(trim($_POST['Seats']));
$FatherName = addslashes(trim($_POST['FatherName']));
$MotherName = addslashes(trim($_POST['MotherName']));
$ParentOccupation = addslashes(trim($_POST['ParentOccupation']));
$ParentPhoneNo = addslashes(trim($_POST['ParentPhoneNo']));
$ParentEmailId = addslashes(trim($_POST['ParentEmailId']));
$RollNo = addslashes(trim($_POST['RollNo']));
$Status = $_POST['Status'];
$CreatedDate = date('Y-m-d');

$Photo = $_POST['OldPhoto'] ?? ''; // Fallback to old photo

if (isset($_FILES['Photo']) && $_FILES['Photo']['error'] === UPLOAD_ERR_OK) {
    $randno = rand(1, 100);
    $src = $_FILES['Photo']['tmp_name'];
    $fnm = pathinfo($_FILES["Photo"]["name"], PATHINFO_FILENAME);
    $fnm = str_replace(" ", "_", $fnm);
    $ext = strtolower(pathinfo($_FILES["Photo"]["name"], PATHINFO_EXTENSION));

    // Optional: Allow only specific file types
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (in_array($ext, $allowed_ext)) {
        $imagepath = $randno . "_" . $fnm . "." . $ext;
        $dest = '../../uploads/' . $imagepath;

        if (is_uploaded_file($src) && move_uploaded_file($src, $dest)) {
            $Photo = $imagepath;
        } else {
            echo 0;
            //echo "<script>alert('Failed to upload photo.');</script>";
        }
    } else {
        echo 0;
        //echo "<script>alert('Invalid file type. Only JPG, PNG, GIF, WEBP allowed.');</script>";
    }
} elseif (isset($_FILES['Photo']) && $_FILES['Photo']['error'] !== UPLOAD_ERR_NO_FILE) {
    echo 0;
    //echo "<script>alert('Photo upload error: " . $_FILES['Photo']['error'] . "');</script>";
}


if($id == ''){
$sql2 = "SELECT * FROM tbl_users WHERE (Phone='$Phone' OR EmailId='$EmailId') AND Roll=2";
}
else{
$sql2 = "SELECT * FROM tbl_users WHERE (Phone='$Phone' OR EmailId='$EmailId') AND Roll=2 AND id!='$id'";
//$sql21 = "SELECT * FROM tbl_allocate_courses WHERE DeptId='$DeptId' AND CourseId='$CourseId' AND id='$id' AND Main!=1";	
}
$res2 = $conn->query($sql2);
$row2 = mysqli_num_rows($res2);

//$row21 = getRow($sql21);
if($row2 > 0){
	echo 0;
}
/*else if($row21 > 0){
    echo 2;
}*/
else{
if($id == ''){
$sql = "INSERT INTO tbl_users SET RollNo='$RollNo',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Gender='$Gender',Day='$Day',Month='$Month',Year='$Year',Photo='$Photo',Education='$Education',College='$College',Occupation='$Occupation',MaritalStatus='$MaritalStatus',DeptId='$DeptId',CourseId='$CourseId',BatchId='$BatchId',StartDate='$StartDate',BatchTime='$BatchTime',Duration='$Duration',CourseType='$CourseType',CourseFees='$CourseFees',Seats='$Seats',FatherName='$FatherName',MotherName='$MotherName',ParentOccupation='$ParentOccupation',ParentPhoneNo='$ParentPhoneNo',ParentEmailId='$ParentEmailId',Roll='2',AccName='Student',Status='$Status',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
$conn->query($sql);
$CustId = mysqli_insert_id($conn);
$CustomerId = "C".$CustId;
$sql3 = "UPDATE tbl_users SET CustomerId='$CustomerId',RollNo='$CustId' WHERE id='$CustId'";
     $conn->query($sql3);

$sql22 = "SELECT * FROM tbl_allocate_courses WHERE UserId='$id' AND DeptId='$DeptId' AND CourseId='$CourseId'";
$rncnt22 = getRow($sql22);
if($rncnt22 > 0){}
else{
$sql4 = "INSERT INTO tbl_allocate_courses SET UserId='$id',DeptId='$DeptId',CourseId='$CourseId',BatchId='$BatchId',StartDate='$StartDate',BatchTime='$BatchTime',Duration='$Duration',CourseType='$CourseType',CourseFees='$CourseFees',Status='1',Main=1,CreatedBy='$user_id',CreatedDate='$CreatedDate'";
$conn->query($sql4);
}
echo 1;
}
else{
$sql = "UPDATE tbl_users SET Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Gender='$Gender',Day='$Day',Month='$Month',Year='$Year',Photo='$Photo',Education='$Education',College='$College',Occupation='$Occupation',MaritalStatus='$MaritalStatus',DeptId='$DeptId',CourseId='$CourseId',BatchId='$BatchId',StartDate='$StartDate',BatchTime='$BatchTime',Duration='$Duration',CourseType='$CourseType',CourseFees='$CourseFees',Seats='$Seats',FatherName='$FatherName',MotherName='$MotherName',ParentOccupation='$ParentOccupation',ParentPhoneNo='$ParentPhoneNo',ParentEmailId='$ParentEmailId',Status='$Status',ModifiedBy='$user_id',ModifiedDate='$CreatedDate' WHERE id='$id'";
$conn->query($sql);
$sql22 = "SELECT * FROM tbl_allocate_courses WHERE UserId='$id' AND DeptId='$DeptId' AND CourseId='$CourseId'";
$rncnt22 = getRow($sql22);
if($rncnt22 > 0){}
else{
$sql4 = "INSERT INTO tbl_allocate_courses SET UserId='$id',DeptId='$DeptId',CourseId='$CourseId',BatchId='$BatchId',StartDate='$StartDate',BatchTime='$BatchTime',Duration='$Duration',CourseType='$CourseType',CourseFees='$CourseFees',Status='1',Main=1,CreatedBy='$user_id',CreatedDate='$CreatedDate'";
$conn->query($sql4);
}
echo 1;
}
}
}

if($_POST['action'] == 'deletePhoto'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_users SET Photo='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Photo Delete Successfully";
} 
?>