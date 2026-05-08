<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Create Task";
$UserId = $_SESSION['User']['id'];
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname']." ".$row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId']; ?>
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
    <link href="css/toastr.min.css" rel="stylesheet">
      <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
</head>

<style>
    .custom-control {
  line-height: 24px;
  padding-top: 5px;
}

.autocomplete-list {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            background-color: white;
            z-index: 1000;
            width: 100%;
        }

        .autocomplete-item {
            padding: 8px;
            cursor: pointer;
        }

        .autocomplete-item:hover,
        .autocomplete-item.active {
            background-color: #98e6ed;
        }
</style>
<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        
<?php 
$CompId = $_GET['id'];
$sql77 = "SELECT * FROM tbl_task_new WHERE id='$CompId'";
                        $row7 = getRecord($sql77);
                        if($_GET['id'] == ''){
                            $TaskDate = date('Y-m-d');
                        }
                        else{
                            $TaskDate = $row7['TaskDate'];
                        }
                        ?>
        <div class="main-container">
            <div class="container">
               
 <form id="validation-form" method="post" enctype="multipart/form-data">
               

                <div class="card">
                     
                
                   
                    <div class="card-body">
                      
                         
                          <div class="card-body">
                      
                        
<div class="form-group float-label active">
                                <input type="text" class="form-control" autocomplete="off" name="EmployeeName" id="EmployeeName" placeholder="search Employee Name..." value="" required onClick="this.select();" required>
                                <div id="autocomplete-list2" class="autocomplete-list" style="display: none; position: absolute;"></div>
                                <label class="form-control-label">Employee <span class="text-danger">*</span></label>
                            </div>

                            <div class="form-group float-label active">
                                <input type="text" class="form-control" name="ExeId" id="ExeId" value="" readonly required>
                                <label class="form-control-label">Emp ID <span class="text-danger">*</span></label>
                            </div>
                            
                         <div class="form-group float-label active">
                            <input type="date" name="TaskDate" id="TaskDate" class="form-control"
                                                placeholder="" value="" required>
                            <label class="form-control-label">Task Date</label>                            
                        </div>
                        
                        <div class="form-group float-label active">
                            <input type="date" name="DueDate" id="DueDate" class="form-control"
                                                placeholder="" value="" required>
                            <label class="form-control-label">Task Completion Date</label>                            
                        </div>

                        <div class="form-group float-label active">
                          <textarea name="TaskName" id="TaskName" class="form-control"
                                                placeholder="" required></textarea>
                            <label class="form-control-label active">Task Name</label>
                        </div>
                   

                      <input type="hidden" name="action" value="SaveTask" id="action">  
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" name="submit" id="submit">Submit</button>
                    </div>
               
                </div>
            </div>
             </form>
        </div>
    </main>

    <!-- footer-->
    


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
    
    <script type="text/javascript">
        let currentFocus2 = -1;

        $(document).ready(function() {
            $("#EmployeeName").on("input", function() {
                let EmployeeName = $(this).val();
                if (EmployeeName.length === 0) {
                    $("#autocomplete-list2").hide();
                    return;
                }
                var action = "getEmpLocationList";
                $.ajax({
                    url: "ajax_files/ajax_dropdown.php",
                    method: "POST",
                    data: {
                        action: action,
                        EmployeeName: EmployeeName
                    },
                    success: function(data) {
                        console.log(data);
                        $("#autocomplete-list2").empty().show();
                        currentFocus2 = -1;

                        if (data.length === 0) {
                            $("#autocomplete-list2").hide();
                            return;
                        }

                        data.forEach(function(item) {
                            $("#autocomplete-list2").append(`<div class="autocomplete-item" onclick="getFrDetails(${item.id})">${item.Fname}-${item.Phone}</div>`);
                        });

                        $(".autocomplete-item").on("click", function() {
                            $("#EmployeeName").val($(this).text());
                            $("#autocomplete-list2").hide();
                        });
                    }
                });
            });

            $("#EmployeeName").on("keydown", function(e) {
                let items = $(".autocomplete-item");

                if (e.key === "ArrowDown") {
                    currentFocus2++;
                    if (currentFocus2 >= items.length) currentFocus2 = 0;
                    setActive(items);
                    e.preventDefault();
                } else if (e.key === "ArrowUp") {
                    currentFocus2--;
                    if (currentFocus2 < 0) currentFocus2 = items.length - 1;
                    setActive(items);
                    e.preventDefault();
                } else if (e.key === "Enter") {
                    e.preventDefault();
                    if (currentFocus2 > -1 && items[currentFocus2]) {
                        items.eq(currentFocus2).click();
                    }
                }
            });

            function setActive(items) {
                items.removeClass("active");
                if (currentFocus2 >= 0 && currentFocus2 < items.length) {
                    items.eq(currentFocus2).addClass("active");
                    items.eq(currentFocus2)[0].scrollIntoView({
                        block: "nearest"
                    });
                }
            }

            $(document).click(function(e) {
                if (!$(e.target).closest("#EmployeeName, #autocomplete-list2").length) {
                    $("#autocomplete-list2").hide();
                }
            });
        });

        function getFrDetails(id) {
            $('#ExeId').val(id);
        }
    </script>
    
     <script type="text/javascript">
     
     $(document).ready(function() {
     $('#validation-form').on('submit', function(e){
      e.preventDefault();    
     
     $.ajax({  
                url :"ajax_files/ajax_queries.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                 beforeSend:function(){
     $('#submit').attr('disabled','disabled');
     $('#submit').text('Please Wait...');
    },
                success:function(data){ 
                    //alert(data);exit();
                 //console.log(data);exit();
                     
                   if(data == 1){
            toastr.success('Task Created Successfully!', 'Success', {timeOut: 2000});  
            window.location.href="view-task.php";
              
        }
        else{
            toastr.error('Task Not Created. Try Again...', 'Error', {timeOut: 5000}); 
        }
                    
                    
                     
                      $('#submit').attr('disabled',false);
                    $('#submit').text('Update');
                }  
           })
     });

            
        });

        /*function save(){
            var TaskDate = $('#TaskDate').val();
            var TaskName = $('#TaskName').val();
            var action = "SaveTask";
    $.ajax({
    url:"ajax_files/ajax_queries.php",
    method:"POST",
    data : {action:action,TaskDate:TaskDate,TaskName:TaskName},
    success:function(data)
    {
        console.log(data);
       
        if(data == 1){
            toastr.success('Task Created Successfully!', 'Success', {timeOut: 2000});  
            window.location.href="view-task.php";
              
        }
        else{
            toastr.error('Task Not Created. Try Again...', 'Error', {timeOut: 5000}); 
        }
      
    }
    });
        }*/
    
</script>
</body>

</html>
