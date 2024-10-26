<?php
require_once('../../../utils/database.php');
require_once('../../../utils/jwt.php');
require_once('../../../utils/http_responses.php');
require_once('../../../utils/check_authentication.php');

// Collect headers and body
$headers = apache_request_headers();
checkUserAuthentication($headers);

// Remove 'Bearer ' from the token
$token = getAuthTokenFromHeaders($headers);

$jwt = new JWTManager(SECRET_KEY);

// Check if the token has the expected signature
if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
    sendNotAuthenticatedResponse();

$payload = $jwt->decodeToken($token);

$adoptions = Database::query(
    "SELECT cats.id AS cat_id,
        cats.name AS cat_name,
        cats.picture_url AS cat_picture_url,
        adoptions.request_datetime,
        adoptions.hand_over_datetime,
        adoptions.status
    FROM cats
    JOIN adoptions
    ON cats.id = adoptions.cat_id
    AND adoptions.user_id = %d
    ORDER BY request_datetime ASC, hand_over_datetime ASC",
    [$payload['sub']],
    true
);

sendOKResponse(json_encode($adoptions));
