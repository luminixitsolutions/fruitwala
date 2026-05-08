<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
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
        $q = "select * from tbl_city WHERE StateId = '$StateId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 

if($_POST['action'] == 'getZone'){?>
    <option value="" selected="selected" disabled="">Select Zone</option>
<?php 
    $StateId = $_POST['id'];
        $q = "select * from tbl_zone WHERE CityId = '$StateId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 

if($_POST['action'] == 'getWard'){?>
    <option value="" selected="selected" disabled="">Select Ward No</option>
<?php 
    $StateId = $_POST['id'];
        $q = "select * from tbl_ward WHERE ZoneId = '$StateId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 


if($_POST['action'] == 'getArea'){?>
   <!-- <option value="" selected="selected" disabled="">Select Area</option>-->
<?php 
    $CityId = $_POST['id'];
        $q = "select * from tbl_area WHERE WardId = '$CityId' AND Status='1' ORDER BY Name";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 


if($_POST['action'] == 'getAreaMult'){
       if (!empty($_POST['WardIds'])) {

        $WardIds = array_map('intval', $_POST['WardIds']);
        $WardIdsStr = implode(",", $WardIds);

        $q = "SELECT * FROM tbl_area 
              WHERE WardId IN ($WardIdsStr)
              AND Status='1'
              ORDER BY Name";

        $r = $conn->query($q);

        while ($rw = $r->fetch_assoc()) {
            echo '<option value="'.$rw['id'].'">'.$rw['Name'].'</option>';
        }
    }
}
    



if($_POST['action'] == 'getCityArea'){?>
   <!-- <option value="" selected="selected" disabled="">Select Area</option>-->
<?php 
    $CityId = $_POST['id'];
        $q = "select * from tbl_area WHERE CityId = '$CityId' AND Status='1' ORDER BY Name";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 


if($_POST['action'] == 'getCourse'){?>
    <option value="" selected="selected" disabled="">Select Course</option>
<?php 
    $DeptId = $_POST['id'];
        $q = "select * from tbl_courses WHERE DeptId = '$DeptId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }

if($_POST['action'] == 'getBatch'){?>
    <option value="" selected="selected" disabled="">Select Batch</option>
<?php 
    $CourseId = $_POST['id'];
        //$q = "select * from tbl_batches WHERE CourseId = '$CourseId' AND Status='1'";
    $q = "select * from tbl_batches WHERE DeptId = '$CourseId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['BatchName']." (".$rw['Month']." - ".$rw['Year'].")"; ?></option>
<?php } }

if($_POST['action'] == 'getStudent'){?>
    <option value="" selected="selected" disabled="">Select Student</option>
<?php 
    $BatchId = $_POST['id'];
        $q = "select * from tbl_users WHERE BatchId = '$BatchId' AND Status='1' AND Roll=2";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']." ".$rw['Lname']; ?></option>
<?php } }

if($_POST['action'] == 'getSubject'){?>
    <option value="" selected="selected" disabled="">Select Subject</option>
<?php 
    $DeptId = $_POST['id'];
        $q = "select * from tbl_subjects WHERE DeptId = '$DeptId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }

if($_POST['action'] == 'getTsSubject'){?>
    <option value="" selected="selected" disabled="">Select Subject</option>
<?php 
    $DeptId = $_POST['id'];
        $q = "select * from tbl_ts_allocate_subjects WHERE TestSeriesId = '$DeptId'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['SubjectId']; ?>"><?php echo $rw['Subjects']." (Ques : ".$rw['TotQues'].")"; ?></option>
<?php } }

if($_POST['action'] == 'getSubTopic'){?>
    <option value="" selected="selected" disabled="">Select Sub Topic</option>
<?php 
    $SubjectId = $_POST['id'];
        $q = "select * from tbl_sub_topics WHERE SubjectId = '$SubjectId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }

if($_POST['action'] == 'getCourseDetails'){
 $id = $_POST['id'];
    $query = "SELECT Type,Duration,CourseFees FROM tbl_courses WHERE id = '$id'";
    $row = getRecord($query);
    echo json_encode($row);
}

if($_POST['action'] == 'getBatchDetails'){
 $id = $_POST['id'];
    $query = "SELECT StartDate,BatchTime,Strength FROM tbl_batches WHERE id = '$id'";
    $row = getRecord($query);

    $sql = "SELECT * FROM tbl_users WHERE BatchId='$id' AND Roll=2";
    $rncnt = getRow($sql);

    $Seats = $row['Strength'] - $rncnt;

    echo json_encode(array('StartDate'=> $row['StartDate'],'BatchTime'=> $row['BatchTime'],'Seats'=> $Seats));
}

if($_POST['action'] == 'getStudentDetails'){
 $StudId = $_POST['id'];
 $DeptId = $_POST['DeptId'];
 $CourseId = $_POST['CourseId'];
 $BatchId = $_POST['BatchId'];
    $query = "SELECT CourseFees FROM tbl_users WHERE id = '$StudId'";
    $row = getRecord($query);

    $sql = "SELECT SUM(Amount) As Amount FROM tbl_general_ledger WHERE CustId='$StudId' AND Type='PR' AND DeptId='$DeptId' AND CourseId='$CourseId' AND BatchId='$BatchId' AND CrDr='dr'";
    $row2 = getRecord($sql);

    $BalAmt = $row['CourseFees'] - $row2['Amount'];

    echo json_encode(array('CourseFees'=> $row['CourseFees'],'BalAmt'=> $BalAmt));
}

if($_POST['action'] == 'getTestSeries'){?>
    <option value="" selected="selected" disabled="">Select Test Series</option>
<?php 
    $CourseId = $_POST['id'];
    //$DeptId = $_POST['id'];
        $q = "select * from tbl_test_series WHERE Status='1'";
        if($DeptId!=''){
      $q.= " AND DeptId='$DeptId'";
      }
      if($CourseId!=''){
      $q.= " AND CourseId = '$CourseId'";
        }
        //echo $q;
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['TestName']; ?></option>
<?php } }

if($_POST['action'] == 'getMulSubjects'){
    $deptid = $_POST['id'];
      $sql22 = "SELECT * FROM tbl_subjects WHERE Status=1 AND DeptId='$deptid'";
      /*if($deptid!=''){
      $sql22.= " AND DeptId='$deptid'";
      }
      if($courseid!=''){
      $sql22.= " AND CourseId='$courseid'";  
      }
      if($subjectid!=''){
      $sql22.= " AND SubjectId='$subjectid'";  
      }
      if($subtopicid!=''){
      $sql22.= " AND SubTopicId='$subtopicid'";  
      }*/
      //echo $sql22;
      $row22 = getList($sql22);
      foreach($row22 as $result22){
  ?>
  <div class="col-lg-4">
    <div class="form-group">
      <div class="custom-control custom-checkbox custom-control-inline">
        <input type="checkbox" id="m<?php echo $result22['id']; ?>" name="Subjects[]" class="custom-control-input advisior" value="<?php echo $result22['id']; ?>" > <label class="custom-control-label" for="m<?php echo $result22['id']; ?>"><?php echo $result22['Name']; ?></label></div>
  </div></div>
<?php }}


if($_POST['action'] == 'getSubMenu'){?>
    <option value="" selected="selected" >Select Sub Menu</option>
<?php 
    $MenuId = $_POST['id'];
        $q = "select * from tbl_pages WHERE MenuId = '$MenuId'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Title']; ?></option>
<?php } } 

if($_POST['action'] == 'getSubMenu2'){?>
    <option value="" selected="selected">Select Sub Sub Menu</option>
<?php 
    $SubId = $_POST['id'];
    $MenuId = $_POST['MenuId'];
        $q = "select * from tbl_sub_sub_menu WHERE MenuId = '$MenuId' AND SubId = '$SubId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 


if($_POST['action'] == 'checkCourse'){
$CourseId = $_POST['id'];
$sql = "SELECT Type FROM tbl_courses WHERE id='$CourseId'";
$row = getRecord($sql);
$Type = $row['Type'];
if($Type == 'Paid'){
echo 1;
}
else{
echo 0;
}

    }
    
 if($_POST['action'] == 'getSubjectRoll'){
     $testid = $_POST['id'];
     $sql = "SELECT * FROM tbl_test_series WHERE id='$testid'";
     $row = getRecord($sql);
     $SubjectRoll = $row['SubjectRoll'];
     echo $SubjectRoll;
 }


if($_POST['action'] == 'getTsCategory'){?>
    <option value="" selected="selected" disabled="">Select Category</option>
<?php 
    $DeptId = $_POST['id'];
        $q = "select * from tbl_ts_category WHERE DeptId = '$DeptId'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
<option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 
  
  if($_POST['action'] == 'getTsGroup'){?>
    <option value="" selected="selected" disabled="">Select Group</option>
<?php 
    $CatId = $_POST['id'];
     $q = "select tg.*,tt.Name from tbl_allocate_group_category tg INNER JOIN tbl_ts_type tt ON tg.GroupId=tt.id WHERE tg.CatId = '$CatId'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
<option value="<?php echo $rw['GroupId']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }   
?>