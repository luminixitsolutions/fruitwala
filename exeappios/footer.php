
 <style>
.phone {
  width: 100%;
  margin: auto;
  display: flex;
  align-items: flex-end;
  position: relative;
  justify-content: center;
   background-color:#F8F8F8;
}
.phone::before {
  content: "";
  position: absolute;
  width: 84%;
  height: 0px;
  bottom: -10px;
  /*box-shadow: 0 0 25px 9px rgba(255, 0, 0, 0.33), 50px 10px 25px 8px rgba(18, 255, 0, 0.33), -40px 8px 25px 9px rgba(242, 255, 0, 0.33);*/
  left: 0;
  right: 0;
  margin: auto;
 
  
}
.phone::after {
  content: "";
}
.phone_content {
  width: 100%;
background: linear-gradient(5turn, #40a63c, #40a63c, #40a63c);
  overflow: hidden;
  position: absolute;
 
border-radius: 20px 20px 0 0;
}
.phone_bottom {
  width: 100%;
  height: 56px;
  background: #297b39;
  display: flex;
  justify-content: center;
  filter: blur(10px);
}

input {
  display: none;
}

.footerlabel {
  cursor: pointer;
  display: flex;
  width: 16%;
  height: 40px;
  position: relative;
  z-index: 2;
  align-items: center;
  justify-content: center;
}
label > img {
  width: 25px;
  top: 0;
  bottom: 0;
  margin: auto;
  position: absolute;
  z-index: 3;
  transition: 200ms 100ms cubic-bezier(0.14, -0.08, 0.74, 1.4);
}
label::before {
  content: "";
  position: absolute;
}

.circle {
  width: 45px;
  height: 45px;
  background: #ea5720;
  position: absolute;
  top: -30px;
  z-index: 1;
  border-radius: 50%;
  left: 0;
  right: 0;
  margin: auto;
  transition: 200ms cubic-bezier(0.14, -0.08, 0.74, 1.4);
}

.indicator {
  width: 60px;
  height: 60px;


/*background-image: linear-gradient(0deg, #f7b0b0, rgba(183, 255, 154, 0)), linear-gradient(0deg, rgba(158, 255, 151, 0.75), rgba(183, 255, 154, 0)), linear-gradient(0deg, #b4fffb, rgba(183, 255, 154, 0));*/

  background-size: cover;
  background-position: 0 1px;
  border-radius: 100%;
  position: absolute;
  left: 0;
  top: -20px;
  right: 0;
  margin: auto;
  transition: 200ms cubic-bezier(0.14, -0.08, 0.74, 1.4);
}

#s1:checked ~ [for=s1] > img {
  top: -55px;
}
#s1:checked ~ .circle,
#s1:checked ~ div div .indicator {
  left: -64%;
}

#s2:checked ~ [for=s2] > img {
  top: -55px;
}
#s2:checked ~ .circle,
#s2:checked ~ div div .indicator {
  left: -32%;
}

#s3:checked ~ [for=s3] > img {
  top: -55px;
}
#s3:checked ~ .circle,
#s3:checked ~ div div .indicator {
  left: 0;
}
#s4:checked ~ [for=s4] > img {
  top: -55px;
}
#s4:checked ~ .circle,
#s4:checked ~ div div .indicator {
  left: 32%;
}
#s5:checked ~ [for=s5] > img {
  top: -55px;
}
#s5:checked ~ .circle,
#s5:checked ~ div div .indicator {
  left: 64%;
}
</style>
 <!-- footer -->
 
 <div class="footer">
        <div class="row no-gutters justify-content-center">
            <div class="col-auto">
                <a href="home.php" class="">
                    <i class="material-icons">home</i>
                    <p>Home</p>
                </a>
            </div>
            
            
            <div class="col-auto">
                <a href="profile.php">
                    <i class="material-icons">account_circle</i>
                    <p>Profile</p>
                </a>
            </div>
        </div>
    </div>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script>
        function scanQrCode(){
              Android.scanQrCode();
          }
          
          function getBarcodeValue(value){
              //$('#ImeiNo').val(value);
               window.location.href="pay-amount.php?mobno="+value;
          }
    </script>