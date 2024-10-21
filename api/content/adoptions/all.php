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

if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
    sendNotAuthenticatedResponse();

$payload = $jwt->decodeToken($token);

if (!in_array($payload['rol'], ['root', 'supervisor', 'manager']))
    sendResponse(json_encode(['detail' => 'O usuário não tem permissões suficientes.']), 401);

$adoptions = Database::query(
    "SELECT
        cats.name AS cat_name,
        cats.picture_url AS cat_picture_url,
        users.name AS requester_name,
        adoptions.user_id,
        adoptions.cat_id,
        adoptions.request_datetime,
        adoptions.hand_over_datetime,
        adoptions.status
    FROM adoptions
    JOIN users
    ON users.id = adoptions.user_id
    JOIN cats
    ON cats.id = adoptions.cat_id
    ORDER BY request_datetime DESC",
    [],
    true
);

sendOKResponse(json_encode($adoptions));
