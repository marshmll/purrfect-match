<?php
class CurlFetcher {
    public CurlHandle $curlHandle;

    public function __construct($configuration) {
        $this->curlHandle = curl_init($configuration['url']);

        switch ($configuration['method']) {
            case 'POST':
                curl_setopt($this->curlHandle, CURLOPT_POST, 1);
                break;
            case 'GET':
                break;
            default:
                die("CurlFetcher::ERROR_UNHANDLED_METHOD_" . $configuration['method']);
                break;
        }

        curl_setopt_array(
            $this->curlHandle,
            $configuration['opt_array'],
        );
    }

    public function fetch() {
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
