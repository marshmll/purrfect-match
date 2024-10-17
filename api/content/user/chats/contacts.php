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
         FROM messages
         JOIN (
            SELECT 
                LEAST(sender_id, receiver_id) AS user1,
                GREATEST(sender_id, receiver_id) AS user2,
                MAX(sent_datetime) AS last_message_datetime
            FROM messages
            WHERE sender_id = %d OR receiver_id = %d
            GROUP BY user1, user2
         ) AS latest_messages
         ON (LEAST(messages.sender_id, messages.receiver_id) = latest_messages.user1
             AND GREATEST(messages.sender_id, messages.receiver_id) = latest_messages.user2
             AND messages.sent_datetime = latest_messages.last_message_datetime)
         JOIN users
         ON users.id = IF(messages.sender_id = %d, messages.receiver_id, messages.sender_id)
         ORDER BY messages.sent_datetime DESC",
        [$payload['sub'], $payload['sub'], $payload['sub']],
        true
    );    
    
    sendOKResponse(json_encode($contacts));
}

sendNotAuthenticatedResponse();
