<?php
require_once('../../utils/database.php');
require_once('../../utils/http_responses.php');
require_once('../../utils/check_authentication.php');

header('Content-Type: application/json');

$headers = apache_request_headers();

// Sempre verificar se o usuário está autenticado, usa essa funçao de 
// check_authentication.php
checkUserAuthentication($headers);

if (
    !isset($body['name']) or
    !isset($body['email']) or
    !isset($body['phone']) or
    !isset($body['cep']) or
    !isset($body['state']) or
    !isset($body['city']) or
    !isset($body['neighborhood']) or
    !isset($body['street']) or
    !isset($body['characteristics'])
) {
    sendBadRequestResponse();
}

try {
    Database::beginTransaction();

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
        VALUES (%d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
        [
            // @renan: Não vai funcionar pois os argumentos não são os valores certos :P
            $body['name'],
            $body['email'],
            $body['phone'],
            $body['cep'],
            $body['state'],
            $body['city'],
            $body['neighborhood'],
            $body['street'],
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
    sendResponse(json_encode(['detail' => $e->getMessage()]), 500);
}
