<?php
// admin/update_status.php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo json_encode(['error'=>'not admin']);
    exit;
}

$sessionId = $_POST['session_id'] ?? null;
$status    = $_POST['status'] ?? null;

if ($sessionId && $status) {
    updateSessionStatus($sessionId, $status, 'admin'); // pass "admin"
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['error'=>'Missing parameters']);
}
