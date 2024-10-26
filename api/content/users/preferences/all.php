<?php
require_once('../../../utils/database.php');
require_once('../../../utils/http_responses.php');
require_once('../../../utils/jwt.php');
require_once('../../../utils/check_authentication.php');

$headers = apache_request_headers();
checkUserAuthentication($headers);

// Remove 'Bearer ' from the token
$token = getAuthTokenFromHeaders($headers);

$jwt = new JWTManager(SECRET_KEY);

// Check if the token has the expected signature
if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
    sendNotAuthenticatedResponse();

$payload = $jwt->decodeToken($token);

$personalities = Database::query("SELECT * FROM personalities");

$user_personality_preferences = Database::query(
    "SELECT id
        FROM personalities
        WHERE id IN
        (
            SELECT personality_id
            FROM personality_preferences
            WHERE user_id = %d
        )",
    [$payload['sub']],
    true
);

if (empty($user_personality_preferences)) {
    foreach ($personalities as &$personality)
        $personality += ["selected" => false];
} else {
    foreach ($personalities as &$personality) {
        foreach ($user_personality_preferences as $user_preference) {
            if ($personality['id'] == $user_preference['id']) {
                $personality += ["selected" => true];
                break;
            }
        }
    }
}

sendOKResponse(json_encode($personalities));
