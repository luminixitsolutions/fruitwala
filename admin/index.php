<?php include_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $Proj_Title; ?> - Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include_once 'header_script.php'; ?>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #FFDEE9 0%, #B5FFFC 100%);
      overflow: hidden;
      position: relative;
    }

    /* Wavy Gradient Background */
    .bg-wave {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(180deg, #FFD26F 0%, #FFB347 50%, #FFCC33 100%);
      clip-path: ellipse(120% 60% at 50% 0%);
      z-index: 0;
      opacity: 0.7;
    }

    .login-container {
      position: relative;
      z-index: 2;
      background: rgba(255, 255, 255, 0.92);
      border-radius: 30px;
      padding: 3rem 2.5rem;
      width: 95%;
      max-width: 420px;
      box-shadow: 0 12px 40px rgba(255, 160, 80, 0.2);
      backdrop-filter: blur(10px);
      animation: fadeIn 0.8s ease-in-out;
      text-align: center;
    }

    .login-container img {
      width: 140px;
      margin-bottom: 1.2rem;
    }

    .login-container h4 {
      color: #333;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .login-container p {
      color: #777;
      font-size: 15px;
      margin-bottom: 2.5rem;
    }

    /* Floating Rounded Inputs */
    .form-group {
      position: relative;
      margin-bottom: 1.8rem;
    }

    .form-control {
      width: 100%;
      border: none;
      border-radius: 50px;
      padding: 14px 45px 14px 20px;
      font-size: 15px;
      background: rgba(255, 255, 255, 0.95);
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
      color: #444;
    }

    .form-control:focus {
      outline: none;
      background: #fff;
      box-shadow: 0 0 12px rgba(255, 150, 50, 0.4);
      transform: scale(1.02);
    }

    .form-group label {
      position: absolute;
      top: 13px;
      left: 22px;
      color: #777;
      font-size: 15px;
      pointer-events: none;
      transition: all 0.25s ease;
      background: transparent;
    }

    .form-control:focus + label,
    .form-control:not(:placeholder-shown) + label {
      top: -10px;
      left: 25px;
      background: #fff;
      color: #FF7F50;
      font-size: 13px;
      font-weight: 600;
      padding: 0 10px;
      border-radius: 20px;
      box-shadow: 0 2px 8px rgba(255,127,80,0.1);
    }

    /* Show/Hide Password Icon */
    .toggle-password {
      position: absolute;
      right: 20px;
      top: 50%;
      transform: translateY(-50%);
      color: #aaa;
      cursor: pointer;
      font-size: 17px;
      transition: color 0.3s;
    }

    .toggle-password:hover {
      color: #ff7f50;
    }

    /* Sign In Button */
    .btn-primary {
      background: linear-gradient(90deg, #FF7F50, #FFB347);
      border: none;
      border-radius: 50px;
      padding: 12px 0;
      font-weight: 700;
      font-size: 16px;
      color: white;
      transition: all 0.3s ease;
      box-shadow: 0 5px 15px rgba(255,127,80,0.3);
    }

    .btn-primary:hover {
      background: linear-gradient(90deg, #FFB347, #FF7F50);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(255,127,80,0.4);
    }

    /* Footer */
    .login-footer {
      font-size: 13px;
      color: #777;
      margin-top: 25px;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(40px);}
      to {opacity: 1; transform: translateY(0);}
    }
  </style>
</head>
<body>

  <div class="bg-wave"></div>

  <div class="login-container">
    <img src="logo.png" alt="<?php echo $Proj_Title; ?> Logo">
    <h4>Welcome Back 🍊</h4>
    <p>Login to continue managing your fruit business dashboard</p>

    <form id="validation-form" method="post">

      <div class="form-group">
        <input type="text" name="Username" id="Username" class="form-control" placeholder=" " required>
        <label for="Username">Username</label>
      </div>

      <div class="form-group">
        <input type="password" name="Password" id="Password" class="form-control" placeholder=" " required>
        <label for="Password">Password</label>
        <i class="fas fa-eye toggle-password" id="togglePassword"></i>
      </div>

      <button type="submit" id="submit" class="btn btn-primary w-100">SIGN IN</button>
    </form>

    <div class="login-footer">
      © <?php echo date("Y"); ?> <strong><?php echo $Proj_Title; ?></strong> — Fresh & Organic Business
    </div>
  </div>

  <?php include_once 'footer_script.php'; ?>

  <script>
  // Show/Hide Password Toggle
  document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('Password');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
  });

  // AJAX login logic
  function error_toast(){
    $.growl.error({ title:'Error', message:'Invalid Username / Password', location:'tr' });
  }
  function success_toast(){
    $.growl.success({ title:'Success', message:'Login Successful! Please Wait...', location:'tr' });
  }

  $(document).ready(function(){
    $('#validation-form').on('submit', function(e){
      e.preventDefault();
      if ($('#validation-form').valid()){ 
        $.ajax({  
          url :"ajax_files/ajax_login.php",  
          method:"POST",  
          data:new FormData(this),  
          contentType:false,  
          processData:false,  
          beforeSend:function(){
            $('#submit').attr('disabled','disabled').text('Please Wait...');
          },
          success:function(data){ 
            res = JSON.parse(data);
            if(res.Status == 1){
              success_toast();
              setTimeout(function(){ window.location.href = 'dashboard.php'; }, 2000);
            } else { error_toast(); }
            $('#submit').attr('disabled',false).text('Sign In');
          }  
        });
      }
    });
  });
  </script>

  <!-- Font Awesome for the eye icon -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</body>
</html>
