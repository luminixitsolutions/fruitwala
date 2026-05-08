<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'deletePhoto') {
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
    $query = "UPDATE tbl_ques_papers SET Photo='' WHERE id=$id";
    $conn->query($query);
    $src = "../../uploads/$Photo";
    unlink($src);
    echo "Photo Delete Successfully";

  }

if($_POST['action'] == 'deleteDoc'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_ques_papers SET File='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Document Delete Successfully";
}


if($_POST['action'] == 'showMaterial'){
    $deptid = $_POST['deptid'];
    $batchid = $_POST['batchid'];
    $subjectid = $_POST['subjectid'];
    $subtopicid = $_POST['subtopicid'];
      $sql22 = "SELECT * FROM tbl_upload_materials WHERE Status=1 AND MatFor=6";
     if($deptid!=''){
      $sql22.= " AND DeptId='$deptid'";
      }
      /*if($courseid!=''){
      $sql22.= " AND CourseId='$courseid'";  
      }*/
      if($subjectid!=''){
      $sql22.= " AND SubjectId='$subjectid'";  
      }
      if($subtopicid!=''){
      $sql22.= " AND SubTopicId='$subtopicid'";  
      }
      //echo $sql22;
      $row22 = getList($sql22);
      foreach($row22 as $result22){

        $sql7 = "SELECT File FROM tbl_ques_papers WHERE DeptId='$deptid' AND SubjectId='$subjectid' AND SubTopicId='$subtopicid' AND BatchId='$batchid'";
        $row7 = getRecord($sql7);
        $row7['File'] = explode(',', $row7['File']);
  ?>
  <div class="col-lg-4">
    <div class="form-group">
      <div class="custom-control custom-checkbox custom-control-inline">
        <input type="checkbox" id="m<?php echo $result22['id']; ?>" name="File[]" class="custom-control-input advisior" value="<?php echo $result22['id']; ?>" <?php if(in_array($result22["id"],$row7['File'])) { ?>checked="checked" <?php } ?>> <label class="custom-control-label" for="m<?php echo $result22['id']; ?>"><?php echo $result22['Name']; ?></label></div>
  </div></div>
<?php }} 

if($_POST['action'] == 'showSubjects'){
    $deptid = $_POST['deptid'];
    $courseid = $_POST['courseid'];
      if($deptid!=''){
      $sql22 = "SELECT * FROM tbl_subjects WHERE Status=1 AND DeptId='$deptid'";
      }
      /*if($courseid!=''){
      $sql33 = "SELECT Subjects FROM tbl_courses WHERE id='$courseid'";
      $row33 = getRecord($sql33);
      $Subjects = $row33['Subjects'];
      $sql22 = "SELECT * FROM tbl_subjects WHERE Status=1 AND DeptId='$deptid' AND id IN($Subjects)";
      }*/
      //echo $sql22;
      $row22 = getList($sql22);
      foreach($row22 as $result22){

         $sql7 = "SELECT Subjects FROM tbl_ques_paper_subjects WHERE DeptId='$deptid'";
      if($courseid!=''){
      $sql7.= " AND CourseId='$courseid'";  
      } 
        $row7 = getRecord($sql7);
        $row7['Subjects'] = explode(',', $row7['Subjects']);
  ?>
  <div class="col-lg-4">
    <div class="form-group">
      <div class="custom-control custom-checkbox custom-control-inline">
        <input type="checkbox" id="m<?php echo $result22['id']; ?>" name="Subjects[]" class="custom-control-input advisior" value="<?php echo $result22['id']; ?>" <?php if(in_array($result22["id"],$row7['Subjects'])) { ?>checked="checked" <?php } ?>> <label class="custom-control-label" for="m<?php echo $result22['id']; ?>"><?php echo $result22['Name']; ?></label></div>
  </div></div>
<?php }}    

if($_POST['action'] == 'getSubject'){?>
    <option value="" selected="selected" disabled="">Select Subject</option>
<?php 
    $DeptId = $_POST['deptid'];
    $CourseId = $_POST['id'];
        $sql= "select Subjects from tbl_ques_paper_subjects WHERE DeptId = '$DeptId' AND CourseId='$CourseId'";
        $row = getRecord($sql);
        $Subjects = $row['Subjects'];

    $sql22 = "SELECT * FROM tbl_subjects WHERE id IN($Subjects)";    
    $row22 = getList($sql22);
    foreach($row22 as $result22){
?>
    <option value="<?php echo $result22['id']; ?>"><?php echo $result22['Name']; ?></option>
<?php } }
?>
