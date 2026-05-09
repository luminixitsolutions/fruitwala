<?php 
  $user_id = $_SESSION['Admin']['id'];
  $sql77 = "SELECT * FROM tbl_admin WHERE id='$user_id'";
  $row77 = getRecord($sql77);
?>
<div class="page-loader">
  <div class="bg-primary"></div>
</div>

<div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-white logo-dark shadow-lg">
  <div class="app-brand demo text-center py-3">
    <a href="dashboard.php" class="app-brand-text demo sidenav-text font-weight-normal">
      <img src="logo.png" alt="<?php echo $Proj_Title; ?>" class="img-fluid rounded shadow-sm" style="width:160px;height:45px;">
    </a>
    <a href="javascript:" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
      <i class="ion ion-md-menu align-middle text-muted"></i>
    </a>
  </div>

  <div class="sidenav-divider my-2"></div>

  <ul class="sidenav-inner py-2">

    <!-- DASHBOARD -->
    <li class="sidenav-item <?php echo ($Page=='Dashboard') ? 'active' : ''; ?>">
      <a href="dashboard.php" class="sidenav-link">
        <i class="sidenav-icon fas fa-chart-line me-2 text-info"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <!-- MASTERS -->
    <li class="sidenav-item <?php echo ($MainPage=='Masters') ? 'open active' : ''; ?>">
      <a href="javascript:void(0);" class="sidenav-link sidenav-toggle">
        <i class="sidenav-icon fas fa-cogs me-2 text-warning"></i>
        <span>Masters</span>
      </a>
      <ul class="sidenav-menu">
        <li class="sidenav-item <?php echo ($Page=='Location') ? 'open active' : ''; ?>">
          <a href="javascript:void(0);" class="sidenav-link sidenav-toggle">
            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
            <span>Locations</span>
          </a>
          <ul class="sidenav-menu">
            <li class="sidenav-item"><a href="country.php" class="sidenav-link"> Country</a></li>
            <li class="sidenav-item"><a href="state.php" class="sidenav-link">State</a></li>
            <li class="sidenav-item"><a href="city.php" class="sidenav-link">City / District</a></li>
            <li class="sidenav-item"><a href="zone.php" class="sidenav-link">Zone</a></li>
            <li class="sidenav-item"><a href="ward.php" class="sidenav-link">Ward No</a></li>
            <li class="sidenav-item"><a href="area.php" class="sidenav-link">Area</a></li>
          </ul>
        </li>

        <li class="sidenav-item">
          <a href="view-packages.php" class="sidenav-link">
            <i class="fas fa-box me-2 text-success"></i>
            <span>Packages</span>
          </a>
        </li>
      </ul>
    </li>

    <!-- CUSTOMERS -->
    <li class="sidenav-item <?php echo ($MainPage=='Customers') ? 'open active' : ''; ?>">
      <a href="javascript:void(0);" class="sidenav-link sidenav-toggle">
        <i class="sidenav-icon fas fa-users me-2 text-primary"></i>
        <span>Customers</span>
      </a>
      <ul class="sidenav-menu">
        <li class="sidenav-item"><a href="add-customer.php" class="sidenav-link">Add Customer</a></li>
        <li class="sidenav-item"><a href="view-customers.php" class="sidenav-link">View Customers</a></li>
         <li class="sidenav-item"><a href="view-expired-customers-before-2days.php" class="sidenav-link">2 Day Before Package Expired Customers</a></li>
        <li class="sidenav-item"><a href="view-expired-customers.php" class="sidenav-link">Package Expired Customers</a></li>
      </ul>
    </li>

    <!-- EXECUTIVES -->
    <li class="sidenav-item <?php echo ($MainPage=='Executives') ? 'open active' : ''; ?>">
      <a href="javascript:void(0);" class="sidenav-link sidenav-toggle">
        <i class="sidenav-icon fas fa-user-tie me-2 text-success"></i>
        <span>Executives</span>
      </a>
      <ul class="sidenav-menu">
        <li class="sidenav-item"><a href="add-executive.php" class="sidenav-link">Add Executive</a></li>
        <li class="sidenav-item"><a href="view-executive.php" class="sidenav-link">View Executives</a></li>
      </ul>
    </li>

    <!-- ORDERS -->
    <li class="sidenav-item <?php echo ($MainPage=='Orders') ? 'open active' : ''; ?>">
      <a href="javascript:void(0);" class="sidenav-link sidenav-toggle">
        <i class="sidenav-icon fas fa-shopping-basket me-2 text-info"></i>
        <span>Today Orders</span>
      </a>
      <ul class="sidenav-menu">
          <li class="sidenav-item"><a href="tomorrow-orders.php" class="sidenav-link">Tomorrow Orders</a></li>
        <li class="sidenav-item"><a href="today-pending-orders.php" class="sidenav-link">Pending Orders</a></li>
        <li class="sidenav-item"><a href="today-delivered-orders.php" class="sidenav-link">Delivered Orders</a></li>
      </ul>
    </li>

    <!-- DELIVERY STATUS -->
    <li class="sidenav-item <?php echo ($MainPage=='Delivery Status') ? 'open active' : ''; ?>">
      <a href="customer-delivery-status.php" class="sidenav-link">
        <i class="sidenav-icon fas fa-boxes me-2 text-teal"></i>
        <span>Delivery Status</span>
      </a>
    </li>

    <!-- REPORTS -->
    <li class="sidenav-item <?php echo ($MainPage=='Reports') ? 'open active' : ''; ?>">
      <a href="javascript:void(0);" class="sidenav-link sidenav-toggle">
        <i class="sidenav-icon fas fa-file-alt me-2 text-danger"></i>
        <span>Reports</span>
      </a>
      <ul class="sidenav-menu">
          <li class="sidenav-item"><a href="all-order-report.php" class="sidenav-link">All Orders</a></li>
        <li class="sidenav-item"><a href="delivered-report.php" class="sidenav-link">Delivered Orders</a></li>
        <li class="sidenav-item"><a href="pending-report.php" class="sidenav-link">Pending Orders</a></li>
        <li class="sidenav-item"><a href="recent-activity-report.php" class="sidenav-link">Recent Activity</a></li>
         <li class="sidenav-item"><a href="delivery-graph-report.php" class="sidenav-link">Delivery Growth Report</a></li>
         <li class="sidenav-item"><a href="earning-growth-report.php" class="sidenav-link">Earning Growth Report</a></li>
        <li class="sidenav-item"><a href="active-customers-report.php" class="sidenav-link">Active Customers</a></li>
        <li class="sidenav-item"><a href="expired-packages-report.php" class="sidenav-link">Expired Packages</a></li>
        <li class="sidenav-item"><a href="attendance-report-month-wise.php" class="sidenav-link">Attendance Report</a></li>
        <li class="sidenav-item"><a href="lead-report.php" class="sidenav-link">Lead Report</a></li>
        <li class="sidenav-item"><a href="expense-report.php" class="sidenav-link">Expense Report</a></li>
        <li class="sidenav-item"><a href="customer-payment-ledger.php" class="sidenav-link">Payment Ledger</a></li>
      </ul>
    </li>
    
    <li class="sidenav-item <?php echo ($MainPage=='Expenses') ? 'open active' : ''; ?>">
      <a href="javascript:void(0);" class="sidenav-link sidenav-toggle">
        <i class="sidenav-icon fas fa-file-alt me-2 text-danger"></i>
        <span>Expenses</span>
      </a>
      <ul class="sidenav-menu">
        <li class="sidenav-item"><a href="expenses-category.php" class="sidenav-link">Expense Category</a></li>
        <li class="sidenav-item"><a href="add-expenses.php" class="sidenav-link">Add New Expenses</a></li>
        <li class="sidenav-item"><a href="view-expenses.php" class="sidenav-link">View Expenses</a></li>
      </ul>
    </li>
    
     <li class="sidenav-item <?php echo ($MainPage=='Leads') ? 'open active' : ''; ?>">
      <a href="javascript:void(0);" class="sidenav-link sidenav-toggle">
        <i class="sidenav-icon fas fa-file-alt me-2 text-danger"></i>
        <span>Leads</span>
      </a>
      <ul class="sidenav-menu">
        <li class="sidenav-item"><a href="add-lead.php" class="sidenav-link">Add New Lead</a></li>
        <li class="sidenav-item"><a href="view-leads.php" class="sidenav-link">View Leads</a></li>
      </ul>
    </li>
    
    <li class="sidenav-item <?php echo ($MainPage=='Payments') ? 'open active' : ''; ?>">
      <a href="javascript:void(0);" class="sidenav-link sidenav-toggle">
        <i class="sidenav-icon fas fa-file-alt me-2 text-danger"></i>
        <span>Customer Payments</span>
      </a>
      <ul class="sidenav-menu">
        <li class="sidenav-item"><a href="add-receive-amount.php" class="sidenav-link">Receive Amount</a></li>
        <li class="sidenav-item"><a href="receive-amount.php" class="sidenav-link">View Receive Amount</a></li>
      </ul>
    </li>

    <!-- LOGOUT -->
    <li class="sidenav-item mt-3">
      <a href="logout.php" class="sidenav-link text-danger">
        <i class="sidenav-icon fas fa-sign-out-alt me-2"></i>
        <span>Logout</span>
      </a>
    </li>

  </ul>
</div>

<!-- 🌟 CUSTOM STYLING -->
<style>
#layout-sidenav {
  border-top-right-radius: 20px;
  border-bottom-right-radius: 20px;
  background: #ffffff;
  overflow-y: auto;
  transition: all 0.3s ease-in-out;
}
#layout-sidenav .sidenav-item {
  border-radius: 10px;
  margin: 3px 10px;
}
#layout-sidenav .sidenav-link {
  color: #444;
  font-weight: 500;
  border-radius: 10px;
  padding: 10px 15px;
  transition: all 0.3s ease;
}
#layout-sidenav .sidenav-link:hover {
  background: linear-gradient(45deg, #0072ff, #00c6ff);
  color: #fff !important;
  transform: translateX(4px);
  box-shadow: 0 3px 8px rgba(0,114,255,0.3);
}
#layout-sidenav .sidenav-item.active > .sidenav-link,
#layout-sidenav .sidenav-item.open.active > .sidenav-link {
  background: linear-gradient(45deg, #4776E6, #8E54E9);
  color: #fff !important;
  font-weight: 600;
  box-shadow: 0 3px 10px rgba(71,118,230,0.4);
}
.sidenav-icon {
  font-size: 18px;
  width: 25px;
  text-align: center;
}
.sidenav-menu .sidenav-link {
  padding-left: 35px;
  font-size: 15px;
}
.sidenav-inner::-webkit-scrollbar {
  width: 6px;
}
.sidenav-inner::-webkit-scrollbar-thumb {
  background-color: #d4d4d4;
  border-radius: 10px;
}
.sidenav-inner::-webkit-scrollbar-thumb:hover {
  background-color: #a0a0a0;
}
.sidenav {
  width: 250px;
  min-height: 100vh;
  transition: all 0.3s;
  background: #1e1e2f;
}
.sidenav-item > a {
  display: flex;
  align-items: center;
  padding: 10px 16px;
  color: #b8c2cc;
  text-decoration: none;
  border-radius: 8px;
  transition: 0.3s;
}
.sidenav-item.active > a, 
.sidenav-item > a:hover {
  background: rgba(255,255,255,0.1);
  color: #fff;
}
.sidenav-item .sidenav-icon {
  width: 25px;
  text-align: center;
  opacity: 0.9;
}
.sidenav-menu {
  padding-left: 20px;
}
.sidenav-menu a {
  font-size: 14px;
}
.sidenav-menu a:hover {
  color: #fff !important;
}
.sidenav-item .sidenav-link > :not(.sidenav-icon) {
    flex: 0 1 auto;
    padding-right: 5px;
}
</style>
