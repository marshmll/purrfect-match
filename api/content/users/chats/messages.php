<?php
require_once('../../../utils/database.php');
require_once('../../../utils/jwt.php');
require_once('../../../utils/http_responses.php');
require_once('../../../utils/check_authentication.php');

// Collect headers and body
$headers = apache_request_headers();
$body = json_decode(file_get_contents('php://input'), true);
checkUserAuthentication($headers);

if (!isset($body['contact_id']))
    sendBadRequestResponse();

// Remove 'Bearer ' from the token
$token = getAuthTokenFromHeaders($headers);

$jwt = new JWTManager(SECRET_KEY);

// Check if the token has the expected signature
if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
    sendNotAuthenticatedResponse();

$payload = $jwt->decodeToken($token);

$messages = Database::query(
    "SELECT sender_id, receiver_id, sent_datetime, content, status
        FROM messages
        WHERE (sender_id = %d AND receiver_id = %d)
        OR (sender_id = %d AND receiver_id = %d)
        ORDER BY sent_datetime ASC",
    [
        $body['contact_id'],
        $payload['sub'],
        $payload['sub'],
        $body['contact_id']
    ],
    true
);

foreach ($messages as &$message) {
    $message += ['current_user_id' => $payload['sub']];
}

sendOKResponse(json_encode($messages));
