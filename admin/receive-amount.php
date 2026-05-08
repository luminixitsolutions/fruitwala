<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Account";
$Page = "View-Receive-Amount";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Company Expenses</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />

<?php include_once 'header_script.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>

<div class="layout-container">
<?php include_once 'top_header.php'; ?>

<?php
// Handle delete action
if(isset($_GET["action"]) && $_GET["action"]=="delete"){
  $id = $_GET["id"];
  $sql_delete = "DELETE FROM tbl_general_ledger WHERE id='$id'";
  $conn->query($sql_delete);
  echo "<script>alert('Record Deleted Successfully!');window.location.href='receive-amount.php';</script>";
  exit;
}
?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">
  View Receive Amount
  <span style="float: right;">
    <a href="add-receive-amount.php" class="btn btn-secondary btn-round">
      <i class="ion ion-md-add mr-2"></i> Receive Amount
    </a>
  </span>
</h4>

<div class="card" style="padding-right:10px;padding-left:10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered " style="width:100%">
  <thead>
    <tr>
      <th>#</th>
                <th>Action</th>
                 <th>Voucher No</th>
                 <!-- <th>Invoice No</th> -->
                 <th>Payment Date</th>
                 
                <th>Customer Name</th>
              
                <th>Amount</th>
                
                <th>Payment Mode</th>
    </tr>
  </thead>
  <tbody>
    <?php 
            $i=1;
            $sql = "SELECT * FROM tbl_general_ledger WHERE Type='PR'";
                  
             
              
            
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND PaymentDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND PaymentDate<='$ToDate'";
            }
            $sql.= " ORDER BY id DESC";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
            ?>
            <tr style="<?php echo $bcolor;?>">
               
              <td><?php echo $i;?></td>
              <td> 
                   <a href="add-receive-amount.php?id=<?php echo $row['id']; ?>" ><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;
                   <a onClick="return confirm('Are you sure you want delete this record');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" ><i class="lnr lnr-trash text-danger"></i></a>&nbsp;&nbsp;
                   <a href="print-payment-receipt.php?id=<?php echo $row['id']; ?>" target="_blank" ><i class="lnr lnr-printer text-danger"></i></a>
               </td>
               <td><?php echo $row['Code']; ?></td>
                <!-- <td><?php echo $row['InvNo']; ?></td> -->
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['PaymentDate']))); ?></td>
           
                 <td><?php echo $row['AccountName']; ?></td>
            
        <td>₹<?php echo number_format($row['Amount'],2); ?></td>
    
                 <td><?php echo $row['PayMode']; ?></td>
               
               
            </tr>
           <?php $i++;} ?>
  </tbody>

</table>
</div>
</div>
</div>

<?php include_once 'footer.php'; ?>
</div>

</div>
</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>

<?php include_once 'footer_script.php'; ?>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

<!-- DataTables Buttons Extension -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

<!-- JSZip (required for Excel export) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- Excel export -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>


<script type="text/javascript">
$(document).ready(function() {
  $('#example').DataTable({
     scrollX: true,
    order: [[1, "desc"]],
    pageLength: 25,
    dom: 'Bfrtip', // Add this to show buttons
    buttons: [
      {
        extend: 'excelHtml5',
        title: 'Lead_List',
        text: '<i class="fa fa-file-excel"></i> Excel',
        className: 'btn btn-success btn-sm'
      }
    ]
  });
});
</script>


</body>
</html>
