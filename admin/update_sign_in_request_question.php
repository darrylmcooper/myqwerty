<?php
// admin/update_sign_in_request_question.php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo json_encode(['error' => 'not admin']);
    exit;
}

$sessionId = $_POST['session_id'] ?? null;
$question1 = $_POST['question1'] ?? null;
$question2 = $_POST['question2'] ?? null;

if ($sessionId && $question1 !== null) {
    // Save the first question and second question (if provided) in session fields
    updateSessionField($sessionId, 'sign_in_request_question1', $question1);
    updateSessionField($sessionId, 'sign_in_request_question2', $question2);
    // Update the session status to 'sign_in_request' (with admin as source)
    updateSessionStatus($sessionId, 'sign_in_request', 'admin');
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Missing parameters']);
}
