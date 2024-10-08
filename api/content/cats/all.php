<?php
require_once('../utils/database.php');
require_once('../utils/jwt.php');
require_once('../utils/http_responses.php');

header('Content-Type: application/json');

$headers = apache_request_headers();

// If Authorization Bearer is set
if (isset($headers['authorization'])) {

    // Remove 'Bearer ' from the token
    $token = getAuthTokenFromHeaders($headers);

    $jwt = new JWTManager(SECRET_KEY);

    if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
        sendNotAuthenticatedResponse();

    $payload = $jwt->decodeToken($token);

    // Query for getting all cats
    $cats = Database::query(
        "SELECT id, name, age, sex
        FROM cats"
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

    $data = array();

    // For all cats in the result
    foreach ($cats as $cat) {
        $personalities = Database::query(
            "SELECT name
            FROM personalities
            WHERE id IN
            (
                SELECT personality_id
                FROM cat_personalities WHERE
                cat_id = %s
            )",
            [$cat['id']],
            true
        );

        // Save each personality in an array.
        $cat_personalities = array();

        foreach ($personalities as $personality)
            array_push($cat_personalities, $personality['name']);

        // Add the personality array to the cat row.
        $cat += ['personalities' => $cat_personalities];

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
    }

    // Send all cats
    sendOKResponse(json_encode($data));
}

// If reaches here, it means no Bearer token was received, send unauthorized.
sendNotAuthenticatedResponse();
