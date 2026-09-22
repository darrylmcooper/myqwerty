<?php
// admin/delete_session.php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'delete_all') {
    deleteAllSessions();
    header('Location: index.php');
    exit;
}

$sessionId = $_GET['session_id'] ?? null;
if ($sessionId) {
    deleteSession($sessionId);
    echo "Session deleted.";
} else {
    echo "No session_id provided.";
}
