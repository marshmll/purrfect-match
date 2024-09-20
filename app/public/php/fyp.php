<?php
require_once('./database.php');
require_once('./jwt.php');
require_once('./http_responses.php');
require_once('./headers.php');

CORS::sendCORSHeaders();

// Collect headers
$headers = get_nginx_headers();

// If Authorization Bearer is set
if (isset($headers['Authorization'])) {

    // Remove 'Bearer ' from the token
    $token = substr($headers['Authorization'], 7, strlen($headers['Authorization']) - 6);

    $jwt = new JWTManager(SECRET_KEY);

    // Check if the token has the expected signature
    $is_token_valid = $jwt->isTokenValid($token);

    // If not, send unauthorized.
    if (!$is_token_valid) {
        http_response_code(401);
        echo $not_authenticated_json;
        die();
    }

    // Decode the token's payload
    $payload = $jwt->decodeToken($token);

    // Get expiration time (UNIX timestamp)
    $token_exp = $payload['exp'];

    // If the token is expired, send unauthorized.
    if (time() >= $token_exp) {
        http_response_code(401);
        echo $unautorized_json;
        die();
    }

    // Query for getting all cats
    $cat_query = "SELECT id, name, age, sex FROM cats";
    $result = $mysqli->query($cat_query);
    $data = array();

    // For all cats in the result
    while ($cat = $result->fetch_assoc()) {

        // Set a query to get all personalities associated to the cat.
        $personality_query = sprintf(
            "SELECT name FROM personalities WHERE id IN (SELECT personality_id FROM cat_personalities WHERE cat_id = %s)",
            $mysqli->real_escape_string($cat['id'])
        );
        $tmp = $mysqli->query($personality_query);

        // Save each personality in an array.
        $cat_personalities = array();
        while ($personality = $tmp->fetch_assoc()) {
            array_push($cat_personalities, $personality['name']);
        }

        // Add the personality array to the cat row.
        $cat += ['personalities' => $cat_personalities];

        // Add the cat row in a array.
        $data[] = $cat;
    }

    // Send all cats
    header(200);
    echo json_encode($data);
    die();
}

// If reaches here, it means no Bearer token was received, send unauthorized.
http_response_code(401);
echo json_encode($not_authenticated_json);
