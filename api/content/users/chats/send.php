<?php
require_once('../../../utils/database.php');
require_once('../../../utils/jwt.php');
require_once('../../../utils/http_responses.php');
require_once('../../../utils/check_authentication.php');

// Collect headers and body
$headers = apache_request_headers();
$body = json_decode(file_get_contents('php://input'), true);
checkUserAuthentication($headers);


if (!isset($body['contact_id']) or !isset($body['content']))
    sendBadRequestResponse();

// Remove 'Bearer ' from the token
$token = getAuthTokenFromHeaders($headers);

$jwt = new JWTManager(SECRET_KEY);

// Check if the token has the expected signature
if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
    sendNotAuthenticatedResponse();

$payload = $jwt->decodeToken($token);

Database::beginTransaction();

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

if (!$result) {
    Database::rollbackTransaction();
    sendBadRequestResponse();
}

Database::commitTransaction();

sendOKResponse(json_encode($result));
