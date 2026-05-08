<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'Add'){
$Name = addslashes(trim($_POST["Name"]));
$Details = addslashes(trim($_POST["Details"]));
$Icon = addslashes(trim($_POST["Icon"]));
$BtnUrl = addslashes(trim($_POST["BtnUrl"]));
$Status = $_POST["Status"];
function uploadFile($fieldName, $uploadDir = '../../uploads/', $oldFile = '') {
    if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK) {
        $randno = rand(1, 100);
        $src = $_FILES[$fieldName]['tmp_name'];
        $fnm = pathinfo($_FILES[$fieldName]['name'], PATHINFO_FILENAME);
        $fnm = str_replace(" ", "_", $fnm);
        $ext = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));

        // Optional: File type validation
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
        if (!in_array($ext, $allowed_ext)) {
            echo "<script>alert('Invalid file type for $fieldName');</script>";
            return $oldFile;
        }

        $fileName = $randno . "_" . $fnm . "." . $ext;
        $dest = $uploadDir . $fileName;

        if (is_uploaded_file($src) && move_uploaded_file($src, $dest)) {
            return $fileName;
        }
    }

    return $oldFile; // fallback to old file if no upload or failed upload
}

// Usage:
$Photo  = uploadFile('Photo', '../../uploads/', $_POST['OldPhoto'] ?? '');
$Photo2 = uploadFile('Photo2', '../../uploads/', $_POST['OldPhoto2'] ?? '');

$CreatedDate = date('Y-m-d');
$qx = "INSERT INTO tbl_home_sliders SET Name = '$Name',Details = '$Details',Status='$Status',Photo='$Photo',Photo2='$Photo2',BtnUrl='$BtnUrl'";
	$conn->query($qx);
	echo 1;
}

if($_POST['action'] == 'fetch_record'){
 $id = $_POST['id'];
    $query = "SELECT * FROM tbl_home_sliders WHERE id = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo json_encode($row);


}

if($_POST['action'] == 'Edit') {
     $id = $_POST['id'];
$Name = addslashes(trim($_POST["Name"]));
$Details = addslashes(trim($_POST["Details"]));
$Status = $_POST["Status"];
$Icon = addslashes(trim($_POST["Icon"]));
$BtnUrl = addslashes(trim($_POST["BtnUrl"]));

function uploadFile($fieldName, $oldFile = '', $uploadDir = '../../uploads/') {
    // Check if a new file is uploaded
    if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK) {
        $randno = rand(1, 100);
        $src = $_FILES[$fieldName]['tmp_name'];
        $fnm = pathinfo($_FILES[$fieldName]['name'], PATHINFO_FILENAME);
        $fnm = str_replace(" ", "_", $fnm);
        $ext = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($ext, $allowed_ext)) {
            echo "<script>alert('Invalid file type for $fieldName');</script>";
            return $oldFile;
        }

        // Generate unique filename
        $newFileName = $randno . "_" . $fnm . "." . $ext;
        $dest = $uploadDir . $newFileName;

        // Move file
        if (is_uploaded_file($src) && move_uploaded_file($src, $dest)) {
            // Delete old file if it exists
            if (!empty($oldFile)) {
                $oldPath = $uploadDir . $oldFile;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            return $newFileName;
        }
    }
    return $oldFile; // fallback if no new upload
}

// Usage:
$Photo  = uploadFile('Photo', $_POST['OldPhoto'] ?? '');
$Photo2 = uploadFile('Photo2', $_POST['OldPhoto2'] ?? '');


$ModifiedDate = date('Y-m-d');
  $query2 = "UPDATE tbl_home_sliders SET Name = '$Name',Details = '$Details',Status='$Status',Photo='$Photo',Photo2='$Photo2',BtnUrl='$BtnUrl' WHERE id = '$id'";
 	$conn->query($query2);
  echo 1;
}

  if($_POST['action'] == 'delete') {
   
      $id = $_POST['id'];
       $query2 = "SELECT Photo FROM tbl_home_sliders WHERE id = '$id'";
    $result2 = $conn->query($query2);
    $row2 = $result2->fetch_assoc();
    $Photo = $row2['Photo'];
      $query = "DELETE FROM tbl_home_sliders WHERE id = '$id'";
      $conn->query($query);
       $src = "../../uploads/$Photo";
        unlink($src);
      echo "Delete Successfully";

  }

if($_POST['action'] == 'deletePhoto'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_home_sliders SET Photo='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Photo Delete Successfully";
} 

if($_POST['action'] == 'deletePhoto2'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_home_sliders SET Photo2='' WHERE id=$id";
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
              <th>Website Slider</th>
              <!--<th>Mobile Slider</th>-->
              <th>Title</th>
               <th>Status</th>
             
               <th>Action</th>
           
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
  $sql = "SELECT * FROM tbl_home_sliders ORDER BY id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){

  ?>
           <tr>
             <td><?php echo $srno; ?></td>
              <td><?php if($nx["Photo2"] == '') {?>
                  <img src="no_image.jpg" class="img-fluid ui-w-40"  style="width: 40px;height: 40px;"> 
                 <?php } else if(file_exists('../../uploads/'.$nx["Photo2"])){?>
                 <img src="../uploads/<?php echo $nx["Photo2"];?>" class="img-fluid ui-w-40" style="width: 40px;height: 40px;">
                  <?php }  else{?>
                 <img src="no_image.jpg" class="img-fluid ui-w-40"> 
             <?php } ?></td>
           <!--  <td><?php if($nx["Photo"] == '') {?>
                  <img src="no_image.jpg" class="img-fluid ui-w-40"  style="width: 40px;height: 40px;"> 
                 <?php } else if(file_exists('../../uploads/'.$nx["Photo"])){?>
                 <img src="../uploads/<?php echo $nx["Photo"];?>" class="img-fluid ui-w-40" style="width: 40px;height: 40px;">
                  <?php }  else{?>
                 <img src="no_image.jpg" class="img-fluid ui-w-40"> 
             <?php } ?></td>-->
             <td><?php echo $nx['Name']; ?></td>
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