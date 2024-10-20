<?php
require_once('../../../utils/database.php');
require_once('../../../utils/jwt.php');
require_once('../../../utils/http_responses.php');
require_once('../../../utils/check_authentication.php');

// Collect headers and body
$headers = apache_request_headers();
$body = json_decode(file_get_contents('php://input'), true);

if (!isset($body['contact_id']))
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
    "UPDATE messages
        SET status = 'seen'
        WHERE sender_id = %d AND receiver_id = %d",
    [$body['contact_id'], $payload['sub']]
);

if (!$result) {
    Database::rollbackTransaction();
    sendBadRequestResponse();
}

Database::commitTransaction();

sendOKResponse(json_encode($result));
