<?php
require_once('../../../utils/database.php');
require_once('../../../utils/http_responses.php');
require_once('../../../utils/jwt.php');

$headers = apache_request_headers();
// If Authorization Bearer is set
if (isset($headers['authorization'])) {

    // Remove 'Bearer ' from the token
    $token = getAuthTokenFromHeaders($headers);

    $jwt = new JWTManager(SECRET_KEY);

    // Check if the token has the expected signature
    if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
        sendNotAuthenticatedResponse();

    $payload = $jwt->decodeToken($token);

    $user = Database::query(
        "SELECT name, username, date_birth, contact_email, contact_phone, pfp_url
        FROM users
        WHERE id = '%s'",
        [
            $payload['sub']
        ]
    );

    sendOKResponse(json_encode($user));
}

sendUnauthorizedResponse();
