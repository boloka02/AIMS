<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg" href="https://adongroup.com.au/wp-content/uploads/2020/12/aog-favicon-192px.svg">
    <title>ADON PH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #121f3d;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            display: flex;
            width: 80%;
            max-width: 1200px;
        }
        .image-container {
            flex: 1.5;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .image-container img {
            width: 100%;
            max-width: 500px;
            height: auto;
        }
        .image-container p {
            margin-top: 10px;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .form-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container-box {
            background-color: #192d52;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
            text-align: center;
            width: 350px;
        }
        .btn-custom {
            background-color: #6495ed;
            color: white;
            width: 100%;
        }
        .btn-toggle {
            width: 50%;
            color: #bdc3c7;
            border: none;
            background: none;
            padding: 8px 15px;
        }
        .btn-toggle.active {
            color: white !important;
            border-bottom: 2px solid white;
        }
        .form-control {
            background-color: #28477a;
            color: white;
            border: 1px solid #385f9c;
        }
        .logo {
            border-radius: 50%;
            width: 90px;
            height: 90px;
        }
        .password-container {
            position: relative;
        }
        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .image-container, .form-container {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-container">
            <img src="https://adongroup.com.au/wp-content/uploads/2019/12/AdOn-Logo-v4.gif" alt="Company Logo">
            <p>ADON MANAGEMENT SYSTEM</p>
        </div>
        <div class="form-container">
            <div class="container-box">
                <h2 class="mt-2">AIMS</h2>
                <div class="d-flex justify-content-center mb-3">
                    <button class="btn btn-toggle active" id="show-login">Login</button>
                    <button class="btn btn-toggle" id="show-register">Register</button>
                </div>
                
                <form id="login-form" action="../login/login_process.php" method="POST">
                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>
                    <div class="mb-3 password-container">
                        <input type="password" class="form-control" name="password" id="login-password" placeholder="Password" required>
                        <span class="eye-icon" id="toggle-login-password"><i class="fa fa-eye"></i></span>
                    </div>
                    <button type="submit" class="btn btn-custom">Login</button>
                </form>

                <form id="register-form" action="../login/register_process.php" method="POST" style="display: none;">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="idnumber" placeholder="ID No." required>
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>
                    <div class="mb-3 password-container">
                        <input type="password" class="form-control" name="password" id="register-password" placeholder="Password" required>
                        <span class="eye-icon" id="toggle-register-password"><i class="fa fa-eye"></i></span>
                    </div>
                    <button type="submit" class="btn btn-custom">Register</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#show-login').click(function() {
                $('#login-form').fadeIn();
                $('#register-form').hide();
                $('#show-login').addClass('active');
                $('#show-register').removeClass('active');
            });
            $('#show-register').click(function() {
                $('#register-form').fadeIn();
                $('#login-form').hide();
                $('#show-register').addClass('active');
                $('#show-login').removeClass('active');
            });

            // Toggle password visibility for login
            $('#toggle-login-password').click(function() {
                var passwordField = $('#login-password');
                var type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });

            // Toggle password visibility for register
            $('#toggle-register-password').click(function() {
                var passwordField = $('#register-password');
                var type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>