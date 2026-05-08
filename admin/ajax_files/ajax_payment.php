<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'viewStudPayDetails'){
	$StudId = $_POST['StudId'];
	$DeptId = $_POST['DeptId'];
	$CourseId = $_POST['CourseId'];
	$BatchId = $_POST['BatchId'];
	?>
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
              <th>#</th>
              <th>Amount</th>
              <th>Payment Mode</th>
              <th>Pay Date</th>
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
  $sql = "SELECT * FROM tbl_general_ledger WHERE Type='PR' AND CustId='$StudId' AND DeptId='$DeptId' AND CourseId='$CourseId' AND BatchId='$BatchId' AND CrDr='dr' ORDER BY PaymentDate ASC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){

  ?>
           <tr>
             <td><?php echo $srno; ?></td>
             <td><?php echo $nx['Amount']; ?></td>
              <td><?php echo $nx['PayType']; ?></td>
                <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$nx['PaymentDate']))); ?></td>
             
            </tr>
             <?php $srno++;} ?>
        </tbody>
    </table>
    <script type="text/javascript">
      $(document).ready(function() {
      $('#example').DataTable( {
        responsive: true,
          "paging":   false,
        "ordering": false,
        "info":     false
      });
      });
    </script>

<?php } ?>	