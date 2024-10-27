<?php
require_once('../../utils/database.php');
require_once('../../utils/jwt.php');
require_once('../../utils/http_responses.php');
require_once('../../utils/check_authentication.php');

header('Content-Type: application/json');

$headers = apache_request_headers();

// Sempre verificar se o usuário está autenticado, usa essa funçao de 
// check_authentication.php
checkUserAuthentication($headers);

$token = getAuthTokenFromHeaders($headers);
$jwt = new JWTManager(SECRET_KEY);

if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
    sendNotAuthenticatedResponse();

$payload = $jwt->decodeToken($token);

try {
    Database::beginTransaction();


    $user = Database::query(
        "SELECT * FROM users WHERE name = '%s'",
        [$body['name']]
    );

    $user_id = $user['id'];

    $result = Database::query(
        "INSERT INTO rescues (
            user_id,
            status,
            closure_datetime,
            addr_city,
            addr_state,
            addr_street,
            addr_number,
            addr_zipcode,
            characteristics,
            description
        )
        VALUES ('%d, '%s, '%s, '%s', '%s', '%d', '%s', '%s', '%s')",
        [
            $payload['sub'],
            "pending",
            $body['city'],
            $body['state'],
            $body['street'],
            $body['number'],
            $body['cep'],
            $body['characteristics'],
            isset($body['observations']) ? $body['observations'] : null
        ]
    );

    if (!$result) {
        // @renan: Rollback quando teve erro.
        Database::rollbackTransaction();

        // @renan Conflict é pra quando um recurso já foi crado, código 409.
        sendConflictResponse(json_encode(['detail' => 'Resgate já registrado.']));
    }

    Database::commitTransaction();

    sendOKResponse(json_encode($result));
} catch (Exception $e) {
    Database::rollbackTransaction();

    // @renan Erro interno no servidor, erro 500, quando teve uma exceção não tratada.
    sendResponse(json_encode(['detail' => $e->getMessage()]), HttpStatus::InternalServerError->value);
}
