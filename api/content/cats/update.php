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
    !isset($body['id']) or
    !isset($body['name']) or
    !isset($body['age']) or
    !isset($body['sex']) or
    !isset($body['physical_description']) or
    !isset($body['personalities']) or
    !isset($body['vaccines'])
) {
    sendBadRequestResponse();
}

$token = getAuthTokenFromHeaders($headers);

$jwt = new JWTManager(SECRET_KEY);

if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
    sendNotAuthenticatedResponse();

$payload = $jwt->decodeToken($token);

if (!in_array($payload['rol'], ['root', 'supervisor', 'manager']))
    sendResponse(json_encode(['detail' => 'O usuário não tem permissões suficientes.']), 401);

$cat_id = $body['id'];

// Begin transaction to group updates
Database::beginTransaction();

// Check if cat exists and fetch the current data
$cat = Database::query(
    "SELECT * FROM cats WHERE id = %s FOR UPDATE",
    [$cat_id]
);

if (!$cat)
    sendBadRequestResponse();

// Update only if there are changes in the fields
if ($cat['name'] !== $body['name'] || $cat['age'] !== $body['age'] || $cat['sex'] !== $body['sex'] || $cat['physical_description'] !== $body['physical_description']) {
    $result = Database::query(
        "UPDATE cats
        SET name = '%s',
            age = %s,
            sex = '%s',
            physical_description = '%s'
        WHERE id = %s",
        [
            $body['name'],
            $body['age'],
            $body['sex'],
            $body['physical_description'],
            $cat_id
        ]
    );

    if (!$result) {
        Database::rollbackTransaction();
        sendBadRequestResponse();
    }
}

// Update picture URL if provided
if (isset($body['picture_url']) && $cat['picture_url'] !== $body['picture_url']) {
    Database::query("UPDATE cats SET picture_url = '%s' WHERE id = %s", [$body['picture_url'], $cat_id]);
}

// Update personalities
Database::query("DELETE FROM cat_personalities WHERE cat_id = %s", [$cat_id]);
if (!empty($body['personalities'])) {
    $personalityValues = array_map(function ($id) use ($cat_id) {
        return sprintf("(%d, %d)", $cat_id, $id);
    }, $body['personalities']);

    $personalityQuery = "INSERT INTO cat_personalities (cat_id, personality_id) VALUES " . implode(', ', $personalityValues);
    Database::query($personalityQuery);
}

// Update vaccines
Database::query("DELETE FROM vaccinations WHERE cat_id = %s", [$cat_id]);
if (!empty($body['vaccines'])) {
    $vaccineValues = array_map(function ($id) use ($cat_id) {
        return sprintf("(%d, %d)", $cat_id, $id);
    }, $body['vaccines']);

    $vaccineQuery = "INSERT INTO vaccinations (cat_id, vaccine_id) VALUES " . implode(', ', $vaccineValues);
    Database::query($vaccineQuery);
}

// Update diseases
Database::query("DELETE FROM cat_diseases WHERE cat_id = %s", [$cat_id]);
if (!empty($body['diseases'])) {
    $diseaseValues = array_map(function ($id) use ($cat_id) {
        return sprintf("(%d, %d)", $cat_id, $id);
    }, $body['diseases']);

    $diseaseQuery = "INSERT INTO cat_diseases (cat_id, disease_id) VALUES " . implode(', ', $diseaseValues);
    Database::query($diseaseQuery);
}

// Commit all changes
Database::commitTransaction();

// Send the updated cat data
sendOKResponse(json_encode($cat));
