<?php
require_once('../../utils/database.php');
require_once('../../utils/jwt.php');
require_once('../../utils/http_responses.php');
require_once('../../utils/check_authentication.php');

header('Content-Type: application/json');

$headers = apache_request_headers();
$body = json_decode(file_get_contents('php://input'), true);
checkUserAuthentication($headers);

if (!isset($body['id']))
    sendBadRequestResponse();

// Remove 'Bearer ' from the token
$token = getAuthTokenFromHeaders($headers);

$jwt = new JWTManager(SECRET_KEY);

if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
    sendNotAuthenticatedResponse();

$payload = $jwt->decodeToken($token);

if (!in_array($payload['rol'], ['root', 'supervisor', 'manager']))
    sendResponse(json_encode(['detail' => 'O usuário não tem permissões suficientes.']), 401);

$cat = Database::query(
    "SELECT *
        FROM cats
        WHERE id = %s",
    [$body['id']]
);

$personalities = Database::query(
    "SELECT personality_id
    FROM cat_personalities
    WHERE cat_id = %s",
    [$body['id']],
    true
);

$vaccines = Database::query(
    "SELECT vaccine_id
    FROM vaccinations
    WHERE cat_id = %s",
    [$body['id']],
    true
);

$diseases = Database::query(
    "SELECT disease_id
    FROM cat_diseases
    WHERE cat_id = %s",
    [$body['id']],
    true
);

$personalities_ids = array();
foreach ($personalities as $key )

$cat += ['personalities' => $personalities];
$cat += ['vaccines' => $vaccines];
$cat += ['diseases' => $diseases];

sendOKResponse(json_encode($cat));
