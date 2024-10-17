<?php
require_once('../../../utils/database.php');
require_once('../../../utils/http_responses.php');
require_once('../../../utils/jwt.php');

$headers = apache_request_headers();

// If Authorization Bearer is set
if (isset($headers['authorization'])) {

    // Check for all post data from the form
    if (
        !isset($_POST['name']) or
        !isset($_POST['date_birth']) or
        !isset($_POST['contact_email']) or
        !isset($_POST['contact_phone'])
    ) {
        sendBadRequestResponse();
    }

    // Remove 'Bearer ' from the token
    $token = getAuthTokenFromHeaders($headers);

    $jwt = new JWTManager(SECRET_KEY);

    // Check if the token has the expected signature
    if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
        sendNotAuthenticatedResponse();

    $payload = $jwt->decodeToken($token);

    $result = null;

    if (isset($_POST['pfp_url'])) {
        $result = Database::query(
            "UPDATE users SET
            name = '%s',
            date_birth = '%s',
            contact_email = '%s',
            contact_phone = '%s',
            pfp_url = '%s'
            WHERE id = %d",
            [
                $_POST['name'],
                $_POST['date_birth'],
                $_POST['contact_email'],
                $_POST['contact_phone'],
                $_POST['pfp_url'],
                $payload['sub']
            ]
        );
    } else {
        $result = Database::query(
            "UPDATE users SET
            name = '%s',
            date_birth = '%s',
            contact_email = '%s',
            contact_phone = '%s'
            WHERE id = %d",
            [
                $_POST['name'],
                $_POST['date_birth'],
                $_POST['contact_email'],
                $_POST['contact_phone'],
                $payload['sub']
            ]
        );
    }

    if (!$result) {
        sendConflictResponse();
    }

    $user = Database::query(
        "SELECT name, username, date_birth, contact_email, contact_phone, pfp_url
        FROM users
        WHERE id = '%s'",
        [
            $payload['sub']
        ]
    );

    sendOKResponse(json_encode($user));
}

sendNotAuthenticatedResponse();
