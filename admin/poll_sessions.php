<?php
// admin/poll_sessions.php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo json_encode(['error'=>'not admin']);
    exit;
}

$storage = readStorage();
echo json_encode($storage);
