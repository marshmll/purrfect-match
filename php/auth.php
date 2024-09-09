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

function hasAuthenticationCookieSet() {
    return isset($_COOKIE['token']);
}

function setAuthenticationCookie($jwt) {
    setcookie('token', $jwt, time()+60*60*24*30, '/'); // 30 days
}

function clearAuthenticationCookie() {
    if (isset($_COOKIE['token'])) {
        unset($_COOKIE['token']);
        setcookie('token', '', time() - 3600, '/'); 
    }
}
