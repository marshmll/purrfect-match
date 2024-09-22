<?php

function hashPassword($password, $salt)
{
    return hash_hmac('sha256', mb_convert_encoding($password, 'UTF-8', 'auto'), $salt);
}

function generatePasswordSalt($num_of_bytes = 16)
{
    return bin2hex(random_bytes($num_of_bytes));
}

function checkPassword($original_hash, $password, $salt)
{
    $test_hash = hashPassword($password, $salt);
    return hash_equals($original_hash, $test_hash);
}
