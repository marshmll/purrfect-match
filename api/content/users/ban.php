<?php
require_once('../../utils/database.php');
require_once('../../utils/http_responses.php');
require_once('../../utils/jwt.php');
require_once('../../utils/check_authentication.php');

$headers = apache_request_headers();
$body = json_decode(file_get_contents('php://input'), true);
checkUserAuthentication($headers);

if (!isset($body['user_id']))
    sendBadRequestResponse();

// Remove 'Bearer ' from the token
$token = getAuthTokenFromHeaders($headers);

$jwt = new JWTManager(SECRET_KEY);

// Check if the token has the expected signature
if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
    sendNotAuthenticatedResponse();

$payload = $jwt->decodeToken($token);

if (!in_array($payload['rol'], ['root', 'supervisor', 'manager']))
    sendResponse(json_encode(['detail' => 'O usuário não tem permissões suficientes.']), HttpStatus::Unauthorized->value);

Database::beginTransaction();

$result = Database::query(
    "UPDATE users
    SET status = 'banned'
    WHERE id = %s",
    [$body['user_id']]
);

Database::commitTransaction();

sendOKResponse(json_encode($result));
