<?php
require_once('../../../utils/database.php');
require_once('../../../utils/jwt.php');
require_once('../../../utils/http_responses.php');
require_once('../../../utils/check_authentication.php');

// Collect headers and body
$headers = apache_request_headers();
checkUserAuthentication($headers);

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
    "WITH latest_messages AS (
        SELECT 
            LEAST(sender_id, receiver_id) AS user1,
            GREATEST(sender_id, receiver_id) AS user2,
            MAX(sent_datetime) AS last_message_datetime
        FROM messages
        WHERE sender_id = %d OR receiver_id = %d
        GROUP BY user1, user2
    )
    SELECT 
        u.id,
        u.name,
        u.username,
        u.pfp_url,
        m.sender_id,
        m.receiver_id,
        m.content AS last_message_content,
        m.status AS last_message_status,
        m.sent_datetime AS last_message_datetime
    FROM messages m
    JOIN latest_messages lm ON (
        LEAST(m.sender_id, m.receiver_id) = lm.user1
        AND GREATEST(m.sender_id, m.receiver_id) = lm.user2
        AND m.sent_datetime = lm.last_message_datetime
    )
    JOIN users u ON u.id = IF(m.sender_id = %d, m.receiver_id, m.sender_id)
    ORDER BY m.sent_datetime DESC",
    [$payload['sub'], $payload['sub'], $payload['sub']],
    true
);

sendOKResponse(json_encode($contacts));
