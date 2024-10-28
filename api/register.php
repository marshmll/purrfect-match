<?php
require_once("./utils/database.php");
require_once("./utils/jwt.php");
require_once("./utils/http_responses.php");
require_once("./utils/password.php");

header('Content-Type: application/json');

// Check for all post data from the form
if (
    !isset($_POST['name']) or
    !isset($_POST['username']) or
    !isset($_POST['password']) or
    !isset($_POST['date_birth']) or
    !isset($_POST['contact_email']) or
    !isset($_POST['contact_phone'])
) {
    sendBadRequestResponse();
}

// Check if the username exists in the database.
$user = Database::query(
    "SELECT id
        FROM users
        WHERE username='%s'",
    [$_POST['username']]
);

if (!empty($user))
    sendConflictResponse(json_encode(['detail' => "Nome de usuário em uso."]));

// Check if the email exists in the database.
$email = Database::query(
    "SELECT contact_email FROM users WHERE contact_email='%s'",
    [$_POST['contact_email']]
);

if (!empty($email))
    sendConflictResponse(json_encode(['detail' => "Endereço de e-mail em uso."]));

// Create the user
$salt = generatePasswordSalt();
$pass_hash = hashPassword($_POST['password'], $salt);

Database::beginTransaction();

$result = Database::query(
    "INSERT INTO users
        (
            name,
            username,
            date_birth,
            datetime_register,
            pass_salt,
            pass_hash,
            role,
            contact_email,
            contact_phone
        )
        VALUES
        (
            '%s', '%s', '%s', CURRENT_TIMESTAMP, '%s', '%s', '%s', '%s', '%s'
        )",
    [
        $_POST['name'],
        $_POST['username'],
        $_POST['date_birth'],
        $salt,
        $pass_hash,
        'regular',
        $_POST['contact_email'],
        $_POST['contact_phone']
    ]
);

// If the user creation was not sucessfull, inform user.
if (!$result) {
    Database::rollbackTransaction();
    sendResponse(json_encode(['detail' => 'Ocorreu um erro durante a criação de usuário. Se o erro persistir, entre em contato com os responsáveis pelo site.']), HttpStatus::Accepted->value);
}

Database::commitTransaction();

$user_id = Database::query(
    "SELECT id
    FROM users
    WHERE name = '%s'",
    [$_POST['name']]
);

// Create a JSON Web Token Manager
$jwt_manager = new JWTManager(SECRET_KEY);
$token_expiration = time() + 7 * 24 * 60 * 60;

// Create token payload
$payload = createAuthenticationPayload($_POST['username'], $user_id['id'], $token_expiration, 'regular');

// Create the token
$token = $jwt_manager->createToken($payload);

// Send encapsulated authentication token.
sendResponse(
    json_encode(
        [
            'type' => 'jwt',
            'access_token' => $token,
        ]
    ),
    HttpStatus::Created->value
);
