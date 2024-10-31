<?php
require_once('../../../utils/database.php');
require_once('../../../utils/http_responses.php');
require_once('../../../utils/jwt.php');
require_once('../../../utils/check_authentication.php');

header('Content-Type: application/json');

$headers = apache_request_headers();
$body = json_decode(file_get_contents('php://input'), true);
checkUserAuthentication($headers);

if (!isset($body['request_datetime']))
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
    "DELETE FROM rescues
    WHERE user_id = %d AND request_datetime = '%s'",
    [$payload['sub'], $body['request_datetime']]
);

Database::commitTransaction();

sendOKResponse(json_encode($result));
