<?php
require_once('../../utils/database.php');
require_once('../../utils/jwt.php');
require_once('../../utils/http_responses.php');
require_once('../../utils/check_authentication.php');

header('Content-Type: application/json');

$headers = apache_request_headers();
$body = json_decode(file_get_contents('php://input'), true);

checkUserAuthentication($headers);

if (
    !isset($body['name']) or
    !isset($body['age']) or
    !isset($body['sex']) or
    !isset($body['physical_description']) or
    !isset($body['personalities']) or
    !isset($body['vaccines'])
) {
    sendBadRequestResponse();
}

// Remove 'Bearer ' from the token
$token = getAuthTokenFromHeaders($headers);

$jwt = new JWTManager(SECRET_KEY);

if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
    sendNotAuthenticatedResponse();

$payload = $jwt->decodeToken($token);

$result = Database::query(
    "SELECT id FROM cats
        WHERE name = '%s'",
    [$body['name']]
);

if ($result != false)
    sendConflictResponse();

if (isset($body['picture_url'])) {
    $result = Database::query(
        "INSERT INTO cats
            (name, age, sex, physical_description, picture_url)
            VALUES
            ('%s', %d, '%s', '%s', '%s')",
        [
            $body['name'],
            $body['age'],
            $body['sex'],
            $body['physical_description'],
            $body['picture_url']
        ]
    );
} else {
    $result = Database::query(
        "INSERT INTO cats
            (name, age, sex, physical_description)
            VALUES
            ('%s', %d, '%s', '%s')",
        [
            $body['name'],
            $body['age'],
            $body['sex'],
            $body['physical_description']
        ]
    );
}


if (!$result)
    sendConflictResponse();

$cat = Database::query(
    "SELECT * FROM cats
        WHERE name = '%s'",
    [$body['name']]
);

foreach ($body['personalities'] as $personality_id) {
    $result = Database::query(
        "INSERT INTO cat_personalities
            (cat_id, personality_id)
            VALUES
            (%d, %d)",
        [
            $cat['id'],
            $personality_id
        ]
    );

    if (!$result)
        sendConflictResponse();
}

foreach ($body['vaccines'] as $vaccine_id) {
    $result = Database::query(
        "INSERT INTO vaccinations
            (cat_id, vaccine_id)
            VALUES
            (%d, %d)",
        [
            $cat['id'],
            $vaccine_id
        ]
    );

    if (!$result)
        sendConflictResponse();
}

foreach ($body['diseases'] as $disease_id) {
    $result = Database::query(
        "INSERT INTO cat_diseases
            (cat_id, disease_id)
            VALUES
            (%d, %d)",
        [
            $cat['id'],
            $disease_id
        ]
    );

    if (!$result)
        sendConflictResponse();
}

sendOKResponse(json_encode($cat));
