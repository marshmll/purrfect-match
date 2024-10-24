<?php
require_once('../utils/database.php');
require_once('../utils/http_responses.php');

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
        $insertQuery,
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

    $rescue = Database::query(
        "SELECT * FROM rescues WHERE name = '%s' AND email = '%s' ORDER BY id DESC LIMIT 1",
        [$body['name'], $body['email']]
    );

    $rescue_id = $rescue['id'];


    Database::commitTransaction();

    sendOKResponse(json_encode($rescue));
    
} catch (Exception $e) {
    Database::rollbackTransaction();

    sendConflictResponse(json_encode(['detail' => $e->getMessage()]));
}
