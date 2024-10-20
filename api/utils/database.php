<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

const HOSTNAME = "mysql";
const USERNAME = "client";
const PASSWORD = "bancodedados";
const DATABASE = "purrfect_db";
const PORT     = "3306"; // Docker's database port.

/** 
 * Database utilitary class.
 * 
 * By: Renan Andrade, 15/09/2024.
 */
class Database
{
    // Holds the mysqli connection for transactions
    private static $mysqli = null;

    /**
     * Begins a transaction.
     * 
     * @throws Exception if a transaction is already started.
     */
    public static function beginTransaction()
    {
        if (self::$mysqli !== null) {
            throw new Exception("[Database]: Transaction already started.");
        }

        // Create MySQLi connection
        self::$mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE, PORT);

        // Check for connection errors
        if (self::$mysqli->connect_error) {
            throw new Exception("[Database]: Connection failed: " . self::$mysqli->connect_error);
        }

        // Set character set to UTF-8
        self::$mysqli->set_charset("utf8");

        // Disable autocommit
        self::$mysqli->autocommit(false);

        // Begin the transaction
        self::$mysqli->begin_transaction();
    }

    /**
     * Commits the current transaction.
     * 
     * @throws Exception if no transaction was started.
     */
    public static function commitTransaction()
    {
        if (self::$mysqli === null) {
            throw new Exception("[Database]: No transaction started.");
        }

        self::$mysqli->commit();
        self::$mysqli->autocommit(true);
        self::closeTransactionConnection();
    }

    /**
     * Rolls back the current transaction.
     * 
     * @throws Exception if no transaction was started.
     */
    public static function rollbackTransaction()
    {
        if (self::$mysqli === null) {
            throw new Exception("[Database]: No transaction started.");
        }

        self::$mysqli->rollback();
        self::$mysqli->autocommit(true);
        self::closeTransactionConnection();
    }

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

        // Use transaction connection if available, otherwise create a new connection
        $mysqli = self::$mysqli ?? new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE, PORT);

        // Check for connection errors
        if ($mysqli->connect_error) {
            throw new Exception("[Database]: Connection failed: " . $mysqli->connect_error);
        }

        // Set character set to UTF-8 (only if not using an active transaction)
        if (self::$mysqli === null) {
            $mysqli->set_charset("utf8");
        }

        // Escape parameters to prevent SQL injection
        $escaped_params = array_map(function ($param) use ($mysqli) {
            return is_string($param) ? $mysqli->real_escape_string($param) : $param;
        }, $params);

        // Prepare the query
        $escaped_query = empty($escaped_params) ? $query_format : sprintf($query_format, ...$escaped_params);

        // Execute the query
        $response = $mysqli->query($escaped_query);

        // Handle boolean response (e.g., for INSERT/UPDATE)
        if (is_bool($response)) {
            if (self::$mysqli === null) $mysqli->close(); // Only close if not in transaction
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

        // Close the connection if no transaction
        if (self::$mysqli === null) {
            $mysqli->close();
        }

        return $data;
    }

    /**
     * Closes the current transaction connection.
     */
    private static function closeTransactionConnection()
    {
        if (self::$mysqli !== null) {
            self::$mysqli->close();
            self::$mysqli = null;
        }
    }
}
