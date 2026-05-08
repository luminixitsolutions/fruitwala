<?php 
session_start();
include_once '../config.php';
if($_POST['action'] == 'getState'){?>
	<option value="" selected="selected" disabled="">Select State</option>
<?php 
	$CountryId = $_POST['id'];
        $q = "select * from tbl_state WHERE CountryId = '$CountryId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 

if($_POST['action'] == 'getCity'){?>
	<option value="" selected="selected" disabled="">Select City</option>
<?php 
	$StateId = $_POST['id'];
        $q = "select * from tbl_city WHERE StateId = '$StateId' AND Status='1' ORDER BY Name";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
<option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 

if($_POST['action'] == 'getFuelCity'){?>
	<option value="" selected="selected" disabled="">Select District</option>
<?php 
	$StateId = $_POST['id'];
        $q = "select * from tbl_fuel_city WHERE StateId = '$StateId' AND Status='1' ORDER BY Name";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
<option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } ?>
<option value="Other">Other</option>

<?php } 

if($_POST['action'] == 'getArea'){?>
    <option value="" selected="selected" disabled="">Select Area</option>
<?php 
    $CityId = $_POST['id'];
        $q = "select * from tbl_area WHERE CityId = '$CityId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
<option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }

if($_POST['action'] == 'saveLatlng'){
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $_SESSION['lat'] = $lat;
    $_SESSION['lng'] = $lng;
    echo "saved";
}   

if($_POST['action'] == 'checkSponserId'){
    $SponserId = $_POST['SponserId'];
    $sql = "SELECT * FROM customers WHERE (CustomerId='$SponserId' OR Phone='$SponserId') AND Roll=7 AND Status=1";
    $rncnt = getRow($sql);
    if($rncnt > 0){
    $row = getRecord($sql);
    $id = $row['id'];
    $MemberName = $row['Fname']." ".$row['Lname'];
    echo json_encode(array('status'=>1,'name'=>$MemberName,'id'=>$id));  
        //echo 1;//Member id exist
    }
    else{
         echo json_encode(array('status'=>0,'msg'=>'Sponsor id does not exist')); 
        //echo 0;//Member id not exist
    }
}   

if($_POST['action'] == 'getUserDetails'){
    $id = $_POST['id'];
    $sql = "SELECT tu.* FROM tbl_users tu WHERE tu.id='$id'";
    $row = getRecord($sql);
    echo json_encode($row);
    }
    
     if($_POST['action'] == 'getUserDetails2'){
    $CellNo = $_POST['CellNo'];
    $sql = "SELECT tu.* FROM tbl_users tu WHERE tu.Phone='$CellNo' AND tu.Roll=55";
    $rncnt = getRow($sql);
    if($rncnt > 0){
    $row = getRecord($sql);
    echo json_encode($row);
    }
    else{
        echo 0;
    }
    } 
    
    if($_POST['action'] == 'getVedList'){
        
        $VedName = $_POST['VedName'];
    $data = [];

    $q = "SELECT * FROM tbl_users WHERE Status=1 AND Roll=3 AND (Fname LIKE '%$VedName%' OR TradeName LIKE '%$VedName%')";
    $r = $conn->query($q);

   while ($rw = $r->fetch_assoc()) {
        $data[] = [
            'id' => $rw['id'],
            'Fname' => $rw['Fname'],
            'TradeName' => $rw['TradeName'],
            'Phone' => $rw['Phone']
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
    } 
    
    if($_POST['action'] == 'getFrLocationList'){
        
        $FrLocations = $_POST['FrLocations'];
    $data = [];

    $q = "SELECT * FROM tbl_users_bill WHERE Status=1 AND Roll=5 AND ShopName!='' AND ShopName LIKE '%$FrLocations%' ORDER BY ShopName ";
    $r = $conn->query($q);

   while ($rw = $r->fetch_assoc()) {
        $data[] = [
            'id' => $rw['id'],
            'ShopName' => $rw['ShopName'],
            'Phone' => $rw['Phone']
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
    } 
    
    
    if($_POST['action'] == 'getEmpLocationList'){
        
        $FrLocations = $_POST['EmployeeName'];
    $data = [];

    $q = "SELECT * FROM tbl_users WHERE Status='1' AND Roll NOT IN(1,5,55,9,22,23,63) AND Fname!='' AND Fname LIKE '%$FrLocations%' ORDER BY Fname ";
    $r = $conn->query($q);

   while ($rw = $r->fetch_assoc()) {
        $data[] = [
            'id' => $rw['id'],
            'Fname' => $rw['Fname'],
            'Phone' => $rw['Phone']
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
    }
    
     if($_POST['action'] == 'getNewFrLocationList'){
        
        $FrLocations = $_POST['FrLocations'];
    $data = [];

    $q = "SELECT * FROM tbl_users_bill WHERE Status=1 AND Roll=5 AND ShopName!=''  AND ShopName LIKE '%$FrLocations%' ORDER BY ShopName ";
    $r = $conn->query($q);

   while ($rw = $r->fetch_assoc()) {
        $data[] = [
            'id' => $rw['id'],
            'ShopName' => $rw['ShopName'],
            'Phone' => $rw['Phone']
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
    } 
    
    
    if($_POST['action'] == 'getProductList'){
        
        $ProductName = $_POST['ProductName'];
        $FrId = $_POST['FrId'];
        $sql110 = "SELECT AllocateRawProd FROM tbl_users WHERE id='$FrId'";
        $row110 = getRecord($sql110);
        $AllocateRawProd = $row110['AllocateRawProd'];
    $data = [];
    /*if($AllocateRawProd!=''){
    $q = "SELECT ProdId AS id,ProductName,Unit FROM tbl_cust_products_2025 WHERE Status='1' AND CreatedBy='$FrId' AND ProdType=0 AND ProdType2 IN (1,3) 
    AND checkstatus=1 AND ProductName LIKE '%$ProductName%' UNION ALL SELECT id,ProductName,Unit FROM tbl_cust_products2 WHERE id IN ($AllocateRawProd) AND ProductName LIKE '%$ProductName%' ORDER BY ProductName ";
    }
    else{
        $q = "SELECT ProdId AS id,ProductName,Unit FROM tbl_cust_products_2025 WHERE Status='1' AND CreatedBy='$FrId' AND ProdType=0 AND ProdType2 IN (1,3) 
    AND checkstatus=1 AND ProductName LIKE '%$ProductName%' ORDER BY ProductName";
    }*/
    
     $q = "SELECT id,ProductName,Unit FROM tbl_cust_products_2025 WHERE Status='1' AND CreatedBy='$FrId' AND ProdType2!=2 AND checkstatus=1 AND ProductName LIKE '%$ProductName%' ORDER BY ProductName"; 
    $r = $conn->query($q);

   while ($rw = $r->fetch_assoc()) {
        $data[] = [
            'id' => $rw['id'],
            'ProductName' => $rw['ProductName']
           
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
    } 
?>