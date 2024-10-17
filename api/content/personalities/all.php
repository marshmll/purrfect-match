<?php
require_once('../../utils/database.php');
require_once('../../utils/jwt.php');
require_once('../../utils/http_responses.php');

header('Content-Type: application/json');

$headers = apache_request_headers();

// If Authorization Bearer is set
if (isset($headers['authorization'])) {

    // Remove 'Bearer ' from the token
    $token = getAuthTokenFromHeaders($headers);

    $jwt = new JWTManager(SECRET_KEY);

    if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
        sendNotAuthenticatedResponse();

    $payload = $jwt->decodeToken($token);

    if ($payload['rol'] != 'supervisor' and $payload['rol'] != 'root')
        sendResponse(json_encode(['detail' => 'O usuário não tem permissões suficientes.']), 401);

    $personalities = Database::query("SELECT * FROM personalities");

    sendOKResponse(json_encode($personalities));
}

sendNotAuthenticatedResponse();