<?php
// Check if the user is authenticated. If not, send not authenticated response.
function checkUserAuthentication($headers)
{
    if (!isset($headers['authorization']))
        sendNotAuthenticatedResponse();
}
