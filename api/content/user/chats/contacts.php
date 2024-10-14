<?php
require_once('../../../utils/database.php');
require_once('../../../utils/jwt.php');
require_once('../../../utils/http_responses.php');

// Collect headers and body
$headers = apache_request_headers();

// If Authorization Bearer is set
if (isset($headers['authorization'])) {

    // Remove 'Bearer ' from the token
    $token = getAuthTokenFromHeaders($headers);

    $jwt = new JWTManager(SECRET_KEY);

    // Check if the token has the expected signature
    if (!$jwt->isTokenValid($token) or $jwt->isTokenExpired($token))
        sendNotAuthenticatedResponse();

    $payload = $jwt->decodeToken($token);

    // Get all users that sent any message to the current user and get the last
    // sent message in the chat.
    $contacts = Database::query(
        "SELECT users.id,
        users.name,
        users.username,
        users.pfp_url,
        messages.sender_id,
        messages.receiver_id,
        messages.content AS last_message_content,
        messages.status AS last_message_status,
        messages.sent_datetime AS last_message_datetime
        FROM users
        JOIN messages
        ON messages.sent_datetime = (
            SELECT MAX(sent_datetime)
            FROM messages m
            WHERE messages.sender_id = m.sender_id
            AND messages.receiver_id = m.receiver_id
        )
        AND users.id != %d",
        [$payload['sub']],
        true
    );

    sendOKResponse(json_encode($contacts));
}

sendNotAuthenticatedResponse();
