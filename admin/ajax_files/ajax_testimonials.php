<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'Add'){
$Name = addslashes(trim($_POST["Name"]));
$Designation = addslashes(trim($_POST["Designation"]));
$Feedback = addslashes(trim($_POST["Feedback"]));
$Status = $_POST["Status"];

$Photo = $_POST['OldPhoto'] ?? ''; // Fallback to old photo if upload fails

if (isset($_FILES['Photo']) && $_FILES['Photo']['error'] === UPLOAD_ERR_OK) {
    $randno = rand(1, 100);
    $src = $_FILES['Photo']['tmp_name'];
    $fnm = pathinfo($_FILES["Photo"]["name"], PATHINFO_FILENAME);
    $fnm = str_replace(" ", "_", $fnm);
    $ext = strtolower(pathinfo($_FILES["Photo"]["name"], PATHINFO_EXTENSION));
    $imagepath = $randno . "_" . $fnm . "." . $ext;
    $dest = '../../uploads/' . $imagepath;

    // Validate allowed file types
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (in_array($ext, $allowed)) {
        if (is_uploaded_file($src) && move_uploaded_file($src, $dest)) {
            $Photo = $imagepath; // Successfully uploaded
        } else {
            echo "<script>alert('Failed to upload photo.');</script>";
        }
    } else {
        echo "<script>alert('Invalid file type!');</script>";
    }
}


$CreatedDate = date('Y-m-d');
$qx = "INSERT INTO tbl_testimonials SET Name = '$Name', Designation = '$Designation',Feedback = '$Feedback',Status='$Status',Photo='$Photo',CreatedDate='$CreatedDate'";
	$conn->query($qx);
	echo 1;
}

if($_POST['action'] == 'fetch_record'){
 $id = $_POST['id'];
    $query = "SELECT * FROM tbl_testimonials WHERE id = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo json_encode($row);


}

if($_POST['action'] == 'Edit') {
     $id = $_POST['id'];
$Name = addslashes(trim($_POST["Name"]));
$Status = $_POST["Status"];
$Designation = addslashes(trim($_POST["Designation"]));
$Feedback = addslashes(trim($_POST["Feedback"]));

$Photo = $_POST['OldPhoto'] ?? ''; // Default to old photo

if (isset($_FILES['Photo']) && $_FILES['Photo']['error'] === UPLOAD_ERR_OK) {
    $randno = rand(1, 100);
    $src = $_FILES['Photo']['tmp_name'];
    $fnm = pathinfo($_FILES["Photo"]["name"], PATHINFO_FILENAME);
    $fnm = str_replace(" ", "_", $fnm);
    $ext = strtolower(pathinfo($_FILES["Photo"]["name"], PATHINFO_EXTENSION));
    $imagepath = $randno . "_" . $fnm . "." . $ext;
    $dest = '../../uploads/' . $imagepath;

    // Upload file
    if (is_uploaded_file($src) && move_uploaded_file($src, $dest)) {
        // Delete old file if it exists
        if (!empty($Photo)) {
            $oldFile = '../../uploads/' . $Photo;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $Photo = $imagepath; // Set new photo
    }
}


$ModifiedDate = date('Y-m-d');
  $query2 = "UPDATE tbl_testimonials SET Name = '$Name', Designation = '$Designation',Feedback = '$Feedback',Status='$Status',Photo='$Photo',ModifiedDate='$ModifiedDate' WHERE id = '$id'";
 	$conn->query($query2);
  echo 1;
}

  if($_POST['action'] == 'delete') {
   
      $id = $_POST['id'];
       $query2 = "SELECT Photo FROM tbl_testimonials WHERE id = '$id'";
    $result2 = $conn->query($query2);
    $row2 = $result2->fetch_assoc();
    $Photo = $row2['Photo'];
      $query = "DELETE FROM tbl_testimonials WHERE id = '$id'";
      $conn->query($query);
       $src = "../../uploads/$Photo";
        unlink($src);
      echo "Delete Successfully";

  }

if($_POST['action'] == 'deletePhoto'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_testimonials SET Photo='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Category Photo Delete Successfully";
} 
  if($_POST['action']=='view'){?>
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
              <th>#</th>
              <th>Photo</th>
              <th>Name</th>
              <th>Feedback</th>
               <th>Status</th>
               <th>Action</th>
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
  $sql = "SELECT * FROM tbl_testimonials ORDER BY id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){

  ?>
           <tr>
             <td><?php echo $srno; ?></td>
             <td><?php if($nx["Photo"] == '') {?>
                  <img src="user_icon.jpg" class="img-fluid ui-w-40"  style="width: 40px;height: 40px;"> 
                 <?php } else if(file_exists('../../uploads/'.$nx["Photo"])){?>
                 <img src="../uploads/<?php echo $nx["Photo"];?>" class="img-fluid ui-w-40" style="width: 40px;height: 40px;">
                  <?php }  else{?>
                 <img src="user_icon.jpg" class="img-fluid ui-w-40"> 
             <?php } ?></td>
             <td><?php echo $nx['Name']."<br>(".$nx['Designation'].")"; ?></td>
             <td><?php echo substr($nx['Feedback'],0,100); ?></td>
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
        responsive: true
      });
      });
    </script>
 <?php }
?>