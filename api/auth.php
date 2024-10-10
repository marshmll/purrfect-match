<?php
require_once('./utils/database.php');
require_once('./utils/jwt.php');
require_once('./utils/http_responses.php');
require_once('./utils/password.php');

header('Content-Type: application/json');

// If all necessary form data was posted
if (isset($_POST['username']) && isset($_POST['password'])) {

    // Save data into variables
    $username = $_POST['username'];
    $password = $_POST['password'];
    $keep_connected = isset($_POST['keep_connected']) ? $_POST['keep_connected'] : false;

    $user = Database::query(
        "SELECT pass_salt, pass_hash FROM users WHERE username='%s'",
        [$username]
    );

    if (empty($user))
        sendUnauthorizedResponse();

    // Hash the received password with the salt
    $hash = hashPassword($_POST['password'], $user['pass_salt']);

    $hash_owner = Database::query(
        "SELECT id, role FROM users WHERE pass_hash='%s'",
        [$hash]
    );

    if (empty($hash_owner))
        sendUnauthorizedResponse();

    // Create a JSON Web Token manager.
    $jwt_manager = new JWTManager(SECRET_KEY);

    // 1 hour expiration time.
    $token_expiration = time() + 1 * 60 * 60;

    // If user asked to keep connection, set expiration delta to 7 days
    if ($keep_connected == 'on')
        $token_expiration = time() + 7 * 24 * 60 * 60;

    // JWT payload
    $payload = createAuthenticationPayload($username, $hash_owner['id'], $token_expiration, $hash_owner['role']);

    // Create token from payload
    $token = $jwt_manager->createToken($payload);

    // Prepare HTTP response data
    $res = [
        'type' => 'jwt',
        'access_token' => $token,
    ];

    sendOKResponse(json_encode($res));
}

// If there is not post data, throw a bad request code.
sendBadRequestResponse();
