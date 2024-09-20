<?php
// JSON response to send in case that the token is invalid or not present.
$not_authenticated_json = json_encode([
    'detail' => mb_convert_encoding('Não foi possível validar as credenciais.', 'UTF-8', 'auto'),
]);

// JSON response to send in case that the username or password is incorrect
$unautorized_json = json_encode([
    'detail' => mb_convert_encoding('Usuário ou senha incorretos.', 'UTF-8', 'auto'),
]);

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
