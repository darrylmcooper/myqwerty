<?php
// poll_status.php
require_once __DIR__ . '/includes/db.php';

$sessionId = $_GET['session_id'] ?? null;
if (!$sessionId) {
    echo json_encode(['error' => 'No session_id']);
    exit;
}
$sess = getSession($sessionId);
if (!$sess) {
    echo json_encode(['error' => 'Session not found']);
    exit;
}
echo json_encode(['status' => $sess['status']]);
