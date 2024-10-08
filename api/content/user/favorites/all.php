<?php
require_once('../../../utils/database.php');
require_once('../../../utils/jwt.php');
require_once('../../../utils/http_responses.php');

// Collect headers and body
$headers = apache_request_headers();
$body = json_decode(file_get_contents('php://input'), true);

// If Authorization Bearer is set
if (isset($headers['authorization'])) {

    // Remove 'Bearer ' from the token
    $token = getAuthTokenFromHeaders($headers);

    $jwt = new JWTManager(SECRET_KEY);

    // Check if the token has the expected signature
    if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
        sendNotAuthenticatedResponse();

    $payload = $jwt->decodeToken($token);

    $cats = Database::query(
        "SELECT id, name, age, sex
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
        $cat += ['favorite' => true];

        // Add the cat row in a array.
        $data[] = $cat;
    }

    // Send all cats
    sendOKResponse(json_encode($data));
}

sendBadRequestResponse();
