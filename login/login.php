<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AIMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <style>
         body {
            font-family: 'Roboto', sans-serif;
        }

        #authPage {
            opacity: 0;
            pointer-events: none;
            transform: translateY(20px);
            transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
        }

        #authPage.active {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }

        #landingPage {
            opacity: 1;
            transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
            transform: scale(1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        #landingPage.hidden {
            opacity: 0;
            pointer-events: none;
            display: none;
            transform: scale(0.9);
        }

        #landingTitle,
        #startBtn {
            transform: scale(0.8);
            opacity: 0;
            transition: transform 0.5s ease-out, opacity 0.5s ease-out;
        }

        #landingTitle.popped,
        #startBtn.popped {
            transform: scale(1);
            opacity: 1;
        }

        .aims-logo {
            width: auto;
            height: 200px;
            margin-bottom: 20px;
            transform: scale(0.8);
            opacity: 0;
            transition: transform 0.5s ease-out, opacity 0.5s ease-out;
            margin-top: -50px;
        }

        .aims-logo.popped {
            transform: scale(1);
            opacity: 1;
        }

        .auth-logo { /* New class for auth page logo */
            width: auto;
            height: 80px; /* Even smaller for auth box */
            margin-bottom: 10px;
            display: block; /* Ensure it behaves as a block */
            margin-left: auto; /* Center horizontally */
            margin-right: auto;
        }
    </style>

</head>

<body
    class="h-screen w-screen overflow-hidden bg-cover bg-center bg-[url('../image/cp.png')] md:bg-[url('../image/pc.png')]">

    <div id="landingPage"
        class="h-full w-full flex flex-col items-center justify-center text-white transition-opacity duration-500">
        <img src="../image/logo.png" alt="AIMS" class="aims-logo" />
        <div class="text-center">
            <h1 id="landingTitle" class="text-4xl md:text-6xl font-bold mb-4">ADON INFORMATION MANAGEMENT SYSTEM</h1>
            <button id="startBtn"
                class="mt-10 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full text-lg md:text-xl">
                Get Started</button>
        </div>
    </div>

    <div id="authPage"
        class="absolute top-0 left-0 h-full w-full flex flex-col md:flex-row items-center justify-center text-white opacity-0 pointer-events-none px-6">

        <div class="w-full md:w-1/2 flex flex-col items-center md:items-start text-center md:text-left mb-10 md:mb-0">
            <h1 id="welcomeText" class="text-4xl md:text-6xl font-bold leading-tight"></h1>
            <p id="credentialsPrompt" class="text-lg md:text-2xl mt-4"></p>
        </div>

        <div class="w-full max-w-md bg-white/20 backdrop-blur-sm p-6 rounded-2xl shadow-lg text-white">
        <img src="../image/logo.png" alt="AIMS" class="auth-logo" />
            <div class="flex justify-center space-x-4 mb-6">
                <button id="loginBtn"
                    class="w-1/2 py-2 rounded-full font-semibold bg-white text-black hover:bg-gray-300 transition">
                    LOGIN</button>
                <button id="registerBtn"
                    class="w-1/2 py-2 rounded-full font-semibold bg-transparent border border-white hover:bg-white hover:text-black transition">
                    REGISTER</button>
            </div>

            <div id="loginForm" class="space-y-4">
    <form id="login-form" action="../login/login_process.php" method="POST">
        <div class="mb-3">
            <input type="email" class="w-full py-2 px-4 bg-white/30 text-white placeholder-white rounded-full outline-none" name="email" placeholder="Email" required>
        </div>
        <div class="mb-3 relative">
            <input type="password" class="w-full py-2 px-4 bg-white/30 text-white placeholder-white rounded-full outline-none pr-10" name="password" id="login-password" placeholder="Password" required>
            <span class="absolute top-2.5 right-4 text-white cursor-pointer" id="toggle-login-password"><i class="fa fa-eye"></i></span>
        </div>
        <button type="submit" class="w-full py-2 mt-4 rounded-full bg-white text-black font-semibold hover:bg-gray-300 transition">LOGIN</button>
    </form>
</div>

<div id="registerForm" class="space-y-4 hidden">
    <form id="register-form" action="../login/register_process.php" method="POST">
        <div class="mb-3">
            <input type="text" class="w-full py-2 px-4 bg-white/30 text-white placeholder-white rounded-full outline-none" name="idnumber" placeholder="ID No." required>
        </div>
        <div class="mb-3">
            <input type="email" class="w-full py-2 px-4 bg-white/30 text-white placeholder-white rounded-full outline-none" name="email" placeholder="Email" required>
        </div>
        <div class="mb-3 relative">
            <input type="password" class="w-full py-2 px-4 bg-white/30 text-white placeholder-white rounded-full outline-none pr-10" name="password" id="register-password" placeholder="Password" required>
            <span class="absolute top-2.5 right-4 text-white cursor-pointer" id="toggle-register-password"><i class="fa fa-eye"></i></span>
        </div>
        <button type="submit" class="w-full py-2 mt-4 rounded-full bg-white text-black font-semibold hover:bg-gray-300 transition">REGISTER</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#toggle-login-password').click(function () {
            var passwordField = $('#login-password');
            var type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });

        $('#toggle-register-password').click(function () {
            var passwordField = $('#register-password');
            var type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });
    });
</script>


        </div>
    </div>



    <audio id="welcomeAudio" src="../image/voice.mp3"></audio>

    <script>
        const startBtn = document.getElementById('startBtn');
        const landingPage = document.getElementById('landingPage');
        const authPage = document.getElementById('authPage');
        const loginBtn = document.getElementById('loginBtn');
        const registerBtn = document.getElementById('registerBtn');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const welcomeTextElement = document.getElementById('welcomeText');
        const credentialsPromptElement = document.getElementById('credentialsPrompt');
        const landingTitle = document.getElementById('landingTitle');
        const aimsLogo = document.querySelector('.aims-logo');
        const welcomeAudio = document.getElementById('welcomeAudio');

        const welcomeText = "Welcome to AIMS";
        const credentialsText = "Please Enter your Credentials";
        let charIndexWelcome = 0;
        let charIndexCredentials = 0;
        let inactivityTimer;

        function typeWriterWelcome() {
            if (charIndexWelcome < welcomeText.length) {
                welcomeTextElement.textContent += welcomeText.charAt(charIndexWelcome);
                charIndexWelcome++;
                setTimeout(typeWriterWelcome, 50);
            } else {
                typeWriterCredentials();
            }
        }

        function typeWriterCredentials() {
            if (charIndexCredentials < credentialsText.length) {
                credentialsPromptElement.textContent += credentialsText.charAt(charIndexCredentials);
                charIndexCredentials++;
                setTimeout(typeWriterCredentials, 50);
            } else {
                startInactivityTimer();
                //loop
                setTimeout(() => {
                    welcomeTextElement.textContent = "";
                    credentialsPromptElement.textContent = "";
                    charIndexWelcome = 0;
                    charIndexCredentials = 0;
                    typeWriterWelcome();
                }, 1500);
            }
        }

        function startTyping() {
            welcomeTextElement.textContent = "";
            credentialsPromptElement.textContent = "";
            charIndexWelcome = 0;
            charIndexCredentials = 0;
            typeWriterWelcome();
            welcomeAudio.play();
        }

        function startInactivityTimer() {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(returnToLanding, 10000); // 10 seconds
        }

        function returnToLanding() {
            authPage.style.opacity = '0';
            setTimeout(() => {
                authPage.classList.remove('active');
                landingPage.style.display = 'flex';
                landingPage.style.opacity = '1';
                landingPage.classList.remove('hidden');
                landingPage.style.transform = 'scale(1)';
                aimsLogo.classList.remove('popped');
                landingTitle.classList.remove('popped');
                startBtn.classList.remove('popped');
                setTimeout(() => {
                    aimsLogo.classList.add('popped');
                    landingTitle.classList.add('popped');
                    startBtn.classList.add('popped');
                }, 100);
            }, 500);
        }

        function resetInactivityTimer() {
            startInactivityTimer();
        }

        function authPageClick() {
            resetInactivityTimer();
        }

        startBtn.addEventListener('click', () => {
            landingPage.style.opacity = '0';
            landingPage.style.transform = 'scale(0.9)';
            setTimeout(() => {
                landingPage.style.display = 'none';
                authPage.classList.add('active');
                startTyping();
            }, 500);
        });

        loginBtn.addEventListener('click', () => {
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
            loginBtn.classList.add('bg-white', 'text-black');
            registerBtn.classList.remove('bg-white', 'text-black');
            authPageClick();
        });

        registerBtn.addEventListener('click', () => {
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
            registerBtn.classList.add('bg-white', 'text-black');
            loginBtn.classList.remove('bg-white', 'text-black');
            authPageClick();
        });

        authPage.addEventListener('click', resetInactivityTimer);
        authPage.addEventListener('mousemove', resetInactivityTimer);
        authPage.addEventListener('keypress', resetInactivityTimer);

        // Initial pop-in animation
        setTimeout(() => {
            aimsLogo.classList.add('popped');
            landingTitle.classList.add('popped');
            startBtn.classList.add('popped');
        }, 500);
    </script>
</body>

</html>