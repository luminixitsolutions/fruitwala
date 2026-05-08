<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
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
$sql2 = "SELECT * FROM tbl_consultants WHERE Phone='$Phone'";
}
else{
$sql2 = "SELECT * FROM tbl_consultants WHERE Phone='$Phone' AND id!='$id'";	
}
$res2 = $conn->query($sql2);
$row2 = mysqli_num_rows($res2);
if($row2 > 0){
	echo 0;
}
else{
$sql_1 = "SELECT Name FROM tbl_user_type WHERE id='$Roll'";
$row_1 = getRecord($sql_1);   
$AccName = $row_1['Name'];
if($id == ''){
$sql = "INSERT INTO tbl_consultants SET Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='$Roll',AccName='$AccName',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
$conn->query($sql);
$CustId = mysqli_insert_id($conn);
$CustomerId = "ZN000".$CustId;
$sql3 = "UPDATE tbl_consultants SET CustomerId='$CustomerId' WHERE id='$CustId'";
     $conn->query($sql3);
echo 1;
}
else{
$sql = "UPDATE tbl_consultants SET Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='$Roll',AccName='$AccName',ModifiedBy='$user_id',ModifiedDate='$CreatedDate' WHERE id='$id'";
$conn->query($sql);
echo 1;
}
}
?>