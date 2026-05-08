 <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      
      <div class="modal-header">
        <h4 class="modal-title">Today Start Attendance</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        
      </div>

      <div class="modal-body">
        <form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
             <input type="hidden" id="TempPrdId" name="TempPrdId" value="<?php echo rand(10000,99999);?>">
        <input type="hidden" name="date" id="CreatedDate" value="<?php echo date('Y-m-d');?>">
  
                       
                        
                        <div class="row">
                                         <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                        <div class="form-group float-label active">
                            <input type="file" class="form-control" name="Photo" accept=".jpg" onchange="previewSelfie(this)">
                            <label class="form-control-label">Upload Selfi</label>
                        </div>
                        <!-- Image preview container (initially hidden) -->
<!-- Preview Image -->
<div id="selfie-preview-container" style="margin-top: 10px; display: none;">
    <img id="selfie-preview" src="#" alt="Selfie Preview"
         style="max-width: 20%; height: auto; border-radius: 10px; border: 1px solid #ccc; cursor: pointer;"
         data-toggle="modal" data-target="#selfieModal" />
</div>

<!-- Modal for Fullscreen View -->
<div class="modal fade" id="selfieModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body p-0 position-relative text-center">
        <!-- Close Button -->
        <button type="button" class="close position-absolute" style="
          top: 10px;
          right: 15px;
          z-index: 10;
          background: rgba(0, 0, 0, 0.7);
          color: #fff;
          font-size: 2rem;
          border: none;
          border-radius: 50%;
          width: 40px;
          height: 40px;
          line-height: 36px;
          text-align: center;
          box-shadow: 0 0 5px #000;
        " data-dismiss="modal" aria-label="Close">&times;</button>

        <!-- Fullscreen Image -->
        <img id="modal-selfie-image" src="#" alt="Full View" class="img-fluid rounded">
      </div>
    </div>
  </div>
</div>
                    </div><br>
                                        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-6 ">
                        <div class="form-group float-label active">
                            <input type="date" class="form-control" value="<?php echo date('Y-m-d');?>" readonly>
                            <label class="form-control-label">Date</label>
                        </div>
                    </div>
                    
                    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-6 ">
                        <div class="form-group float-label active">
                            <input type="text" class="form-control" value="<?php echo date('h:i a');?>" readonly>
                            <label class="form-control-label">Time</label>
                        </div>
                    </div>
                    
  
                    
                   
                    </div>
<input type="hidden" name="userid" value="<?php echo $_SESSION['User']['id']; ?>" id="userid">

<input type="hidden" name="frlattitude" value="<?php echo $FrLattitude; ?>" id="frlattitude"> 

<input type="hidden" name="frlongitude" value="<?php echo $FrLongitude; ?>" id="frlongitude"> 



                      <input type="hidden" name="action" value="takeAttendance" id="action">  
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" id="submit">Submit</button>
                        <span style="color:red;display:none;" id="errmsg">Please Mark Attendace From Your Outlet</span>
                    </div>
                </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script>
function previewSelfie(input) {
    const previewContainer = document.getElementById('selfie-preview-container');
    const previewImage = document.getElementById('selfie-preview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.style.display = 'block';
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        previewContainer.style.display = 'none';
        previewImage.src = '#';
    }
}
</script>
