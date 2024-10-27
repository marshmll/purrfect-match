<?php
require_once('../../utils/database.php');
require_once('../../utils/http_responses.php');
require_once('../../utils/jwt.php');
require_once('../../utils/check_authentication.php');

header('Content-Type: application/json');

$headers = apache_request_headers();
$body = json_decode(file_get_contents('php://input'), true);
checkUserAuthentication($headers);

if (!isset($body['cat_id'])) {
    sendBadRequestResponse();
}

// Remove 'Bearer ' from the token
$token = getAuthTokenFromHeaders($headers);

$jwt = new JWTManager(SECRET_KEY);

if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
    sendNotAuthenticatedResponse();

$payload = $jwt->decodeToken($token);

$cat_has_adoptions = Database::query(
    "SELECT * FROM adoptions WHERE cat_id = %s",
    [$body['cat_id']]
);

if (!empty($cat_has_adoptions))
    sendConflictResponse(json_encode(['detail' => 'Este gato já está em um processo de adoção.']));

$result = Database::query(
    "INSERT INTO adoptions (
        user_id, cat_id, request_datetime, status
    )
    VALUES (
        %d, %s, CURRENT_TIMESTAMP, 'pending'
    )",
    [$payload['sub'], $body['cat_id']]
);

if (!$result)
    sendResponse(json_encode(['detail' => "Algo deu errado na criação da solicitação."]), HttpStatus::Accepted->value);

$request = Database::query(
    "SELECT * FROM adoptions WHERE cat_id = %s AND user_id = %d",
    [$body['cat_id'], $payload['sub']]
);

sendResponse(json_encode($request), HttpStatus::Created->value);
