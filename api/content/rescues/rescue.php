<?php
require_once('../../utils/database.php');
require_once('../../utils/http_responses.php');

header('Content-Type: application/json');

$headers = apache_request_headers();
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

    $insertQuery = "
        INSERT INTO rescues (name, email, phone, cep, state, city, neighborhood, street, characteristics, observations)
        VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
    ";

    $result = Database::query(
        "INSERT INTO rescues (user_id, status, closure_datetime, addr_city, addr_state, addr_street, addr_number, addr_zipcode, characteristics, description)
        VALUES ('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
        [
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
        throw new Exception("Error inserting rescue.");
    }

    Database::commitTransaction();

    sendOKResponse(json_encode($result));
    
} catch (Exception $e) {
    Database::rollbackTransaction();

    sendConflictResponse(json_encode(['detail' => $e->getMessage()]));
}
?>