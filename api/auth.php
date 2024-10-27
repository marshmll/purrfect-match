<?php
require_once('./utils/database.php');
require_once('./utils/jwt.php');
require_once('./utils/http_responses.php');
require_once('./utils/password.php');

header('Content-Type: application/json');

// Check if the necessary form data was posted
if (isset($_POST['username']) && isset($_POST['password'])) {
    // Save posted data into variables
    $username = $_POST['username'];
    $password = $_POST['password'];
    $keep_connected = isset($_POST['keep_connected']) && $_POST['keep_connected'] === 'on';

    // Fetch user data from the database
    $user = Database::query(
        "SELECT pass_salt, pass_hash, status
        FROM users
        WHERE username='%s'",
        [$username]
    );

    // If user not found, send unauthorized response
    if (empty($user)) {
        sendUnauthorizedResponse();
    }

    // Hash the received password using the user's salt
    $hash = hashPassword($password, $user['pass_salt']);

    // Check if the hashed password matches the stored hash
    $hash_owner = Database::query(
        "SELECT id, role FROM users WHERE pass_hash='%s'",
        [$hash]
    );

    // If no match is found, send unauthorized response
    if (empty($hash_owner)) {
        sendUnauthorizedResponse();
    }

    if ($user['status'] == 'banned')
        sendResponse(json_encode(['detail' => "O usuário foi banido por tempo indeterminado."]), HttpStatus::Unauthorized->value);

    // Create a JSON Web Token manager
    $jwt_manager = new JWTManager(SECRET_KEY);

    // Set the expiration time for the token (1 hour by default)
    $token_expiration = time() + 1 * 60 * 60;

    // If the user requested to stay logged in, extend expiration to 7 days
    if ($keep_connected) {
        $token_expiration = time() + 7 * 24 * 60 * 60;
    }

    // Create JWT payload with user information
    $payload = createAuthenticationPayload(
        $username,
        $hash_owner['id'],
        $token_expiration,
        $hash_owner['role']
    );

    // Generate the JWT token from the payload
    $token = $jwt_manager->createToken($payload);

    // Prepare the HTTP response data
    $res = [
        'type' => 'jwt',
        'access_token' => $token,
    ];

    // Check user role and add redirect if necessary
    if (in_array($payload['rol'], ['root', 'supervisor', 'manager'])) {
        $res['redirect'] = '/pages/admin/index.html';
    }

    // Send a successful response with the token and redirect URL (if applicable)
    sendOKResponse(json_encode($res));
}

// If no POST data, send a bad request response
sendBadRequestResponse();
