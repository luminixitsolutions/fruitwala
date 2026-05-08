<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];


  if($_POST['action'] == 'delete') {
   
      $id = $_POST['id'];
       $query2 = "SELECT Photo FROM tbl_upload_materials WHERE id = '$id'";
    $result2 = $conn->query($query2);
    $row2 = $result2->fetch_assoc();
    $Photo = $row2['Photo'];
      $query = "DELETE FROM tbl_upload_materials WHERE id = '$id'";
      $conn->query($query);
       $src = "../../uploads/$Photo";
        unlink($src);
      echo "Delete Successfully";

  }

if($_POST['action'] == 'deletePhoto'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_upload_materials SET Photo='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Photo Delete Successfully";
} 

if($_POST['action'] == 'deletePhoto2'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_upload_materials SET Photo2='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Photo Delete Successfully";
} 
  if($_POST['action']=='view'){?>
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
              <th>#</th>
              <th>Material For</th>
                <th>Exam</th>
              <th>Course Name</th>
              <th>Subject</th>
              <th>Sub Topic</th>
              <th>Title</th>
               <th>Attach File</th>
         
               <th>Status</th>
               <th>Action</th>
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
  $sql = "SELECT ts.*,td.Name As Department,tc.Name As Course,tsb.Name As Subjects,tst.Name As SubTopic,tmf.Name As MaterialFor FROM tbl_upload_materials ts
          LEFT JOIN tbl_departments td ON ts.DeptId = td.id 
          LEFT JOIN tbl_courses tc ON ts.CourseId = tc.id 
          LEFT JOIN tbl_subjects tsb ON ts.SubjectId = tsb.id 
          LEFT JOIN tbl_sub_topics tst ON ts.SubTopicId = tst.id 
          LEFT JOIN tbl_material_for tmf ON ts.MatFor = tmf.id 
          ORDER BY id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){

  ?>
           <tr>
             <td><?php echo $srno; ?></td>
             <td><?php echo $nx['MaterialFor']; ?></td>
              <td><?php echo $nx['Department']; ?></td>
              <td><?php echo $nx['Course']; ?></td>
              <td><?php echo $nx['Subjects']; ?></td>
              <td><?php echo $nx['SubTopic']; ?></td>
              <td><?php echo $nx['Name']; ?></td>
         
             <td><?php if($nx["Photo"] == '') {?>
                  <span style="color:red;">Material File Not Found!</span>
                <?php } else if(file_exists('../../uploads/'.$nx["Photo"])){?>  
                <a href="../uploads/<?php echo $nx['Photo']; ?>" target="_blank"><i class="feather icon-download"></i> Download</a>
                <?php } else{?>
                  <span style="color:red;">File Not Found!</span>
                <?php } ?></td>
             <td><?php if($nx['Status']=='1'){echo "<span style='color:green;'>Active</span>";} else { echo "<span style='color:red;'>Inactive</span>";} ?></td>
             <td><a href='upload-material.php?id=<?php echo $nx['id']; ?>' data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit"><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;<a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" class="delete" id="bootbox-confirm"><i class="lnr lnr-trash text-danger"></i></a>
             </td>
            </tr>
             <?php $srno++;} ?>
        </tbody>
    </table>
    <script type="text/javascript">
      $(document).ready(function() {
      $('#example').DataTable( {
        responsive: true
      });
      });
    </script>
 <?php }
?>