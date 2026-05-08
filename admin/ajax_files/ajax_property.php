<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'Add'){
$UserType = $_POST['UserType'];   
$UserId = $_POST['UserId']; 
$Fname = addslashes(trim($_POST['Fname']));    
$Lname = addslashes(trim($_POST['Lname']));
$Phone = addslashes(trim($_POST['Phone']));
$Phone2 = addslashes(trim($_POST['Phone2']));
$EmailId = addslashes(trim($_POST['EmailId']));
$StateId = $_POST['StateId'];
$CityId = $_POST['CityId'];
$Pincode = addslashes(trim($_POST['Pincode']));
$Address = addslashes(trim($_POST['Address']));
$PropType = addslashes(trim($_POST['PropType']));
$PropFor = addslashes(trim($_POST['PropFor']));
$PropertyName = addslashes(trim($_POST['PropertyName']));
$YearBuilt = addslashes(trim($_POST['YearBuilt']));
$PropDetails = addslashes(trim($_POST['PropDetails']));
$PropStateId = addslashes(trim($_POST['PropStateId']));
$PropCityId = addslashes(trim($_POST['PropCityId']));
$PropAreaId = addslashes(trim($_POST['PropAreaId']));
$PropPincode = addslashes(trim($_POST['PropPincode']));
$PropAddress = addslashes(trim($_POST['PropAddress']));
$CommCat = addslashes(trim($_POST['CommCat']));
$PlotArea = addslashes(trim($_POST['PlotArea']));
$Bedrooms = addslashes(trim($_POST['Bedrooms']));
$Bathrooms = addslashes(trim($_POST['Bathrooms']));
$Balcony = addslashes(trim($_POST['Balcony']));
$Parking = addslashes(trim($_POST['Parking']));
$Floors = addslashes(trim($_POST['Floors']));
$PropFloor = addslashes(trim($_POST['PropFloor']));
$SuperBuiltArea = addslashes(trim($_POST['SuperBuiltArea']));
$BuiltUpArea = addslashes(trim($_POST['BuiltUpArea']));
$CarpetArea = addslashes(trim($_POST['CarpetArea']));
$PropPrice = addslashes(trim($_POST['PropPrice']));
$Youtube = addslashes(trim($_POST['Youtube']));
$PropMapCode = addslashes(trim($_POST['PropMapCode']));
$NewDeals = addslashes(trim($_POST['NewDeals']));
$Auction = addslashes(trim($_POST['Auction']));
$NewProject = addslashes(trim($_POST['NewProject']));
$FeaturedProperty = addslashes(trim($_POST['FeaturedProperty']));
$Amenities = implode(",",$_POST['Amenities']);
$Status = $_POST['Status'];
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

$randno2 = rand(1,100);
$src2 = $_FILES['LocPhoto']['tmp_name'];
$fnm2 = substr($_FILES["LocPhoto"]["name"], 0,strrpos($_FILES["LocPhoto"]["name"],'.')); 
$fnm2 = str_replace(" ","_",$fnm2);
$ext2 = substr($_FILES["LocPhoto"]["name"],strpos($_FILES["LocPhoto"]["name"],"."));
$dest2 = '../../uploads/'. $randno2 . "_".$fnm2 . $ext2;
$imagepath2 =  $randno2 . "_".$fnm2 . $ext2;
if(move_uploaded_file($src2, $dest2))
{
$LocPhoto = $imagepath2 ;
} 
else{
  $LocPhoto = $_POST['OldLocPhoto'];
}

$randno3 = rand(1,100);
$src3 = $_FILES['FloorPlan']['tmp_name'];
$fnm3 = substr($_FILES["FloorPlan"]["name"], 0,strrpos($_FILES["FloorPlan"]["name"],'.')); 
$fnm3 = str_replace(" ","_",$fnm3);
$ext3 = substr($_FILES["FloorPlan"]["name"],strpos($_FILES["FloorPlan"]["name"],"."));
$dest3 = '../../uploads/'. $randno3 . "_".$fnm3 . $ext3;
$imagepath3 =  $randno3 . "_".$fnm3 . $ext3;
if(move_uploaded_file($src3, $dest3))
{
$FloorPlan = $imagepath3 ;
} 
else{
  $FloorPlan = $_POST['OldFloorPlan'];
}

$randno4 = rand(1,100);
$src4 = $_FILES['Broucher']['tmp_name'];
$fnm4 = substr($_FILES["Broucher"]["name"], 0,strrpos($_FILES["Broucher"]["name"],'.')); 
$fnm4 = str_replace(" ","_",$fnm4);
$ext4 = substr($_FILES["Broucher"]["name"],strpos($_FILES["Broucher"]["name"],"."));
$dest4 = '../../uploads/'. $randno4 . "_".$fnm4 . $ext4;
$imagepath4 =  $randno4 . "_".$fnm4 . $ext4;
if(move_uploaded_file($src4, $dest4))
{
$Broucher = $imagepath4 ;
} 
else{
  $Broucher = $_POST['OldBroucher'];
}

$sql = "INSERT INTO tbl_property SET UserType='$UserType',UserId='$UserId',Fname='$Fname',Lname='$Lname',Phone='$Phone',Phone2='$Phone2',EmailId='$EmailId',StateId='$StateId',CityId='$CityId',Pincode='$Pincode',Address='$Address',PropType='$PropType',PropFor='$PropFor',PropertyName='$PropertyName',YearBuilt='$YearBuilt',PropDetails='$PropDetails',PropStateId='$PropStateId',PropCityId='$PropCityId',PropAreaId='$PropAreaId',PropPincode='$PropPincode',PropAddress='$PropAddress',CommCat='$CommCat',PlotArea='$PlotArea',Bedrooms='$Bedrooms',Bathrooms='$Bathrooms',Balcony='$Balcony',Parking='$Parking',Floors='$Floors',PropFloor='$PropFloor',SuperBuiltArea='$SuperBuiltArea',BuiltUpArea='$BuiltUpArea',CarpetArea='$CarpetArea',PropPrice='$PropPrice',Youtube='$Youtube',PropMapCode='$PropMapCode',NewDeals='$NewDeals',Auction='$Auction',NewProject='$NewProject',FeaturedProperty='$FeaturedProperty',Amenities='$Amenities',Photo='$Photo',LocPhoto='$LocPhoto',FloorPlan='$FloorPlan',Broucher='$Broucher',Status='$Status',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
$conn->query($sql);
$ProdId = mysqli_insert_id($conn);

 if (isset($_FILES['Files'])) {
    $errors = array();
    foreach ($_FILES['Files']['tmp_name'] as $key => $tmp_name) {
        $file_name = $key . $_FILES['Files']['name'][$key];
        $file_size = $_FILES['Files']['size'][$key];
        $file_tmp = $_FILES['Files']['tmp_name'][$key];
        $file_type = $_FILES['Files']['type'][$key];
        $FileName = $_FILES['Files']['name'][$key];
        
        if ($file_size > 2097152) {
            $errors[] = 'File size must be less than 2 MB';
        }
        if ($file_name == '0' || $file_size == '0') {} else {
             $query = "INSERT into tbl_property_images SET PropertyId='$ProdId',Files='$file_name',FileName='$FileName'";
            $desired_dir = "../../uploads/";
            if (empty($errors) == true) {
                if (is_dir($desired_dir) == false) {
                    mkdir("$desired_dir", 0700); // Create directory if it does not exist
                }
                if (is_dir("$desired_dir/" . $file_name) == false) {
                    move_uploaded_file($file_tmp, "../../uploads/" . $file_name);
                } else {
                    // rename the file if another one exist
                    $new_dir = "../../uploads/" . $file_name . time();
                    rename($file_tmp, $new_dir);
                }
                $conn->query($query);
            } else {
                print_r($errors);
            }
        }
        if (empty($error)) {
           
           
        }
    }
}

?>
<script type="text/javascript">
    alert("New Property Added Successfully!");
    window.location.href="../view-properties.php";
</script>
<?php
}


if($_POST['action'] == 'Edit'){
    $id = $_POST['id'];
$UserType = $_POST['UserType'];  
$UserId = $_POST['UserId'];   
$Fname = addslashes(trim($_POST['Fname']));    
$Lname = addslashes(trim($_POST['Lname']));
$Phone = addslashes(trim($_POST['Phone']));
$Phone2 = addslashes(trim($_POST['Phone2']));
$EmailId = addslashes(trim($_POST['EmailId']));
$StateId = $_POST['StateId'];
$CityId = $_POST['CityId'];
$Pincode = addslashes(trim($_POST['Pincode']));
$Address = addslashes(trim($_POST['Address']));
$PropType = addslashes(trim($_POST['PropType']));
$PropFor = addslashes(trim($_POST['PropFor']));
$PropertyName = addslashes(trim($_POST['PropertyName']));
$YearBuilt = addslashes(trim($_POST['YearBuilt']));
$PropDetails = addslashes(trim($_POST['PropDetails']));
//$PropDetails = mysqli_real_escape_string($conn,$_POST['PropDetails']);
$PropStateId = addslashes(trim($_POST['PropStateId']));
$PropCityId = addslashes(trim($_POST['PropCityId']));
$PropAreaId = addslashes(trim($_POST['PropAreaId']));
$PropPincode = addslashes(trim($_POST['PropPincode']));
$PropAddress = addslashes(trim($_POST['PropAddress']));
$CommCat = addslashes(trim($_POST['CommCat']));
$PlotArea = addslashes(trim($_POST['PlotArea']));
$Bedrooms = addslashes(trim($_POST['Bedrooms']));
$Bathrooms = addslashes(trim($_POST['Bathrooms']));
$Balcony = addslashes(trim($_POST['Balcony']));
$Parking = addslashes(trim($_POST['Parking']));
$Floors = addslashes(trim($_POST['Floors']));
$PropFloor = addslashes(trim($_POST['PropFloor']));
$SuperBuiltArea = addslashes(trim($_POST['SuperBuiltArea']));
$BuiltUpArea = addslashes(trim($_POST['BuiltUpArea']));
$CarpetArea = addslashes(trim($_POST['CarpetArea']));
$PropPrice = addslashes(trim($_POST['PropPrice']));
$Youtube = addslashes(trim($_POST['Youtube']));
$PropMapCode = addslashes(trim($_POST['PropMapCode']));
$NewDeals = addslashes(trim($_POST['NewDeals']));
$Auction = addslashes(trim($_POST['Auction']));
$NewProject = addslashes(trim($_POST['NewProject']));
$FeaturedProperty = addslashes(trim($_POST['FeaturedProperty']));
$Amenities = implode(",",$_POST['Amenities']);
$Status = $_POST['Status'];
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

$randno2 = rand(1,100);
$src2 = $_FILES['LocPhoto']['tmp_name'];
$fnm2 = substr($_FILES["LocPhoto"]["name"], 0,strrpos($_FILES["LocPhoto"]["name"],'.')); 
$fnm2 = str_replace(" ","_",$fnm2);
$ext2 = substr($_FILES["LocPhoto"]["name"],strpos($_FILES["LocPhoto"]["name"],"."));
$dest2 = '../../uploads/'. $randno2 . "_".$fnm2 . $ext2;
$imagepath2 =  $randno2 . "_".$fnm2 . $ext2;
if(move_uploaded_file($src2, $dest2))
{
$LocPhoto = $imagepath2 ;
} 
else{
  $LocPhoto = $_POST['OldLocPhoto'];
}

$randno3 = rand(1,100);
$src3 = $_FILES['FloorPlan']['tmp_name'];
$fnm3 = substr($_FILES["FloorPlan"]["name"], 0,strrpos($_FILES["FloorPlan"]["name"],'.')); 
$fnm3 = str_replace(" ","_",$fnm3);
$ext3 = substr($_FILES["FloorPlan"]["name"],strpos($_FILES["FloorPlan"]["name"],"."));
$dest3 = '../../uploads/'. $randno3 . "_".$fnm3 . $ext3;
$imagepath3 =  $randno3 . "_".$fnm3 . $ext3;
if(move_uploaded_file($src3, $dest3))
{
$FloorPlan = $imagepath3 ;
} 
else{
  $FloorPlan = $_POST['OldFloorPlan'];
}

$randno4 = rand(1,100);
$src4 = $_FILES['Broucher']['tmp_name'];
$fnm4 = substr($_FILES["Broucher"]["name"], 0,strrpos($_FILES["Broucher"]["name"],'.')); 
$fnm4 = str_replace(" ","_",$fnm4);
$ext4 = substr($_FILES["Broucher"]["name"],strpos($_FILES["Broucher"]["name"],"."));
$dest4 = '../../uploads/'. $randno4 . "_".$fnm4 . $ext4;
$imagepath4 =  $randno4 . "_".$fnm4 . $ext4;
if(move_uploaded_file($src4, $dest4))
{
$Broucher = $imagepath4 ;
} 
else{
  $Broucher = $_POST['OldBroucher'];
}


$sql = "UPDATE tbl_property SET UserType='$UserType',UserId='$UserId',Fname='$Fname',Lname='$Lname',Phone='$Phone',Phone2='$Phone2',EmailId='$EmailId',StateId='$StateId',CityId='$CityId',Pincode='$Pincode',Address='$Address',PropType='$PropType',PropFor='$PropFor',PropertyName='$PropertyName',YearBuilt='$YearBuilt',PropDetails='$PropDetails',PropStateId='$PropStateId',PropCityId='$PropCityId',PropAreaId='$PropAreaId',PropPincode='$PropPincode',PropAddress='$PropAddress',CommCat='$CommCat',PlotArea='$PlotArea',Bedrooms='$Bedrooms',Bathrooms='$Bathrooms',Balcony='$Balcony',Parking='$Parking',Floors='$Floors',PropFloor='$PropFloor',SuperBuiltArea='$SuperBuiltArea',BuiltUpArea='$BuiltUpArea',CarpetArea='$CarpetArea',PropPrice='$PropPrice',Youtube='$Youtube',PropMapCode='$PropMapCode',NewDeals='$NewDeals',Auction='$Auction',NewProject='$NewProject',FeaturedProperty='$FeaturedProperty',Amenities='$Amenities',Photo='$Photo',LocPhoto='$LocPhoto',FloorPlan='$FloorPlan',Broucher='$Broucher',Status='$Status',ModifiedBy='$user_id',ModifiedDate='$CreatedDate' WHERE id='$id'";
$conn->query($sql);
$ProdId = $_POST['id'];

 if (isset($_FILES['Files'])) {
    $errors = array();
    foreach ($_FILES['Files']['tmp_name'] as $key => $tmp_name) {
        $file_name = $key . $_FILES['Files']['name'][$key];
        $file_size = $_FILES['Files']['size'][$key];
        $file_tmp = $_FILES['Files']['tmp_name'][$key];
        $file_type = $_FILES['Files']['type'][$key];
        $FileName = $_FILES['Files']['name'][$key];
        
        if ($file_size > 2097152) {
            $errors[] = 'File size must be less than 2 MB';
        }
        if ($file_name == '0' || $file_size == '0') {} else {
             $query = "INSERT into tbl_property_images SET PropertyId='$ProdId',Files='$file_name',FileName='$FileName'";
            $desired_dir = "../../uploads/";
            if (empty($errors) == true) {
                if (is_dir($desired_dir) == false) {
                    mkdir("$desired_dir", 0700); // Create directory if it does not exist
                }
                if (is_dir("$desired_dir/" . $file_name) == false) {
                    move_uploaded_file($file_tmp, "../../uploads/" . $file_name);
                } else {
                    // rename the file if another one exist
                    $new_dir = "../../uploads/" . $file_name . time();
                    rename($file_tmp, $new_dir);
                }
                $conn->query($query);
            } else {
                print_r($errors);
            }
        }
        if (empty($error)) {
           
           
        }
    }
}
?>
<script type="text/javascript">
    alert("Property Update Successfully!");
    window.location.href="../view-properties.php";
</script>
<?php
}

if($_POST['action'] == 'deletePhoto'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_property SET Photo='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Property Photo Delete Successfully";
} 

if($_POST['action'] == 'deleteLocPhoto'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_property SET LocPhoto='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Location Photo Delete Successfully";
}

if($_POST['action'] == 'deleteFloorPlan'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_property SET FloorPlan='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Floor Plan Delete Successfully";
}

if($_POST['action'] == 'deleteBroucher'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_property SET Broucher='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Broucher Delete Successfully";
}
if($_POST['action'] == 'deletePhoto2'){
    $id = $_POST['id'];
    $pid = $_POST['pid'];
    $Photo = $_POST['Photo'];
        $q = "DELETE FROM tbl_property_images WHERE id=$id AND PropertyId='$pid'";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Property Photo Delete Successfully";
}

if($_POST['action'] == 'showProdImages'){ 
    $id = $_POST['id'];
  $sql2 = "SELECT * FROM tbl_property_images WHERE PropertyId='$id'";
  $res2 = $conn->query($sql2);
  $rncnt = mysqli_num_rows($res2);
  if($rncnt > 0){
    while($row2 = $res2->fetch_assoc()){?>
    <input type="hidden" name="OldMulImage" id="OldMulImage<?php echo $row2["id"]; ?>" value="<?php echo $row2["Files"]; ?>">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" onclick="delete_photo2(<?php echo $row2["id"]; ?>,<?php echo $_POST["id"]; ?>)"></a><img src="../uploads/<?php echo $row2['Files'];?>" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>
<?php }} }?>






