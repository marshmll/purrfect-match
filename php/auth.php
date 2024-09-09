<?php
require_once("../php/net.php");

const API_URL = "http://127.0.0.1:8000/auth";

function authenticateUser($username, $password) {
    $data = [
        'username' => $username,
        'password' => $password,
    ];

    $encoded_form_url = http_build_query($data);

    $curlFetcher = new CurlFetcher(
        API_URL,
        "POST",
        "application/json",
        "application/x-www-form-urlencoded"
    );

    $res = $curlFetcher->fetch($encoded_form_url);

    $curlFetcher->close();

    return $res;
}

function setAuthenticationCookie($jwt) {

}
