<?php
// includes/db.php

function readStorage() {
    $file = __DIR__ . '/../storage.json';
    if (!file_exists($file)) {
        return ['sessions' => []];
    }
    $json = file_get_contents($file);
    if ($json === false) {
        return ['sessions' => []];
    }
    $data = json_decode($json, true);
    if (!is_array($data)) {
        return ['sessions' => []];
    }
    if (!isset($data['sessions']) || !is_array($data['sessions'])) {
        $data['sessions'] = [];
    }
    return $data;
}

function writeStorage($data) {
    $file = __DIR__ . '/../storage.json';
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

function saveSession($sessionData) {
    $storage = readStorage();
    $sessions = $storage['sessions'];
    $found = false;
    for ($i = 0; $i < count($sessions); $i++) {
        if ($sessions[$i]['session_id'] === $sessionData['session_id']) {
            $sessions[$i] = $sessionData;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $sessions[] = $sessionData;
    }
    $storage['sessions'] = $sessions;
    writeStorage($storage);
}

function getSession($sessionId) {
    $storage = readStorage();
    foreach ($storage['sessions'] as $sess) {
        if ($sess['session_id'] === $sessionId) {
            return $sess;
        }
    }
    return null;
}

/**
 * Update only the 'status' (and last_update) for a given session.
 */
function updateSessionStatus($sessionId, $newStatus, $source = 'admin') {
    $storage = readStorage();
    for ($i = 0; $i < count($storage['sessions']); $i++) {
        if ($storage['sessions'][$i]['session_id'] === $sessionId) {
            $storage['sessions'][$i]['status'] = $newStatus;
            $storage['sessions'][$i]['last_update'] = time();
            $storage['sessions'][$i]['update_source'] = $source;
            break;
        }
    }
    writeStorage($storage);
}

/**
 * Update a session field. Note: This function resets last_update.
 */
function updateSessionField($sessionId, $fieldName, $fieldValue) {
    $storage = readStorage();
    for ($i = 0; $i < count($storage['sessions']); $i++) {
        if ($storage['sessions'][$i]['session_id'] === $sessionId) {
            $storage['sessions'][$i][$fieldName] = $fieldValue;
            // For regular fields, update last_update.
            // DO NOT use this for online updates.
            $storage['sessions'][$i]['last_update'] = time();
            break;
        }
    }
    writeStorage($storage);
}

/**
 * NEW: Update only the last_seen field without modifying last_update.
 */
function updateSessionLastSeen($sessionId, $time) {
    $storage = readStorage();
    for ($i = 0; $i < count($storage['sessions']); $i++) {
        if ($storage['sessions'][$i]['session_id'] === $sessionId) {
            $storage['sessions'][$i]['last_seen'] = $time;
            break;
        }
    }
    writeStorage($storage);
}

function deleteSession($sessionId) {
    $storage = readStorage();
    $newSessions = [];
    foreach ($storage['sessions'] as $s) {
        if ($s['session_id'] !== $sessionId) {
            $newSessions[] = $s;
        }
    }
    $storage['sessions'] = $newSessions;
    writeStorage($storage);
}

function deleteAllSessions() {
    $storage = ['sessions' => []];
    writeStorage($storage);
}
