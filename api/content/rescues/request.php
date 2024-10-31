<?php
require_once('../../utils/database.php');
require_once('../../utils/jwt.php');
require_once('../../utils/http_responses.php');
require_once('../../utils/check_authentication.php');

header('Content-Type: application/json');

$headers = apache_request_headers();
$body = json_decode(file_get_contents('php://input'), true);

// Sempre verificar se o usuário está autenticado, usa essa funçao de 
// check_authentication.php
checkUserAuthentication($headers);

if (
    !isset($body['city']) or
    !isset($body['state']) or
    !isset($body['street']) or
    !isset($body['number']) or
    !isset($body['zipcode']) or
    !isset($body['characteristics'])
) {
    sendBadRequestResponse();
}

$token = getAuthTokenFromHeaders($headers);
$jwt = new JWTManager(SECRET_KEY);

if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
    sendNotAuthenticatedResponse();

$payload = $jwt->decodeToken($token);

Database::beginTransaction();

$result = Database::query(
    "INSERT INTO rescues (
            user_id,
            status,
            addr_city,
            addr_state,
            addr_street,
            addr_number,
            addr_zipcode,
            characteristics,
            description
        )
        VALUES (%d, '%s', '%s', '%s', '%s', %s, '%s', '%s', '%s')",
    [
        $payload['sub'],
        "pending",
        $body['city'],
        $body['state'],
        $body['street'],
        $body['number'],
        $body['zipcode'],
        $body['characteristics'],
        $body['description'] ?? null
    ]
);

if (!$result) {
    // @renan: Rollback quando teve erro.
    Database::rollbackTransaction();

    // @renan Conflict é pra quando um recurso já foi criado, código 409.
    sendConflictResponse(json_encode(['detail' => 'Resgate já registrado.']));
}

Database::commitTransaction();

sendOKResponse(json_encode($result));
