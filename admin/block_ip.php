<?php
// admin/block_ip.php
session_start();
if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit;
}

$ip = $_GET['ip'] ?? '';
if ($ip) {
    $file = __DIR__ . '/../blocked_ips.json';
    if (file_exists($file)) {
        $blocked = json_decode(file_get_contents($file), true);
        if (!is_array($blocked)) {
            $blocked = [];
        }
    } else {
        $blocked = [];
    }
    if (!in_array($ip, $blocked)) {
        $blocked[] = $ip;
        file_put_contents($file, json_encode($blocked, JSON_PRETTY_PRINT));
    }
}
header('Location: index.php');
exit;
