<?php
require_once('../../../utils/database.php');
require_once('../../../utils/jwt.php');
require_once('../../../utils/http_responses.php');

// Collect headers and body
$headers = apache_request_headers();
$body = json_decode(file_get_contents('php://input'), true);

// If Authorization Bearer is set
if (isset($headers['authorization'])) {

    if (!isset($body['contact_id']) or !isset($body['content']))
        sendBadRequestResponse();

    // Remove 'Bearer ' from the token
    $token = getAuthTokenFromHeaders($headers);

    $jwt = new JWTManager(SECRET_KEY);

    // Check if the token has the expected signature
    if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
        sendNotAuthenticatedResponse();

    $payload = $jwt->decodeToken($token);

    $result = Database::query(
        "INSERT INTO messages (
            sender_id,
            receiver_id,
            sent_datetime,
            content,
            status
        )
        VALUES (
            %d,
            %d,
            CURRENT_TIMESTAMP,
            '%s',
            'sent'
        )",
        [
            $payload['sub'],
            $body['contact_id'],
            $body['content'],
        ]
    );

    sendOKResponse(json_encode($result));
}

sendNotAuthenticatedResponse();
