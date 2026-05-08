<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['User']['id'];
$sql = "SELECT Lattitude,Longitude,PerDaySalary,SalaryType,CreditSalaryStatus FROM tbl_users WHERE id='$user_id'";
$row = getRecord($sql);
$Latitude = $row['Lattitude'];
$Longitude = $row['Longitude'];

if($_POST['action']=='searchTask'){
    $TaskDate = $_POST['SearchText'];
    $Status = $_POST['status'];
    $sql2 = "SELECT tq.* FROM tbl_task_new tq WHERE tq.UserId='$user_id'";
    if($TaskDate!=''){
    $sql2.=" AND tq.TaskDate='$TaskDate'"; 
    }
    if($Status!=''){
    $sql2.=" AND tq.Status='$Status'"; 
    }
    $sql2.=" ORDER BY tq.id DESC";
    //echo $sql2;
    $rncnt2 = getRow($sql2);
    if($rncnt2 > 0){
        $res2 = getList($sql2);
            foreach($res2 as $row){ ?>
              <input type="hidden" id="user_id" value="<?php echo $UserId; ?>">
        
      
      
            <div class="col-12 col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                
                                <div class="row align-items-center">
                                   <!--<a href="task-details.php?id=<?php echo $row['id'];?>">-->
                                    <div class="col align-self-center pr-0">
                                        <h6 class=" mb-1" style="font-weight:500;font-size:16px;"><?php echo $row["TaskName"];?>
                                        <span style="font-size:12px;color:#0066ff;float: right;padding-right: 15px;"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['TaskDate']))); ?></span>
                                        </h6>
                                        
                                       <!--  <p class="small text-secondary" style="font-size:12px;"><i style="font-size:16px; color:rgba(232,37,37,1);" class="fa fa-user"></i> 
                                        Completed this task</p> -->
                                        <textarea class="form-control" name="Details" id="Details<?php echo $row['id'];?>" style="width: 98%;" <?php if($row['Status'] == 1){?>disabled<?php } ?>><?php echo $row['Details'];?></textarea>
                                    </div>
                                   <!-- </a>-->
                                    
                                  
                                </div>
                                <div class="row align-items-center" style="padding-left: 15px;padding-top: 7px;">
                                        <?php if($row['Status'] == 1){?>
                                        <button class="btn btn-sm btn-default px-4 rounded" disabled style="background-color: #009300;font-size: 13px;">
                                            Task Completed Dt. On <?php echo date("d/m/Y h:i a", strtotime(str_replace('-', '/',$row['ActionDate']))); ?>
                                        </button>
                                        
                                        <?php } else { ?>
                                        <button class="btn btn-sm btn-default px-4 rounded" onclick="completeTask(<?php echo $row['id'];?>,document.getElementById('Details<?php echo $row['id'];?>').value)" id="CompleteBtn<?php echo $row['id'];?>">
                                            Complete Task
                                        </button>
                                        <?php } ?>
                                    </div>
                                
                              
                            </div>
                        </div>
                    </div>
                    
                  
                     <?php $i++;} } else{?>
                        <h3 style="color:grey;padding-left: 10px;font-size: 15px;">No Task Found</h3>
                    <?php } 
            
}


if($_POST['action']=='completeTask'){
$id = $_POST['id'];
$Details = addslashes(trim($_POST['Details']));
$Status = 1;
$CreatedDate = date('Y-m-d H:i:s');
$CreatedDate2 = date('d/m/Y h:i a');
$ModifiedDate = date('Y-m-d');

    $query2 = "UPDATE tbl_task_new SET Details='$Details',Status='$Status',Latitude = '$Latitude',Longitude='$Longitude',ActionDate='$CreatedDate' WHERE id = '$id'";
  $conn->query($query2);
  echo $CreatedDate2;



    }
?>