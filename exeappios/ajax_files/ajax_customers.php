<?php
session_start();
include_once '../config.php';
require_once ("../database.php");
$SiteUrl = "https://dailydoorservices.com/mobapp";
//include_once '../class.phpmailer.php';
//include_once '../class.smtp.php';
$user_id = $_SESSION['User']['id'];
if($_POST['action']=='Register'){
$Fname = addslashes(trim($_POST['Fname']));
$Lname = addslashes(trim($_POST['Lname']));
$Phone = $_POST['Phone'];
$EmailId = $_POST['EmailId'];
$Password = $_POST['Password'];
$CountryId = addslashes($_POST['CountryId']);
$StateId = addslashes($_POST['StateId']);
$CityId = addslashes($_POST['CityId']);
$AreaId = addslashes($_POST['AreaId']);
$Address = addslashes(trim($_POST['Address']));
$Pincode = trim($_POST['Pincode']);
$CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');
$Roll = $_POST['Roll'];  
$UnderBy = $_POST['UnderUserId'];
if($Roll == 4){
$AccName = "Doctor";
$Code = "DOC000";
}
if($Roll == 5){
$AccName = "Opticians";
$Code = "OPT000";
}
if($Roll == 6){
$AccName = "Wholesalers";
$Code = "WHOL000";
}
if($Roll == 7){
$AccName = "tbl_users";
$Code = "CUST000";
}
if($Roll == 8){
$AccName = "Retailer";
$Code = "VED000";
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
//$ReferenceNo = RandomStringGenerator($n); 

$sql2 = "SELECT * FROM tbl_users WHERE Phone='$Phone'";
$res2 = $conn->query($sql2);
$row2 = mysqli_num_rows($res2);
if($row2 > 0){
    echo json_encode(array('msg'=>"Your Phone No Already Registered With Us",'status'=>'0'));
}
else{
$sql = "INSERT INTO tbl_users SET Fname='$Fname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',AreaId='$AreaId',Address='$Address',Pincode='$Pincode',Status='0',Roll='$Roll',CreatedDate='$CreatedDate'";
$conn->query($sql);
$CustId = mysqli_insert_id($conn);
$to = $EmailId;
//include("../inc_register_mail.php");
//include("../sendmailsmtp.php");
$randno = rand(100,999);
$ReferenceNo = "C".$randno."".$CustId;
$sql3 = "INSERT INTO customer_address SET UserId='$CustId',Fname='$Fname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',AreaId='$AreaId',Address='$Address',Pincode='$Pincode',Status='1',CreatedDate='$CreatedDate'";
$conn->query($sql3);
$CustomerId = "C000".$CustId;

$sql3 = "UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$CustId'";
$conn->query($sql3);

$otp = rand(1000,9999);
$_SESSION['otp'] = $otp;
//include '../incsmsfile.php';

$query = "SELECT * FROM tbl_users WHERE Phone = '$Phone' AND Password = '$Password'";
    $result = $conn->query($query);
    $rncnt = mysqli_num_rows($result);
    $row = $result->fetch_assoc();
    $_SESSION['User'] = $row;

     echo json_encode(array('msg'=>"Registration Successfull!Your Account in pending. Wait for admin approval...",'status'=>'1','Username'=>$Phone,'uid'=>$CustId,'roll'=>$Roll));
}
}
if($_POST['action']=='Login'){
$Username = addslashes(trim($_POST['Username']));
$Password = addslashes(trim($_POST['Password']));
$query = "SELECT * FROM tbl_users WHERE Phone = '$Username' AND Status=1 AND id NOT IN(5)";
 $rncnt = getRow($query);
 $row = getRecord($query);
 if($rncnt > 0){
    $Name = addslashes(trim($row['Fname']));
    $Phone = $row['Phone'];
  $otp = rand(1000,9999);
  $_SESSION['otp'] = $otp;
  //include '../incsmsfile.php';  
  $Roll = $row['Roll'];
  $_SESSION['Roll'] = $Roll;
  if($Roll == 1 || $Roll == 2 || $Roll == 9){
  //$_SESSION['Admin'] = $row;
  unset($_SESSION['User']);
  }
  else{
    unset($_SESSION['User']);      
   //unset($_SESSION['Admin']);   
  }
  //$_SESSION['User'] = $row;
  $uid = $row['id'];
  //$user_id = $_SESSION['User']['id'];
  
  if($_POST['city_id']!=''){
  $city_id = $_POST['city_id'];  
  }
  else{
  $city_id = $row['CityId'];
  }
/*  $sql = "UPDATE tbl_users SET Location='$city_id' WHERE id='$uid'";
    $conn->query($sql);*/
  echo json_encode(array('status'=>1,'roll'=>$Roll,'Username'=>$Username,'uid'=>$uid));
 }
 else{
  unset($_SESSION['User']);   
  //unset($_SESSION['Admin']);
  unset($_SESSION['Roll']);
  echo json_encode(array('status'=>0,'roll'=>$Roll,'Username'=>$Username));
 }
}

if($_POST['action']=='OtpVerify'){
$Phone = addslashes(trim($_POST['Phone']));
$YourOtp = addslashes(trim($_POST['YourOtp']));
$GetOtp = addslashes(trim($_POST['GetOtp']));
if($YourOtp == $GetOtp){
$query = "SELECT * FROM tbl_users WHERE Phone = '$Phone' AND Status=1";
$rncnt = getRow($query);
if($rncnt > 0){
$row = getRecord($query);
$_SESSION['User'] = $row;
$Phone = $row['Phone'];
$uid = $row['id'];
unset($_SESSION['otp']);
echo json_encode(array('status'=>1,'Username'=>$Phone,'uid'=>$uid));
}
}
else{
echo json_encode(array('status'=>0));   
}
}    

if($_POST['action']=='OtpVerify2'){
$Phone = addslashes(trim($_POST['Phone']));
$YourOtp = addslashes(trim($_POST['YourOtp']));
$GetOtp = addslashes(trim($_POST['GetOtp']));
if($YourOtp == $GetOtp){
$query = "SELECT * FROM tbl_users WHERE Phone = '$Phone'";
$rncnt = getRow($query);
if($rncnt > 0){
$row = getRecord($query);
//$_SESSION['User'] = $row;
$Phone = $row['Phone'];
$uid = $row['id'];
unset($_SESSION['otp']);
echo json_encode(array('status'=>1,'Username'=>$Phone,'uid'=>$uid));
}
}
else{
echo json_encode(array('status'=>0));   
}
} 

if($_POST['action']=='Edit'){
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
$AreaId = addslashes($_POST['AreaId']);
$Address = addslashes(trim($_POST['Address']));
$Pincode = trim($_POST['Pincode']);
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

$sql2 = "SELECT * FROM tbl_users WHERE Phone='$Phone' AND id!='$id'";   
$res2 = $conn->query($sql2);
$row2 = mysqli_num_rows($res2);
if($row2 > 0){
    echo 0;
}
else{
echo $sql = "UPDATE tbl_users SET Fname='$Fname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',AreaId='$AreaId',Address='$Address',Pincode='$Pincode',ModifiedDate='$CreatedDate',Photo='$Photo' WHERE id='$id'";
$conn->query($sql);
echo 1;
}
 }   

 if($_POST['action']=='ChangePassword'){
$id = $_POST['id'];
$OldPassword = addslashes(trim($_POST['OldPassword']));
$NewPassword = addslashes(trim($_POST['NewPassword']));
$CnfPassword = addslashes(trim($_POST['CnfPassword']));
if($NewPassword == $CnfPassword){
   $sql2 = "SELECT * FROM tbl_users WHERE id='$id'";   
$res2 = $conn->query($sql2);
$row2 = $res2->fetch_assoc();
$old_Pass = $row2['Password'];
if($old_Pass == $OldPassword){
$sql = "UPDATE tbl_users SET Password='$NewPassword' WHERE id='$id'";
$conn->query($sql);
echo 1;
  
}
else{
    //old not match
  echo 2;
} 
}
else{
    //cnf not match
    echo 3;
 }
}


 if($_POST['action']=='NewPassword'){
$id = $_POST['id'];
$NewPassword = addslashes(trim($_POST['NewPassword']));
$CnfPassword = addslashes(trim($_POST['CnfPassword']));
if($NewPassword == $CnfPassword){
$sql = "UPDATE tbl_users SET Password='$NewPassword' WHERE id='$id'";
$conn->query($sql);
$query = "SELECT * FROM tbl_users WHERE id='$id' AND Status=1";
 $rncnt = getRow($query);
 $row = getRecord($query);
 if($rncnt > 0){
  $Roll = $row['Roll'];
  $_SESSION['Roll'] = $Roll;
  $_SESSION['User'] = $row;
  $uid = $row['id'];
  $user_id = $_SESSION['User']['id'];
echo 1;
}
}
else{
    //cnf not match
    echo 3;
 }
}

if($_POST['action']=='addNewAddress'){
$aid = $_POST['aid'];    
$Fname = addslashes(trim($_POST['Fname']));
$Lname = addslashes(trim($_POST['Lname']));
$Phone = $_POST['Phone'];
$EmailId = $_POST['EmailId'];
$CountryId = addslashes($_POST['CountryId']);
$StateId = addslashes($_POST['StateId']);
$CityId = addslashes($_POST['CityId']);
$AreaId = addslashes($_POST['AreaId']);
$Address = addslashes(trim($_POST['Address']));
$Pincode = trim($_POST['Pincode']);
$CreatedDate = date('Y-m-d');
if($aid==''){
$sql3 = "INSERT INTO customer_address SET UserId='$user_id',Fname='$Fname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',AreaId='$AreaId',Address='$Address',Pincode='$Pincode',Status='1',CreatedDate='$CreatedDate'";
$conn->query($sql3);
echo "<b>Congratulations!</b> Your address has been saved to your adresss list successfully.";    
}
else{
$sql3 = "UPDATE customer_address SET Fname='$Fname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',AreaId='$AreaId',Address='$Address',Pincode='$Pincode',ModifiedDate='$CreatedDate' WHERE id='$aid'";
$conn->query($sql3);
echo "<b>Congratulations!</b> Your address has been Update Successfully!";
}
}

 if($_POST['action']=='ForgotPass'){
$Username = $_POST['Username'];
$sql = "SELECT * FROM tbl_users WHERE (Phone = '$Username' OR EmailId='$Username')";
$rncnt = getRow($sql);
if($rncnt > 0){
    $sql2 = "SELECT * FROM tbl_users WHERE (Phone = '$Username' OR EmailId='$Username')";
$row = getRecord($sql2);
$EmailId = $row['EmailId'];
$Phone = $row['Phone'];
$Password = $row['Password'];
$Fname = $row['Fname'];
    $to = $EmailId;
//include("../incforgotmail.php");
//include("../sendmailsmtp.php");

$username = $sms_username;
$password = $sms_password;
$type ="TEXT";
$sender=$sms_sender;
$mobile = $Phone;
$message = urlencode("Your Login Details Are");
$message.= urlencode("\n");
$message.= urlencode("Username:");
$message.= urlencode($EmailId." / ".$Phone);
$message.= urlencode("\n");
$message.= urlencode("Password:");
$message.= urlencode($Password);
$message.= urlencode("\n");
$message.= urlencode("Regards,");
$message.= urlencode("\n");
$message.= urlencode("Multifix");
$baseurl ="https://app.indiasms.com/sendsms/bulksms";
$url =$baseurl."?username=".$username."&password=".$password."&type=".$type."&sender=".$sender."&mobile=".$mobile."&message=".$message;
$return = file($url);
echo 1;
  } 
  else{
    echo 0;
  } 
}

if($_POST['action']=='getWalletBal'){
$UserId = $_POST['user_id'];
$Amount = $_POST['Amount'];
$sql11 = "select sum(debit) as debit,sum(credit) as credit from (SELECT (case when Status='Cr' then sum(Amount) else 0 end) as credit,(case when Status='Dr' then sum(Amount) else 0 end) as debit FROM wallet WHERE UserId='$UserId' group by Status) as a";
$res11 = $conn->query($sql11);
$row11 = $res11->fetch_assoc();
$Wallet = $row11['credit'] - $row11['debit'];
if($Amount <= $Wallet){
    echo 1;
    }
else{
    echo 0;
}    
}

if($_POST['action']=='rechargeProcess'){
    $UserId = $_POST['user_id'];
     $optid = $_POST['optid'];
    $RecStateId = $_POST['RecStateId'];
    $MobileNo = $_POST['MobileNo'];
    $Amount = $_POST['Amount'];
    $orderid = $_POST['orderid'];

    $sql11 = "select sum(debit) as debit,sum(credit) as credit from (SELECT (case when Status='Cr' then sum(Amount) else 0 end) as credit,(case when Status='Dr' then sum(Amount) else 0 end) as debit FROM wallet WHERE UserId='$UserId' group by Status) as a";
$res11 = $conn->query($sql11);
$row11 = $res11->fetch_assoc();
$Wallet = $row11['credit'] - $row11['debit'];
if($Amount <= $Wallet){ 

$UserName = "rajatdh07@gmail.com";

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.maharashtrarecharge.in/Recharge/Recharge_Get?UserID=$UserName&Customernumber=$MobileNo&Optcode=$optid&Amount=$Amount&Yourrchid=$orderid&Tokenid=8M6i8PIIravmXIyllQaU7A==&optional1=&optional2=",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache"
    
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

//echo $response;
$character = json_decode($response,true);
$rec_status = $character['Status'];
$rec_msg = $character['Errormsg'];
$recharge_date = date('Y-m-d H:i:s');
$rec_id = $character['RechargeID'];
  $CreatedDate = date('Y-m-d');
  $CreatedTime = date('h:i a');
if($rec_status == 'FAILED' || $rec_status == 'Failed'){
$sql = "INSERT INTO recharge_details SET SkyUserId='$UserId',Status='$rec_status',RechargeDate='$recharge_date',RechId='$rec_id',OperatorId='$optid',MobileNo='$MobileNo',Amount='$Amount',OrderId='$orderid',StatusRes='0',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',roll='1',Response='$rec_msg'";
$conn->query($sql);
$RechId = mysqli_insert_id($conn);
echo json_encode(array('status'=>'0','msg'=>$rec_msg,'rechid'=>$RechId));
}
else if($rec_status == 'Pending' || $rec_status == 'PENDING'){
  $sql = "INSERT INTO recharge_details SET SkyUserId='$UserId',Status='$rec_status',RechargeDate='$recharge_date',RechId='$rec_id',OperatorId='$optid',MobileNo='$MobileNo',Amount='$Amount',OrderId='$orderid',StatusRes='0',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',roll='1',Response='$rec_msg'";
$conn->query($sql);
$RechId = mysqli_insert_id($conn);
$sql22 = "INSERT INTO wallet SET UserId='$UserId',Amount='$Amount',Status='Dr',Reason='Mobile Recharge',RechId='$RechId',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime'";
$conn->query($sql22);
echo json_encode(array('status'=>'4','msg'=>$rec_msg,'rechid'=>$RechId));
  }
 else if($rec_status == 'SUCCESS' || $rec_status == 'Success'){
  $sql = "INSERT INTO recharge_details SET SkyUserId='$UserId',Status='$rec_status',RechargeDate='$recharge_date',RechId='$rec_id',OperatorId='$optid',MobileNo='$MobileNo',Amount='$Amount',OrderId='$orderid',StatusRes='0',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',roll='1',Response='$rec_msg'";
$conn->query($sql);
$RechId = mysqli_insert_id($conn);
$sql22 = "INSERT INTO wallet SET UserId='$UserId',Amount='$Amount',Status='Dr',Reason='Mobile Recharge',RechId='$RechId',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime'";
$conn->query($sql22);
echo json_encode(array('status'=>'1','msg'=>$rec_msg,'rechid'=>$RechId));
  }
else{
  echo json_encode(array('status'=>'2','msg'=>'Due to some reason'));
}
}
else{
  echo json_encode(array('status'=>'3','msg'=>'Insuffiecient Balance in your Wallet'));
}
}


if($_POST['action']=='NewReg'){
$Fname = addslashes(trim($_POST['Fname']));
$Lname = addslashes(trim($_POST['Lname']));
$Phone = $_POST['Phone'];
$EmailId = $_POST['EmailId'];
$Password = $_POST['Password'];
$CountryId = addslashes($_POST['CountryId']);
$StateId = addslashes($_POST['StateId']);
$CityId = addslashes($_POST['CityId']);
$Address = addslashes(trim($_POST['Address']));
$Pincode = trim($_POST['Pincode']);
$CreatedBy = $_POST['CreatedBy'];
$Roll = $_POST['Roll'];
$CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');
 
$sql2 = "SELECT * FROM tbl_users WHERE Phone='$Phone'";
$res2 = $conn->query($sql2);
$row2 = mysqli_num_rows($res2);
if($row2 > 0){
    echo json_encode(array('msg'=>"Your Phone No Already Registered With Us",'status'=>'0'));
}
else{
$sql = "INSERT INTO tbl_users SET Fname='$Fname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Roll='$Roll',CreatedBy='$CreatedBy',CreatedDate='$CreatedDate'";
$conn->query($sql);
$CustId = mysqli_insert_id($conn);
$to = $EmailId;
//include("../inc_register_mail.php");
//include("../sendmailsmtp.php");
$sql3 = "INSERT INTO customer_address SET UserId='$CustId',Fname='$Fname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='1',CreatedDate='$CreatedDate'";
$conn->query($sql3);
if($Roll == 4){
$CustCode = "DOC000";
$AccName = "Doctor";
}
if($Roll == 5){
$CustCode = "OPT000";
$AccName = "Opticians";
}
if($Roll == 6){
$CustCode = "WHOL000";
$AccName = "Wholesalers";
}
if($Roll == 7){
$CustCode = "CUST000";
$AccName = "tbl_users";
}
if($Roll == 8){
$CustCode = "VED000";
$AccName = "Retailer";
}
$CustomerId = $CustCode."".$CustId;
$sql3 = "UPDATE tbl_users SET CustomerId='$CustomerId',AccName='$AccName' WHERE id='$CustId'";
$conn->query($sql3);

     echo json_encode(array('msg'=>"Registration Successfull! Please Waiting For Admin Approval",'status'=>'1'));
}
}

if($_POST['action'] == 'SaveHelpSupport'){
    $Message = addslashes(trim($_POST['Message']));
    $Name = addslashes(trim($_POST['Name']));
    $Phone = addslashes(trim($_POST['Phone']));
    $EmailId = addslashes(trim($_POST['EmailId']));
    $CreatedDate = date('Y-m-d H:i:s');
    
    $sql73 = "INSERT INTO tbl_helps_enquiry SET UserId='$user_id',Name='$Name',Phone='$Phone',EmailId='$EmailId',Message='$Message',CreatedDate='$CreatedDate'";
   $conn->query($sql73);
   $tokenid = mysqli_insert_id($conn);
   $TokenNo = "#".rand(1000,9999)."".$tokenid;
   $sql = "UPDATE tbl_helps_enquiry SET TokenNo='$TokenNo',Status=1 WHERE id='$tokenid'";
   $conn->query($sql);

    $sql73 = "INSERT INTO tbl_help_support_details SET TokenId='$tokenid',TokenNo='$TokenNo',UserId='$user_id',Status='1',Message='$Message',SenderName='Customer',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
   $conn->query($sql73);
   echo 1;
}

if($_POST['action'] == 'ReplyHelpSupport'){
$Message = addslashes(trim($_POST['Message']));
$TokenId = addslashes(trim($_POST['TokenId']));
$TokenNo = $_POST['TokenNo'];
$Status = $_POST['Status'];
$CreatedDate = date('Y-m-d H:i:s');
$sql = "INSERT INTO tbl_help_support_details SET TokenId='$TokenId',TokenNo='$TokenNo',UserId='$user_id',Message='$Message',Status='$Status',SenderName='Customer',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
$conn->query($sql);
echo 1;
}   

if($_POST['action'] == 'DonateCow'){
    $Message = addslashes(trim($_POST['Message']));
    $Name = addslashes(trim($_POST['Name']));
    $Phone = addslashes(trim($_POST['Phone']));
    $EmailId = addslashes(trim($_POST['EmailId']));
    $Price = addslashes(trim($_POST['Price']));
    $CreatedDate = date('Y-m-d H:i:s');
    
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

    $sql73 = "INSERT INTO tbl_donate_cow SET UserId='$user_id',Name='$Name',Phone='$Phone',EmailId='$EmailId',Message='$Message',Price='$Price',Photo='$Photo',CreatedDate='$CreatedDate',Status=0";
   $conn->query($sql73);
   echo 1;
}


if($_POST['action']=='VerifyOtp'){
$YourOtp = addslashes(trim($_POST['YourOtp']));
$Name = addslashes(trim($_POST['Name']));
$Phone = addslashes(trim($_POST['Phone']));
$OldOtp = addslashes(trim($_POST['OldOtp']));
$userid = addslashes(trim($_POST['userid']));
$PageVal = addslashes(trim($_POST['PageVal']));
$CreatedDate = date('Y-m-d');
if($YourOtp == $OldOtp){
if($PageVal == 'index'){
    $sql = "INSERT INTO tbl_popup_enquiry SET SessionId='$sessionid',Name='$Name',EmailId='$EmailId',Phone='$Phone',Subject='$Subject',Message='$Message',CreatedDate='$CreatedDate'";
    $conn->query($sql);
   
}
echo 1;
}
else{
    echo 0;
}
}

if($_POST['action']=='checkCustomer'){
$Phone = $_POST['Phone'];
$sql = "SELECT * FROM tbl_users WHERE Roll=5 AND Status=1 AND Phone='$Phone'";
$rncnt = getRow($sql);
if($rncnt > 0){
$row = getRecord($sql);
echo json_encode(array('Fname'=>$row['Fname'],'id'=>$row['id'],'Status'=>1));
}
else{
   echo json_encode(array('Status'=>0));
}

    }
?>