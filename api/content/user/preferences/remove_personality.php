<?php
require_once('../../../utils/database.php');
require_once('../../../utils/jwt.php');
require_once('../../../utils/http_responses.php');

// Collect headers and body
$headers = apache_request_headers();
$body = json_decode(file_get_contents('php://input'), true);

// If Authorization Bearer is set
if (isset($headers['authorization'])) {

    if (!isset($body['personality_id']))
        sendBadRequestResponse();

    // Remove 'Bearer ' from the token
    $token = getAuthTokenFromHeaders($headers);

    $jwt = new JWTManager(SECRET_KEY);

    // Check if the token has the expected signature
    if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
        sendNotAuthenticatedResponse();

    $payload = $jwt->decodeToken($token);

    $result = Database::query(
        "DELETE FROM personality_preferences
        WHERE personality_id = %d
        AND user_id = %d",
        [
            $body['personality_id'],
            $payload['sub'],
        ]
    );

    if (!$result)
        sendConflictResponse();

    sendResponse(json_encode(['removed' => $body['personality_id']]), 200);
}

// If reaches here, it means no Bearer token was received, send unauthorized.
sendBadRequestResponse();
