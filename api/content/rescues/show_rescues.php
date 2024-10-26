<?php
require_once('../../utils/database.php');
require_once('../../utils/http_responses.php');

header('Content-Type: application/json');

try {
    // Fetch rescues from the database
    $rescues = Database::query("SELECT * FROM rescues");

    sendOKResponse(json_encode($rescues));
} catch (Exception $e) {
    sendConflictResponse(json_encode(['detail' => $e->getMessage()]));
}
?>
