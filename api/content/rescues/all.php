<?php
require_once('../../utils/database.php');
require_once('../../utils/http_responses.php');
require_once('../../utils/jwt.php');
require_once('../../utils/check_authentication.php');

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

if (!in_array($payload['rol'], ['root', 'supervisor', 'manager']))
    sendResponse(json_encode(['detail' => 'O usuário não tem permissões suficientes.']), HttpStatus::Unauthorized->value);

try {
    // Fetch rescues from the database
    $rescues = Database::query(
        "SELECT
            r.*,
            u.name AS requester_name,
            u.username AS requester_username,
            u.pfp_url AS requester_pfp_url
        FROM rescues r
        JOIN users u
        ON r.user_id = u.id
        ORDER BY r.request_datetime ASC",
        [],
        true
    );

    sendOKResponse(json_encode($rescues));
} catch (Exception $e) {
    sendResponse(json_encode(['detail' => $e->getMessage()]), HttpStatus::InternalServerError->value);
}
