<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'Add'){
$Question = addslashes(trim($_POST["Question"]));
$Answer = addslashes(trim($_POST["Answer"]));
$Status = $_POST["Status"];

$qx = "INSERT INTO tbl_faqs SET Question = '$Question',Answer = '$Answer',Status='$Status'";
	$conn->query($qx);
	echo 1;
}

if($_POST['action'] == 'fetch_record'){
 $id = $_POST['id'];
    $query = "SELECT * FROM tbl_faqs WHERE id = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo json_encode($row);


}

if($_POST['action'] == 'Edit') {
     $id = $_POST['id'];
$Question = addslashes(trim($_POST["Question"]));
$Answer = addslashes(trim($_POST["Answer"]));
$Status = $_POST["Status"];

  $query2 = "UPDATE tbl_faqs SET Question = '$Question',Answer = '$Answer',Status='$Status' WHERE id = '$id'";
 	$conn->query($query2);
  echo 1;

}

  if($_POST['action'] == 'delete') {
   
      $id = $_POST['id'];
      $query = "DELETE FROM tbl_faqs WHERE id = '$id'";
      $conn->query($query);
      echo "Delete Successfully";

  }


  if($_POST['action']=='view'){?>
<table id="example" class="table-striped table-bordered">
        <thead>
            <tr>
              <th>#</th>
              
              <th>Question</th>
              <th>Answer</th>
               <th>Status</th>
               <th>Action</th>
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
  $sql = "SELECT * FROM tbl_faqs ORDER BY id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){

  ?>
           <tr>
             <td><?php echo $srno; ?></td>
           
             <td nowrap=""><?php echo $nx['Question']; ?></td>
             <td style="text-align:justify;"><?php echo $nx['Answer']; ?></td>
             <td><?php if($nx['Status']=='1'){echo "<span style='color:green;'>Active</span>";} else { echo "<span style='color:red;'>Inactive</span>";} ?></td>
             <td><a data-id="" href='add-faqs.php?id=<?php echo $nx['id']; ?>' data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit" class=""><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;<a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" class="delete" id="bootbox-confirm"><i class="lnr lnr-trash text-danger"></i></a>
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