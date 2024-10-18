<?php

/**
 * Hash a password using a given salt.
 *
 * @param string $password The password to be hashed.
 * @param string $salt The salt to use in hashing.
 * @return string The resulting hash.
 */
function hashPassword($password, $salt)
{
    return hash_hmac('sha256', mb_convert_encoding($password, 'UTF-8', 'auto'), $salt);
}

/**
 * Generate a random salt for password hashing.
 *
 * @param int $num_of_bytes The number of bytes for the salt (default: 16).
 * @return string The generated salt in hexadecimal format.
 */
function generatePasswordSalt($num_of_bytes = 16)
{
    return bin2hex(random_bytes($num_of_bytes));
}

/**
 * Check if a password matches the original hash using the salt.
 *
 * @param string $original_hash The hash to check against.
 * @param string $password The password to test.
 * @param string $salt The salt used to hash the original password.
 * @return bool True if the password matches, false otherwise.
 */
function checkPassword($original_hash, $password, $salt)
{
    $test_hash = hashPassword($password, $salt);
    return hash_equals($original_hash, $test_hash);
}
