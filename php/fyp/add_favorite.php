<?php
require_once('../utils/database.php');
require_once('../utils/jwt.php');
require_once('../utils/http_responses.php');

// Collect headers and body
$headers = apache_request_headers();
$body = json_decode(file_get_contents('php://input'), true);

// If Authorization Bearer is set
if (isset($headers['authorization'])) {

    if (!isset($body['cat_id']) || !isset($body['choice_datetime']))
        sendBadRequestResponse();

    // Remove 'Bearer ' from the token
    $token = getAuthTokenFromHeaders($headers);

    $jwt = new JWTManager(SECRET_KEY);

    // Check if the token has the expected signature
    if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
        sendNotAuthenticatedResponse();

    $payload = $jwt->decodeToken($token);

    $result = Database::query(
        "INSERT INTO favorites
        (user_id, cat_id, choice_datetime)
        VALUES
        (%d, %d, '%s')",
        [
            $payload['sub'],
            $body['cat_id'],
            $body['choice_datetime']
        ]
    );

    if (!$result)
        sendConflictResponse();

    sendResponse(json_encode(['added' => $body['cat_id']]), 201);
}

// If reaches here, it means no Bearer token was received, send unauthorized.
sendBadRequestResponse();
