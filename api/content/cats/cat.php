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

$cat = Database::query(
    "SELECT *
        FROM cats
        WHERE id = %s",
    [$body['id']]
);

$user_favorites = Database::query(
    "SELECT id
        FROM cats
        WHERE id IN
        (
            SELECT cat_id
            FROM favorites
            WHERE user_id = %d
        )",
    [
        $payload['sub']
    ],
    true
);

$personalities = Database::query(
    "SELECT name, description
        FROM personalities
        WHERE id IN
        (
            SELECT personality_id
            FROM cat_personalities
            WHERE cat_id = %s
        )",
    [$cat['id']],
    true
);

$vaccines = Database::query(
    "SELECT vaccines.name, vaccines.description, vaccinations.dose
        FROM vaccines
        JOIN vaccinations
        ON vaccines.id = vaccinations.vaccine_id
        AND vaccinations.cat_id = %d",
    [$cat['id']],
    true
);

$diseases = Database::query(
    "SELECT name, description
        FROM diseases
        WHERE id IN
        (
            SELECT disease_id
            FROM cat_diseases
            WHERE cat_id = %s
        )",
    [$cat['id']],
    true
);

// Add the personality array to the cat row.
$cat += ['personalities' => $personalities];
$cat += ['diseases' => $diseases];
$cat += ['vaccines' => $vaccines];

$is_favorite = false;

foreach ($user_favorites as $favorite) {
    if ($favorite['id'] == $cat['id']) {
        $is_favorite = true;
        break;
    }
}

$cat += ['favorite' => $is_favorite];

// Add the cat row in a array.
$data[] = $cat;

sendOKResponse(json_encode($data));
