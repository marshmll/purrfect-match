<?php
require_once('./database.php');
require_once('./jwt.php');

header('Content-Type: application/json');

$bad_request_json = json_encode([
    'detail' => mb_convert_encoding('Erro no processamento da requisição', 'UTF-8', 'auto'),
]);

$unautorized_json = json_encode([
    'detail' => mb_convert_encoding('Usuário ou senha incorretos', 'UTF-8', 'auto'),
]);

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['keep_connected'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $keep_connected = $_POST['keep_connected'];

    $user_exists_query = sprintf(
        "SELECT pass_salt, pass_hash FROM users WHERE username='%s'",
        $mysqli->real_escape_string($username)
    );

    $result = $mysqli->query($user_exists_query);

    if ($result->num_rows == 0) {
        http_response_code(401);
        echo $unautorized_json;
        die();
    }

    $data = $result->fetch_assoc();

    $hash = hash('sha256', mb_convert_encoding($data['pass_salt'] . $password, 'UTF-8', 'auto'));

    $check_hash_query = sprintf(
        "SELECT * FROM users WHERE pass_hash='%s'",
        $mysqli->real_escape_string($hash)
    );

    $result = $mysqli->query($check_hash_query);

    if ($result->num_rows == 0) {
        http_response_code(401);
        echo $unautorized_json;
        die();
    }

    $jwt_manager = new JWTManager("justtestingggg");
    $token_expiration = 0;

    if ($keep_connected == 'on') {
        $token_expiration = time() + 7 * 24 * 60 * 60;
    }

    $payload = [
        'username' => $username,
        'exp' => $token_expiration
    ];

    $token = $jwt_manager->createToken($payload);

    $res = [
        'type' => 'jwt',
        'access_token' => $token,
    ];

    http_response_code(200);
    echo json_encode($res);
} else {
    http_response_code(400);
}
