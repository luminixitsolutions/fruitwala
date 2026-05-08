<?php 
$UserId = $_SESSION['User']['id'];
$sql110 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row110 = getRecord($sql110);
$Name = $row110['Fname']." ".$row110['Lname'];
$Photo = $row110['Photo'];
?>

<!-- Sidebar -->
<div class="main-menu">
    <div class="row mb-4 no-gutters">
        <div class="col-auto">
            <button class="btn btn-link btn-40 btn-close">
                <span class="material-icons" style="color:#e2982d;">chevron_left</span>
            </button>
        </div>

        <div class="col-auto">
            <div class="avatar avatar-40 rounded-circle position-relative">
                <figure class="background">
                    <?php if($Photo == '') { ?>
                        <img src="user.jpg" alt="User" style="width: 140px;height: 140px;">
                    <?php } else { ?>
                        <img src="<?php echo $Uploadurl; ?>/uploads/<?php echo $Photo; ?>" alt="User" style="width: 140px;height: 140px;">
                    <?php } ?>
                </figure>
            </div>
        </div>

        <div class="col pl-3 text-left align-self-center">
            <h6 class="mb-1"><?php echo $Name; ?></h6>
            <p class="small text-default-secondary" style="color:#e2982d;font-weight:bold;">
                Wallet: ₹<?php echo number_format($mybalance, 2); ?>
            </p>
        </div>
    </div>

    <div class="menu-container">
        <ul class="nav nav-pills flex-column">

            <li class="nav-item">
                <a class="nav-link active" href="<?php echo $SiteUrl; ?>/home.php">
                    <div><span class="material-icons icon">home</span> Home</div>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="today-orders.php">
                    <div><span class="material-icons icon">assignment</span> Today Orders</div>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="attendance.php">
                    <div><span class="material-icons icon">how_to_reg</span> Mark Attendance</div>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="my-attendance.php">
                    <div><span class="material-icons icon">event_note</span> My Attendance</div>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="add-advance-payment-request.php">
                    <div><span class="material-icons icon">account_balance_wallet</span> Advance Request</div>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="view-resign-request.php">
                    <div><span class="material-icons icon">logout</span> Resign Request</div>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="view-leave-request.php">
                    <div><span class="material-icons icon">time_to_leave</span> Leave Request</div>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="add-ticket.php">
                    <div><span class="material-icons icon">confirmation_number</span> Create Ticket</div>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="view-tickets.php">
                    <div><span class="material-icons icon">receipt_long</span> View Tickets</div>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="create-task.php">
                    <div><span class="material-icons icon">add_task</span> Create Task</div>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="view-task.php">
                    <div><span class="material-icons icon">check_circle</span> Today Task</div>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0)" onclick="logout()">
                    <div><span class="material-icons icon">power_settings_new</span> Logout</div>
                </a>
            </li>

        </ul>
    </div>
</div>
<div class="backdrop"></div>

<script>
function logout() {
    Android.logout?.();
    window.location.href = "logout.php";
}
</script>
