<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'Add'){
$DeptId = addslashes(trim($_POST["DeptId"]));
$CourseId = addslashes(trim($_POST["CourseId"]));
$BatchName = addslashes(trim($_POST["BatchName"]));
$StartDate = addslashes(trim($_POST["StartDate"]));
$BatchTime = addslashes(trim($_POST["BatchTime"]));
$Strength = addslashes(trim($_POST["Strength"]));
$Month = addslashes(trim($_POST["Month"]));
$Year = addslashes(trim($_POST["Year"]));

$Status = $_POST["Status"];
$CreatedDate = date('Y-m-d');
$query = "SELECT * FROM tbl_batches WHERE DeptId='$DeptId' AND CourseId='$CourseId' AND BatchName = '$BatchName' AND Month='$Month' AND Year='$Year'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
$qx = "INSERT INTO tbl_batches SET DeptId='$DeptId',CourseId='$CourseId',BatchName = '$BatchName',StartDate='$StartDate',BatchTime='$BatchTime',Strength='$Strength',Status='$Status',CreatedBy='$user_id',CreatedDate='$CreatedDate',Month='$Month',Year='$Year'";
  $conn->query($qx);
  echo 1;
}
}

if($_POST['action'] == 'fetch_record'){
 $id = $_POST['id'];
    $query = "SELECT * FROM tbl_batches WHERE id = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo json_encode($row);


}

if($_POST['action'] == 'Edit') {
$id = $_POST['id'];
$DeptId = addslashes(trim($_POST["DeptId"]));
$CourseId = addslashes(trim($_POST["CourseId"]));
$BatchName = addslashes(trim($_POST["BatchName"]));
$StartDate = addslashes(trim($_POST["StartDate"]));
$BatchTime = addslashes(trim($_POST["BatchTime"]));
$Strength = addslashes(trim($_POST["Strength"]));
$Month = addslashes(trim($_POST["Month"]));
$Year = addslashes(trim($_POST["Year"]));
$Status = $_POST["Status"];
$CreatedDate = date('Y-m-d');
$query = "SELECT * FROM tbl_batches WHERE DeptId='$DeptId' AND CourseId='$CourseId' AND BatchName = '$BatchName' AND Month='$Month' AND Year='$Year' AND id != '$id'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
  $query2 = "UPDATE tbl_batches SET DeptId='$DeptId',CourseId='$CourseId',BatchName = '$BatchName',StartDate='$StartDate',BatchTime='$BatchTime',Strength='$Strength',Status='$Status',ModifiedBy='$user_id',ModifiedDate='$CreatedDate',Month='$Month',Year='$Year' WHERE id = '$id'";
  $conn->query($query2);
  echo 1;
}
}

  if($_POST['action'] == 'delete') {
   
      $id = $_POST['id'];
      $query = "DELETE FROM tbl_batches WHERE id = '$id'";
      $conn->query($query);
      echo "Delete Successfully";

  }



  if($_POST['action']=='view'){?>
 <table id="example" class="table table-striped table-bordered">
        <thead>
            <tr>
              <th>#</th>
              <!--<th>Department</th>
              <th>Course</th>-->
              <th>Batch Name</th>
              <th>Start Date</th>
              <th>Batch Time</th>
              <th>Batch Strength</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
 $DeptId = $_POST['DeptId2'];
 $CourseId = $_POST['CourseId2'];
  $sql = "SELECT tb.*,tc.Name As Course,td.Name As Department FROM tbl_batches tb 
          LEFT JOIN tbl_departments td ON tb.DeptId = td.id 
          LEFT JOIN tbl_courses tc ON tb.CourseId = tc.id
           WHERE tb.Status IN(1,0)"; 
             if($_POST['DeptId2']!=''){
    $sql.= " AND tb.DeptId='".$_POST['DeptId2']."'";     
    }
    if($_POST['CourseId2']!=''){
    $sql.= " AND tb.CourseId='".$_POST['CourseId2']."'";     
    }
         
     $sql.= "ORDER BY tb.id DESC";
     //echo $sql;
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){

  ?>
           <tr>
             <td><?php echo $srno; ?></td>
           
            <!-- <td><?php echo $nx['Department']; ?></td>
             <td><?php echo $nx['Course']; ?></td>-->
             <td><?php echo $nx['BatchName']; ?></td>
             <td><?php echo $nx['StartDate']; ?></td>
             <td><?php echo $nx['BatchTime']; ?></td>
             <td><?php echo $nx['Strength']; ?></td>
             <td><?php if($nx['Status']=='1'){echo "<span style='color:green;'>Active</span>";} else { echo "<span style='color:red;'>Inactive</span>";} ?></td>
             <td><a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit" class="update"><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;<a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" class="delete" id="bootbox-confirm"><i class="lnr lnr-trash text-danger"></i></a>
             </td>
            </tr>
             <?php $srno++;} ?>
        </tbody>
    </table>
    <script type="text/javascript">
      $(document).ready(function() {
      $('#example').DataTable( {
       "scrollX": true
      });
      });
    </script>
 <?php }
?>