<?php
// update_online.php
require_once __DIR__ . '/includes/db.php';

$sessionId = $_GET['session_id'] ?? null;
if ($sessionId) {
    // Update only the last_seen timestamp without affecting last_update.
    updateSessionLastSeen($sessionId, time());
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'No session_id provided']);
}
