<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'Save'){
$id = $_POST['id'];
$SchoolName = addslashes(trim($_POST['SchoolName']));
$EstYear = addslashes(trim($_POST['EstYear']));
$Details = addslashes(trim($_POST['Details']));
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
$Status = $_POST['Status'];
$Roll = $_POST['Roll'];
$UnderBy = $_POST['UnderBy'];
$CreatedDate = date('Y-m-d');

$randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$Photo = $imagepath ;
} 
else{
	$Photo = $_POST['OldPhoto'];
}


function RandomStringGenerator($n)
{
    $generated_string = "";   
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $len = strlen($domain);
    for ($i = 0; $i < $n; $i++)
    {
        $index = rand(0, $len - 1);
        $generated_string = $generated_string . $domain[$index];
    }
    return $generated_string;
} 
$n = 12;
$ReferenceNo = RandomStringGenerator($n); 

if($id == ''){
$sql2 = "SELECT * FROM tbl_users WHERE Phone='$Phone' AND Roll=2";
}
else{
$sql2 = "SELECT * FROM tbl_users WHERE Phone='$Phone' AND Roll=2 AND id!='$id'";	
}
$res2 = $conn->query($sql2);
$row2 = mysqli_num_rows($res2);
if($row2 > 0){
	echo 0;
}
else{
if($id == ''){
$sql = "INSERT INTO tbl_users SET SchoolName='$SchoolName',EstYear='$EstYear',Details='$Details',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='2',AccName='School',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
$conn->query($sql);
$CustId = mysqli_insert_id($conn);
$CustomerId = "SCH000".$CustId;
$sql3 = "UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$CustId'";
     $conn->query($sql3);
echo 1;
}
else{
$sql = "UPDATE tbl_users SET SchoolName='$SchoolName',EstYear='$EstYear',Details='$Details',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',ModifiedBy='$user_id',ModifiedDate='$CreatedDate' WHERE id='$id'";
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