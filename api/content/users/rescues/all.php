<?php
require_once('../../../utils/database.php');
require_once('../../../utils/http_responses.php');
require_once('../../../utils/jwt.php');
require_once('../../../utils/check_authentication.php');

header('Content-Type: application/json');

$headers = apache_request_headers();
checkUserAuthentication($headers);

// Remove 'Bearer ' from the token
$token = getAuthTokenFromHeaders($headers);

$jwt = new JWTManager(SECRET_KEY);

// Check if the token has the expected signature
if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
    sendNotAuthenticatedResponse();

$payload = $jwt->decodeToken($token);

try {
    // Fetch rescues from the database
    $rescues = Database::query(
        "SELECT * FROM rescues WHERE user_id = %d",
        [$payload['sub']],
        true
    );
    sendOKResponse(json_encode($rescues));
} catch (Exception $e) {
    sendResponse(json_encode(['detail' => $e->getMessage()]), HttpStatus::InternalServerError->value);
}
