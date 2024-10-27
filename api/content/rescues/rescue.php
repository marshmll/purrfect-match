<?php
require_once('../../utils/database.php');
require_once('../../utils/http_responses.php');

header('Content-Type: application/json');

$headers = apache_request_headers();
if (
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

    $rescue = Database::query(
        "SELECT * FROM rescues WHERE name = '%s'",
        [$body['name']]
    );

    $rescue_id = $rescue['id'];

    $result = Database::query(
        "INSERT INTO rescues (user_id, status, addr_city, addr_state, addr_street, addr_number, addr_zipcode, characteristics, description)
        VALUES ('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
        [
            $rescue_id,
            "pendente",
            $body['city'],
            $body['state'],
            $body['street'],
            $body['city'],
            $body['neighborhood'],
            $body['street'],
            $body['characteristics'],
            isset($body['observations']) ? $body['observations'] : null
        ]
    );

    if (!$result) {
        throw new Exception("Error inserting rescue.");
    }

    Database::commitTransaction();

    sendOKResponse(json_encode($result));
    
} catch (Exception $e) {
    Database::rollbackTransaction();

    sendConflictResponse(json_encode(['detail' => $e->getMessage()]));
}
?>