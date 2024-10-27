<?php
require_once('../../utils/database.php');
require_once('../../utils/http_responses.php');
require_once('../../utils/jwt.php');
require_once('../../utils/check_authentication.php');

$headers = apache_request_headers();
checkUserAuthentication($headers);

// Remove 'Bearer ' from the token
$token = getAuthTokenFromHeaders($headers);

$jwt = new JWTManager(SECRET_KEY);

// Check if the token has the expected signature
if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
    sendNotAuthenticatedResponse();

$payload = $jwt->decodeToken($token);

if (!in_array($payload['rol'], ['root', 'supervisor', 'manager']))
    sendResponse(json_encode(['detail' => 'O usuário não tem permissões suficientes.']), HttpStatus::Unauthorized->value);

$users = Database::query(
    "SELECT id,
        name,
        username,
        date_birth,
        datetime_register,
        role,
        contact_email,
        contact_phone,
        pfp_url,
        status
    FROM users
    WHERE id != %d
    ORDER BY name ASC",
    [$payload['sub']],
    true
);

sendOKResponse(json_encode($users));
