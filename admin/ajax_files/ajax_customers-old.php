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
$PackageId = addslashes(trim($_POST['PackageId']));
$PkgDate = addslashes(trim($_POST['PkgDate']));
$Validity = addslashes(trim($_POST['Validity']));
$PkgAmount = addslashes(trim($_POST['PkgAmount']));
$latitude = addslashes(trim($_POST['latitude']));
$longitude = addslashes(trim($_POST['longitude']));
$AreaId = addslashes(trim($_POST['AreaId']));
$WardId = addslashes(trim($_POST['WardId']));
$ZoneId = addslashes(trim($_POST['ZoneId']));

$PaidAmount = addslashes(trim($_POST['PaidAmount']));
$BalAmount = addslashes(trim($_POST['BalAmount']));
$PayMode = addslashes(trim($_POST['PayMode']));
$Details = addslashes(trim($_POST['Details']));
$LocationLink = addslashes(trim($_POST['LocationLink']));
$ExeId = addslashes(trim($_POST['ExeId']));
$PaidStatus = addslashes(trim($_POST['PaidStatus']));
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
$FullName = $Fname." ".$Lname;


if($id == ''){
$sql2 = "SELECT * FROM tbl_users WHERE Phone='$Phone' AND Roll=5";
}
else{
$sql2 = "SELECT * FROM tbl_users WHERE Phone='$Phone' AND Roll=5 AND id!='$id'";	
}
$res2 = $conn->query($sql2);
$row2 = mysqli_num_rows($res2);
if($row2 > 0){
	echo 0;
}
else{
if($id == ''){
$sql = "INSERT INTO tbl_users SET PaidStatus='$PaidStatus',ExeId='$ExeId',PaidAmount='$PaidAmount',LocationLink='$LocationLink',Details='$Details',WardId='$WardId',ZoneId='$ZoneId',AreaId='$AreaId',Lattitude='$latitude',Longitude='$longitude',PackageId='$PackageId',PkgDate='$PkgDate',Validity='$Validity',PkgAmount='$PkgAmount',ReferCode='$ReferCode',CommisionPer='$CommisionPer',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Photo='$Photo',Gender='$Gender',Education='$Education',Status='$Status',CreatedBy='$user_id',Roll='5',CreatedDate='$CreatedDate'";
$conn->query($sql);
$CustId = mysqli_insert_id($conn);
$CustomerId = "FC".$CustId;
$sql3 = "UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$CustId'";
     $conn->query($sql3);
     
     $sql = "INSERT INTO tbl_cust_package_history SET CreatedBy='$user_id',PayMode='$PayMode',CustId='$CustId',PackageId='$PackageId',PkgDate='$PkgDate',Validity='$Validity',PkgAmount='$PkgAmount',UpdateStatus=1";
     $conn->query($sql);
     
    
    $sql = "INSERT INTO tbl_general_ledger SET PayMode='$PayMode',UserId='$CustId',AccountName='$FullName',Amount='$PkgAmount',PaymentDate='$PkgDate',CrDr='dr',Roll=5,Type='CINV',CreatedBy='$user_id',CreatedDate='$CreatedDate',SellType='CustInv'";
    $conn->query($sql);
    if($PaidAmount > 0){
        $sql2 = "SELECT MAX(SrNo) as maxid FROM tbl_general_ledger WHERE Type='PR'";
    $row2 = getRecord($sql2);
    if($row2['maxid'] == ''){
        $SrNo = 1;
        $Code = "PR".$SrNo;
    }
    else{
        $SrNo = $row2['maxid']+1;
        $Code = "PR".$SrNo;
    }
    
    $sql4 = "INSERT INTO tbl_general_ledger SET PaymentDate='$PkgDate',PayMode='$PayMode',SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$FullName',InvoiceNo='$InvoiceNo',Amount='$PaidAmount',CrDr='cr',Roll=5,Type='PR',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
    $conn->query($sql4);
    }
echo 1;
}
else{
$sql = "UPDATE tbl_users SET PaidStatus='$PaidStatus',ExeId='$ExeId',PaidAmount='$PaidAmount',PkgDate='$PkgDate',Validity='$Validity',LocationLink='$LocationLink',Details='$Details',WardId='$WardId',ZoneId='$ZoneId',AreaId='$AreaId',Lattitude='$latitude',Longitude='$longitude',CommisionPer='$CommisionPer',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Photo='$Photo',Gender='$Gender',Education='$Education',Status='$Status',ModifiedBy='$user_id',ModifiedDate='$CreatedDate' WHERE id='$id'";
$conn->query($sql);

/*$sql = "INSERT INTO tbl_cust_package_history SET CustId='$CustId',PackageId='$PackageId',PkgDate='$PkgDate',Validity='$Validity',PkgAmount='$PkgAmount',UpdateStatus=1";
     $conn->query($sql);*/
echo 1;
}
}
}

if($_POST['action'] == 'getUserDetails'){
$id = $_POST['id'];
$sql = "SELECT tu.* FROM tbl_users tu WHERE tu.id='$id'";
$row = getRecord($sql);
echo json_encode($row);
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