<?php
require_once("./database.php");
require_once("./jwt.php");

// Sets response format to JSON
header('Content-Type: application/json');

// JSON response in case the username is already in use.
$user_already_registered_json = json_encode([
    'detail' => mb_convert_encoding('Nome de usuário já cadastrado.', 'UTF-8', 'auto'),
]);

// JSON response in case the email is already in use.
$email_already_registered_json = json_encode([
    'detail' => mb_convert_encoding('E-mail já cadastrado.', 'UTF-8', 'auto'),
]);

// JSON response in case the user creation goes wrong.
$user_creation_error_json = json_encode([
    'detail' => mb_convert_encoding('Um erro inesperado ocorreu durante a criação do usuário. Caso o problema persista, entre em contato com os responsáveis pelo site.', 'UTF-8', 'auto'),
]);

// Check for all post data from the form
if (
    isset($_POST['name']) &&
    isset($_POST['username']) &&
    isset($_POST['password']) &&
    isset($_POST['date_birth']) &&
    isset($_POST['datetime_register']) &&
    isset($_POST['contact_email']) &&
    isset($_POST['contact_phone'])
) {
    // Generate a random 16 bytes salt
    $salt = bin2hex(random_bytes(16));

    // SQL Injection free query to check if the user is in the database.
    $username_exists_query = sprintf(
        "SELECT id FROM users WHERE username='%s'",
        $mysqli->real_escape_string($_POST['username'])
    );

    // SQL Injection free query to check if the email is in the database.
    $email_exists_query = sprintf(
        "SELECT contact_email FROM users WHERE contact_email='%s'",
        $mysqli->real_escape_string($_POST['contact_email'])
    );

    // SQL Injection free query to create a user in the database.
    $user_insert_query = sprintf(
        "INSERT INTO users (
            name,
            username,
            date_birth,
            datetime_register,
            pass_salt,
            pass_hash,
            role,
            contact_email,
            contact_phone
        ) VALUES (
            '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'
        )",
        $mysqli->real_escape_string($_POST['name']),
        $mysqli->real_escape_string($_POST['username']),
        $mysqli->real_escape_string($_POST['date_birth']),
        $mysqli->real_escape_string($_POST['datetime_register']),
        $salt,
        hash('sha256', mb_convert_encoding($salt . $_POST['password'], 'UTF-8', 'auto')),
        'regular',
        $mysqli->real_escape_string($_POST['contact_email']),
        $mysqli->real_escape_string($_POST['contact_phone'])
    );

    // Check if the username exists in the database.
    $result = $mysqli->query($username_exists_query);
    if ($result->num_rows != 0) {

        // If exists, send forbidden code.
        http_response_code(403);
        echo $user_already_registered_json;
        die();
    }

    // Check if the email exists in the database.
    $result = $mysqli->query($username_exists_query);
    if ($result->num_rows != 0) {

        // If exists, send forbidden code.
        http_response_code(403);
        echo $email_already_registered_json;
        die();
    }

    // Create the user
    $result = $mysqli->query($user_insert_query);

    // If the creation was sucessfull
    if ($result) {
        // Create a JSON Web Token Manager
        $jwt_manager = new JWTManager("justtestingggg");
        $token_expiration = time() + 7 * 24 * 60 * 60;

        // Create token payload
        $payload = [
            'username' => $_POST['username'],
            'exp' => $token_expiration
        ];

        // Create the token
        $token = $jwt_manager->createToken($payload);

        // Prepare server response data
        $res = [
            'type' => 'jwt',
            'access_token' => $token,
        ];

        // Set code to "Created"
        http_response_code(201);

        // Send response
        echo json_encode($res);
        die();
    } else {
        // If the user creation was not sucessfull, inform user.
        http_response_code(202); // Accepted
        echo $user_creation_error_json;
    }
} else {
    // If there is not post data, throw a bad request code.
    http_response_code(400);
    die();
}
