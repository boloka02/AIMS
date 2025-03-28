<?php
// login.php - Login and Register Page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AIMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
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
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden; /* Hide overflow */
        }
        .image-slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .image-slider img {
            width: 100%;
            height: auto;
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
            transition: color 0.3s ease-in-out;
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
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .image-container, .form-container {
                flex: none;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-container">
            <div class="image-slider" id="imageSlider">
                <img src="../image.jpg" alt="AIMS Image 1">
                <img src="../image.jpg" alt="AIMS Image 2">
                <img src="../image.jpg" alt="AIMS Image 3">
            </div>
        </div>
        <div class="form-container">
            <div class="container-box">
                <img src="logo/adon.png" alt="Logo" class="logo">
                <h2 class="mt-2">AIMS</h2>
                <p>Asset Inventory Management System</p>
                <div class="d-flex justify-content-center mb-3">
                    <button class="btn btn-toggle active" id="show-login">Login</button>
                    <button class="btn btn-toggle" id="show-register">Register</button>
                </div>
                
                <form id="login-form" action="../login/login_process.php" method="POST">
                    <div class="mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-custom">Login</button>
                </form>

                <form id="register-form" action="../login/register_process.php" method="POST" style="display: none;">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="reg-name" name="idnumber" placeholder="ID No." required>
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" id="reg-email" name="email" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" id="reg-password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-custom">Register</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            let currentSlide = 0;
            const slides = document.querySelectorAll('#imageSlider img');
            const slideWidth = slides[0].offsetWidth;

            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                $('#imageSlider').css('transform', `translateX(-${currentSlide * slideWidth}px)`);
            }

            setInterval(nextSlide, 3000); // Change slide every 3 seconds

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
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>