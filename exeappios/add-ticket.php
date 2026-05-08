<?php 
session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Create Ticket";
$UserId   = $_SESSION['User']['id'];

$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name   = $row11['Fname']." ".$row11['Lname'];
$Phone  = $row11['Phone'];
$EmailId= $row11['EmailId'];

$id = $_GET['id'] ?? 0;
$sql7 = "SELECT * FROM tbl_tickets WHERE id='$id'";
$row7 = getRecord($sql7);

// ------------------- Insert / Update Logic ------------------- //
if (isset($_POST['submit'])) {
    $Subject      = $_POST['Subject'];
    $DepartmentId = $_POST['DepartmentId'];
    $Priority     = $_POST['Priority'];
    $Description  = $_POST['Description'];
    $CreatedBy    = $UserId; 
    $date         = date('Y-m-d H:i:s');

    // Generate Ticket No
    if($id){
        $TicketNo = $row7['ticket_no'];
    } else {
        function generateTicketNo($conn) {
            do {
                $ticketNo = "TKT" . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
                $sql = "SELECT id FROM tbl_tickets WHERE ticket_no='$ticketNo'";
                $result = $conn->query($sql);
            } while ($result->num_rows > 0);
            return $ticketNo;
        }
        $TicketNo = generateTicketNo($conn);
    }

    // Create ticket folder
    $ticketFolder = "../ticketfiles/".$TicketNo."/";
    if (!is_dir($ticketFolder)) {
        mkdir($ticketFolder, 0777, true);
    }

    // File upload
    $uploadedFiles = [];
    if (!empty($_FILES['Attachments']['name'][0])) {
        foreach ($_FILES['Attachments']['name'] as $key => $name) {
            $tmp_name = $_FILES['Attachments']['tmp_name'][$key];
            $randno   = rand(1, 1000);
            $fnm      = pathinfo($name, PATHINFO_FILENAME);
            $fnm      = str_replace(" ","_", $fnm);
            $ext      = ".".pathinfo($name, PATHINFO_EXTENSION);
            $filename = $randno."_".$fnm.$ext;
            $target   = $ticketFolder.$filename;
            if (move_uploaded_file($tmp_name, $target)) {
                $uploadedFiles[] = $filename;
            }
        }
    }

    // Merge old + new attachments
    if($id){
        $Attachments = $row7['attachments'];
        if (!empty($uploadedFiles)) {
            $Attachments = trim($Attachments.','.implode(',', $uploadedFiles), ',');
        }
    } else {
        $Attachments = !empty($uploadedFiles) ? implode(',', $uploadedFiles) : "";
    }

    // Save in DB
    if($id){ 
        $sql = "UPDATE tbl_tickets SET 
                    subject='$Subject',
                    department_id='$DepartmentId',
                    priority='$Priority',
                    description='$Description',
                    attachments='$Attachments',
                    updated_at='$date'
                WHERE id='$id'";
        if ($conn->query($sql)) {
            echo "<script>alert('Ticket Updated Successfully!');window.location.href='view-tickets.php';</script>";
        } else {
            echo "<script>alert('Error updating ticket: ".$conn->error."');</script>";
        }
    } else {
        $sql = "INSERT INTO tbl_tickets SET 
                    ticket_no='$TicketNo',
                    subject='$Subject',
                    department_id='$DepartmentId',
                    priority='$Priority',
                    description='$Description',
                    attachments='$Attachments',
                    created_by='$CreatedBy',
                    created_at='$date',
                    status='open'";
        if ($conn->query($sql)) {
            echo "<script>alert('Ticket Raised Successfully! Ticket No: $TicketNo');window.location.href='view-tickets.php';</script>";
        } else {
            echo "<script>alert('Error creating ticket: ".$conn->error."');</script>";
        }
    }
}
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?php echo $Proj_Title; ?> - <?php echo ($id) ? "Edit Ticket" : "Raise Ticket"; ?></title>

  <!-- Favicons -->
  <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
  <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
  <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

  <!-- Google Fonts + Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

  <!-- Bootstrap + Custom -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet" id="style">
  <link href="css/toastr.min.css" rel="stylesheet">

  <!-- JS Libraries -->
  <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
  <script src="js/toastr.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">

  <style>
    body { background-color:#f8f9fa; }
    .ticket-card {
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      margin-top: 20px;
    }
    .ticket-card .card-body {
      padding: 30px;
    }
    label { font-weight: 500; }
    .form-control, .custom-file-input {
      border-radius: 6px;
    }
    .attachment-preview img {
      width:70px;
      height:70px;
      object-fit:cover;
      border-radius:6px;
      margin:5px;
      border:1px solid #ddd;
    }
    .attachment-preview a.btn {
      margin:5px;
    }
  </style>
</head>
<body class="d-flex flex-column h-100">
<main class="flex-shrink-0 main">
  <?php include_once 'back-header.php'; ?> 

  <div class="container">
    <div class="card ticket-card">
      <div class="card-body">
        <h4 class="mb-4 text-primary"><?php echo ($id) ? "Edit Ticket" : "Raise a New Ticket"; ?></h4>

        <form method="post" enctype="multipart/form-data">

          <!-- Subject -->
          <div class="form-group mb-3">
            <label>Subject <span class="text-danger">*</span></label>
            <input type="text" name="Subject" class="form-control"
                   value="<?php echo htmlspecialchars($row7['subject'] ?? ''); ?>" required>
          </div>

          <div class="row">
            <!-- Department -->
            <div class="form-group col-md-6 mb-3">
              <label>Department <span class="text-danger">*</span></label>
              <select class="form-control" name="DepartmentId" required>
                <option value="">Select Department</option>
                <?php 
                  $q = "SELECT * FROM tbl_departments WHERE Status=1 ORDER BY Name";
                  $r = $conn->query($q);
                  while($rw = $r->fetch_assoc()){
                ?>
                  <option value="<?php echo $rw['id']; ?>" 
                    <?php if(($row7['department_id'] ?? '')==$rw['id']) echo "selected"; ?>>
                    <?php echo $rw['Name']; ?>
                  </option>
                <?php } ?>
              </select>
            </div>

            <!-- Priority -->
            <div class="form-group col-md-6 mb-3">
              <label>Priority <span class="text-danger">*</span></label>
              <select class="form-control" name="Priority" required>
                <option value="">Select Priority</option>
                <option value="low" <?php if(($row7['priority'] ?? '')=='low') echo 'selected'; ?>>Low</option>
                <option value="medium" <?php if(($row7['priority'] ?? '')=='medium') echo 'selected'; ?>>Medium</option>
                <option value="high" <?php if(($row7['priority'] ?? '')=='high') echo 'selected'; ?>>High</option>
              </select>
            </div>
          </div>

          <!-- Description -->
          <div class="form-group mb-3">
            <label>Description <span class="text-danger">*</span></label>
            <textarea name="Description" rows="5" class="form-control" required><?php echo htmlspecialchars($row7['description'] ?? ''); ?></textarea>
          </div>

          <!-- Attachments -->
          <div class="form-group mb-4">
            <label>Attach Files / Screenshots</label>
            <input type="file" class="form-control" name="Attachments[]" multiple>

            <?php 
            if (!empty($row7['attachments'])) { 
              $files = explode(',', $row7['attachments']);
              $ticketNo = $row7['ticket_no']; 
              $ticketFolder = "../ticketfiles/".$ticketNo."/";
              echo "<div class='attachment-preview mt-3'>";
              foreach ($files as $file) { 
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION)); 
                if(in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
                  echo "<a href='$ticketFolder$file' target='_blank'>
                          <img src='$ticketFolder$file' alt='file'>
                        </a>";
                } else {
                  echo "<a href='$ticketFolder$file' target='_blank' class='btn btn-sm btn-outline-secondary'>
                          <i class='fa fa-file'></i> $file
                        </a>";
                }
              }
              echo "</div>";
            } 
            ?>
          </div>

          <!-- Submit -->
          <button type="submit" name="submit" class="btn btn-primary btn-block py-2">
            <?php echo ($id) ? "Update Ticket" : "Submit Ticket"; ?>
          </button>
        </form>
      </div>
    </div>
  </div>
</main>

<!-- JS -->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="js/jquery.cookie.js"></script>
<script src="vendor/swiper/js/swiper.min.js"></script>
<script src="js/main.js"></script>
<script src="js/color-scheme-demo.js"></script>
<script src="js/app.js"></script>
</body>
</html>
