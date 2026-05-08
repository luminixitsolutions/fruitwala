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



if($id == ''){
$sql2 = "SELECT * FROM tbl_users WHERE (Phone='$Phone' OR EmailId='$EmailId') AND Roll=3";
}
else{
$sql2 = "SELECT * FROM tbl_users WHERE (Phone='$Phone' OR EmailId='$EmailId') AND Roll=3 AND id!='$id'";	
}
$res2 = $conn->query($sql2);
$row2 = mysqli_num_rows($res2);
if($row2 > 0){
	echo 0;
}
else{
if($id == ''){
$sql = "INSERT INTO tbl_users SET Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Photo='$Photo',Gender='$Gender',Day='$Day',Month='$Month',Year='$Year',Education='$Education',Experience='$Experience',Occupation='$Occupation',MaritalStatus='$MaritalStatus',Status='$Status',CreatedBy='$user_id',Roll='3',AccName='Faculty',CreatedDate='$CreatedDate'";
$conn->query($sql);
$CustId = mysqli_insert_id($conn);
$CustomerId = "F".$CustId;
$sql3 = "UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$CustId'";
     $conn->query($sql3);
echo 1;
}
else{
$sql = "UPDATE tbl_users SET Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Photo='$Photo',Gender='$Gender',Day='$Day',Month='$Month',Year='$Year',Education='$Education',Experience='$Experience',Occupation='$Occupation',MaritalStatus='$MaritalStatus',Status='$Status',ModifiedBy='$user_id',ModifiedDate='$CreatedDate' WHERE id='$id'";
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