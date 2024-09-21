<?php

/**
 * A secret 256 bits hash that is used to digest the cookie and
 * verify its signature. 
 */
const SECRET_KEY = "nah";

/** 
 * Implementation of IETF RFC 7519 (JSON Web Token)
 * 
 * By: Renan Andrade, 15/09/2024.
 */
class JWTManager
{
    private $secretKey;

    /**
     * @brief Constructor for the JWT class utility.
     */
    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @brief Creates a JWT from the payload with the secret key signature.
     */
    public function createToken($payload)
    {
        $base64_url_header = $this->base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $base64_url_payload = $this->base64UrlEncode(json_encode($payload));
        $base64_url_signature = hash_hmac('sha256', $base64_url_header . $base64_url_payload, $this->secretKey, true);
        $base64_url_signature = $this->base64UrlEncode($base64_url_signature);

        return $base64_url_header . '.' . $base64_url_payload . '.' . $base64_url_signature;
    }

    /**
     * @brief Checks if a given JWT is valid according to the secret key.
     */
    public function isTokenValid($token)
    {
        list($base64_url_header, $base64_url_payload, $base64_url_signature) = explode('.', $token);
        $signature = $this->base64UrlDecode($base64_url_signature);
        $expected_signature = hash_hmac('sha256', $base64_url_header . $base64_url_payload, $this->secretKey, true);

        return hash_equals($signature, $expected_signature);
    }

    /**
     * @brief Checks if a given JWT is expired.
     */
    public function isTokenExpired($token)
    {
        $payload = $this->decodeToken($token);

        // Get expiration time (UNIX timestamp)
        $token_exp = $payload['exp'];

        if (time() >= $token_exp)
            return true;

        return false;
    }

    /**
     * @brief Decodes the payload of a given JWT.
     */
    public function decodeToken($token)
    {
        list(, $base64_url_payload,) = explode('.', $token);
        $payload = $this->base64UrlDecode($base64_url_payload);

        return json_decode($payload, true);
    }

    private function base64UrlEncode($data)
    {
        $base64 = base64_encode($data);
        $base64Url = strtr($base64, '+/', '-_');
        return rtrim($base64Url, '=');
    }

    private function base64UrlDecode($data)
    {
        $base64 = strtr($data, '-_', '+/');
        $base64Padded = str_pad($base64, strlen($base64) % 4, '=', STR_PAD_RIGHT);
        return base64_decode($base64Padded);
    }
}

function getAuthTokenFromHeaders($headers)
{
    $token = substr($headers['authorization'], 7, strlen($headers['authorization']) - 6);

    return $token;
}

function createAuthenticationPayload($username, $sub, $exp)
{
    return [
        'usr' => $username,
        'sub' => $sub,
        'exp' => $exp
    ];
}
