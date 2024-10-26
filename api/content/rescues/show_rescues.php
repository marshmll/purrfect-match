<?php
require_once('../utils/database.php');
require_once('../utils/http_responses.php');

header('Content-Type: application/json');

try {
    // Query to fetch all rescues
    $rescues = Database::query("SELECT * FROM rescues ORDER BY id DESC");

    if ($rescues) {
        sendOKResponse(json_encode($rescues));
    } else {
        sendNotFoundResponse(json_encode(['message' => 'No rescues found.']));
    }
} catch (Exception $e) {
    sendConflictResponse(json_encode(['detail' => $e->getMessage()]));
}
