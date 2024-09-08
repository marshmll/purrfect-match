<?php
    $url = "http://127.0.0.1:8000/auth";

    $data = [
        'grant_type' => '',
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'scope' => '',
        'client_id' => '',
        'client_secret' => '',
    ];
    
    $options = [
        'http' => [
            'header'=> [
                'accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
            ],
            'method' => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    
    try {

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result == false) {
            die("erro");
        }
    }
    catch (Exception $e) {
        echo "erro http";
    }

    $token = json_decode($result);

    var_dump($token);
?>
