<?php
require_once('../../../utils/database.php');
require_once('../../../utils/http_responses.php');
require_once('../../../utils/jwt.php');
require_once('../../../utils/check_authentication.php');

$headers = apache_request_headers();
checkUserAuthentication($headers);

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

try {
    // Start transaction
    Database::beginTransaction();

    // Prepare query with or without pfp_url
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

    // If update fails, rollback and send conflict response
    if (!$result) {
        Database::rollbackTransaction();
        sendConflictResponse();
    }

    // Fetch updated user details
    $user = Database::query(
        "SELECT name, username, date_birth, contact_email, contact_phone, pfp_url
            FROM users
            WHERE id = '%s'",
        [$payload['sub']]
    );

    // Commit the transaction
    Database::commitTransaction();

    // Send OK response with updated user details
    sendOKResponse(json_encode($user));
} catch (Exception $e) {
    // Rollback transaction in case of any error
    Database::rollbackTransaction();

    // Send error response
    sendConflictResponse(json_encode(['detail' => 'An error occurred: ' . $e->getMessage()]));
}
