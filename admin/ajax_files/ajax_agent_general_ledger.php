<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'SaveVed'){
    $id = $_POST['id'];
    $UserId = $_POST['UserId'];
    $InvoiceNo = $_POST['InvoiceNo'];
    $TotAmt = $_POST['TotAmt'];
    $TotPaidAmt = $_POST['TotPaidAmt'];
    $TotBalAmt = $_POST['TotBalAmt'];
    $PaidAmt = $_POST['PaidAmt'];
    $BalAmt = $_POST['BalAmt'];
    $CreatedDate = date('Y-m-d');

    $sql = "SELECT Fname,Lname FROM tbl_users WHERE id='$UserId'";
    $row = getRecord($sql);
    $VedName = $row['Fname']." ".$row['Lname'];

    $sql2 = "SELECT MAX(SrNo) as maxid FROM tbl_agent_general_ledger WHERE Type='PR'";
    $row2 = getRecord($sql2);
    if($row2['maxid'] == ''){
        $SrNo = 1;
        $Code = "PR".$SrNo;
    }
    else{
        $SrNo = $row2['maxid']+1;
        $Code = "PR".$SrNo;
    }
    if($id == ''){
    $sql4 = "INSERT INTO tbl_agent_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='$UserId',AccountName='$VedName',InvoiceNo='$InvoiceNo',TotAmt='$TotAmt',TotPaidAmt='$TotPaidAmt',TotBalAmt='$TotBalAmt',PaidAmt='$PaidAmt',BalAmt='$BalAmt',CrDr='cr',Roll=5,Type='PR',CreatedDate='$CreatedDate'";
    $conn->query($sql4);
}
else{
    $sql4 = "UPDATE tbl_agent_general_ledger SET PaidAmt='$PaidAmt',ModifiedDate='$CreatedDate' WHERE id='$id'";
    $conn->query($sql4);
}
    echo 1;
}

if($_POST['action'] == 'getInvoice'){
    $id = $_POST['id'];?>
    <option value="" selected>...</option>
    <?php 
      $sql1 = "SELECT * FROM tbl_sell WHERE CustId='$id'";
     $row1 = getList($sql1);
     foreach($row1 as $result)
      {
      ?>
    <option <?php if($result['InvoiceNo'] == $row7['InvoiceNo']) {?> selected <?php } ?>  value="<?php echo $result['InvoiceNo']; ?>"><?php echo $result['InvoiceNo']; ?></option>
<?php } 
} 

if($_POST['action'] == 'getInvoiceDetails'){
    $id = $_POST['id'];
    
  
    $sql = "SELECT Total AS Amount FROM tbl_sell WHERE InvoiceNo='$id'";
    $row = getRecord($sql);
    $sql2 = "SELECT SUM(PaidAmt) As TotPaidAmt FROM tbl_agent_general_ledger WHERE InvoiceNo='$id' AND Type='PR'";
    $row2 = getRecord($sql2);
    $TotAmt = $row['Amount'];
    $TotPaidAmt = $row2['TotPaidAmt'];
    $TotBalAmt = $TotAmt - $TotPaidAmt;
    echo json_encode(array('TotAmt'=>$TotAmt,'TotPaidAmt'=>$TotPaidAmt,'TotBalAmt'=>$TotBalAmt));
}



     if ($_POST['action'] == 'getCustCommissionDetails') {
        $id = $_POST['uid'];
        $dealerid = $_POST['dealerid'];
        $sql2 = "SELECT Amount FROM tbl_agent_general_ledger WHERE DealerId='$id' AND Type='DINV'";
        $row2 = getRecord($sql2);
        $sql = "SELECT SUM(Amount) AS PaidAmt FROM tbl_agent_general_ledger WHERE DealerId='$id' AND Type!='DINV' AND CrDr='dr'";
        $row = getRecord($sql);
        $BalAmt = $row2['Amount'] - $row['PaidAmt'];
        echo json_encode(array('PaidAmt' => $row['PaidAmt'], 'BalAmt' => $BalAmt, 'TotAmt' => $row2['Amount']));
    }

    if ($_POST['action'] == 'getCollections') {
        $id = $_POST['uid'];
        $dealerid = $_POST['dealerid'];?>
        <h5>Pay Dealer Commission Transactions</h5>
        <table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr style="text-align:center;">
            <th>Date</th>
            <th>Mode</th>
             <th>Cr</th>
            <th>Dr</th>
           
            <th>Narration</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $srno = 1;
        $TotCreditAmt = 0;
        $TotDebitAmt = 0;
        $creditEntries = [];
        $debitEntries = [];

        $sql = "SELECT * FROM tbl_agent_general_ledger WHERE DealerId='$id' ORDER BY PaymentDate";
        $rx = $conn->query($sql);

        while ($nx = $rx->fetch_assoc()) {
            if ($nx['CrDr'] == 'cr') {
                $creditEntries[] = $nx;
                $TotCreditAmt += $nx['Amount'];
            } else {
                $debitEntries[] = $nx;
                $TotDebitAmt += $nx['Amount'];
            }
        }
        
        // Display Credit Entries First
        foreach ($creditEntries as $nx) {
        ?>
            <tr>
                <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $nx['PaymentDate']))); ?></td>
                <td><?php echo $nx['PayMode']; ?></td>
                <td>₹<?php echo number_format($nx['Amount'], 2); ?></td>
                <td>₹0</td>
                
                <td><?php echo $nx['Narration']; ?></td>
            </tr>
        <?php
        }
        
        // Display Debit Entries After
        foreach ($debitEntries as $nx) {
        ?>
            <tr>
                <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $nx['PaymentDate']))); ?></td>
                <td><?php echo $nx['PayMode']; ?></td>
                <td>₹0</td>
                <td>₹<?php echo number_format($nx['Amount'], 2); ?></td>
                
                <td><?php echo $nx['Narration']; ?></td>
            </tr>
        <?php
        }
        ?>
        
        <tr>
            <th colspan="2">Total</th>
            <th>₹<?php echo number_format($TotCreditAmt, 2); ?></th>
            <th>₹<?php echo number_format($TotDebitAmt, 2); ?></th>
            
            <td></td>
        </tr>

        <tr>
            <th colspan="2">Balance</th>
            <th colspan="3">₹<?php echo number_format($TotCreditAmt - $TotDebitAmt, 2); ?></th>
        </tr>
    </tbody>
</table>
    
        <script type="text/javascript">
            $(document).ready(function() {
                $('#example').DataTable({});
    
    
            });
        </script>
    <?php
    }


    if($_POST['action'] == 'SavePaymentReceive'){
        $id = $_POST['id'];
        $DealerId = addslashes(trim($_POST["DealerId"]));
    $CustId = addslashes(trim($_POST["CustId"]));
    $CustName = addslashes(trim($_POST["CustName"]));
    $CellNo = addslashes(trim($_POST['CellNo']));
    $Address = addslashes(trim($_POST['Address']));
    $InvNo = addslashes(trim($_POST['InvNo']));
    $InvId = addslashes(trim($_POST['InvId']));
    $Amount = addslashes(trim($_POST['Amount']));
    $Interest = addslashes(trim($_POST['Interest']));
    $TotAmount = addslashes(trim($_POST['TotAmount']));
    $PayDate = addslashes(trim($_POST['PayDate']));
    $PayType = addslashes(trim($_POST['PayType']));
    $Narration = addslashes(trim($_POST['Narration']));
    $MonthPeriod = addslashes(trim($_POST['MonthPeriod']));
    $Month = date('m', strtotime($PayDate));
    $ChequeNo = addslashes(trim($_POST['ChequeNo']));
    $ChqDate = addslashes(trim($_POST['ChqDate']));
    $BankName = addslashes(trim($_POST['BankName']));
    $UpiNo = addslashes(trim($_POST['UpiNo']));
    $CreatedDate = date('Y-m-d');
    
    if($id==''){
        
        $sql2 = "SELECT MAX(SrNo) as maxid FROM tbl_agent_general_ledger WHERE Type='PR'";
        $row2 = getRecord($sql2);
        if($row2['maxid'] == ''){
            $SrNo = 1;
            $Code = "PR".$SrNo;
        }
        else{
            $SrNo = $row2['maxid']+1;
            $Code = "PR".$SrNo;
        }
        
         $sql = "INSERT INTO tbl_agent_general_ledger SET DealerId='$DealerId',SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$CustName'
    ,Amount='$Amount',PaymentDate='$PayDate',PayMode='$PayType',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',
    Narration='$Narration',Type='PR',CrDr='dr',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
    $conn->query($sql);
    
        echo 1;
        }
        else{
        $sql4 = "UPDATE tbl_agent_general_ledger SET DealerId='$DealerId',UserId='$CustId',AccountName='$CustName',Amount='$Amount',PaymentDate='$PayDate',PayMode='$PayType',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',
        Narration='$Narration',Type='PR',CrDr='dr',ModifiedBy='$user_id',ModifiedDate='$CreatedDate' WHERE id='$id'";
        $conn->query($sql4);
        echo 1;
        }
    }
?>