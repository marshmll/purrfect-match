<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

const HOSTNAME = "mysql";
const USERNAME = "client";
const PASSWORD = "bancodedados";
const DATABASE = "purrfect_db";
const PORT     = "3306"; // Docker's database port.

class Database
{
    /**
     * Executes a query on the database.
     *
     * @param string $query_format The query with placeholders.
     * @param array $params Parameters to bind to the query.
     * @param bool $always_array Whether to always return an array.
     * @return array|bool The result of the query.
     * @throws Exception If parameters are not an array or if a query fails.
     */
    public static function query($query_format, $params = [], $always_array = false)
    {
        // Ensure $params is an array
        if (!is_array($params)) {
            http_response_code(500);
            throw new Exception("[Database]: Argument \$params must be an array.");
        }

        // Create MySQLi connection
        $mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE, PORT);

        // Check for connection errors
        if ($mysqli->connect_error) {
            throw new Exception("[Database]: Connection failed: " . $mysqli->connect_error);
        }

        // Set character set to UTF-8
        $mysqli->set_charset("utf8");

        // Escape parameters to prevent SQL injection
        $escaped_params = array_map(function($param) use ($mysqli) {
            return is_string($param) ? $mysqli->real_escape_string($param) : $param;
        }, $params);

        // Prepare the query
        $escaped_query = empty($escaped_params) ? $query_format : sprintf($query_format, ...$escaped_params);

        // Execute the query
        $response = $mysqli->query($escaped_query);

        // Handle boolean response (e.g., for INSERT/UPDATE)
        if (is_bool($response)) {
            $mysqli->close();
            return $response;
        }

        // Fetch the results
        $data = [];
        if ($response->num_rows === 1) {
            $data = $always_array ? $response->fetch_all(MYSQLI_ASSOC) : $response->fetch_assoc();
        } elseif ($response->num_rows > 1) {
            while ($row = $response->fetch_assoc()) {
                $data[] = $row;
            }
        }

        // Close the connection
        $mysqli->close();

        return $data;
    }
}
