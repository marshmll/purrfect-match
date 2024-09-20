<?php
require_once('../database.php');
require_once('../http_responses.php');
require_once('../jwt.php');
require_once('../headers.php');
require_once('../cors.php');

CORS::sendCORSHeaders();

$headers = get_nginx_headers();

if (isset($headers['Authorization'])) {
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

    $user_info_query = sprintf("SELECT
    name, username, date_birth, contact_email, contact_phone
    FROM users
    WHERE username = '%s'",
    $mysqli->real_escape_string($payload['username']));

    $result = $mysqli->query($user_info_query);

    if ($result->num_rows == 0) {
        http_response_code(401);
        echo $unautorized_json;
        die();
    }

    $data = $result->fetch_assoc();
    
    http_response_code(200);
    echo json_encode($data);
    die();
}

// If reaches here, it means no Bearer token was received, send unauthorized.
http_response_code(401);
echo json_encode($not_authenticated_json);
