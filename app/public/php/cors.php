<?php

define('ALLOWED_DOMAINS', [
    '127.0.0.1',
    'localhost',
]);

/**
 * This tutorial shows you how to send CORS headers in PHP
 * @see https://wpsandbox.net/909
 */
class CORS
{
    /**
     * CORS::isAllowedURL();
     * @param string $url
     * @return bool
     */
    public static function isAllowedURL($url)
    {
        if (empty($url) || !defined('ALLOWED_DOMAINS')) {
            return false;
        }

        $found = false;

        foreach (ALLOWED_DOMAINS as $domain) {
            if (strpos($url, $domain) !== false) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            return false;
        }

        $allowed_domains_esc = array_map('preg_quote', ALLOWED_DOMAINS);
        $domains_ext_partial_regex = join('|', $allowed_domains_esc);

        if (preg_match('/(' . $domains_ext_partial_regex . ')/si', $url)) {
            return true;
        }

        return false;
    }

    /**
     *  An example CORS-compliant method.  It will allow any GET, POST, or OPTIONS requests from any
     *  origin.
     *
     *  In a production environment, you probably want to be more restrictive, but this gives you
     *  the general idea of what is involved.  For the nitty-gritty low-down, read:
     *
     *  - https://developer.mozilla.org/en/HTTP_access_control
     *  - https://fetch.spec.whatwg.org/#http-cors-protocol
     */
    public static function sendCORSHeaders()
    {
        if (headers_sent()) {
            return;
        }
        
        http_response_code(200);
        
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if (!empty($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            }

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            } else {
                header("Access-Control-Allow-Headers: X-Requested-With, token, Content-Type, Authorization");
            }

            die();
        }
    }
}
