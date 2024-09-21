<?php
// JSON response in case the username is already in use.
$user_already_registered_json = json_encode([
    'detail' => mb_convert_encoding('Nome de usuário já cadastrado.', 'UTF-8', 'auto'),
]);

// JSON response in case the email is already in use.
$email_already_registered_json = json_encode([
    'detail' => mb_convert_encoding('E-mail já cadastrado.', 'UTF-8', 'auto'),
]);

// JSON response in case the user creation goes wrong.
$user_creation_error_json = json_encode([
    'detail' => mb_convert_encoding('Um erro inesperado ocorreu durante a criação do usuário. Caso o problema persista, entre em contato com os responsáveis pelo site.', 'UTF-8', 'auto'),
]);

function sendResponse($json_response, $status = 200)
{
    http_response_code($status);
    echo $json_response;
    die();
}

function sendOKResponse($json_response)
{
    http_response_code(200);
    echo $json_response;
    die();
}

function sendNotAuthenticatedResponse()
{
    http_response_code(401);
    echo json_encode([
        'detail' => mb_convert_encoding('Não foi possível validar as credenciais.', 'UTF-8', 'auto'),
    ]);
    die();
}

function sendUnauthorizedResponse()
{
    http_response_code(401);
    echo json_encode([
        'detail' => mb_convert_encoding('Usuário ou senha incorretos.', 'UTF-8', 'auto'),
    ]);
    die();
}

function sendBadRequestResponse()
{
    http_response_code(400);
    die();
}

function sendConflictResponse()
{
    http_response_code(409);
    echo json_encode([
        'detail' => mb_convert_encoding('Não foi possível criar o recurso solicitado pois já é existente.', 'UTF-8', 'auto'),
    ]);
    die();
}
