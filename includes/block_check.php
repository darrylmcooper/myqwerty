<?php
// includes/block_check.php
$blocked_file = __DIR__ . '/../blocked_ips.json';
if (file_exists($blocked_file)) {
    $blocked_ips = json_decode(file_get_contents($blocked_file), true);
    if (is_array($blocked_ips)) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'] 
            ?? ($_SERVER['HTTP_X_FORWARDED_FOR'] 
                ? trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0])
                : ($_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN'));
        if (in_array($ip, $blocked_ips)) {
            echo "Access blocked.";
            exit;
        }
    }
}
?>
