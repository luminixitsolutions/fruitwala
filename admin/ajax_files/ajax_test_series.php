<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'deletePhoto') {
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
    $query = "UPDATE tbl_test_series SET Photo='' WHERE id=$id";
    $conn->query($query);
    $src = "../../uploads/$Photo";
    unlink($src);
    echo "Photo Delete Successfully";

  }

if($_POST['action'] == 'deleteDoc'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_test_series SET File='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Document Delete Successfully";
}

if($_POST['action'] == 'showTestSeries'){
    $deptid = $_POST['deptid'];
    $batchid = $_POST['batchid'];
    $CatId = $_POST['CatId'];
       $sql22 = "select tg.*,tt.Name from tbl_allocate_group_category tg INNER JOIN tbl_ts_type tt ON tg.GroupId=tt.id WHERE tg.DeptId='$deptid' AND tg.CatId='$CatId'";
      $row22 = getList($sql22);
      foreach($row22 as $result22){

        $sql7 = "SELECT GROUP_CONCAT(TestSeriesId) AS TestSeriesId FROM tbl_allocate_test_series WHERE DeptId='$deptid' AND BatchId='$batchid' AND CatId='$CatId'";
        $row7 = getRecord($sql7);
        $row7['TestSeriesId'] = explode(',', $row7['TestSeriesId']);
  ?>
  <div class="col-lg-4">
    <div class="form-group">
      <div class="custom-control custom-checkbox custom-control-inline">
        <input type="checkbox" id="m<?php echo $result22['GroupId']; ?>" name="TestSeriesId[]" class="custom-control-input advisior" value="<?php echo $result22['GroupId']; ?>" <?php if(in_array($result22["GroupId"],$row7['TestSeriesId'])) { ?>checked="checked" <?php } ?>> <label class="custom-control-label" for="m<?php echo $result22['GroupId']; ?>"><?php echo $result22['Name']; ?></label></div>
  </div></div>
<?php }} 


if($_POST['action'] == 'showGroupTestSeries'){
    $GroupId = $_POST['GroupId'];
    $DeptId = $_POST['DeptId'];
    $CatId = $_POST['CatId'];
      $sql22 = "SELECT * FROM tbl_test_series WHERE Status=1";
      $row22 = getList($sql22);
      foreach($row22 as $result22){

        $sql7 = "SELECT GROUP_CONCAT(TestSeriesId) AS TestSeriesId FROM tbl_allocate_ts_groups WHERE GroupId='$GroupId' AND DeptId='$DeptId' AND CatId='$CatId'";
        $row7 = getRecord($sql7);
        $row7['TestSeriesId'] = explode(',', $row7['TestSeriesId']);
  ?>
  <div class="col-lg-4">
    <div class="form-group">
      <div class="custom-control custom-checkbox custom-control-inline">
        <input type="checkbox" id="m<?php echo $result22['id']; ?>" name="TestSeriesId[]" class="custom-control-input advisior" value="<?php echo $result22['id']; ?>" <?php if(in_array($result22["id"],$row7['TestSeriesId'])) { ?>checked="checked" <?php } ?>> <label class="custom-control-label" for="m<?php echo $result22['id']; ?>"><?php echo $result22['TestName']; ?></label></div>
  </div></div>
<?php }} 

if($_POST['action'] == 'showtestsubjects'){
$sql = "SELECT * FROM tbl_ts_subjects WHERE DeptId='".$_POST['deptid']."'";
$row = getList($sql);
foreach($row as $result){
  ?>
 
  <div class="form-group col-lg-2" style="padding-top: 25px;padding-left: 100px;">
      <div class="custom-control custom-checkbox custom-control-inline">
        <input type="checkbox" id="sbchk<?php echo $result['id']; ?>" name="SubjectsChk[]" class="custom-control-input advisior" value="<?php echo $result['id']; ?>" onclick="featured(<?php echo $result['id']; ?>)"><label class="custom-control-label" for="sbchk<?php echo $result['id']; ?>">&nbsp;</label></div>
  </div>

<input type="hidden" id="chkvalue<?php echo $result['id']; ?>" name="chkvalue[]" value="0">
<input type="hidden" name="SubjectId[]" value="<?php echo $result['id']; ?>">
<div class="form-group col-lg-5">
<label class="form-label">Subject </label>
<input type="text" class="form-control" name="Subjects[]" value="<?php echo $result['Name']; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-5">
<label class="form-label">Total Ques </label>
<input type="number" name="Tot_Ques[]" class="form-control" id="TotQues" value="" min="1">
<div class="clearfix"></div>
</div>

<?php } }

if($_POST['action'] == 'showGroupCategory'){
    $CatId = $_POST['CatId'];
    $DeptId = $_POST['DeptId'];
      $sql22 = "SELECT * FROM tbl_ts_type WHERE Status=1 AND DeptId='$DeptId'";
      $row22 = getList($sql22);
      foreach($row22 as $result22){

        $sql7 = "SELECT GROUP_CONCAT(GroupId) AS GroupId FROM tbl_allocate_group_category WHERE DeptId='$DeptId' AND CatId='$CatId'";
        $row7 = getRecord($sql7);
        $row7['GroupId'] = explode(',', $row7['GroupId']);
  ?>
  <div class="col-lg-4">
    <div class="form-group">
      <div class="custom-control custom-checkbox custom-control-inline">
        <input type="checkbox" id="m<?php echo $result22['id']; ?>" name="GroupId[]" class="custom-control-input advisior" value="<?php echo $result22['id']; ?>" <?php if(in_array($result22["id"],$row7['GroupId'])) { ?>checked="checked" <?php } ?>> <label class="custom-control-label" for="m<?php echo $result22['id']; ?>"><?php echo $result22['Name']; ?></label></div>
  </div></div>
<?php }} 
?>