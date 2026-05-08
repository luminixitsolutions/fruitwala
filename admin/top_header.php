<nav class="layout-navbar navbar navbar-expand-lg align-items-center top-navbar shadow-sm sticky-top" id="layout-navbar">

  <!-- Brand Logo -->
  
  <!-- Sidebar Toggle (Mobile) -->
  <div class="layout-sidenav-toggle navbar-nav d-lg-none align-items-center me-auto">
    <a class="nav-item nav-link px-0" href="javascript:">
      <i class="fas fa-bars text-white text-large"></i>
    </a>
  </div>

  <!-- Collapse Button (Mobile) -->
  <button class="navbar-toggler border-0 text-white ms-auto" type="button" data-toggle="collapse" data-target="#layout-navbar-collapse">
    <i class="fas fa-ellipsis-v"></i>
  </button>

  <!-- Navbar Right Section -->
  <div class="collapse navbar-collapse justify-content-end" id="layout-navbar-collapse">

    <ul class="navbar-nav align-items-center ms-auto">

      <!-- 🔔 Notification Dropdown -->
      <!--<li class="nav-item dropdown me-3" style="padding-right: 10px;">
        <a class="nav-link text-white position-relative" href="#" id="notificationsDropdown" data-toggle="dropdown">
          <i class="fas fa-bell fa-lg"></i>
          <span class="badge bg-danger rounded-circle position-absolute top-0 start-100 translate-middle p-1 border border-light" style="font-size:10px;">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow border-0">
          <h6 class="dropdown-header">Notifications</h6>
          <a href="#" class="dropdown-item"><i class="fas fa-box me-2 text-primary"></i> 3 new orders received</a>
          <a href="#" class="dropdown-item"><i class="fas fa-user-plus me-2 text-success"></i> New customer registered</a>
          <a href="#" class="dropdown-item"><i class="fas fa-truck me-2 text-warning"></i> 1 delivery pending</a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item text-center text-muted small">View all</a>
        </div>
      </li>-->
        
      <!-- 👤 User Profile Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" data-toggle="dropdown">
          <?php if($row77['Photo']=='') { ?>
            <img src="user_icon.jpg" class="rounded-circle border border-light shadow-sm" style="width:35px;height:35px;">
          <?php } else { ?>
            <img src="../uploads/<?php echo $row77['Photo']; ?>" class="rounded-circle border border-light shadow-sm" style="width:35px;height:35px;">
          <?php } ?>
          <span class="ms-2 fw-semibold"><?php echo $row77['AdminName'] ?? 'Admin'; ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow border-0">
          <div class="dropdown-header text-center">
            <strong><?php echo $row77['AdminName'] ?? 'Administrator'; ?></strong>
            <div class="small text-muted"><?php echo $row77['Email'] ?? ''; ?></div>
          </div>
          <div class="dropdown-divider"></div>
          <a href="company-information.php" class="dropdown-item">
            <i class="fas fa-user me-2 text-primary"></i> My Profile
          </a>
          <a href="change-password.php" class="dropdown-item">
            <i class="fas fa-lock me-2 text-success"></i> Change Password
          </a>
          <div class="dropdown-divider"></div>
          <a href="logout.php" class="dropdown-item text-danger fw-semibold">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
          </a>
        </div>
      </li>

    </ul>
  </div>
</nav>

<!-- 🌈 Custom Styling -->
<style>
.top-navbar {
  background: linear-gradient(90deg, #0072ff, #00c6ff);
  padding: 0.6rem 1.2rem;
  border-bottom: 2px solid rgba(255,255,255,0.1);
}
.top-navbar .nav-link {
  color: #fff !important;
  font-weight: 500;
  transition: all 0.3s ease;
}
.top-navbar .nav-link:hover {
  opacity: 0.9;
  transform: translateY(-1px);
}
.top-navbar .dropdown-menu {
  border-radius: 12px;
  overflow: hidden;
  min-width: 230px;
  animation: dropdownFade 0.2s ease-in-out;
}
.top-navbar .dropdown-item:hover {
  background: #f7f9fc;
}
@keyframes dropdownFade {
  from {opacity: 0; transform: translateY(-8px);}
  to {opacity: 1; transform: translateY(0);}
}
.navbar-toggler {
  background-color: rgba(255,255,255,0.2);
  border-radius: 6px;
}
.badge {
  font-size: 11px;
  font-weight: 600;
}
</style>
