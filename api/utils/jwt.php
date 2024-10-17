<?php

/**
 * A secret 256-bit hash used to digest the cookie and verify its signature. 
 */
const SECRET_KEY = "d0c49157fea2ccff061bfec97a77d4a000efb82a632f171e8f7cc4ddee73668158e2db2bdc043ca23f6d86e555dd5ccf4c65e98c09a99262c301309cc07f89fc";

/** 
 * Implementation of IETF RFC 7519 (JSON Web Token)
 * 
 * By: Renan Andrade, 15/09/2024.
 */
class JWTManager
{
    private $secretKey;

    /**
     * Constructor for the JWT class utility.
     *
     * @param string $secretKey The secret key used for signing.
     */
    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * Creates a JWT from the payload with the secret key signature.
     *
     * @param array $payload The payload data to encode in the token.
     * @return string The generated JWT.
     */
    public function createToken(array $payload): string
    {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $base64_url_header = $this->base64UrlEncode($header);
        $base64_url_payload = $this->base64UrlEncode(json_encode($payload));
        $signature = hash_hmac('sha256', $base64_url_header . '.' . $base64_url_payload, $this->secretKey, true);
        $base64_url_signature = $this->base64UrlEncode($signature);

        return "{$base64_url_header}.{$base64_url_payload}.{$base64_url_signature}";
    }

    /**
     * Checks if a given JWT is valid according to the secret key.
     *
     * @param string $token The JWT to validate.
     * @return bool True if valid, false otherwise.
     */
    public function isTokenValid(string $token): bool
    {
        list($base64_url_header, $base64_url_payload, $base64_url_signature) = explode('.', $token);
        $signature = $this->base64UrlDecode($base64_url_signature);
        $expected_signature = hash_hmac('sha256', $base64_url_header . '.' . $base64_url_payload, $this->secretKey, true);

        return hash_equals($signature, $expected_signature);
    }

    /**
     * Checks if a given JWT is expired.
     *
     * @param string $token The JWT to check.
     * @return bool True if expired, false otherwise.
     */
    public function isTokenExpired(string $token): bool
    {
        $payload = $this->decodeToken($token);
        return isset($payload['exp']) && time() >= $payload['exp'];
    }

    /**
     * Decodes the payload of a given JWT.
     *
     * @param string $token The JWT to decode.
     * @return array The decoded payload.
     */
    public function decodeToken(string $token): array
    {
        list(, $base64_url_payload) = explode('.', $token);
        $payload = $this->base64UrlDecode($base64_url_payload);

        return json_decode($payload, true);
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string
    {
        $base64 = strtr($data, '-_', '+/');
        return base64_decode(str_pad($base64, strlen($base64) % 4, '=', STR_PAD_RIGHT));
    }
}

/* TOOLS ==================================================================================================== */

/**
 * Extracts the authentication token from the request headers.
 *
 * @param array $headers The HTTP headers from the request.
 * @return string The extracted token.
 */
function getAuthTokenFromHeaders(array $headers): string
{
    return substr($headers['authorization'], 7); // Removing "Bearer " prefix
}

/**
 * Creates the authentication payload for the JWT.
 *
 * @param string $username The username of the user.
 * @param string $sub The subject (user ID).
 * @param int $exp The expiration time (UNIX timestamp).
 * @param string $rol The role of the user.
 * @return array The authentication payload.
 */
function createAuthenticationPayload(string $username, $sub, int $exp, string $rol): array
{
    return [
        'usr' => $username,
        'sub' => $sub,
        'exp' => $exp,
        'rol' => $rol
    ];
}
