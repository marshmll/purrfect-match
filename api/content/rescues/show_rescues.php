<?php
require_once('../../utils/database.php');
require_once('../../utils/http_responses.php');
require_once('../../utils/check_authentication.php');

header('Content-Type: application/json');

$headers = apache_request_headers();
checkUserAuthentication($headers);

try {
    // Fetch rescues from the database
    $rescues = Database::query("SELECT * FROM rescues");

    sendOKResponse(json_encode($rescues));
} catch (Exception $e) {
    
    sendResponse(json_encode(['detail' => $e->getMessage()]), HttpStatus::InternalServerError->value);
}
