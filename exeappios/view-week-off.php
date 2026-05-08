<?php session_start();
require_once 'config.php';
$user_id = $_SESSION['User']['id'];
$PageName = "Week Off";
$Page = "Recharge";
$WallMsg = "NotShow"; ?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $Proj_Title; ?></title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <?php include_once 'back-header.php'; ?>

        <!-- page content start -->


        <div class="main-container">
            <div class="container">
                 <div style="float:right;">
                     <button type="button" class="btn btn-sm btn-primary rounded" data-toggle="modal" data-target="#punchModal">
                         <i class="fa fa-clock-o"></i> Punch In Week Off
                     </button>
                 </div><br><br>
                 
                 <!-- Punch In Modal -->
                 <div class="modal fade" id="punchModal" tabindex="-1" role="dialog" aria-labelledby="punchModalLabel" aria-hidden="true">
                     <div class="modal-dialog" role="document">
                         <div class="modal-content">
                             <div class="modal-header">
                                 <h5 class="modal-title" id="punchModalLabel">Week Off Punch In</h5>
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                     <span aria-hidden="true">&times;</span>
                                 </button>
                             </div>
                             <div class="modal-body">
                                 <form id="punchForm">
                                     <div class="form-group">
                                         <label for="punch_date">Select Date:</label>
                                         <input type="date" class="form-control" id="punch_date" name="punch_date" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" readonly required>
                                     </div>
                                     <div class="form-group">
                                         <label for="reason">Reason (Optional):</label>
                                         <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Enter reason for week off..."></textarea>
                                     </div>
                                 </form>
                             </div>
                             <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                 <button type="button" class="btn btn-primary" onclick="submitPunch()">Punch In</button>
                             </div>
                         </div>
                     </div>
                 </div>
                <!-- Week Off Punch Records Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h4 class="mb-3"><i class="fa fa-clock-o"></i> Week Off Punch Records</h4>
                        <div id="punchRecords">
                            <?php 
                            $punch_sql = "SELECT * FROM tbl_week_off_punch WHERE user_id='$user_id' ORDER BY punch_date DESC, punch_time DESC";
                            $punch_records = getList($punch_sql);
                            if(count($punch_records) > 0) {
                                foreach($punch_records as $punch) {
                            ?>
                            <div class="card mb-3 punch-record" data-punch-id="<?php echo $punch['id']; ?>">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h6 class="mb-1">
                                                <i class="fa fa-calendar"></i> 
                                                <?php echo date("d/m/Y", strtotime($punch['punch_date'])); ?>
                                                <span class="badge badge-<?php echo $punch['status'] == 'active' ? 'success' : 'secondary'; ?> ml-2">
                                                    <?php echo ucfirst($punch['status']); ?>
                                                </span>
                                            </h6>
                                            <p class="text-muted mb-1">
                                                <i class="fa fa-clock-o"></i> Punched at: <?php echo date("h:i A", strtotime($punch['punch_time'])); ?>
                                            </p>
                                            <?php if(!empty($punch['reason'])) { ?>
                                            <p class="text-secondary mb-0">
                                                <i class="fa fa-comment"></i> <?php echo htmlspecialchars($punch['reason']); ?>
                                            </p>
                                            <?php } ?>
                                        </div>
                                        <div class="col-4 text-right">
                                            <?php if($punch['status'] == 'active') { ?>
                                           <!-- <button class="btn btn-sm btn-outline-danger" onclick="cancelPunch(<?php echo $punch['id']; ?>)">
                                                <i class="fa fa-times"></i> Cancel
                                            </button>-->
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php 
                                }
                            } else {
                            ?>
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fa fa-clock-o fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No punch records found</h5>
                                    <p class="text-muted">Click "Punch In Week Off" to create your first record</p>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                
               
                   

            </div>
        </div>
    </main>

<?php include 'footer.php';?>
    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="js/app.js"></script>
<script>
    function getExpId(id){
        $('#myModal').modal('show');
        $('.Exp_Id').val(id);
    }
    
    // Punch In functionality
    function submitPunch() {
        const form = document.getElementById('punchForm');
        const formData = new FormData(form);
        formData.append('action', 'punch_in');
        
        // Disable submit button to prevent double submission
        const submitBtn = event.target;
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Processing...';
        
        fetch('punch_week_off.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                swal({
                    title: "Success!",
                    text: data.message,
                    type: "success",
                    confirmButtonText: "OK"
                }, function() {
                    $('#punchModal').modal('hide');
                    location.reload(); // Refresh to show new record
                });
            } else {
                swal({
                    title: "Error!",
                    text: data.message,
                    type: "error",
                    confirmButtonText: "OK"
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            swal({
                title: "Error!",
                text: "An unexpected error occurred. Please try again.",
                type: "error",
                confirmButtonText: "OK"
            });
        })
        .finally(() => {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Punch In';
        });
    }
    
    // Cancel punch functionality
    function cancelPunch(punchId) {
        swal({
            title: "Are you sure?",
            text: "Do you want to cancel this punch record?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, cancel it!",
            cancelButtonText: "No, keep it"
        }, function(isConfirm) {
            if (isConfirm) {
                const formData = new FormData();
                formData.append('action', 'cancel_punch');
                formData.append('punch_id', punchId);
                
                fetch('punch_week_off.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        swal({
                            title: "Cancelled!",
                            text: data.message,
                            type: "success",
                            confirmButtonText: "OK"
                        }, function() {
                            location.reload(); // Refresh to update records
                        });
                    } else {
                        swal({
                            title: "Error!",
                            text: data.message,
                            type: "error",
                            confirmButtonText: "OK"
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    swal({
                        title: "Error!",
                        text: "An unexpected error occurred. Please try again.",
                        type: "error",
                        confirmButtonText: "OK"
                    });
                });
            }
        });
    }
    
    // Reset form when modal is closed
    $('#punchModal').on('hidden.bs.modal', function () {
        document.getElementById('punchForm').reset();
        document.getElementById('punch_date').value = '<?php echo date('Y-m-d'); ?>';
    });
    
    // Validate date selection
    document.getElementById('punch_date').addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            swal({
                title: "Invalid Date!",
                text: "Cannot select past dates for punch in.",
                type: "warning",
                confirmButtonText: "OK"
            });
            this.value = '<?php echo date('Y-m-d'); ?>';
        }
    });
</script>
   
</body>

</html>
