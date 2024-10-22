<?php
require_once('../../../utils/database.php');
require_once('../../../utils/jwt.php');
require_once('../../../utils/http_responses.php');
require_once('../../../utils/check_authentication.php');

// Collect headers and body
$headers = apache_request_headers();
$body = json_decode(file_get_contents('php://input'), true);
checkUserAuthentication($headers);

if (!isset($body['cat_id']))
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
    "DELETE FROM favorites
        WHERE user_id = %d
        AND cat_id = %d",
    [
        $payload['sub'],
        $body['cat_id']
    ]
);

if (!$result) {
    Database::rollbackTransaction();
    sendBadRequestResponse();
}

Database::commitTransaction();

sendOKResponse(json_encode(['deleted' => $body['cat_id']]));