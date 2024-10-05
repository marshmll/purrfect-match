<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

const HOSTNAME = "127.0.0.1";
const USERNAME = "client";
const PASSWORD = "bancodedados";
const DATABASE = "purrfect_db";
const PORT     = "3307"; // Docker's database port.

class Database
{
    public static function query($query_format, $params = [], $always_array = false)
    {
        if (gettype($params) != 'array') {
            http_response_code(500);
            die("[Database]: Argument \$params need to be an array.");
        }

        // MySQLi connection
        $mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE, PORT);

        // Check connection
        if ($mysqli->connect_error) {
            die("[Database]: Connection failed: " . $mysqli->connect_error);
        }

        // Assure utf8 charset.
        $mysqli->set_charset("utf8");

        $escaped_params = [];

        foreach ($params as $param) {
            if (gettype($param) == "string")
                array_push($escaped_params, $mysqli->real_escape_string($param));
            else
                array_push($escaped_params, $param);
        }

        $escaped_query = empty($escaped_params) ? $query_format : sprintf($query_format, ...$escaped_params);

        $response = $mysqli->query($escaped_query);

        if (gettype($response) == 'boolean') {
            $mysqli->close();
            return $response;
        }

        $data = array();

        if ($response->num_rows == 1) {
            if ($always_array == false)
                $data = $response->fetch_assoc();
            else
                $data = $response->fetch_all(MYSQLI_ASSOC);
        } else if ($response->num_rows > 1) {
            while ($row = $response->fetch_assoc())
                $data[] = $row;
        }

        $mysqli->close();

        return $data;
    }
}
