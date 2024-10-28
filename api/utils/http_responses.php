<?php
enum HttpStatus: int
{
    case Ok                  = 200;
    case Created             = 201;
    case Accepted            = 202;
    case BadRequest          = 400;
    case Unauthorized        = 401;
    case Forbidden           = 403;
    case NotFound            = 404;
    case Conflict            = 409;
    case ImATeapot           = 418;
    case InternalServerError = 500;
};

function sendResponse($json_response, $status = HttpStatus::Ok->value)
{
    http_response_code($status);
    echo $json_response;
    die();
}

function sendOKResponse($json_response)
{
    http_response_code(HttpStatus::Ok->value);
    echo $json_response;
    die();
}

function sendNotAuthenticatedResponse()
{
    http_response_code(HttpStatus::Unauthorized->value);
    echo json_encode([
        'detail' => mb_convert_encoding('Não foi possível validar as credenciais.', 'UTF-8', 'auto'),
    ]);
    die();
}

function sendUnauthorizedResponse()
{
    http_response_code(HttpStatus::Unauthorized->value);
    echo json_encode([
        'detail' => mb_convert_encoding('Usuário ou senha incorretos.', 'UTF-8', 'auto'),
    ]);
    die();
}

function sendBadRequestResponse()
{
    http_response_code(HttpStatus::BadRequest->value);
    die();
}

function sendConflictResponse()
{
    http_response_code(HttpStatus::Conflict->value);
    echo json_encode([
        'detail' => mb_convert_encoding('Não foi possível criar o recurso solicitado pois já é existente.', 'UTF-8', 'auto'),
    ]);
    die();
}
