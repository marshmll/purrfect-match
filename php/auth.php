<?php
require_once("../php/net.php");

const API_URL = "https://purrfect-match-api.onrender.com";

function authenticateUser($username, $password) {
    $data = [
        'username' => $username,
        'password' => $password,
    ];

    $encoded_form_url = http_build_query($data);

    $curl = new CurlFetcher([
        'url' => API_URL . '/auth',
        'method' => "POST",
        'opt_array' => [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'accept: application/json',
                'Content-Type: application/x-www-form-urlencoded'
            ],
            CURLOPT_POSTFIELDS => $encoded_form_url,
        ],
    ]);

    $res = $curl->fetch();

    $curl->close();

    return $res;
}

function hasAuthenticationCookieSet() {
    return isset($_COOKIE['token']);
}

function getAuthenticationCookie() {
    if (hasAuthenticationCookieSet()) {
        return $_COOKIE['token'];
    } else {
        return false;
    }
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
