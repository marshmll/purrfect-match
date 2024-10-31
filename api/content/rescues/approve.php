<?php
require_once('../../utils/database.php');
require_once('../../utils/http_responses.php');
require_once('../../utils/jwt.php');
require_once('../../utils/check_authentication.php');

header('Content-Type: application/json');

$headers = apache_request_headers();
$body = json_decode(file_get_contents('php://input'), true);

checkUserAuthentication($headers);

if (!isset($body['user_id']) or !isset($body['request_datetime']))
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

try {
    Database::beginTransaction();

    $result = Database::query(
        "UPDATE rescues
        SET status = 'approved'
        WHERE user_id = %s AND request_datetime = '%s'",
        [
            $body['user_id'],
            $body['request_datetime'],
        ]
    );

    if (!$result) {
        Database::rollbackTransaction();
        sendResponse(json_encode(['detail' => "Não foi possível atualizar o registro."]));
    }

    Database::commitTransaction();

    sendOKResponse(json_encode($result));
} catch (Exception $e) {
    Database::rollbackTransaction();
    sendResponse(json_encode(['detail' => $e->getMessage()]), HttpStatus::InternalServerError->value);
}
