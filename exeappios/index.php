<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | FRUIT WALA BREAK FAST</title>
    <link rel="shortcut icon" type="image/x-icon" href="login/assets/images/favicon.png" />

    <!-- Styles -->
    <link rel="stylesheet" href="login/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="login/assets/vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="login/assets/css/style.css">
    <link rel="stylesheet" href="css/toastr.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Nunito:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: #333;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 25px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            width: 90%;
            max-width: 380px;
            padding: 40px 30px;
            text-align: center;
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card h1 {
            font-weight: 700;
            font-size: 26px;
            color: #1a1a1a;
            margin-bottom: 10px;
        }

        .login-card p {
            font-size: 14px;
            color: #555;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            font-size: 15px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(37, 117, 252, 0.3);
        }

        .alert {
            font-size: 13px;
            border-radius: 10px;
        }

        .logo {
            width: 90px;
            margin-bottom: 15px;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid #fff;
            border-top-color: transparent;
            border-radius: 50%;
            margin-left: 8px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% { transform: rotate(360deg); }
        }

        .footer-text {
            font-size: 13px;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <img src="logo33.png" alt="FRUIT WALA BREAK FAST" class="logo">
         <h1>Welcome Back</h1>
        <p>Sign in to your account</p>

        <div id="danger_message" class="alert alert-danger" style="display: none;"></div>
        <div id="success_message" class="alert alert-success" style="display: none;"></div>

        <form id="loginForm">
            <div class="mb-3">
                <input type="text" class="form-control" id="Username" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" id="Password" placeholder="Password" required>
            </div>
            <input type="hidden" id="pageid" value="<?php echo $_GET['page'] ?? ''; ?>">
        </form>

        <button class="btn btn-primary" id="login">
            Login
            <div class="loading-spinner" id="spinner"></div>
        </button>

        <p class="footer-text">Powered by <strong>FRUIT WALA BREAK FAST</strong></p>
    </div>

    <!-- JS -->
    <script src="login/assets/js/jquery.js"></script>
    <script src="login/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/toastr.min.js"></script>

    <script>
        $(document).ready(function(){
            $(document).on("click", "#login", function(){
                var Username = $('#Username').val().trim();
                var Password = $('#Password').val().trim();
                var pageid = $('#pageid').val();

                if(Username === '' || Password === ''){
                    $('#danger_message').show().html("⚠️ Please enter username and password");
                    setTimeout(() => $('#danger_message').fadeOut("slow"), 2000);
                    return;
                }

                $("#login").attr('disabled', true);
                $("#spinner").show();
                $("#login").text('Logging in...');

                $.ajax({
                    url: "ajax_files/ajax_login.php",
                    method: "POST",
                    data: { action: "Login", Username: Username, Password: Password },
                    success: function(response){
                        let res;
                        try { res = JSON.parse(response); } catch (e) { res = {}; }

                        $("#login").attr('disabled', false);
                        $("#spinner").hide();
                        $("#login").text('Login');

                        if(res.status == 1){
                            $('#success_message').show().html("✅ Login successful! Redirecting...");
                            setTimeout(() => {
                                window.location.href = res.redirect || "home.php";
                            }, 1500);
                        } else {
                            $('#danger_message').show().html("❌ Invalid username or password");
                            setTimeout(() => $('#danger_message').fadeOut("slow"), 2000);
                        }
                    },
                    error: function(){
                        $("#login").attr('disabled', false);
                        $("#spinner").hide();
                        $("#login").text('Login');
                        toastr.error("Something went wrong. Please try again.");
                    }
                });
            });
        });
    </script>
</body>
</html>
