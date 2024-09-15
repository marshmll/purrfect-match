<?php
require_once('./database.php');
require_once('./jwt.php');

header('Content-Type: application/json');

// JSON response to send in case that the username or password is incorrect
$unautorized_json = json_encode([
    'detail' => mb_convert_encoding('Usuário ou senha incorretos', 'UTF-8', 'auto'),
]);

// If all necessary form data was posted
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['keep_connected'])) {

    // Save data into variables
    $username = $_POST['username'];
    $password = $_POST['password'];
    $keep_connected = $_POST['keep_connected'];

    // SQL Injection free query to check if the user already exists in the database
    $user_exists_query = sprintf(
        "SELECT pass_salt, pass_hash FROM users WHERE username='%s'",
        $mysqli->real_escape_string($username)
    );

    // Check if there are any rows in the result.
    $result = $mysqli->query($user_exists_query);
    if ($result->num_rows == 0) {

        // Case not, send unauthorized
        http_response_code(401);
        echo $unautorized_json;
        die();
    }

    // Get hash and salt from the result as an associative array
    $data = $result->fetch_assoc();

    // Hash the received password with the salt
    $hash = hash('sha256', mb_convert_encoding($data['pass_salt'] . $password, 'UTF-8', 'auto'));

    // SQL Injection free query to check if the hash exists in the database
    $check_hash_query = sprintf(
        "SELECT * FROM users WHERE pass_hash='%s'",
        $mysqli->real_escape_string($hash)
    );

    // Check if there are any rows in the result.
    $result = $mysqli->query($check_hash_query);
    if ($result->num_rows == 0) {

        // Case not, send unauthorized
        http_response_code(401);
        echo $unautorized_json;
        die();
    }

    // Create a JSON Web Token manager.
    $jwt_manager = new JWTManager("justtestingggg");
    $token_expiration = 0;

    // If user asked to keep connection
    if ($keep_connected == 'on') {

        // Set expiration delta to 7 days
        $token_expiration = time() + 7 * 24 * 60 * 60;
    }

    // JWT payload
    $payload = [
        'username' => $username,
        'exp' => $token_expiration
    ];

    // Create token from payload
    $token = $jwt_manager->createToken($payload);

    // Prepare HTTP response data
    $res = [
        'type' => 'jwt',
        'access_token' => $token,
    ];

    // Send response
    http_response_code(200);
    echo json_encode($res);
} else {
    // If there is not post data, throw a bad request code.
    http_response_code(400);
    die();
}
