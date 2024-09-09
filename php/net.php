<?php
class CurlFetcher {
    public CurlHandle $curlHandle;

    public function __construct($url, $method, $accept, $content_type) {
        $this->curlHandle = curl_init($url);

        switch ($method) {
            case 'POST':
                curl_setopt($this->curlHandle, CURLOPT_POST, 1);
                break;
            case 'GET':
                break;
            default:
                die("CurlFetcher::ERROR_UNHANDLED_METHOD_" . $method);
                break;
        }

        curl_setopt_array(
            $this->curlHandle,
            [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'accept: ' . $accept . '\r\n',
                    'Content-Type: ' . $content_type,
                    '\r\n',
                ]
            ]
        );
    }

    public function fetch($data) {
        curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $data);

        $res = curl_exec($this->curlHandle);

        $http_code = curl_getinfo($this->curlHandle, CURLINFO_HTTP_CODE);

        if ($http_code != 200)
            return false;

        $json = json_decode($res, true);

        return $json;
    }

    public function close() {
        curl_close($this->curlHandle);
    }
}
