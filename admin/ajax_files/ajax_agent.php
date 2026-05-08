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
$Subjects = addslashes($_POST['Subjects']);
$CountryId = addslashes($_POST['CountryId']);
$StateId = addslashes($_POST['StateId']);
$CityId = addslashes($_POST['CityId']);
$Address = addslashes(trim($_POST['Address']));
$Pincode = trim($_POST['Pincode']);
$Status = $_POST['Status'];
$Education = addslashes(trim($_POST['Education']));
$Experience = addslashes(trim($_POST['Experience']));
$Occupation = addslashes(trim($_POST['Occupation']));
$MaritalStatus = addslashes(trim($_POST['MaritalStatus']));
$CommisionPer = addslashes(trim($_POST['CommisionPer']));
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

function generateSimpleReferCode($length = 6) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $referCode = '';
    for ($i = 0; $i < $length; $i++) {
        $referCode .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $referCode;
}

// Example usage
$ReferCode = generateSimpleReferCode();  // Example output: G7K4ZQ



if($id == ''){
$sql2 = "SELECT * FROM tbl_users WHERE (Phone='$Phone' OR EmailId='$EmailId') AND Roll=4";
}
else{
$sql2 = "SELECT * FROM tbl_users WHERE (Phone='$Phone' OR EmailId='$EmailId') AND Roll=4 AND id!='$id'";	
}
$res2 = $conn->query($sql2);
$row2 = mysqli_num_rows($res2);
if($row2 > 0){
	echo 0;
}
else{
if($id == ''){
$sql = "INSERT INTO tbl_users SET ReferCode='$ReferCode',CommisionPer='$CommisionPer',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Photo='$Photo',Gender='$Gender',Day='$Day',Month='$Month',Year='$Year',Education='$Education',Experience='$Experience',Occupation='$Occupation',MaritalStatus='$MaritalStatus',Status='$Status',CreatedBy='$user_id',Roll='4',AccName='Faculty',CreatedDate='$CreatedDate'";
$conn->query($sql);
$CustId = mysqli_insert_id($conn);
$CustomerId = "F".$CustId;
$sql3 = "UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$CustId'";
     $conn->query($sql3);
echo 1;
}
else{
$sql = "UPDATE tbl_users SET CommisionPer='$CommisionPer',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Photo='$Photo',Gender='$Gender',Day='$Day',Month='$Month',Year='$Year',Education='$Education',Experience='$Experience',Occupation='$Occupation',MaritalStatus='$MaritalStatus',Status='$Status',ModifiedBy='$user_id',ModifiedDate='$CreatedDate' WHERE id='$id'";
$conn->query($sql);
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