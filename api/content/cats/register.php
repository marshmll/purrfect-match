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

if (!in_array($payload['rol'], ['root', 'supervisor', 'manager']))
    sendResponse(json_encode(['detail' => 'O usuário não tem permissões suficientes.']), 401);

try {
    // Start transaction
    Database::beginTransaction();

    // Check if cat already exists
    $result = Database::query(
        "SELECT id FROM cats WHERE name = '%s'",
        [$body['name']]
    );

    if ($result != false)
        throw new Exception("Conflict: Cat with this name already exists.");

    // Insert cat data into the 'cats' table
    $insertQuery = isset($body['picture_url']) ?
        "INSERT INTO cats (name, age, sex, physical_description, picture_url) VALUES ('%s', %d, '%s', '%s', '%s')" :
        "INSERT INTO cats (name, age, sex, physical_description) VALUES ('%s', %d, '%s', '%s')";

    $result = Database::query(
        $insertQuery,
        [
            $body['name'],
            $body['age'],
            $body['sex'],
            $body['physical_description'],
            isset($body['picture_url']) ? $body['picture_url'] : null
        ]
    );

    if (!$result)
        throw new Exception("Error inserting cat.");

    // Get the cat ID of the newly inserted cat
    $cat = Database::query(
        "SELECT * FROM cats WHERE name = '%s'",
        [$body['name']]
    );

    $cat_id = $cat['id'];

    // Batch insert for personalities
    if (!empty($body['personalities'])) {
        $personalityValues = [];
        foreach ($body['personalities'] as $personality_id) {
            $personalityValues[] = sprintf("(%d, %d)", $cat_id, $personality_id);
        }
        $personalityQuery = "INSERT INTO cat_personalities (cat_id, personality_id) VALUES " . implode(', ', $personalityValues);
        Database::query($personalityQuery);
    }

    // Batch insert for vaccines
    if (!empty($body['vaccines'])) {
        $vaccineValues = [];
        foreach ($body['vaccines'] as $vaccine_id) {
            $vaccineValues[] = sprintf("(%d, %d)", $cat_id, $vaccine_id);
        }
        $vaccineQuery = "INSERT INTO vaccinations (cat_id, vaccine_id) VALUES " . implode(', ', $vaccineValues);
        Database::query($vaccineQuery);
    }

    // Batch insert for diseases
    if (!empty($body['diseases'])) {
        $diseaseValues = [];
        foreach ($body['diseases'] as $disease_id) {
            $diseaseValues[] = sprintf("(%d, %d)", $cat_id, $disease_id);
        }
        $diseaseQuery = "INSERT INTO cat_diseases (cat_id, disease_id) VALUES " . implode(', ', $diseaseValues);
        Database::query($diseaseQuery);
    }

    // Commit the transaction
    Database::commitTransaction();

    // Send OK response with the inserted cat data
    sendOKResponse(json_encode($cat));
} catch (Exception $e) {
    // Rollback the transaction in case of error
    Database::rollbackTransaction();
    // Send appropriate error response
    sendConflictResponse(json_encode(['detail' => $e->getMessage()]));
}
